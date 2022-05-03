<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class PermissionGroup extends Model{

    use SoftDeletes, RevisionableTrait;

    protected $table = 'permission_group';

    protected $primaryKey = 'per_gp_id';

    protected $fillable = [
        'per_gp_name','u_tp_id'
    ];

    public function getUserType(){
        $userType = UserType::find($this->u_tp_id);
        return $userType->user_type;
    }
}