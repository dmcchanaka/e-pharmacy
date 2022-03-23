<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class InvoiceOtherFee extends Model
{
    use HasFactory, SoftDeletes, RevisionableTrait;

    protected $primaryKey = 'iof_id';

    protected $table = 'invoice_other_fees';

    protected $fillable = [
        'invoice_id',
        'fee_id',
        'other_price'
    ];

    public function getFeeType(){
        $fee = OtherFee::find($this->fee_id);
        if($fee) return $fee->fee_description;
    }
}
