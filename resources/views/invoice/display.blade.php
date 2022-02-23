@extends('layouts.app')
@section('css')
<link href="{{ asset('template/css/plugins/dataTables/datatables.min.css')}}" rel="stylesheet">
@endsection
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Display Invoices</h2>
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
                                    <td class="text-left">INVOICE NO.</td>
                                    <td>:</td>
                                    <td class="text-left">{{$invoice->invoice_no}}</td>
                                    <td class="text-left">DATE</td>
                                    <td>:</td>
                                    <td class="text-left">{{$invoice->invoice_date}}</td>
                                </tr>
                                <tr>
                                    <td class="text-left">DOCTOR</td>
                                    <td>:</td>
                                    <td class="text-left">{{$invoice->getDoctor()}}</td>
                                    <td class="text-left">PAYMENT TYPE</td>
                                    <td>:</td>
                                    <td class="text-left">{{$invoice->getPaymentType()}}</td>
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
                        @if(isset($invoiceItem) && sizeof($invoiceItem) > 0)
                        <table class="table table-striped table-bordered table-hover dataTables-example" id="invoice_table" >
                            <thead>
                                <tr>
                                    <th style="text-align: center;background-color: #1ab394;color:#fff">Line No.</th>
                                    <th style="text-align: center;background-color: #1ab394;color:#fff">Product Code</th>
                                    <th style="text-align: center;background-color: #1ab394;color:#fff">Description</th>
                                    <th style="text-align: center;background-color: #1ab394;color:#fff">Price</th>
                                    <th style="text-align: center;background-color: #1ab394;color:#fff">Qty</th>
                                    <th style="text-align: center;background-color: #1ab394;color:#fff">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $total_amt = 0;
                                @endphp
                                @foreach ($invoiceItem as $item)
                                @php
                                $total_amt += $item->ip_line_amt;
                                @endphp
                                <tr>
                                    <td>{{$item->line_no}}</td>
                                    <td>{{$item->getProductCode()}}</td>
                                    <td>{{$item->getProduct()}}</td>
                                    <td style="text-align: right">{{$item->ip_price}}</td>
                                    <td style="text-align: right">{{$item->ip_qty}}</td>
                                    <td style="text-align: right">{{number_format($item->ip_line_amt,2)}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="tfoot">
                                <tr>
                                    <td colspan="5">GROSS AMOUNT</td>
                                    <td style="text-align: right;font-weight:bold">{{number_format($total_amt,2)}}</td>
                                </tr>
                                <tr>
                                    <td colspan="5">NET AMOUNT</td>
                                    <td style="text-align: right;font-weight:bold">{{number_format($total_amt - $invoice->sr_amt,2)}}</td>
                                </tr>
                            </tfoot>
                        </table>
                        @else
                            <div style="text-align:center;color:red"><label class="col-form-label">No Record Found</label></div>
                        @endif
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

</script>
@endsection
