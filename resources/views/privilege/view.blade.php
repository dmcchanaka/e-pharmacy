@extends('layouts.app')
@section('css')
<link href="{{ asset('template/css/plugins/dataTables/datatables.min.css')}}" rel="stylesheet">

<link href="{{ asset('template/css/select2.min.css') }}" rel="stylesheet" />
@endsection
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>User Privilege View</h2>
    </div>
    <div class="col-lg-2">

    </div>
</div>
@include('flash-message')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                            <label class="col-sm-3 col-form-label">PERMISSION GROUP</label>
                                <div class="col-sm-9">
                                    <input type="text" id="permission_group" name="permission_group" class="form-control form-control-sm" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                            <label class="col-sm-3 col-form-label">PRODUCT</label>
                                <div class="col-sm-9">
                                    <select class="form-control form-control-sm" id="u_tp_id" name="u_tp_id">
                                        <option value="0">SELECT USER TYPE</option>
                                        @foreach ($userType as $ut)
                                        <option value="{{$ut->u_tp_id}}">{{$ut->user_type}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                            <label class="col-sm-3 col-form-label"></label>
                            <div class="col-sm-9">

                            </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                            <label class="col-sm-9 col-form-label"></label>
                            <div class="col-sm-3">
                                <button class="btn btn-primary btn-sm" type="button" onclick="search();">Search</button>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div id="permission_details"></div>
        </div>
    </div>
</div>

@endsection
@section('js')
<script src="{{ asset('template/js/plugins/dataTables/datatables.min.js')}}"></script>
<script src="{{ asset('template/js/plugins/dataTables/dataTables.bootstrap4.min.js')}}"></script>

<script src="{{asset('template/js/select2.min.js') }}"></script>
<script type="text/javascript">
    function search(){
        $("#permission_details").empty();
        $.ajax({
            type: "POST",
            url: "{{ url('/privilege/search') }}",
            data: {
                "_token": "{{ csrf_token() }}",
                'u_tp_id': $('#u_tp_id').val(),
                'permission_group': $('#permission_group').val(),
            },
            cache: false,
                beforeSend: function() { 
                    $("#permission_details").html('<div style="text-align:center"><img class="logo-img" src="{{ asset('template/images/loading.gif') }}" style="height:100px;weight:100px"></div>');
                },
            complete: function(){
            },
            success: function (html) {
                $("#permission_details").html(html).show('slow');
            }
        });
    }
</script>
@endsection
