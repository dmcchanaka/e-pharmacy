@extends('layouts.app')
@section('css')
<link href="{{ asset('template/css/plugins/dataTables/datatables.min.css')}}" rel="stylesheet">

<link href="{{ asset('template/css/select2.min.css') }}" rel="stylesheet" />
@endsection
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Doctor payements report</h2>
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
                            <label class="col-sm-3 col-form-label">DOCTOR</label>
                                <div class="col-sm-9">
                                    <select class="form-control form-control-sm" id="doctor_id" name="doctor_id">
                                        <option value="0">SELECT DOCTOR</option>
                                        @foreach ($doctors as $item)
                                        <option value="{{$item->doctor_id}}">{{$item->doctor_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                            <label class="col-sm-3 col-form-label">FROM</label>
                                <div class="col-sm-9">
                                    <input type="date" id="start_date" name="start_date" class="form-control form-control-sm" value="{{date('Y-m-d')}}" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                            <label class="col-sm-3 col-form-label">TO</label>
                                <div class="col-sm-9">
                                    <input type="date" id="end_date" name="end_date" class="form-control form-control-sm" value="{{date('Y-m-d')}}" />
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
            <div id="daily_sales_details"></div>
        </div>
    </div>
</div>

@endsection
@section('js')
<script src="{{ asset('template/js/plugins/dataTables/datatables.min.js')}}"></script>
<script src="{{ asset('template/js/plugins/dataTables/dataTables.bootstrap4.min.js')}}"></script>

<script src="{{asset('template/js/select2.min.js') }}"></script>
<script type="text/javascript">
$(document).ready(function(){
    $("#doctor_id").select2({
        maximumSelectionLength: 2
    });
});
function search() {
            $.ajax({
                type: "POST",
                url: "{{ url('/reports/doctor-payments/search') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    'doctor_id': $('#doctor_id').val(),
                    'start_date': $('#start_date').val(),
                    'end_date': $('#end_date').val(),
                },
                cache: false,
                beforeSend: function() { 
                    $("#daily_sales_details").html('<div style="text-align:center"><img class="logo-img" src="{{ asset('template/images/loading.gif') }}" style="height:100px;weight:100px"></div>');
                },
                success: function (html) {
                    $("#daily_sales_details").html('');
                    $("#daily_sales_details").html(html).show('slow');
                },
                complete: function (data) {
                }
            });
    }
</script>
@endsection
