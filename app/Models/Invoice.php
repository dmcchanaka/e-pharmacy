<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class Invoice extends Model
{
    use HasFactory, SoftDeletes, RevisionableTrait;

    protected $primaryKey = 'invoice_id';

    protected $table = 'invoice';

    protected $fillable = [
        'invoice_no',
        'invoice_date',
        'invoice_gross_amt',
        'invoice_discount',
        'invoice_net_amt',
        'added_by',
        'payment_type'
    ];

    public static function getInvoiceNo(){
        $newSoNo = NULL;
        $soNo = Invoice::whereNull('deleted_at')->max('invoice_no');
        if($soNo){
            $explodeString = explode("/",$soNo);
            $nxtNo = $explodeString[2] + 1;
            $newSoNo = 'POS/INV'.'/' . str_pad($nxtNo,5,0,STR_PAD_LEFT);
        } else {
            $newSoNo = 'POS/INV'.'/' . str_pad(1,5,0,STR_PAD_LEFT);
        }
        return $newSoNo;
    }

    public function getPaymentType(){
        if($this->payment_type == 1){
            return 'CASH';
        } else {
            return 'CREDIT/DEBIT CARD';
        }
    }
}
