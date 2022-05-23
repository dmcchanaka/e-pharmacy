@extends('layouts.app')
@section('css')
<link href="{{ asset('template/css/plugins/dataTables/datatables.min.css')}}" rel="stylesheet">
@endsection
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>View GRNs</h2>
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
                            <label class="col-sm-3 col-form-label">GRN NO.</label>
                            <div class="col-sm-9">
                                <input type="text" id="grn_no" name="grn_no" class="form-control form-control-sm" />
                            </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                            <label class="col-sm-3 col-form-label"></label>
                                <div class="col-sm-9">

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
                <div class="ibox-content" id="grn_table_div" style="display: none">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-example" id="invoice_table" >
                            <thead>
                                <tr>
                                    <th style="background-color: #1ab394;color:#fff">GRN No</th>
                                    <th style="background-color: #1ab394;color:#fff">GRN Date</th>
                                    <th style="background-color: #1ab394;color:#fff">GRN Time</th>
                                    <th style="background-color: #1ab394;color:#fff">GRN Amount</th>
                                    <th style="background-color: #1ab394;color:#fff">View</th>
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

$("#grn_table_div").show();
$('#invoice_table').DataTable({
    processing: true,
    serverSide: true,
    destroy: true,
    responsive: true,
    ajax: {
        url: "{{ url('/grn/search') }}",
        data: function (d) {
            d.grn_no = $('#grn_no').val();
            d.from_date = $('#from_date').val();
            d.to_date = $('#to_date').val();
        }
    },
    columns: [
        { data: 'grn_no', name: 'grn_no' },
        { data: 'grn_date', name: 'grn_date', className: 'text-center' },
        { data: 'grn_time', name: 'grn_time', className: 'text-center' },
        { data: 'grn_amt', name: 'grn_amt', className: 'text-right' },
        { data: 'display', name: 'display', className: 'text-center' }
    ],
    order: [
        0, 'desc'
    ],
});
}
</script>
@endsection
