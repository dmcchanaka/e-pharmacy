<?php

namespace App\Http\Controllers\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ExpiryProductReportController extends Controller
{
    public function index(){
        $products = Product::get();
        return view('reports.expiry-product.index',compact('products'));
    }

    public function search(Request $request){
        $currentDate = date('Y-m-d');
        $forteenDaysAgo = date('Y-m-d', strtotime($currentDate. ' + 10 days'));
        $query = DB::table('received_stock as rs')
        ->join('product as p','p.pro_id','rs.pro_id')
        ->whereNull('p.deleted_at')
        ->whereNull('rs.deleted_at')
        ->where(function ($query) use($currentDate,$forteenDaysAgo) {
            $query->whereBetween('p.expiry_date', [$currentDate, $forteenDaysAgo])
                  ->orWhere('p.expiry_date', '<', $currentDate);
        })
        ->select([
            'p.pro_name',
            'rs.cost_price',
            DB::raw("SUM(rs.rs_qty) AS rs_qty"),
            DB::raw("SUM(rs.rs_remaining_qty) AS rs_remaining_qty"),
            DB::raw("DATEDIFF(p.expiry_date,'".$currentDate."')AS Days"),
            'p.expiry_date'
        ])
        ->groupBy('p.pro_id','rs.cost_price')
        ->orderBy('p.pro_name');

        if ($request->has('pro_id') && $request->get('pro_id') != "0") {
            $query->where('rs.pro_id', '=', "{$request->get('pro_id')}");
        }
        $received_stk = $query->get();
        return view('reports.expiry-product.load',compact('received_stk'));
    }
}
