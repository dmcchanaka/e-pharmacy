<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class Expenses extends Model
{
    use HasFactory, SoftDeletes, RevisionableTrait;

    protected $primaryKey = 'exp_id';

    protected $table = 'expenses';

    protected $fillable = [
        'exp_date',
        'exp_description',
        'exp_amt',
        'added_by'
    ];
}
