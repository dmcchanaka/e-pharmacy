<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class Permission extends Model
{
    use SoftDeletes, RevisionableTrait;

    protected $table = 'permissions';

    protected $revisionCreationsEnabled = true;

    protected $primaryKey = 'permission_id';

    protected $fillable = [
        'section_name',
        'parent_section',
        'navbar_name',
        'nubar_url',
        'third_level_menu_status',
        'menu_icon'
    ];

    public function navBarName(){
        return $this->navbar_name;
    }

    public function navBarUrl(){
        return $this->nubar_url;
    }

    public function menuThirdLvl(){
        return $this->third_level_menu_status;
    }
}
