<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class Doctor extends Model
{
    use HasFactory, SoftDeletes, RevisionableTrait;

    protected $primaryKey = 'doctor_id';

    protected $table = 'doctors';

    protected $fillable = [
        'doctor_no',
        'doctor_name'
    ];

}
