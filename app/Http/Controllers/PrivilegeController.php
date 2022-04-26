<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\PermissionGroup;
use App\Models\UserPermission;
use App\Models\UserType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrivilegeController extends Controller{

    
    public function index(){
        $userType = UserType::whereNotIn('u_tp_id',config('pos.super_admin_user_type'))->orderBy('user_type')->get();
        $mainPermission = Permission::whereNull('parent_section')->get();
        $mainPermission->transform(function ($mp) {
            $subSection = Permission::where('parent_section', '=', $mp->permission_id)->get();
            $subSection->transform(function ($sub) {
                    $bottomSection = Permission::where('parent_section', '=', $sub->permission_id)->get();
                    $bottomSection->transform(function($btm){
                        $fourthSection = Permission::where('parent_section', '=', $btm->permission_id)->get();
                        $fourthSection->transform(function($ft){
                            return [
                                'forth_sec_id' => $ft->permission_id,
                                'forth_sec_name' => $ft->section_name
                            ];
                        });
                        return [
                            'bottom_sec_id' => $btm->permission_id,
                            'bottom_sec_name' => $btm->section_name,
                            'forth_section'=> $fourthSection
                        ];
                    });
                    return [
                        'sub_sec_id' => $sub->permission_id,
                        'sub_sec_name' => $sub->section_name,
                        'third_level_menu_status' => $sub->third_level_menu_status,
                        'bottom_section' => $bottomSection,
                    ];
            });
            return [
                'main_permission_id' => $mp->permission_id,
                'section_name' => $mp->section_name,
                'sub_section' => $subSection,
            ];
        });
        return view('privilege.index',['userType'=>$userType,'mainPermission'=>$mainPermission]);
    }

    public function save(Request $request) {
        DB::beginTransaction();
        try {
            if (isset($request->main_count) && $request->main_count > 0) {

                $permissionGroup = PermissionGroup::create([
                    'per_gp_name' => $request->per_group,
                    'u_tp_id' => $request->u_tp_id,
                ]);

                $permissionGroup = PermissionGroup::select('per_gp_id')
                    ->latest()
                    ->first();

                /**FIRST LEVEL MENU */
                if(isset($request->main_count) && $request->main_count>0){
                    for ($i = 1; $i <= $request->main_count; $i++) {
                        if ($request['main_sec_status_' . $i] == '1') {

                            $userPermission = UserPermission::create([
                                'per_gp_id' => $permissionGroup->per_gp_id,
                                'permission_id' => $request['main_sec_' . $i],
                                'permission_parent_id' => NULL,
                            ]);

                            /**SECOND LEVEL MENU */
                            if(isset($request['sub_count_' . $i]) && $request['sub_count_' . $i]>0){
                                for ($j = 1; $j <= $request['sub_count_' . $i]; $j++) {
                                    if ($request['sub_sec_status_' . $i .'_'. $j] == '1') {
                                        $userPermission = UserPermission::create([
                                            'per_gp_id' => $permissionGroup->per_gp_id,
                                            'permission_id' => $request['sub_sec_' . $i . '_'. $j],
                                            'permission_parent_id' => $request['main_sec_' . $i]
                                        ]);
                                    }

                                    /**THIRD LEVEL MENU */
                                    if(isset($request['bottom_count_'. $i .'_'.$j]) && $request['bottom_count_'. $i .'_'.$j]>0 && $request['third_lvl_status_'. $i .'_'.$j] == 0){
                                        for ($k = 1; $k <= $request['bottom_count_'. $i .'_'.$j]; $k++) {
                                            if ($request['bottom_sec_status_' . $i .'_'. $j .'_'.$k] == '1') {
                                                $userPermission = UserPermission::create([
                                                    'per_gp_id' => $permissionGroup->per_gp_id,
                                                    'permission_id' => $request['bottom_sec_' . $i . '_'. $j . '_'. $k],
                                                    'permission_parent_id' => $request['sub_sec_' . $i . '_'. $j]
                                                ]);
                                            }
                                        }
                                    } else if (isset($request['bottom_count_'. $i .'_'.$j]) && $request['bottom_count_'. $i .'_'.$j]>0 && $request['third_lvl_status_'. $i .'_'.$j] == 1){
                                        for ($k = 1; $k <= $request['bottom_count_'. $i .'_'.$j]; $k++) {
                                            if ($request['bottom_sec_status_' . $i .'_'. $j .'_'.$k] == '1') {
                                                $userPermission = UserPermission::create([
                                                    'per_gp_id' => $permissionGroup->per_gp_id,
                                                    'permission_id' => $request['bottom_sec_' . $i . '_'. $j . '_'. $k],
                                                    'permission_parent_id' => $request['sub_sec_' . $i . '_'. $j]
                                                ]);
                                            }
                                            /**FOURTH LEVEL MENU */
                                            if(isset($request['fourth_count_' . $i . '_' . $j . '_' . $k]) && $request['fourth_count_' . $i . '_' . $j . '_' . $k]>0){
                                                for ($l = 1; $l <= $request['fourth_count_'. $i .'_'.$j . '_' . $k]; $l++) {
                                                    if ($request['fourth_sec_status_' . $i .'_'. $j .'_'.$k . '_' . $l] == '1') {
                                                        $userPermission = UserPermission::create([
                                                            'per_gp_id' => $permissionGroup->per_gp_id,
                                                            'permission_id' => $request['fourth_sec_' . $i . '_'. $j . '_'. $k . '_' . $l],
                                                            'permission_parent_id' => $request['bottom_sec_' . $i . '_'. $j . '_'. $k]
                                                        ]);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

            DB::commit();
            return redirect()->route('privilege')->with('success', 'RECORD HAS BEEN SUCCESSFULLY INSERTED!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('privilege')->with('error', 'RECORD HAS NOT BEEN SUCCESSFULLY INSERTED!');
        }
    }

    public function show() {

        $userType = UserType::whereNotIn('u_tp_id',config('pos.super_admin_user_type'))->get();
        return view('privilege.view',['userType'=>$userType]);
    }

    public function search(Request $request){
        $permission = DB::table('permission_group AS pg')
        ->join('user_type AS ut','ut.u_tp_id','pg.u_tp_id')
        ->whereNull('pg.deleted_at')
        ->whereNull('ut.deleted_at')
        ->select([
            'pg.per_gp_id',
            'pg.per_gp_name',
            'ut.u_tp_id',
            'ut.user_type'
        ])
        ->get();
        return view('privilege.load',['permission'=>$permission]);
    }

    public function display($id){
        $permissionGroup = PermissionGroup::find($id);
        $userPermission = UserPermission::where('per_gp_id',$id)->whereNull('permission_parent_id')->get();
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
        return view('privilege.display',['permissionGroup'=>$permissionGroup,'userPermission'=>$userPermission]);
    }

    public function edit($id){
        $permissionGroup = PermissionGroup::find($id);
        $mainPermission = Permission::whereNull('parent_section')->get();
        $mainPermission->transform(function ($mp) use ($id) {
            $addedMainSection = UserPermission::where('per_gp_id',$id)->whereNull('permission_parent_id')->where('permission_id',$mp->permission_id)->latest()->first();

            $subSection = Permission::where('parent_section', '=', $mp->permission_id)->get();
            $subSection->transform(function ($sub) use ($id,$mp) {
                $addedSubSection = UserPermission::where('per_gp_id',$id)->where('permission_parent_id',$mp->permission_id)->where('permission_id',$sub->permission_id)->latest()->first();

                $bottomSection = Permission::where('parent_section', '=', $sub->permission_id)->get();
                $bottomSection->transform(function($btm) use($id,$sub){

                    $addedBottomSection = UserPermission::where('per_gp_id',$id)->where('permission_parent_id',$sub->permission_id)->where('permission_id',$btm->permission_id)->latest()->first();

                    $fourthSection = Permission::where('parent_section', '=', $btm->permission_id)->get();
                    $fourthSection->transform(function($ft) use($id,$btm){

                        $addedFourthSection = UserPermission::where('per_gp_id',$id)->where('permission_parent_id',$btm->permission_id)->where('permission_id',$ft->permission_id)->latest()->first();
                        return [
                            'forth_sec_id' => $ft->permission_id,
                            'forth_sec_name' => $ft->section_name,
                            'added_fourth_permission_id' =>$addedFourthSection?$addedFourthSection->permission_id:""
                        ];
                    });
                    return [
                        'bottom_sec_id' => $btm->permission_id,
                        'bottom_sec_name' => $btm->section_name,
                        'added_bottom_permission_id' => $addedBottomSection? $addedBottomSection->permission_id:"",
                        'forth_section'=> $fourthSection
                    ];
                });
                return [
                    'sub_sec_id' => $sub->permission_id,
                    'sub_sec_name' => $sub->section_name,
                    'third_level_menu_status' => $sub->third_level_menu_status,
                    'added_sub_permission_id' => $addedSubSection?$addedSubSection->permission_id:"",
                    'bottom_section' => $bottomSection
                ];
            });
            return [
                'main_permission_id' => $mp->permission_id,
                'section_name' => $mp->section_name,
                'added_permission_id'=> ($addedMainSection)?$addedMainSection->permission_id:"",
                'sub_section' => $subSection,
            ];
        });

        return view('privilege.edit',['permissionGroup'=>$permissionGroup,'mainPermission'=>$mainPermission]);
    }

    function update(Request $request){
        DB::beginTransaction();
        try {
            if (isset($request->main_count) && $request->main_count > 0) {
                
                /**DELETE PREVIOUS PERMISSION */
                UserPermission::where('per_gp_id',$request->per_group)->delete();

                /**FIRST LEVEL MENU */
                if(isset($request->main_count) && $request->main_count>0){
                    for ($i = 1; $i <= $request->main_count; $i++) {
                        if ($request['main_sec_status_' . $i] == '1') {

                            $userPermission = UserPermission::create([
                                'per_gp_id' => $request->per_group,
                                'permission_id' => $request['main_sec_' . $i],
                                'permission_parent_id' => NULL,
                            ]);

                            /**SECOND LEVEL MENU */
                            if(isset($request['sub_count_' . $i]) && $request['sub_count_' . $i]>0){
                                for ($j = 1; $j <= $request['sub_count_' . $i]; $j++) {
                                    if ($request['sub_sec_status_' . $i .'_'. $j] == '1') {
                                        $userPermission = UserPermission::create([
                                            'per_gp_id' => $request->per_group,
                                            'permission_id' => $request['sub_sec_' . $i . '_'. $j],
                                            'permission_parent_id' => $request['main_sec_' . $i]
                                        ]);
                                    }

                                    /**THIRD LEVEL MENU */
                                    if(isset($request['bottom_count_'. $i .'_'.$j]) && $request['bottom_count_'. $i .'_'.$j]>0 && $request['third_lvl_status_'. $i .'_'.$j] == 0){
                                        for ($k = 1; $k <= $request['bottom_count_'. $i .'_'.$j]; $k++) {
                                            if ($request['bottom_sec_status_' . $i .'_'. $j .'_'.$k] == '1') {
                                                $userPermission = UserPermission::create([
                                                    'per_gp_id' => $request->per_group,
                                                    'permission_id' => $request['bottom_sec_' . $i . '_'. $j . '_'. $k],
                                                    'permission_parent_id' => $request['sub_sec_' . $i . '_'. $j]
                                                ]);
                                            }
                                        }
                                    } else if (isset($request['bottom_count_'. $i .'_'.$j]) && $request['bottom_count_'. $i .'_'.$j]>0 && $request['third_lvl_status_'. $i .'_'.$j] == 1){
                                        for ($k = 1; $k <= $request['bottom_count_'. $i .'_'.$j]; $k++) {
                                            if ($request['bottom_sec_status_' . $i .'_'. $j .'_'.$k] == '1') {
                                                $userPermission = UserPermission::create([
                                                    'per_gp_id' => $request->per_group,
                                                    'permission_id' => $request['bottom_sec_' . $i . '_'. $j . '_'. $k],
                                                    'permission_parent_id' => $request['sub_sec_' . $i . '_'. $j]
                                                ]);
                                            }
                                            /**FOURTH LEVEL MENU */
                                            if(isset($request['fourth_count_' . $i . '_' . $j . '_' . $k]) && $request['fourth_count_' . $i . '_' . $j . '_' . $k]>0){
                                                for ($l = 1; $l <= $request['fourth_count_'. $i .'_'.$j . '_' . $k]; $l++) {
                                                    if ($request['fourth_sec_status_' . $i .'_'. $j .'_'.$k . '_' . $l] == '1') {
                                                        $userPermission = UserPermission::create([
                                                            'per_gp_id' => $request->per_group,
                                                            'permission_id' => $request['fourth_sec_' . $i . '_'. $j . '_'. $k . '_' . $l],
                                                            'permission_parent_id' => $request['bottom_sec_' . $i . '_'. $j . '_'. $k]
                                                        ]);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

            DB::commit();
            return redirect()->route('view_privilege')->with('success', 'RECORD HAS BEEN SUCCESSFULLY UPDATED!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('view_privilege')->with('error', 'RECORD HAS NOT BEEN SUCCESSFULLY UPDATED!');
        }
    }
}