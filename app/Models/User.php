<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'u_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'email','u_tp_id','username', 'password','per_gp_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getPermissionGroup(){
        $perGroup = PermissionGroup::find($this->per_gp_id);
        if($perGroup)
        return $perGroup->per_gp_name;
    }

    public static function userPrivilage($per_gp_id,$user_type){

        if($user_type == config('pos.super_admin_user_type')[0]){

            $mainPermission = Permission::whereNull('parent_section')->get();
            $mainPermission->transform(function($mp){

                $subSection = Permission::where('parent_section',$mp->permission_id)->get();
                $subSection->transform(function($ss){
                    $bottomSection = Permission::where('parent_section',$ss->permission_id)->get();
                        $bottomSection->transform(function($bs){
                            $fourthSection = Permission::where('parent_section',$bs->permission_id)->get();
                            $fourthSection->transform(function($fth){
                                return [
                                    'fourth_sec_id'=>$fth->permission_id,
                                    'parent_id'=>$fth->parent_section,
                                    'fourth_sec_nav_name'=>$fth->navBarName(),
                                    'fourth_sec_nav_url'=>$fth->navBarUrl()
                                ];
                            });
                            return [
                                'bottom_sec_id'=>$bs->permission_id,
                                'parent_id'=>$bs->parent_section,
                                'bottom_sec_nav_name'=>$bs->navBarName(),
                                'bottom_sec_nav_url'=>$bs->navBarUrl(),
                                'fourthSection'=>$fourthSection
                            ];
                        });
                    return [
                        'sub_sec_id'=>$ss->permission_id,
                        'parent_id'=>$ss->parent_section,
                        'permission_group_id'=>NULL,
                        'sub_sec_nav_name'=>$ss->navBarName(),
                        'sub_sec_nav_url'=>$ss->navBarUrl(),
                        'third_lvl_nav_status'=>$ss->menuThirdLvl(),
                        'bottom_section'=>$bottomSection
                    ];
                });
                return [
                    'permission_group_id'=>NULL,
                    'main_sec_id'=>$mp->permission_id,
                    'parent_id'=>$mp->parent_section,
                    'main_sec_nav_name'=>$mp->navBarName(),
                    'main_sec_nav_url'=>$mp->navBarUrl(),
                    'sub_section'=>$subSection
                ];
            });

            return $mainPermission;

        } else {
            $userPermissionGroup = UserPermission::where('per_gp_id',$per_gp_id)->latest()->first();
            if($userPermissionGroup){
                $userPermission = UserPermission::where('per_gp_id',$userPermissionGroup->per_gp_id)->whereNull('permission_parent_id')->get();
                $userPermission->transform(function($up){
                    $subSection = UserPermission::where('permission_parent_id',$up->permission_id)->where('per_gp_id',$up->per_gp_id)->get();
                    $subSection->transform(function($ss){
                        $bottomSection = UserPermission::where('permission_parent_id',$ss->permission_id)->where('per_gp_id',$ss->per_gp_id)->get();
                        $bottomSection->transform(function($bs){
                            $fourthSection = UserPermission::where('permission_parent_id',$bs->permission_id)->where('per_gp_id',$bs->per_gp_id)->get();
                            $fourthSection->transform(function($bs){
                                return [
                                    'fourth_sec_id'=>$bs->permission_id,
                                    'parent_id'=>$bs->permission_parent_id,
                                    'fourth_sec_nav_name'=>$bs->navBarName(),
                                    'fourth_sec_nav_url'=>$bs->navBarUrl()
                                ];
                            });
                            return [
                                'bottom_sec_id'=>$bs->permission_id,
                                'parent_id'=>$bs->permission_parent_id,
                                'bottom_sec_nav_name'=>$bs->navBarName(),
                                'bottom_sec_nav_url'=>$bs->navBarUrl(),
                                'fourthSection'=>$fourthSection
                            ];
                        });
                        return [
                            'sub_sec_id'=>$ss->permission_id,
                            'parent_id'=>$ss->permission_parent_id,
                            'permission_group_id'=>$ss->per_gp_id,
                            'sub_sec_nav_name'=>$ss->navBarName(),
                            'sub_sec_nav_url'=>$ss->navBarUrl(),
                            'third_lvl_nav_status'=>$ss->menuThirdLvl(),
                            'bottom_section'=>$bottomSection
                        ];
                    });
                    return [
                        'permission_group_id'=>$up->per_gp_id,
                        'main_sec_id'=>$up->permission_id,
                        'parent_id'=>$up->permission_parent_id,
                        'main_sec_nav_name'=>$up->navBarName(),
                        'main_sec_nav_url'=>$up->navBarUrl(),
                        'sub_section'=>$subSection
                    ];
                });
            }
            return $userPermission;
        }
        // return $userPermission;
        // return $this->hasMany('App\Models\UserPermission','per_gp_id','per_gp_id');
    }

    public static function checkUserPermission($per_gp_id,$user_type,$section_name){
        if($user_type == config('pos.super_admin_user_type')[0]){
            $mainPermission = Permission::where('section_name',$section_name)->latest()->first();
            if($mainPermission){
                return 1;
            } else{
                return 0;
            }
        } else {
            $mainPermission = Permission::where('section_name',$section_name)->latest()->first();
            if($mainPermission){
                $userPermission = UserPermission::where('per_gp_id',$per_gp_id)->where('permission_id',$mainPermission->permission_id)->latest()->first();
                if($userPermission) {
                    return 1;
                } else {
                    return 0;
                }
            } else {
                return 0;
            }
        }
    }
}
