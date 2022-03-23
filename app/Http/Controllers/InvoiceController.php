<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Invoice;
use App\Models\InvoiceOtherFee;
use App\Models\InvoiceProduct;
use App\Models\OtherFee;
use App\Models\Product;
use App\Models\ReceivedStock;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Yajra\DataTables\Facades\DataTables;

class InvoiceController extends Controller{

    public function index(){
        $doctors = Doctor::get();
        $otherFees = OtherFee::get();
        return view('invoice.index', compact('doctors','otherFees'));
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

    public function search_other_fee(Request $request){
        $other_fee = OtherFee::get();
        return $other_fee;
    }

    public function search_other_fee_by_id(Request $request){
        $other_fee = OtherFee::find($request->other_type);
        return $other_fee;
    }

    public function search_product_stock(Request $request){
        $received_stk = ReceivedStock::where('pro_id',$request->item_id)->where('rs_remaining_qty','>',0)->get();
        return ['stock'=>$received_stk->sum('rs_remaining_qty')];
    }

    public function store(Request $request){
        // return $request;
        DB::beginTransaction();
        try {
            $added_by = Auth::user()->getKey();
            $inv_no = Invoice::getInvoiceNo();

            $ckInvNo = Invoice::where('invoice_no',$request->invoice_no)->get();
            // return $ckInvNo;
            if(!$ckInvNo->isEmpty()){
                return redirect()->route('invoice')->with('error', 'RECORD IS ALREADY EXIST!');
            }

            $invoice = Invoice::create([
                'invoice_no'=>$inv_no,
                'invoice_date'=>date('Y-m-d H:i:s'),
                'invoice_gross_amt'=>$request->tot_amount,
                'invoice_net_amt'=>$request->net_amount,
                'doctor_id'=>$request->doctor_id,
                'added_by'=>$added_by,
                'price_option'=>$request->inv_type,
                'payment_type'=>1,
                'invoice_discount_per'=>$request->discount,
                'invoice_discount'=>$request->discount_amt,
                'doc_consult_fee'=>$request->consultation_amt
            ]);
            $lastInvoice = Invoice::select('invoice_id', 'invoice_no')->where('added_by',$added_by)->latest()->first();
            $totalInvAmt = 0;
            $sold_qty = 0;
            $sold_price = 0;
            $cost_price = 0;
            for ($i = 1; $i <= $request->item_count; $i++) {
                if(isset($request['pro_id_'.$i])){
                    $marketPrice = Product::where('pro_id',$request['pro_id_' . $i])->latest()->first();

                    $sold_qty = $request['qty_' . $i];
                    $sold_price = $request['prc_' . $i];
                    $cost_price = $marketPrice->buying_price;

                    $total_remain_qty = 0;
                    $received_stk = ReceivedStock::where('pro_id',$request['pro_id_' . $i])->where('rs_remaining_qty','>',0)->get();
                    foreach($received_stk as $stk) {
                        //for update stock
                        $rp_remaining_qty = $stk->rs_remaining_qty;
                        $total_remain_qty += $stk->rs_remaining_qty;

                        if ($rp_remaining_qty >= $sold_qty && $sold_qty > 0) {

                            $line_amt = 0;
                            if($sold_qty > 0) {
                                $line_amt = $sold_qty * str_replace(',', '',$request['prc_' . $i]);
                                $totalInvAmt += $sold_qty * str_replace(',', '',$request['prc_' . $i]);
                            }
    
                            /**INSERT INVOICE PRODUCT */
                            $invProduct = InvoiceProduct::create([
                                'line_no'=>$i,
                                'invoice_id'=>$lastInvoice->invoice_id,
                                'pro_id'=>$request['pro_id_' . $i],
                                'ip_buying_price'=>$cost_price,
                                'ip_price'=>$sold_price,
                                'ip_qty'=>$sold_qty,
                                'ip_line_amt'=>str_replace(',', '',$line_amt)
                            ]);
    
                            $update_stk = ReceivedStock::find($stk->rs_id);
                            $update_stk->rs_remaining_qty = round($stk->rs_remaining_qty - $sold_qty,2);
                            $update_stk->save();
    
                            $sold_qty = 0;
                        } else if ($rp_remaining_qty < $sold_qty) {
                            $line_amt = 0;
                            if($sold_qty > 0) {
                                $line_amt = $sold_qty * str_replace(',', '',$request['prc_' . $i]);
                                $totalInvAmt += $sold_qty * str_replace(',', '',$request['prc_' . $i]);
                            }
                            /**INSERT INVOICE PRODUCT */
                            $invProduct = InvoiceProduct::create([
                                'line_no'=>$i,
                                'invoice_id'=>$lastInvoice->invoice_id,
                                'pro_id'=>$request['pro_id_' . $i],
                                'ip_buying_price'=>$cost_price,
                                'ip_price'=>$sold_price,
                                'ip_qty'=>$sold_qty,
                                'ip_line_amt'=>str_replace(',', '',$line_amt)
                            ]);
    
                            $update_stk = ReceivedStock::find($stk->rs_id);
                            $update_stk->rs_remaining_qty = 0;
                            $update_stk->save();
                            /** */
                            $sold_qty -= $rp_remaining_qty;
                        }
                        if((count($received_stk)==0 || $total_remain_qty < $request['pro_id_' . $i])){
                            DB::rollback();
                            $pro_name = Product::find($request['pro_id_' . $i]);
                            return redirect()->route('invoice')->with('error', 'Stock is not enough to invoice '.$pro_name->pro_name.' product');
                        }
                    }
                }
            }

            /**
             * OTHER FEES
             */
            $otherPyments = 0;
            for ($j = 1; $j <= $request->other_item_count; $j++) {
                if(isset($request['other_type_'.$j])){
                    $otherPyments += $request['other_amt_'.$j];
                    $invoiceOtherFee = InvoiceOtherFee::create([
                        'invoice_id'=>$lastInvoice->invoice_id,
                        'fee_id'=>$request['other_type_'.$j],
                        'other_price'=>$request['other_amt_'.$j]
                    ]);
                }
            }

            $upInvoice = Invoice::find($lastInvoice->invoice_id);
            // $upInvoice->invoice_gross_amt = $totalInvAmt;
            $upInvoice->invoice_other_amt = $otherPyments;
            $upInvoice->save();

            DB::commit();

            return redirect()->route('print_invoice',['id' => $lastInvoice->invoice_id]);
            // return redirect()->route('invoice')->with('success', 'RECORD HAS BEEN SUCCESSFULLY INSERTED!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('invoice')->with('error', 'RECORD HAS NOT BEEN SUCCESSFULLY INSERTED!');
        }
    }

    public function print_invoice($id){
        $invoice = Invoice::find($id);
        $invoice_product = DB::table('invoice_product as ip')
            ->join('product as p', 'p.pro_id', 'ip.pro_id')
            ->whereNull('p.deleted_at')
            ->whereNull('ip.deleted_at')
            ->where('ip.invoice_id', $id)
            ->groupBy('p.pro_id')
            ->orderBy('p.pro_name')
            ->get();

            try {
                // Enter the share name for your USB printer here
                // $connector = null;
                $connector = new WindowsPrintConnector("EPSON TM-T81III Receipt"); //shared printer name
            
                $printer = new Printer($connector);
                $printer->setFont(Printer::FONT_B); // Dot Matrix - Default
                $printer->setJustification(Printer::JUSTIFY_CENTER);
                $printer->setTextSize(2, 1); //text size
                $printer->text("CITIZENS HOSPITAL (PVT) LTD\n");
                $printer->setTextSize(1, 1);
                $printer->text("No.338/1, Nugagoda junction, Bandaragama road, Waskaduwa.\n");
                $printer->text("TP - 034 22 30 840 | 070 35 14 555 \n");

                $printer->text("--------------------------------------------------------------\n");
                $printer->setJustification(Printer::JUSTIFY_LEFT);
                $printDate = sprintf('%s %-13.40s',"Date & Time :",Date('Y-m-d H:i:s'));
                $printer->text($printDate. "\n");
                $invNo = sprintf('%s %-13.40s',"Reference No :",$invoice->invoice_no);
                $printer->text($invNo. "\n");
                $paymentType = NULL;
                if($invoice->payment_type == 1){
                    $paymentType = 'Cash';
                } elseif($invoice->payment_type == 2){
                    $paymentType = 'Credit/Debit Card';
                }
                $invNo = sprintf('%s %-13.40s',"Payment Type :",$paymentType);
                $printer->text($invNo. "\n");

                $printer->text("--------------------------------------------------------------\n");
                $printer->setJustification(Printer::JUSTIFY_LEFT);
                $printer->setEmphasis(true);
                $printer->text("Code              Description          Qty          Amount\n");
                $printer->setEmphasis(false);
                $gross = 0;
                foreach ($invoice_product as $key=>$item) {
                   $gross+= $item->ip_line_amt;

                    $line = sprintf('%s. %-13.40s %-3.40s',($key+1),$item->pro_code,$item->pro_name,$item->ip_qty);
                    $lineNew = sprintf('%40s %18s',$item->ip_qty,$item->ip_line_amt);
                    $printer -> text("$line\n");
                    $printer -> text("$lineNew\n");
                }
                $printer->text("--------------------------------------------------------------");

                $grandLine = sprintf('%40s %20.2f',"Net Total",$gross);
                $printer->text($grandLine);
                $printer -> text("\n");
                $printer->text("--------------------------------------------------------------");
                $printer -> text("\n");

                $printer->selectPrintMode();

                /* Footer */
                $printer->feed(2);
                $printer->setJustification(Printer::JUSTIFY_CENTER);
                $printer->text("THANK YOU.PLEASE COME AGAIN.\n");
                $printer->feed(2);

                $printer -> cut();
                
                /* Close printer */
                $printer -> close();
            } catch (Exception $e) {
                echo "Couldn't print to this printer: " . $e -> getMessage() . "\n";
            }
            return redirect()->route('invoice');
    }

    public function view_invoice(){
        $doctors = Doctor::get();
        return view('invoice.view',compact('doctors'));
    }

    public function search(Request $request){
        $invQuery = DB::table('invoice as i')
        // ->join('invoice_product as ip','ip.invoice_id','i.invoice_id')
        ->join('doctors as d','i.doctor_id','d.doctor_id')
        ->select([
            'i.invoice_id',
            'i.invoice_no',
            DB::raw("DATE_FORMAT(i.invoice_date,'%Y-%m-%d') AS invoice_date"),
            DB::raw("DATE_FORMAT(i.invoice_date,'%H:%i:%s') AS invoice_time"),
            'i.payment_type',
            'i.invoice_gross_amt',
            'i.invoice_net_amt',
            'd.doctor_name'
        ])
        ->whereNull('i.deleted_at')
        // ->whereNull('ip.deleted_at')
        ->groupBy('i.invoice_id')
        ->orderBy('i.invoice_id', 'DESC');
        return DataTables::of($invQuery)
        ->addColumn('payment_type', function ($invQuery) {
            if($invQuery->payment_type == 1){
                return '<div style="text-align:center"><span>CASH</span></div>';
            } elseif($invQuery->payment_type == 2){
                return '<div style="text-align:center"><span>CREDIT/DEBIT CARD</span></div>';
            }
        })
        ->addColumn('display', function ($invQuery) {
            return '<div style="text-align:center">
                <a href="' . url('display_invoice', $invQuery->invoice_id) . '" target="_blank"><i class="fa fa-list-alt fa-lg"></i></a>
            </div>';
        })
        ->rawColumns(['payment_type','display'])
        ->filter(function ($query) use ($request) {
            if ($request->has('doctor_id') && $request->get('doctor_id') != "0") {
                $query->where('i.doctor_id', '=', "{$request->get('doctor_id')}");
            }
            if ($request->has('invoice_no')) {
                $query->where('i.invoice_no', 'like', "%{$request->get('invoice_no')}%");
            }
            if(isset($request->from_date) && $request->from_date != "" && isset($request->to_date) && $request->to_date != "") {
                $query->whereBetween(DB::raw('DATE(i.invoice_date)'),[$request->from_date,$request->to_date]);
            }
            if ($request->has('search') && ! is_null($request->get('search')['value']) ) {
                $regex = $request->get('search')['value'];
                return $query->where(function($queryNew) use($regex){
                    $queryNew->where('i.invoice_no', 'like', '%' . $regex . '%')
                    ->orWhere('i.invoice_date', 'like', '%' . $regex . '%')
                    ->orWhere('i.invoice_gross_amt', 'like', '%' . $regex . '%')
                    ->orWhere('i.invoice_net_amt', 'like', '%' . $regex . '%');
                });
            }
        })
        ->make(true);
    }

    public function show($id){
        $invoice = Invoice::find($id);
        $invoiceItem = InvoiceProduct::where('invoice_id',$id)->orderBy('line_no')->get();
        $invoiceOtherFee = InvoiceOtherFee::where('invoice_id',$id)->get();
        return view('invoice.display',['invoice'=>$invoice,'invoiceItem'=>$invoiceItem,'invoiceOtherFee'=>$invoiceOtherFee]);
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