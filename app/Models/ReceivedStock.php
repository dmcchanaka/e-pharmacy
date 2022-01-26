<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class ReceivedStock extends Model
{
    use HasFactory, SoftDeletes, RevisionableTrait;

    protected $primaryKey = 'rs_id';

    protected $table = 'received_stock';

    protected $fillable = [
        'grn_id',
        'pro_id',
        'cost_price',
        'rs_qty',
        'rs_remaining_qty'
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
