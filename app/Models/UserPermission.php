<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class UserPermission extends Model{

    use SoftDeletes, RevisionableTrait;

    protected $table = 'user_permission';

    protected $primaryKey = 'up_id';

    protected $fillable = [
        'per_gp_id','permission_id', 'permission_parent_id'
    ];

    public function navBarName(){
        $navbar = Permission::find($this->permission_id);
        return $navbar->navbar_name;
    }

    public function navBarUrl(){
        $navbar = Permission::find($this->permission_id);
        return $navbar->nubar_url;
    }

    public function menuThirdLvl(){
        $navbar = Permission::find($this->permission_id);
        return $navbar->third_level_menu_status;
    }
}