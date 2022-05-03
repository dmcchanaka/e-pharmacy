<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class UserType extends Model{
    use SoftDeletes, RevisionableTrait;

    protected $table = 'user_type';

    protected $primaryKey = 'u_tp_id';

    protected $fillable = [
        'user_type_no','user_type_code','user_type'
    ];
}