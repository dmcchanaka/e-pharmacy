<?php 
namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FastMovingItemsReportController extends Controller{

    public function index(){
        $products = Product::get();
        return view('reports.fast-moving-items.index',compact('products'));
    }

    public function search(Request $request){
        $query = DB::table('invoice as i')
        ->join('invoice_product as ip','i.invoice_id','ip.invoice_id')
        ->join('product as p','p.pro_id','ip.pro_id')
        ->whereNull('p.deleted_at')
        ->whereNull('i.deleted_at')
        ->whereNull('ip.deleted_at')
        ->select([
            'p.pro_name',
            'ip.ip_buying_price',
            'ip.ip_price',
            DB::raw("SUM(ip.ip_qty) AS sold_qty"),
            DB::raw("SUM(ip.ip_qty * ip.ip_buying_price) AS wholesale_amt"),
            DB::raw("SUM(ip.ip_qty * ip.ip_price) AS retailer_amt"),
        ])
        ->groupBy('p.pro_id','ip.ip_price')
        ->orderByRaw('SUM(ip.ip_qty) DESC');
        // ->orderBy('p.pro_name');

        if ($request->has('pro_id') && $request->get('pro_id') != "0") {
            $query->where('ip.pro_id', '=', "{$request->get('pro_id')}");
        }
        if(isset($request->start_date) && $request->start_date != "" && isset($request->end_date) && $request->end_date != "") {
            $query->whereBetween('i.invoice_date',[$request->start_date. ' 00:00:00',$request->end_date. ' 23:59:59']);
        }
        $daily_sales = $query->get();
        return view('reports.fast-moving-items.load',compact('daily_sales'));
    }
}