<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Milon\Barcode\DNS1D;
use Venturecraft\Revisionable\RevisionableTrait;

class Product extends Model{

    use SoftDeletes, RevisionableTrait;

    protected $primaryKey = 'pro_id';

    protected $table = 'product';

    protected $fillable = [
        'pro_code',
        'pro_name',
        'measure_of_units',
        'buying_price',
        'retailer_price',
        'deactivated_at'
    ];
}
