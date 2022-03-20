<?php

namespace App\Http\Controllers;

use App\Models\InvoiceProduct;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(){
        $product = Product::get();
        return view('product.index', compact('product'));
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
            $product = Product::find($request->id);
            $product->deactivated_at = date('Y-m-d H:i:s');
            $product->save();
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
