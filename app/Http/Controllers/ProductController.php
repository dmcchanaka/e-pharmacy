<?php

namespace App\Http\Controllers;

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
            'retailer_price'=>$request->retailer_price
        ]);

        if($product){
            $notification=array(
                'message'=>"Category has been added",
                'alert-type'=>'success',
            );
            return back()->with($notification);
            // toastr()->success('RECORD HAS BEEN SUCCESSFULLY INSERTED!');
            // return redirect('product');
            // return redirect()->route('product')->with('success', 'RECORD HAS BEEN SUCCESSFULLY INSERTED!');
        } else {
            $notification=array(
                'message'=>"Category has not been added",
                'alert-type'=>'error',
            );
            return back()->with($notification);
            // toastr()->success('RECORD HAS NOT BEEN SUCCESSFULLY INSERTED!');
            // return redirect('product');
            // return redirect()->route('product')->with('error', 'RECORD HAS NOT BEEN SUCCESSFULLY INSERTED!');
        }
    }
}
