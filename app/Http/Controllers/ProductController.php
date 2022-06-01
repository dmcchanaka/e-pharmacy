<?php

namespace App\Http\Controllers;

use App\Models\InvoiceProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    public function index(){
        $product = Product::get();
        return view('product.index', compact('product'));
    }

    public function search(Request $request){
        $proQuery = DB::table('product as p')
        ->select([
            'p.pro_id',
            'p.pro_code',
            'p.pro_name',
            'p.buying_price',
            'p.retailer_price',
            'p.deleted_at'
        ])
        ->whereNull('p.deleted_at')
        ->groupBy('p.pro_id')
        ->orderBy('p.pro_id', 'DESC');
        return DataTables::of($proQuery)
        ->addColumn('status', function ($proQuery) {
            if($proQuery->deleted_at == ''){
                return '<div style="text-align:center;color: green"><span>ACTIVE</span></div>';
            } elseif($proQuery->deleted_at != ''){
                return '<div style="text-align:center;color: red"><span>INACTIVE</span></div>';
            }
        })
        ->addColumn('edit', function ($proQuery) {
            if ($permission = \App\Models\User::checkUserPermission(Auth::user()->per_gp_id,Auth::user()->u_tp_id,"PRODUCT EDIT") == 1){
                return '<div style="text-align:center">
                    <a href="#" onclick="edit_product('.$proQuery->pro_id.')"><i class="fa fa-pencil fa-lg"></i></a>
                </div>';
            }
        })
        ->addColumn('delete', function ($proQuery) {
            if ($permission = \App\Models\User::checkUserPermission(Auth::user()->per_gp_id,Auth::user()->u_tp_id,"PRODUCT DELETE") == 1){
                return '<div style="text-align:center">
                    <a href="#" onclick="delete_product('.$proQuery->pro_id.')" data-method="delete"><i style="color: red" class="fa fa-trash-o fa-lg"></i></a>
                </div>';
            }
        })
        ->filter(function ($query) use ($request) {
            if ($request->has('pro_id') && $request->get('pro_id') != "0") {
                $query->where('p.pro_id', '=', "{$request->get('pro_id')}");
            }
            if ($request->has('search') && ! is_null($request->get('search')['value']) ) {
                $regex = $request->get('search')['value'];
                return $query->where(function($queryNew) use($regex){
                    $queryNew->where('p.pro_code', 'like', '%' . $regex . '%')
                    ->orWhere('p.pro_name', 'like', '%' . $regex . '%')
                    ->orWhere('p.buying_price', 'like', '%' . $regex . '%')
                    ->orWhere('p.retailer_price', 'like', '%' . $regex . '%');
                });
            }
        })
        ->rawColumns(['status','edit','delete'])
        ->make(true);
    }

    public function store(Request $request){
        $product = Product::create([
            'pro_code'=>$request->pro_code,
            'pro_name'=>$request->pro_name,
            'measure_of_units'=>$request->unit_of_measure,
            'buying_price'=>$request->buying_price,
            'market_price'=>$request->market_price,
            'wholesale_price'=>$request->wholesale_price,
            'retailer_price'=>$request->retailer_price,
            'expiry_date'=>$request->expiry_date,
        ]);

        if($product){
            return redirect()->route('product')->with('success', 'RECORD HAS BEEN SUCCESSFULLY INSERTED!');
        } else {
            return redirect()->route('product')->with('error', 'RECORD HAS NOT BEEN SUCCESSFULLY INSERTED!');
        }
    }

    public function edit(Request $request){
        $product = Product::find($request->id);
        return $product;
    }

    public function update(Request $request){
        $product = Product::find($request->edit_pro_id);
        $product->pro_code = $request->edit_pro_code;
        $product->pro_name = $request->edit_pro_name;
        $product->measure_of_units = $request->edit_unit_of_measure;
        $product->buying_price = $request->edit_buying_price;
        $product->retailer_price = $request->edit_retailer_price;
        $product->expiry_date = $request->edit_expiry_date;
        $product->save();
        if($product){
            return redirect('product')->with('success', 'RECORD HAS BEEN SUCCESSFULLY UPDATED!');
        } else {
            return redirect('product')->with('error', 'RECORD HAS NOT BEEN SUCCESSFULLY UPDATED!');
        }
    }

    public function destroy(Request $request){
        $invoice = InvoiceProduct::where('pro_id',$request->id)->get();
        if($invoice->isEmpty()){
            $product = Product::find($request->id)->delete();
            // $product->deactivated_at = date('Y-m-d H:i:s');
            // $product->save();
            if($product){
                return redirect('product')->with('success', 'RECORD HAS BEEN SUCCESSFULLY DELETED!');
            } else {
                return redirect('product')->with('error', 'RECORD HAS NOT BEEN SUCCESSFULLY DELETED!');
            }
        } else {
            return redirect('product')->with('error', 'SYSTEM IS NOT ALLOWING TO REMOVE THIS ITEM. THIS ITEM IS INCLUDING SOME INVOICE!');
        }
    }
}
