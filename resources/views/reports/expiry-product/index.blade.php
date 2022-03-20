@extends('layouts.app')
@section('css')
<link href="{{ asset('template/css/plugins/dataTables/datatables.min.css')}}" rel="stylesheet">
@endsection
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Product expiry report</h2>
        <h5>This products will expire withing next two weeks</h5>
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
                            <label class="col-sm-3 col-form-label">PRODUCT</label>
                                <div class="col-sm-9">
                                    <select class="form-control form-control-sm" id="pro_id" name="pro_id">
                                        <option value="0">SELECT PRODUCT</option>
                                        @foreach ($products as $item)
                                        <option value="{{$item->pro_id}}">{{$item->pro_name}}</option>
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
            <div id="exp_details"></div>
        </div>
    </div>
</div>

@endsection
@section('js')
<script src="{{ asset('template/js/plugins/dataTables/datatables.min.js')}}"></script>
<script src="{{ asset('template/js/plugins/dataTables/dataTables.bootstrap4.min.js')}}"></script>
<script type="text/javascript">
function search() {
    $.ajax({
        type: "POST",
        url: "{{ url('/reports/expiry-product/search') }}",
        data: {
            "_token": "{{ csrf_token() }}",
            'pro_id': $('#pro_id').val(),
        },
        cache: false,
        beforeSend: function() { 
            $("#exp_details").html('<div style="text-align:center"><img class="logo-img" src="{{ asset('template/images/loading.gif') }}" style="height:100px;weight:100px"></div>');
        },
        success: function (html) {
            $("#exp_details").html('');
            $("#exp_details").html(html).show('slow');
        },
        complete: function (data) {
        }
    });
}
</script>
@endsection
