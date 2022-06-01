<?php

namespace App\Console\Commands;

use App\Mail\NotifyMail;
use App\Models\Expenses;
use App\Models\Invoice;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class sendDailySummaryMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:daily_summary';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try{
            $start = date('Y-m-d'). ' 00:00:00';
            $end = date('Y-m-d'). ' 23:59:59';

            $invoice = Invoice::whereBetween('invoice_date',[$start,$end])->get();

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
            ->orderBy('p.pro_name');
            $query->whereBetween('i.invoice_date',[$start,$end]);
            $daily_sales = $query->get();

            $expenses = Expenses::where('exp_date',date('Y-m-d'))->get();

            $details = [
                'title'=>'Daily summary of citizens hospital (PVT) Ltd',
                'consultFee'=>$invoice->sum('doc_consult_fee'),
                'pharmacySaleProductQty'=>$daily_sales->sum('sold_qty'),
                'pharmacySale'=>$daily_sales->sum('retailer_amt'),
                'pharmacyProfit'=>($daily_sales->sum('retailer_amt') - $daily_sales->sum('wholesale_amt')),
                'expenses'=>$expenses->sum('exp_amt')
            ];

            Mail::to('tbpliyanage@gmail.com')->send(new NotifyMail($details));
            $this->info('Email has been successfully sent');
        } catch(Exception $e){
            $this->error($e->getMessage());
        }
      
    }
}
