<?php

namespace App\Http\Controllers;

use App\Models\GoodReceivedNote;
use App\Models\Product;
use App\Models\ReceivedStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class GrnController extends Controller
{
    public function index(){
        return view('grn.index');
    }

    public function get_product(Request $request){
        $term = $request->term;

        $query = DB::table('product as p')
        ->select([
            'p.pro_name AS label',
            'p.pro_id AS id'
            ])
        ->whereNull('p.deleted_at')
        ->where('p.pro_name', 'LIKE', '%' . trim($term) . '%')
        ->groupBy('p.pro_id')
        ->orderBy('p.pro_name','ASC');

        $products = $query->get();
        return $products;
    }

    public function get_products(Request $request){
        $term = $request->term;

        $query = DB::table('product as p')
        ->select([
            'p.pro_name AS label',
            'p.pro_id AS id'
            ])
        ->whereNull('p.deleted_at')
        ->where('p.pro_name', 'LIKE', '%' . trim($term) . '%')
        ->groupBy('p.pro_id');

        $products = $query->get();
        return $products;
    }

    public function search_product_price(Request $request){
        $price = Product::where('pro_id',$request->item_id)->orderBy('pro_id','DESC')->limit(1)->get();
        $price->transform(function($ccpl){
            return [
                'price'=>$ccpl->buying_price
            ];
        });
        return $price;
    }

    public function search_product_stock(Request $request){
        $received_stk = ReceivedStock::where('pro_id',$request->item_id)->where('rs_remaining_qty','>',0)->get();
        return ['stock'=>$received_stk->sum('rs_remaining_qty')];
    }

    public function store(Request $request){

        DB::beginTransaction();
        try {

            $added_by = Auth::user()->getKey();

            $ckOrderNo = GoodReceivedNote::where('grn_no',$request->grn_no)->get();
            if($ckOrderNo){
                $grn_no = GoodReceivedNote::getGRNNo();
            } else {
                $grn_no = $request->grn_no;
            }

            $salesOrder = GoodReceivedNote::create([
                'grn_no'=>$grn_no,
                'grn_date'=>date('Y-m-d H:i:s'),
                'grn_amt'=>str_replace(',', '', $request->tot_amount),
                'added_by'=>$added_by
            ]);

            $lastOrder = GoodReceivedNote::select('grn_id', 'grn_no')->where('added_by',$added_by)->latest()->first();

            for ($i = 1; $i <= $request->item_count; $i++) {
                $line_amt = 0;
                if($request['qty_' . $i] > 0) {
                    $line_amt = $request['qty_' . $i] * $request['prc_' . $i];
                }
                $orderProduct = ReceivedStock::create([
                    'grn_id'=>$lastOrder->grn_id,
                    'pro_id'=>$request['pro_id_' . $i],
                    'cost_price'=>$request['price_' . $i],
                    'rs_qty'=>$request['qty_' . $i],
                    'rs_remaining_qty'=>$request['qty_' . $i],
                ]);
            }

            DB::commit();
            return redirect()->route('grn')->with('success', 'RECORD HAS BEEN SUCCESSFULLY INSERTED!');
        } catch (\Exception $e) {
            throw $e;
            DB::rollback();
            return redirect()->route('grn')->with('error', 'RECORD HAS NOT BEEN SUCCESSFULLY INSERTED!');
        }
    }

    public function view_grn(Request $request){
        return view('grn.view_grn');
    }

    public function search(Request $request){
        $grnQuery = DB::table('good_received_note as grn')
        ->join('received_stock as rs', 'rs.grn_id','grn.grn_id')
        ->select([
            'grn.grn_id',
            'grn.grn_no',
            DB::raw("DATE_FORMAT(grn.grn_date,'%Y-%m-%d') AS grn_date"),
            DB::raw("DATE_FORMAT(grn.grn_date,'%H:%i:%s') AS grn_time"),
            'grn.grn_amt'
        ])
        ->whereNull('grn.deleted_at')
        ->whereNull('rs.deleted_at')
        ->groupBy('grn.grn_id')
        ->orderBy('grn.grn_id');
        return DataTables::of($grnQuery)
        ->addColumn('display', function ($grnQuery) {
            return '<div style="text-align:center">
                <a href="' . url('display_grn', $grnQuery->grn_id) . '" target="_blank"><i class="fa fa-list-alt fa-lg"></i></a>
            </div>';
        })
        ->rawColumns(['display'])
        ->filter(function ($query) use ($request) {
            if ($request->has('grn_no')) {
                $query->where('grn.grn_no', 'like', "%{$request->get('grn_no')}%");
            }
            if(isset($request->from_date) && $request->from_date != "" && isset($request->to_date) && $request->to_date != "") {
                $query->whereBetween(DB::raw('DATE(grn.grn_date)'),[$request->from_date,$request->to_date]);
            }
            if ($request->has('search') && ! is_null($request->get('search')['value']) ) {
                $regex = $request->get('search')['value'];
                return $query->where(function($queryNew) use($regex){
                    $queryNew->where('grn.grn_no', 'like', '%' . $regex . '%')
                    ->orWhere('grn.grn_date', 'like', '%' . $regex . '%')
                    ->orWhere('grn.grn_amt', 'like', '%' . $regex . '%');
                });
            }
        })
        ->make(true);
    }

    public function show($id){
        $grn = GoodReceivedNote::find($id);
        $grnItem = ReceivedStock::where('grn_id',$id)->orderBy('pro_id')->get();
        return view('grn.display_grn',['grn'=>$grn,'grnItem'=>$grnItem]);
    }
}
