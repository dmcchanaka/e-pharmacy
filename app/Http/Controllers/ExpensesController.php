<?php 
namespace App\Http\Controllers;

use App\Models\Expenses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class ExpensesController extends Controller {

    public function index(){
        return view('expenses.index');
    }

    public function store(Request $request){
        $added_by = Auth::user()->getKey();
        $product = Expenses::create([
            'exp_date'=>$request->exp_date,
            'exp_description'=>$request->exp_desc,
            'exp_amt'=>$request->exp_amt,
            'added_by'=>$added_by
        ]);

        if($product){
            return redirect()->route('expenses')->with('success', 'RECORD HAS BEEN SUCCESSFULLY INSERTED!');
        } else {
            return redirect()->route('expenses')->with('error', 'RECORD HAS NOT BEEN SUCCESSFULLY INSERTED!');
        }
    }

    public function view_expenses(){
        return view('expenses.view');
    }

    public function search(Request $request){
        $invQuery = DB::table('expenses as x')
        ->select([
            'x.exp_id',
            'x.exp_description',
            DB::raw("DATE_FORMAT(x.exp_date,'%Y-%m-%d') AS exp_date"),
            DB::raw("DATE_FORMAT(x.created_at,'%H:%i:%s') AS exp_time"),
            'x.exp_amt'
        ])
        ->whereNull('x.deleted_at')
        ->groupBy('x.exp_id')
        ->orderBy('x.exp_id');
        return DataTables::of($invQuery)
        ->filter(function ($query) use ($request) {
            if(isset($request->from_date) && $request->from_date != "" && isset($request->to_date) && $request->to_date != "") {
                $query->whereBetween(DB::raw('DATE(x.exp_date)'),[$request->from_date,$request->to_date]);
            }
            if ($request->has('search') && ! is_null($request->get('search')['value']) ) {
                $regex = $request->get('search')['value'];
                return $query->where(function($queryNew) use($regex){
                    $queryNew->where('x.exp_description', 'like', '%' . $regex . '%')
                    ->orWhere('x.exp_date', 'like', '%' . $regex . '%')
                    ->orWhere('x.exp_amt', 'like', '%' . $regex . '%');
                });
            }
        })
        ->make(true);
    }
}