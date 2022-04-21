@extends('layouts.app')
@section('css')

<link href="{{asset('template/css/jquery-ui.min.css')}}" rel="stylesheet"/>
<link rel="stylesheet" href="{{asset('template/css/jquery-confirm.min.css')}}">

@endsection
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Expenses</h2>
    </div>
    <div class="col-lg-2">

    </div>
</div>
@include('flash-message')
<form action="{{url('add_expenses')}}" method="post" name="exp_form" id="exp_form" onsubmit="exp_validation();">
@csrf
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-md-2 col-sm-12 col-xs-12 form-group">
                        </div>
                        <div class="col-md-8 col-sm-12 col-xs-12 form-group">
                            <div class="form-group">
                                <label>Date</label>
                                <input type="date" placeholder="Date" id="exp_date" name="exp_date" value="<?php echo date('Y-m-d'); ?>" class="form-control" required="" autocomplete="off" />
                            </div>
                            <div class="form-group">
                                <label>Expenses Description</label>
                                <input type="text" placeholder="Enter Expenses Description" id="exp_desc" name="exp_desc" class="form-control" required="" autocomplete="off" />
                            </div>
                            <div class="form-group">
                                <label>Amount</label>
                                <input type="text" placeholder="Enter Expenses Amount" id="exp_amt" name="exp_amt" class="form-control" required="" autocomplete="off" onkeyup="exp_amount_checker()" />
                            </div>
                            <div class="form-group mt-sm-1 mb-sm-1">
                                <div class="">
                                    <button class="btn btn-secondary btn-sm" type="reset">Reset</button>
                                    <button type="button" id="add" class="btn btn-primary btn-sm" onclick="form_submit('add', 'exp_form')">Submit</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12 col-xs-12 form-group">
                        </div>
                    </div>
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

    function exp_amount_checker(){
        if (!$('#exp_amt').val().match(/^\d+(\.\d{0,2})?$/) && $('#exp_amt').val() != "") {
            var exp_amt = parseFloat(document.getElementById('exp_amt').value).toFixed(2);

            if (isNaN(exp_amt)) {
                exp_amt = 0;
            }
            $('#exp_amt').val(exp_amt.toFixed(2));
            $.alert({
                title: 'Alert',
                icon: 'fa fa-warning',
                type: 'green',
                content: 'Enter valid amount'
            });
        } 
    }

    function exp_validation() {
        valid = true;
        if ($('#exp_desc').val() == "") {
            valid = false;
            $('#exp_desc').focus();
            $.alert({
                title: 'Alert',
                icon: 'fa fa-warning',
                type: 'green',
                content: 'Please enter expenses description'
            });
        } else if ($('#exp_amt').val() == "" || $('#exp_amt').val() == 0) {
            valid = false;
            $('#exp_amt').focus();
            $.alert({
                title: 'Alert',
                icon: 'fa fa-warning',
                type: 'green',
                content: 'Please enter expenses amount'
            });
        }
        return valid;
    }

    function form_submit(button_id, form_id) {
        if (exp_validation()) {

            $.confirm({
            title: 'Confirm?',
                content: 'Are you sure do you want submit this record',
                type: 'green',
                buttons: {
                    Okey: {
                        text: 'confirm',
                        btnClass: 'btn-blue',
                        action: function () {
                            document.getElementById(button_id).style.display = "none";
                            document.forms[form_id].submit();
                        }
                    },
                    cancel: {
                        text: 'cancel',
                        btnClass: 'btn-red',
                        action: function () {

                        }
                    }
                }
            });
        }
    }

</script>
@endsection
