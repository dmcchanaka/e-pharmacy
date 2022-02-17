<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class InvoiceController extends Controller{

    public function index(){
        return view('invoice.index');
    }

    public function search_product(Request $request){
        $term = $request->term;

        $query = DB::table('product as p')
        ->select([
            'p.pro_name AS label',
            'p.pro_id AS id',
            'p.measure_of_units'
            ])
        ->whereNull('p.deleted_at')
        ->where('p.pro_name', 'LIKE', '%' . trim($term) . '%')
        ->groupBy('p.pro_id');

        $products = $query->get();
        return $products;
    }

    public function search_product_price(Request $request){
        $price = Product::where('pro_id',$request->item_id)->orderBy('pro_id','DESC')->limit(1)->get();
        $price->transform(function($ccpl) use($request){
            $price = NULL;
            $price = $ccpl->retailer_price;
            return [
                'price'=>$price
            ];
        });
        return $price;
    }

    public function test_print(){

        try {
            // Enter the share name for your USB printer here
            // $connector = null;
            $connector = new WindowsPrintConnector("EPSON TM-T81III Receipt"); //shared printer name
        
            /* Print a "Hello world" receipt" */
            $printer = new Printer($connector);
            $printer -> text("Hello World!\n");
            $printer -> cut();
            
            /* Close printer */
            $printer -> close();
        } catch (Exception $e) {
            echo "Couldn't print to this printer: " . $e -> getMessage() . "\n";
        }
    }
}