@extends('layouts.app')
@section('css')
<link href="{{ asset('template/css/plugins/dataTables/datatables.min.css')}}" rel="stylesheet">
@endsection
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Display User Privilege</h2>
    </div>
    <div class="col-lg-2">

    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-md-2 col-sm-12 col-xs-12 form-group">
                        </div>
                        <div class="col-md-8 col-sm-12 col-xs-12 form-group text-center">
                            <table class="table table-bordered">
                                <tr>
                                    <td class="text-left">PERMISSION GROUP</td>
                                    <td>:</td>
                                    <td class="text-left">{{$permissionGroup->per_gp_name}}</td>
                                    <td class="text-left">USER TYPE</td>
                                    <td>:</td>
                                    <td class="text-left">{{$permissionGroup->getUserType()}}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-2 col-sm-12 col-xs-12 form-group">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-title">
                    <div class="ibox-tools animated">
                        <div class="col-lg-2" style="padding-bottom: 25px">

                        </div>
                    </div>

                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        @if(isset($userPermission) && sizeof($userPermission) > 0)
                <table class="table table-striped table-bordered first" id="good_issue_table">
                    <thead class="thead-custom">
                        <tr>
                            <th style="text-align: center" colspan="4">USER PERMISSION DETAILS</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($userPermission as $item)
                    <tr>
                        <td colspan="4">{{$item['main_sec_nav_name']}}</td>
                    </tr>
                        @if(count($item['sub_section'])>0)
                        @foreach ($item['sub_section'] AS $sub_sec)
                        <tr>
                            <td>&nbsp;</td>
                            <td>{{$sub_sec['sub_sec_nav_name']}}</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                            @if (isset($sub_sec['bottom_section']) && count($sub_sec['bottom_section'])>0 && $sub_sec['third_lvl_nav_status'] == '1')
                                @foreach ($sub_sec['bottom_section'] AS $bottom_sec)
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>{{$bottom_sec['bottom_sec_nav_name']}}</td>
                                    <td>&nbsp;</td>
                                </tr>
                                    @if (count($bottom_sec['fourthSection']) > 0)
                                    @foreach ($bottom_sec['fourthSection'] AS $forth_sec)
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>{{$forth_sec['fourth_sec_nav_name']}}</td>
                                    </tr>
                                    @endforeach
                                    @endif
                                @endforeach
                            @elseif (isset($sub_sec['bottom_section']) && count($sub_sec['bottom_section'])>0 && $sub_sec['third_lvl_nav_status'] == '0')
                                @foreach ($sub_sec['bottom_section'] AS $bottom_sec)
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>{{$bottom_sec['bottom_sec_nav_name']}}</td>
                                    <td>&nbsp;</td>
                                </tr>
                                @endforeach
                            @endif
                        @endforeach
                        @endif
                    @endforeach
                    </tbody>
                </table>
                @else 
                    <div style="text-align:center;color:red"><label class="col-form-label">No Record Found</label></div>
                @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('js')
<script src="{{ asset('template/js/plugins/dataTables/datatables.min.js')}}"></script>
<script src="{{ asset('template/js/plugins/dataTables/dataTables.bootstrap4.min.js')}}"></script>
<script type="text/javascript">


</script>
@endsection
