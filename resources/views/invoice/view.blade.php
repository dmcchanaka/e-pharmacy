@extends('layouts.app')
@section('css')
<link href="{{ asset('template/css/plugins/dataTables/datatables.min.css')}}" rel="stylesheet">
<style>
    .dataTables_paginate {
        margin-top: 15px;
        position: absolute;
        text-align: right;
        left: 50%;
    }
</style>
@endsection
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>View Invoices</h2>
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
                            <label class="col-sm-3 col-form-label">INVOICE NO.</label>
                            <div class="col-sm-9">
                                <input type="text" id="invoice_no" name="invoice_no" class="form-control form-control-sm" />
                            </div>
                            </div>
                        </div>
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
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                            <label class="col-sm-3 col-form-label">DATE FROM</label>
                            <div class="col-sm-9">
                                <input type="date" class="form-control form-control-sm" id="from_date">
                            </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                            <label class="col-sm-3 col-form-label">DATE TO</label>
                            <div class="col-sm-9">
                                <input type="date" class="form-control form-control-sm" id="to_date">
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
            <div class="ibox ">
                <div class="ibox-title">
                    <div class="ibox-tools animated">
                        <div class="col-lg-2" style="padding-bottom: 25px">

                        </div>
                    </div>

                </div>
                <div class="ibox-content" id="invoice_table_div" style="display: none">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-example" id="invoice_table" >
                            <thead>
                                <tr>
                                    <th style="background-color: #1ab394;color:#fff">Invoice No</th>
                                    <th style="background-color: #1ab394;color:#fff">Invoice Date</th>
                                    <th style="background-color: #1ab394;color:#fff">Invoice Time</th>
                                    <th style="background-color: #1ab394;color:#fff">Pay Type</th>
                                    <th style="background-color: #1ab394;color:#fff">Doctor</th>
                                    <th style="background-color: #1ab394;color:#fff">Gross Amount</th>
                                    <th style="background-color: #1ab394;color:#fff">Net Amount</th>
                                    <th style="background-color: #1ab394;color:#fff">View</th>
                                    <th style="background-color: #1ab394;color:#fff">Print</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>

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
function search(){

$("#invoice_table_div").show();
$('#invoice_table').DataTable({
    processing: true,
    serverSide: true,
    destroy: true,
    responsive: true,
    ajax: {
        url: "{{ url('/invoice/search') }}",
        data: function (d) {
            d.invoice_no = $('#invoice_no').val();
            d.doctor_id = $('#doctor_id').val();
            d.from_date = $('#from_date').val();
            d.to_date = $('#to_date').val();
        }
    },
    columns: [
        { data: 'invoice_no', name: 'invoice_no' },
        { data: 'invoice_date', name: 'invoice_date', className: 'text-center' },
        { data: 'invoice_time', name: 'invoice_time', className: 'text-center' },
        { data: 'payment_type', name: 'payment_type' },
        { data: 'doctor_name', name: 'doctor_name' },
        { data: 'invoice_gross_amt', name: 'invoice_gross_amt', className: 'text-right' },
        { data: 'invoice_net_amt', name: 'invoice_net_amt', className: 'text-right' },
        { data: 'display', name: 'display', className: 'text-center' },
        { data: 'print', name: 'print', className: 'text-center' }
    ],
    order: [
        0, 'desc'
    ],
});
}
</script>
@endsection
