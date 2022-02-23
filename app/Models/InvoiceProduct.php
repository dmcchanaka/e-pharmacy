<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class InvoiceProduct extends Model
{
    use HasFactory, SoftDeletes, RevisionableTrait;

    protected $primaryKey = 'ip_id';

    protected $table = 'invoice_product';

    protected $fillable = [
        'line_no',
        'invoice_id',
        'pro_id',
        'ip_buying_price',
        'ip_price',
        'ip_qty',
        'ip_line_amt'
    ];

    public function getProduct(){
        $pro = Product::find($this->pro_id);
        if($pro) return $pro->pro_name;
    }
    public function getProductCode(){
        $pro = Product::find($this->pro_id);
        if($pro) return $pro->pro_code;
    }
}
