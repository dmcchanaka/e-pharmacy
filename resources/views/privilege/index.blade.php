@extends('layouts.app')
@section('css')

<link href="{{asset('template/css/jquery-ui.min.css')}}" rel="stylesheet"/>
<link rel="stylesheet" href="{{asset('template/css/jquery-confirm.min.css')}}">
<link rel="stylesheet" href="{{asset('template/css/priviladge-style.css')}}">

@endsection
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>User Privilege</h2>
    </div>
    <div class="col-lg-2">

    </div>
</div>
@include('flash-message')
<form action="{{url('add_privilege')}}" method="post" name="permission_form" id="permission_form" onsubmit="permission_validation();">
@csrf
<div class="wrapper wrapper-content animated fadeInRight">
    
    <div class="row">
        <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 co-12"></div>
        <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 co-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12 form-group text-center">
                            <table class="table table-bordered" id="main_tbl">
                                <tr>
                                    <td class="text-left">PERMISSION GROUP</td>
                                    <td>:</td>
                                    <td class="text-left">
                                        <input type="text" class="form-control form-control-sm" id="per_group" name="per_group" value="" required />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-left">USER TYPE</td>
                                    <td>:</td>
                                    <td class="text-left">
                                        <select class="form-control form-control-sm" id="u_tp_id" name="u_tp_id">
                                            <option value="">SELECT USER TYPE</option>
                                            @foreach ($userType AS $ut)
                                            <option value="{{$ut->u_tp_id}}">{{$ut->user_type}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>    
                </div>    
            </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 co-12"></div>
    </div>
    <div class="row">
        <!-- ============================================================== -->
        <!-- accrodions style three -->
        <!-- ============================================================== -->
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="section-block">
                
            </div>
            <div class="accrodion">
                <div id="accordion">
                @php $main_count = 0; @endphp
                @foreach ($mainPermission AS $item)
                @php $main_count++; @endphp
                    <div class="card mb-0">
                        <div class="card-header" id="headingEight">
                            <div class="dd-handle" style="padding: .0rem 1rem; border-top: 0px solid red; border-bottom: 0px solid rgba(19, 29, 40, .125) !important">
                                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse_{{$item['main_permission_id']}}" aria-expanded="false" aria-controls="collapseEight" onclick="return false">
                                    <span class="fa fa-angle-down mr-3"></span>{{$item['section_name']}} <span> - ( Main Section )</span>
                                </button> 
                                <div></div>
                                <div class="dd-nodrag btn-group ml-auto">
                                    <div class="form-group row">
                                        <label class="col-12 col-sm-3 col-form-label text-sm-right"></label>
                                        <div class="col-12 col-sm-8 col-lg-6 pt-1">
                                            <div class="switch-button switch-button-danger switch-button-xs">
                                                <input type="checkbox" name="switch_{{$main_count}}" id="switch_{{$main_count}}" onclick="toggleMainSection({{ $main_count }})">
                                                <span>
                                                    <label for="switch_{{$main_count}}"></label>
                                                </span>
                                            </div>
                                            <input type="hidden" id="main_sec_{{$main_count}}" name="main_sec_{{$main_count}}" value="{{$item['main_permission_id']}}" >
                                            <input type="hidden" id="main_sec_status_{{$main_count}}" name="main_sec_status_{{$main_count}}" value="0" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="collapse_{{$item['main_permission_id']}}" class="collapse" aria-labelledby="headingEight" data-parent="#accordion3">
                            
                            <section class="card card-fluid">
                                <div class="dd" id="nestable2">
                                    <ol class="dd-list">
                                    @php $sub_count = 0; @endphp
                                    @foreach($item['sub_section'] AS $sub_sec)
                                    @php $sub_count++; @endphp
                                        <li class="dd-item" data-id="15">
                                            <div class="dd-handle" style="padding: .15rem 1rem !important">
                                                <div>  {{$sub_sec['sub_sec_name']}}  </div>
                                                <div class="dd-nodrag btn-group ml-auto">
                                                    <div class="form-group row">
                                                        <label class="col-12 col-sm-3 col-form-label text-sm-right"></label>
                                                        <div class="col-12 col-sm-9 col-lg-6 pt-1" style="padding-right:25px">
                                                            <div class="switch-button switch-button switch-button-xs">
                                                                <input type="checkbox" name="switch_{{$main_count}}_{{$sub_count}}" id="switch_{{$main_count}}_{{$sub_count}}" onclick="toggleSubSection({{$main_count}},{{$sub_count}})">
                                                                <span>
                                                                    <label for="switch_{{$main_count}}_{{$sub_count}}"></label>
                                                                </span>
                                                            </div>
                                                            <input type="hidden" id="sub_sec_{{$main_count}}_{{$sub_count}}" name="sub_sec_{{$main_count}}_{{$sub_count}}" value="{{$sub_sec['sub_sec_id']}}" />
                                                            <input type="hidden" id="sub_sec_status_{{$main_count}}_{{$sub_count}}" name="sub_sec_status_{{$main_count}}_{{$sub_count}}" value="0" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="hidden" id="third_lvl_status_{{$main_count}}_{{$sub_count}}" name="third_lvl_status_{{$main_count}}_{{$sub_count}}" value="{{$sub_sec['third_level_menu_status']}}" />
                                            @if(count($sub_sec['bottom_section'])>0 && $sub_sec['third_level_menu_status'] == 0)
                                            <ol class="dd-list">
                                                <li class="dd-item" data-id="16">
                                                    <div class="dd-handle"> 
                                                        <div class="col-md-12" style="border: 1px solid #c2c2c2;height:auto; background-color:#f3f3f3">
                                                            <div class="row mb-6" style="margin-bottom:-20px">
                                                                @php $bottom_count = 0; @endphp
                                                                @foreach ($sub_sec['bottom_section'] AS $btm)
                                                                @php $bottom_count++; @endphp
                                                                <div class="col-md-3 pb-3">
                                                                    <div class="form-group row">
                                                                        <label class="col-12 col-sm-8 col-form-label text-sm-right">{{$btm['bottom_sec_name']}}</label>
                                                                        <div class="col-12 col-sm-4 col-lg-4 pt-1">
                                                                            <div class="switch-button switch-button-success switch-button-xs">
                                                                                <input type="checkbox" name="switch_{{$main_count}}_{{$sub_count}}_{{$bottom_count}}" id="switch_{{$main_count}}_{{$sub_count}}_{{$bottom_count}}" onclick="toggleBottomSection({{$main_count}},{{$sub_count}},{{$bottom_count}})">
                                                                                <span>
                                                                                    <label for="switch_{{$main_count}}_{{$sub_count}}_{{$bottom_count}}"></label>
                                                                                </span>
                                                                            </div>
                                                                            <input type="hidden" id="bottom_sec_{{$main_count}}_{{$sub_count}}_{{$bottom_count}}" name="bottom_sec_{{$main_count}}_{{$sub_count}}_{{$bottom_count}}" value="{{$btm['bottom_sec_id']}}" />
                                                                            <input type="hidden" id="bottom_sec_status_{{$main_count}}_{{$sub_count}}_{{$bottom_count}}" name="bottom_sec_status_{{$main_count}}_{{$sub_count}}_{{$bottom_count}}" value="0" />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                @endforeach
                                                                <input type="hidden" id="bottom_count_{{$main_count}}_{{$sub_count}}" name="bottom_count_{{$main_count}}_{{$sub_count}}" value="{{$bottom_count}}" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ol>
                                            @elseif(count($sub_sec['bottom_section'])>0 && $sub_sec['third_level_menu_status'] == 1)
                                            <ol class="dd-list">
                                                @php $bottom_count_new = 0; @endphp
                                                @foreach ($sub_sec['bottom_section'] AS $btm)
                                                @php $bottom_count_new++; @endphp
                                                <li class="dd-item" data-id="15">
                                                <div class="dd-handle" style="padding: .15rem 1rem !important">
                                                    <div>  {{$btm['bottom_sec_name']}}  </div>
                                                    <div class="dd-nodrag btn-group ml-auto">
                                                        <div class="form-group row">
                                                            <label class="col-12 col-sm-3 col-form-label text-sm-right"></label>
                                                            <div class="col-12 col-sm-9 col-lg-6 pt-1" style="padding-right:25px">
                                                                <div class="switch-button switch-button switch-button-xs">
                                                                    <input type="checkbox" name="switch_{{$main_count}}_{{$sub_count}}_{{$bottom_count_new}}" id="switch_{{$main_count}}_{{$sub_count}}_{{$bottom_count_new}}" onclick="toggleBottomSection({{$main_count}},{{$sub_count}},{{$bottom_count_new}})">
                                                                    <span>
                                                                        <label for="switch_{{$main_count}}_{{$sub_count}}_{{$bottom_count_new}}"></label>
                                                                    </span>
                                                                </div>
                                                                <input type="hidden" id="bottom_sec_{{$main_count}}_{{$sub_count}}_{{$bottom_count_new}}" name="bottom_sec_{{$main_count}}_{{$sub_count}}_{{$bottom_count_new}}" value="{{$btm['bottom_sec_id']}}" />
                                                                <input type="hidden" id="bottom_sec_status_{{$main_count}}_{{$sub_count}}_{{$bottom_count_new}}" name="bottom_sec_status_{{$main_count}}_{{$sub_count}}_{{$bottom_count_new}}" value="0" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @if(count($btm['forth_section'])>0)
                                                <ol class="dd-list">
                                                    <li class="dd-item" data-id="16">
                                                        <div class="dd-handle"> 
                                                            <div class="col-md-12" style="border: 1px solid #c2c2c2;height:auto; background-color:#f3f3f3">
                                                                <div class="row mb-6" style="margin-bottom:-20px">
                                                                    @php $fourth_count = 0; @endphp
                                                                    @foreach ($btm['forth_section'] AS $fth)
                                                                    @php $fourth_count++; @endphp
                                                                    <div class="col-md-3 pb-3">
                                                                        <div class="form-group row">
                                                                            <label class="col-12 col-sm-8 col-form-label text-sm-right">{{$fth['forth_sec_name']}}</label>
                                                                            <div class="col-12 col-sm-4 col-lg-4 pt-1">
                                                                                <div class="switch-button switch-button-success switch-button-xs">
                                                                                    <input type="checkbox" name="switch_{{$main_count}}_{{$sub_count}}_{{$bottom_count_new}}_{{$fourth_count}}" id="switch_{{$main_count}}_{{$sub_count}}_{{$bottom_count_new}}_{{$fourth_count}}" onclick="toggleForthSection({{$main_count}},{{$sub_count}},{{$bottom_count_new}},{{$fourth_count}})">
                                                                                    <span>
                                                                                        <label for="switch_{{$main_count}}_{{$sub_count}}_{{$bottom_count_new}}_{{$fourth_count}}"></label>
                                                                                    </span>
                                                                                </div>
                                                                                <input type="hidden" id="fourth_sec_{{$main_count}}_{{$sub_count}}_{{$bottom_count_new}}_{{$fourth_count}}" name="fourth_sec_{{$main_count}}_{{$sub_count}}_{{$bottom_count_new}}_{{$fourth_count}}" value="{{$fth['forth_sec_id']}}" />
                                                                                <input type="hidden" id="fourth_sec_status_{{$main_count}}_{{$sub_count}}_{{$bottom_count_new}}_{{$fourth_count}}" name="fourth_sec_status_{{$main_count}}_{{$sub_count}}_{{$bottom_count_new}}_{{$fourth_count}}" value="0" />
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    @endforeach
                                                                    <input type="hidden" id="fourth_count_{{$main_count}}_{{$sub_count}}_{{$bottom_count_new}}" name="fourth_count_{{$main_count}}_{{$sub_count}}_{{$bottom_count_new}}" value="{{$fourth_count}}" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ol>
                                                @endif
                                                </li>
                                                @endforeach
                                                <input type="hidden" id="bottom_count_{{$main_count}}_{{$sub_count}}" name="bottom_count_{{$main_count}}_{{$sub_count}}" value="{{$bottom_count_new}}" />
                                            </ol>
                                            @endif
                                        </li>
                                        @endforeach
                                        <input type="hidden" id="sub_count_{{$main_count}}" name="sub_count_{{$main_count}}" value="{{$sub_count}}" />
                                    </ol>
                                </div>
                            </section><br>
                        </div>
                    </div>
                    @endforeach
                    <input type="hidden" id="main_count" name="main_count" value="{{$main_count}}" />
                </div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- end accrodions style two -->
        <!-- ============================================================== -->
                
        </div>
        <div class="row" style="padding-top: 10px"> 
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="form-group">
                    <div class="">
                        <button class="btn btn-secondary btn-sm" type="reset">Reset</button>
                        <button type="button" id="add" class="btn btn-primary btn-sm" onclick="form_submit('add', 'permission_form')">Submit</button>
                    </div>
                </div>
            </div>
        </div>
</div>
</form>

@endsection
@section('js')

<script src="{{asset('template/js/jquery-confirm.min.js')}}"></script>
<script src="{{asset('template/js/jquery-ui.js')}}"></script>

<script type="text/javascript">
    function toggleMainSection(main_id){
        var check_status = document.getElementById("switch_" + main_id).checked;
        if ($('#per_group').val() === '' || $('#per_group').val() === "") {
            alert('Please Enter Permission Group');
            document.getElementById("switch_" + main_id).checked = false;
            document.getElementById("main_sec_status_" + main_id).value = 0;
            $('#per_group').focus();
        } else if($('#u_tp_id').val()=== ""){
            alert('Please Select User Type');
            document.getElementById("switch_" + main_id).checked = false;
            document.getElementById("main_sec_status_" + main_id).value = 0;
            $('#u_tp_id').focus();
        } else {
            
            if (check_status === true) {
                document.getElementById("main_sec_status_" + main_id).value = 1;
                for(var i = 1; i <= $('#sub_count_'+main_id).val(); i++){
                    document.getElementById("switch_" + main_id +'_'+ i).checked = true;
                    document.getElementById("sub_sec_status_" + main_id +'_'+ i).value = 1;

                    if($('#bottom_count_'+main_id + '_' + i).val() > 0){
                        for(var j = 1; j <= $('#bottom_count_'+main_id + '_' + i).val(); j++){
                            document.getElementById("switch_" + main_id +'_'+ i + '_' + j).checked = true;
                            document.getElementById("bottom_sec_status_" + main_id +'_'+ i + '_' + j).value = 1;

                            if($('#fourth_count_'+main_id + '_' + i + '_' + j).val() > 0){
                                for(var k = 1; k <= $('#fourth_count_'+main_id + '_' + i + '_' + j).val(); k++){
                                    document.getElementById("switch_" + main_id +'_'+ i + '_' + j + '_' + k).checked = true;
                                    document.getElementById("fourth_sec_status_" + main_id +'_'+ i + '_' + j + '_' + k).value = 1;
                                }
                            }
                        }
                    }

                }
            } else if (check_status === false) {
                document.getElementById("main_sec_status_" + main_id).value = 0;
                for(var i = 1; i <= $('#sub_count_'+main_id).val(); i++){
                    document.getElementById("switch_" + main_id +'_'+ i).checked = false;
                    document.getElementById("sub_sec_status_" + main_id +'_'+ i).value = 0;

                    if($('#bottom_count_'+main_id + '_' + i).val() > 0){
                        for(var j = 1; j <= $('#bottom_count_'+main_id + '_' + i).val(); j++){
                            document.getElementById("switch_" + main_id +'_'+ i + '_' + j).checked = false;
                            document.getElementById("bottom_sec_status_" + main_id +'_'+ i + '_' + j).value = 0;

                            if($('#fourth_count_'+main_id + '_' + i + '_' + j).val() > 0){
                                for(var k = 1; k <= $('#fourth_count_'+main_id + '_' + i + '_' + j).val(); k++){
                                    document.getElementById("switch_" + main_id +'_'+ i + '_' + j + '_' + k).checked = false;
                                    document.getElementById("fourth_sec_status_" + main_id +'_'+ i + '_' + j + '_' + k).value = 0;
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    function toggleSubSection(main,sub){
        var sub_check_status = document.getElementById("switch_" + main + '_' + sub).checked;
        if ($('#per_group').val() === '' || $('#per_group').val() === "") {
            alert('Please Enter Permission Group');
            document.getElementById("switch_" + main).checked = false;
            document.getElementById("main_sec_status_" + main).value = 0;

            document.getElementById("switch_" + main + '_' + sub).checked = false;
            document.getElementById("sub_sec_status_" + main + '_' + sub).value = 0;
            $('#per_group').focus();
        } else if($('#u_tp_id').val()=== ""){
            alert('Please Select User Type');
            document.getElementById("switch_" + main).checked = false;
            document.getElementById("main_sec_status_" + main).value = 0;

            document.getElementById("switch_" + main + '_' + sub).checked = false;
            document.getElementById("sub_sec_status_" + main + '_' + sub).value = 0;
            $('#u_tp_id').focus();
        } else { 
            if (sub_check_status === true) {
                document.getElementById("main_sec_status_" + main).value = 1;
                document.getElementById("switch_" + main).checked = true;

                document.getElementById("switch_" + main + '_' + sub).checked = true;
                document.getElementById("sub_sec_status_" + main + '_' + sub).value = 1;

                if($('#bottom_count_'+main + '_' + sub).val() > 0){
                    for(var j = 1; j <= $('#bottom_count_'+main + '_' + sub).val(); j++){
                        document.getElementById("switch_" + main +'_'+ sub + '_' + j).checked = true;
                        document.getElementById("bottom_sec_status_" + main +'_'+ sub + '_' + j).value = 1;

                        if($('#fourth_count_'+main + '_' + sub + '_' + j).val() > 0){
                            for(var k = 1; k <= $('#fourth_count_'+main + '_' + sub + '_' + j).val(); k++){
                                document.getElementById("switch_" + main +'_'+ sub + '_' + j + '_' + k).checked = true;
                                document.getElementById("fourth_sec_status_" + main +'_'+ sub + '_' + j + '_' + k).value = 1;
                            }
                        }
                    }
                }

            } else if (sub_check_status === false) {

                document.getElementById("switch_" + main + '_' + sub).checked = false;
                document.getElementById("sub_sec_status_" + main + '_' + sub).value = 0;

                if($('#bottom_count_'+main + '_' + sub).val() > 0){
                    for(var j = 1; j <= $('#bottom_count_'+main + '_' + sub).val(); j++){
                        document.getElementById("switch_" + main +'_'+ sub + '_' + j).checked = false;
                        document.getElementById("bottom_sec_status_" + main +'_'+ sub + '_' + j).value = 0;

                        if($('#fourth_count_'+main + '_' + sub + '_' + j).val() > 0){
                            for(var k = 1; k <= $('#fourth_count_'+main + '_' + sub + '_' + j).val(); k++){
                                document.getElementById("switch_" + main +'_'+ sub + '_' + j + '_' + k).checked = false;
                                document.getElementById("fourth_sec_status_" + main +'_'+ sub + '_' + j + '_' + k).value = 0;
                            }
                        }
                    }
                }

                var count_new  = 0;
                for(var i = 1; i <= $('#sub_count_'+ main).val(); i++){
                    console.log(i + ' ** '+ sub + ' +++ ' + $('#sub_sec_status_'+ main + '_' + i).val());
                    if($('#sub_sec_status_'+ main + '_' + i).val() == '1' && i != sub){
                        count_new++;
                    }
                } 

                if(count_new == 0){
                    document.getElementById("main_sec_status_" + main).value = 0;
                    document.getElementById("switch_" + main).checked = false;
                }
            }
        }
    }

    function toggleBottomSection(main, sub, bottom) {
        var bottom_check_status = document.getElementById("switch_" + main + '_' + sub + '_' + bottom).checked;
        if ($('#per_group').val() === '' || $('#per_group').val() === "") {
            alert('Please Enter Permission Group');
            document.getElementById("switch_" + main).checked = false;
            document.getElementById("main_sec_status_" + main).value = 0;

            document.getElementById("switch_" + main + '_' + sub).checked = false;
            document.getElementById("sub_sec_status_" + main + '_' + sub).value = 0;

            document.getElementById("switch_" + main + '_' + sub + '_' + bottom).checked = false;
            document.getElementById("bottom_sec_status_" + main + '_' + sub + '_' + bottom).value = 0;
            $('#per_group').focus();
        } else if($('#u_tp_id').val()=== ""){
            alert('Please Select User Type');
            document.getElementById("switch_" + main).checked = false;
            document.getElementById("main_sec_status_" + main).value = 0;

            document.getElementById("switch_" + main + '_' + sub).checked = false;
            document.getElementById("sub_sec_status_" + main + '_' + sub).value = 0;

            document.getElementById("switch_" + main + '_' + sub + '_' + bottom).checked = false;
            document.getElementById("bottom_sec_status_" + main + '_' + sub + '_' + bottom).value = 0;
            $('#u_tp_id').focus();
        } else {
            if(bottom_check_status === true) {
                document.getElementById("main_sec_status_" + main).value = 1;
                document.getElementById("switch_" + main).checked = true;

                document.getElementById("switch_" + main + '_' + sub).checked = true;
                document.getElementById("sub_sec_status_" + main + '_' + sub).value = 1;

                document.getElementById("switch_" + main +'_'+ sub + '_' + bottom).checked = true;
                document.getElementById("bottom_sec_status_" + main +'_'+ sub + '_' + bottom).value = 1;

                if($('#fourth_count_'+main + '_' + sub + '_' + bottom).val() > 0){
                    for(var k = 1; k <= $('#fourth_count_'+main + '_' + sub + '_' + bottom).val(); k++){
                        document.getElementById("switch_" + main +'_'+ sub + '_' + bottom + '_' + k).checked = true;
                        document.getElementById("fourth_sec_status_" + main +'_'+ sub + '_' + bottom + '_' + k).value = 1;
                    }
                }

            } else if(bottom_check_status === false) {

                document.getElementById("switch_" + main +'_'+ sub + '_' + bottom).checked = false;
                document.getElementById("bottom_sec_status_" + main +'_'+ sub + '_' + bottom).value = 0;

                if($('#fourth_count_'+main + '_' + sub + '_' + bottom).val() > 0){
                    for(var k = 1; k <= $('#fourth_count_'+main + '_' + sub + '_' + bottom).val(); k++){
                        document.getElementById("switch_" + main +'_'+ sub + '_' + bottom + '_' + k).checked = false;
                        document.getElementById("fourth_sec_status_" + main +'_'+ sub + '_' + bottom + '_' + k).value = 0;
                    }
                }

                var count_bottom  = 0;
                for(var k = 1; k <= $('#bottom_count_'+ main + '_' + sub).val(); k++){
                    if($('#bottom_sec_status_'+ main + '_' + sub + '_' + k).val() == '1' && k != bottom){
                        count_bottom++;
                    }
                }

                if(count_bottom == 0){
                    document.getElementById("sub_sec_status_" + main + '_' + sub).value = 0;
                    document.getElementById("switch_" + main + '_' + sub).checked = false;
                }

                var count_new  = 0;
                for(var i = 1; i <= $('#sub_count_'+ main).val(); i++){
                    if($('#sub_sec_status_'+ main + '_' + i).val() == '1' && i != sub){
                        count_new++;
                    }
                } 

                if(count_new == 0){
                    document.getElementById("main_sec_status_" + main).value = 0;
                    document.getElementById("switch_" + main).checked = false;
                }
            }
        }
    } 

    function toggleForthSection(main, sub, bottom, fourth){
        console.log('main '+ main + 'sub '+sub + ' third '+ bottom + ' fourth ' + fourth);
        var fourth_check_status = document.getElementById("switch_" + main + '_' + sub + '_' + bottom + '_' + fourth).checked;
        if ($('#per_group').val() === '' || $('#per_group').val() === "") {
            alert('Please Enter Permission Group');
            document.getElementById("switch_" + main).checked = false;
            document.getElementById("main_sec_status_" + main).value = 0;

            document.getElementById("switch_" + main + '_' + sub).checked = false;
            document.getElementById("sub_sec_status_" + main + '_' + sub).value = 0;

            document.getElementById("switch_" + main + '_' + sub + '_' + bottom).checked = false;
            document.getElementById("bottom_sec_status_" + main + '_' + sub + '_' + bottom).value = 0;

            document.getElementById("switch_" + main + '_' + sub + '_' + bottom + '_' + fourth).checked = false;
            document.getElementById("fourth_sec_status_" + main + '_' + sub + '_' + bottom + '_' + fourth).value = 0;
            $('#per_group').focus();
        } else if($('#u_tp_id').val()=== ""){
            alert('Please Select User Type');
            document.getElementById("switch_" + main).checked = false;
            document.getElementById("main_sec_status_" + main).value = 0;

            document.getElementById("switch_" + main + '_' + sub).checked = false;
            document.getElementById("sub_sec_status_" + main + '_' + sub).value = 0;

            document.getElementById("switch_" + main + '_' + sub + '_' + bottom).checked = false;
            document.getElementById("bottom_sec_status_" + main + '_' + sub + '_' + bottom).value = 0;

            document.getElementById("switch_" + main + '_' + sub + '_' + bottom + '_' + fourth).checked = false;
            document.getElementById("fourth_sec_status_" + main + '_' + sub + '_' + bottom + '_' + fourth).value = 0;
            $('#u_tp_id').focus();
        } else {
            if(fourth_check_status === true) {
                document.getElementById("main_sec_status_" + main).value = 1;
                document.getElementById("switch_" + main).checked = true;

                document.getElementById("switch_" + main + '_' + sub).checked = true;
                document.getElementById("sub_sec_status_" + main + '_' + sub).value = 1;

                document.getElementById("switch_" + main +'_'+ sub + '_' + bottom).checked = true;
                document.getElementById("bottom_sec_status_" + main +'_'+ sub + '_' + bottom).value = 1;

                document.getElementById("switch_" + main +'_'+ sub + '_' + bottom + '_' + fourth).checked = true;
                document.getElementById("fourth_sec_status_" + main +'_'+ sub + '_' + bottom + '_' + fourth).value = 1;

            } else if(fourth_check_status === false) {

                document.getElementById("switch_" + main +'_'+ sub + '_' + bottom + '_' + fourth).checked = false;
                document.getElementById("fourth_sec_status_" + main +'_'+ sub + '_' + bottom + '_' + fourth).value = 0;

                var count_fourth = 0; 
                for(var l = 1; l <= $('#fourth_count_'+ main + '_' + sub + '_' + bottom).val(); l++){
                    if($('#fourth_sec_status_'+ main + '_' + sub + '_' + bottom + '_' + l).val() == '1' && l != fourth){
                        count_fourth++;
                    }
                }
                if(count_fourth == 0){
                    document.getElementById("bottom_sec_status_" + main + '_' + sub + '_' + bottom).value = 0;
                    document.getElementById("switch_" + main + '_' + sub + '_' + bottom).checked = false;
                }

                var count_bottom  = 0;
                for(var k = 1; k <= $('#bottom_count_'+ main + '_' + sub).val(); k++){
                    if($('#bottom_sec_status_'+ main + '_' + sub + '_' + k).val() == '1' && k != bottom){
                        count_bottom++;
                    }
                }

                if(count_bottom == 0){
                    document.getElementById("sub_sec_status_" + main + '_' + sub).value = 0;
                    document.getElementById("switch_" + main + '_' + sub).checked = false;
                }

                var count_new  = 0;
                for(var i = 1; i <= $('#sub_count_'+ main).val(); i++){
                    console.log('main '+ main + ' sub '+ sub + ' i ' + i);
                    if($('#sub_sec_status_'+ main + '_' + i).val() == '1' && i != sub){
                        count_new++;
                    }
                } 

                if(count_new == 0 && count_bottom == 0){
                    document.getElementById("main_sec_status_" + main).value = 0;
                    document.getElementById("switch_" + main).checked = false;
                }
            }
        }
    }

    function permission_validation() {
        valid = true;
        if ($('#per_group').val() == "" || $('#per_group').val() == "") {
            valid = false;
            alert("Enter Permission Group");
            $('#per_group').focus();
        } else if ($('#u_tp_id').val() == "" || $('#u_tp_id').val() == "") {
            valid = false;
            alert("Select User Type");
            $('#u_tp_id').focus();
        } else {
            var main_count = 0;
            for(var i = 1; i <= $('#main_count').val(); i++){
                var check_status = document.getElementById("switch_" + i).checked;
                if(check_status === true){
                    main_count++;
                }
            }
            if(main_count === 0){
                valid = false;
                alert('Select Atleast One Menu Section');
            }
        }
        return valid;
    }

    function form_submit(button_id, form_id) {
        if (permission_validation()) {
            document.getElementById(button_id).style.display = "none";
            document.forms[form_id].submit();
        }
    }
</script>
@endsection
