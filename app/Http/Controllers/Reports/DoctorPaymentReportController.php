<?php 
namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DoctorPaymentReportController extends Controller{

    public function index(){
        $doctors = Doctor::get();
        return view('reports.doctor_payments.index',compact('doctors'));
    }

    public function search(Request $request){
        $query = DB::table('invoice as i')
        ->join('doctors as d','d.doctor_id','i.doctor_id')
        ->whereNull('d.deleted_at')
        ->whereNull('i.deleted_at')
        ->select([
            'd.doctor_name',
            DB::raw("COUNT(i.invoice_id) AS doc_consult_count"),
            DB::raw("SUM(i.doc_consult_fee) AS doc_consult_fee"),
        ])
        ->groupBy('d.doctor_id')
        ->orderBy('d.doctor_name');

        if ($request->has('doctor_id') && $request->get('doctor_id') != "0") {
            $query->where('i.doctor_id', '=', "{$request->get('doctor_id')}");
        }
        if(isset($request->start_date) && $request->start_date != "" && isset($request->end_date) && $request->end_date != "") {
            $query->whereBetween('i.invoice_date',[$request->start_date. ' 00:00:00',$request->end_date. ' 23:59:59']);
        }
        $doctorPayments = $query->get();
        return view('reports.doctor_payments.load',compact('doctorPayments'));
    }
}