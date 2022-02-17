@extends('layouts.app')
@section('css')

<link href="{{asset('template/css/jquery-ui.min.css')}}" rel="stylesheet"/>
<link rel="stylesheet" href="{{asset('template/css/jquery-confirm.min.css')}}">

@endsection
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Invoice</h2>
    </div>
    <div class="col-lg-2">

    </div>
</div>
@include('flash-message')
<form action="{{url('add_invoice')}}" method="post" name="invoice_form" id="invoice_form" onsubmit="invoice_validation();">
@csrf
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-md-2 col-sm-12 col-xs-12 form-group">
                        </div>
                        <div class="col-md-8 col-sm-12 col-xs-12 form-group text-center">
                            <table class="table table-bordered" id="main_tbl">
                                <tr>
                                    <td class="text-left">INVOICE NO.</td>
                                    <td>:</td>
                                    <td class="text-left" colspan="4">
                                        @php
                                        use App\Models\Invoice;
                                        $s_no = Invoice::getInvoiceNo();
                                        @endphp
                                        <input type="text" class="form-control form-control-sm" id="invoice_no" name="invoice_no" value="{{$s_no}}" readonly />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-left">INVOICE DATE</td>
                                    <td>:</td>
                                    <td class="text-left" colspan="4">
                                        <input type="text" class="form-control form-control-sm" value="{{date('Y-m-d')}}" readonly />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-left">DOCTOR</td>
                                    <td>:</td>
                                    <td class="text-left" colspan="4">
                                        <select id="doctor_id" name="doctor_id" class="form-control form-control-sm" onchange="select_doctor()">
                                            <option value="">SELECT DOCTOR</option>
                                            <option value="1">DOCTOR 01</option>
                                            <option value="2">DOCTOR 02</option>
                                            <option value="3">DOCTOR 03</option>
                                        </select>
                                    </td>
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
                    <h5>Invoice Informations</h5>
                    <div class="ibox-tools animated">

                    </div>

                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-example" id="invoice_table" >
                            <thead>
                                <tr>
                                    <th style="text-align: center">&nbsp</th>
                                    <th style="text-align: center">Description</th>
                                    <th style="text-align: center">Price</th>
                                    <th style="text-align: center">Qty/KG/Litre</th>
                                    <th style="text-align: center">Amount</th>
                                    <th style="text-align: center">&nbsp</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="5">
                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label">FIND OR SCAN ITEM OR RECEIPT</label>
                                            <div class="col-lg-9">
                                                <input type="text" onclick="generate_product()" id="product_name" name="product_name" placeholder="START TYPING ITEM NAME OR SCAN BARCODE..." class="form-control" autocomplete="off">
                                                <input type="hidden" value="" id="product_id" name="product_id"  />
                                            </div>
                                        </div>
                                    </td>
                                    <td>&nbsp;</td>
                                </tr>
                            </tbody>
                            <tbody>
                                {{-- <tr id="tr_1">
                                    <td style="text-align: center">
                                        <span class="fa fa-plus fa-lg" style="cursor: pointer" onclick="gen_item();"></span>
                                    </td>
                                    <td>
                                        <input type="text" id="pro_name_1" name="pro_name_1" class="col-md-12 form-control form-control-sm" onclick="load_product('1')" autocomplete="off" />
                                        <input type="hidden" id="pro_id_1" name="pro_id_1" value="" />
                                    </td>
                                    <td>
                                        <input type="text" style="text-align: right;padding-right: 5px" class="col-md-12 form-control form-control-sm" id="price_1" name="price_1" size="5" readonly />
                                        <input type="hidden" style="text-align: right;padding-right: 5px" class="col-md-12 form-control form-control-sm" id="prc_1" name="prc_1" size="5" />
                                    </td>
                                    <td><input type="text" style="text-align: right;padding-right: 5px" class="col-md-12 form-control form-control-sm" id="qty_1" name="qty_1" onkeyup="check_qty(event, '1');" size="5" autocomplete="off" /></td>
                                    <td><input type="text" style="text-align: right;padding-right: 5px" class="col-md-12 form-control form-control-sm" id="line_amt_1" name="line_amt_1" size="5" readonly /></td>
                                    <td style="text-align: center">
                                        <span class="fa fa-minus-circle fa-lg" style="cursor: pointer;" onclick="remove_item('1');"></span>
                                    </td>
                                </tr> --}}
                                <input type="hidden" id="item_count" name="item_count" value="0" />
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td style="padding-top: 20px;text-align: right;font-weight:bold">Gross Amount</td>
                                    <td colspan="1" style="text-align: right;padding-right: 5px">
                                        <input type="text" style="text-align: right;padding-right: 5px;font-weight:bold" class="col-md-12 form-control form-control-sm" id="tot_amount" name="tot_amount" size="10" readonly />
                                    </td>
                                    <td>&nbsp;</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-md-2 col-sm-12 col-xs-12 form-group"></div>
                        <div class="col-md-8 col-sm-12 col-xs-12 form-group text-center">
                            <div class="row">
                                <div class="col-md-3">
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label for="inputEmail" class="col-sm-4 col-form-label">PAYMENT TYPE</label>
                                        <div class="col-sm-8">
                                            <select id="payment_type" name="payment_type" class="form-control form-control-sm" onchange="select_pay_type()">
                                                {{-- <option value="0">SELECT PAYMENT TYPE</option> --}}
                                                <option value="1">CASH</option>
                                                <option value="2">DEBIT/CREDIT CARD</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12 col-xs-12 form-group"></div>
                    </div>
                    <div class="form-group mt-sm-1 mb-sm-1">
                        <div class="">
                            <button class="btn btn-secondary btn-sm" type="reset">Reset</button>
                            <button type="button" id="add" class="btn btn-primary btn-sm" onclick="form_submit('add', 'invoice_form')">Submit</button>
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
    /* GENERATE NEW ROW */
    function gen_item(){
        var num = parseFloat($('#item_count').val()) + 1;
        $('#item_count').val(num);
        $('#invoice_table').append('<tr class="even pointer" id="tr_' + num + '">'
            + '<td style="text-align: center">'
                // +'<span class="fa fa-plus fa-lg" style="cursor: pointer" onclick="gen_item();"></span>'
            + '</td>'
            + '<td>'
                +'<input type="text" id="pro_name_' + num + '" name="pro_name_' + num + '" class="col-md-12 form-control form-control-sm" onclick="load_product('+ num +')" autocomplete="off" readonly />'
                +'<input type="hidden" id="pro_id_' + num + '" name="pro_id_' + num + '" value="" />'
                +'<input type="hidden" id="pro_uom_' + num + '" name="pro_uom_' + num + '" value="" />'

            + '</td>'
            + '<td>'
                +'<input type="text" style="text-align: right;padding-right: 5px" class="col-md-12 form-control form-control-sm" id="price_' + num + '" name="price_' + num + '" size="5" readonly />'
                +'<input type="hidden" style="text-align: right;padding-right: 5px" class="col-md-12 form-control form-control-sm" id="prc_' + num + '" name="prc_' + num + '" size="5" />'
            +'</td>'
            + '<td><input type="text" style="text-align: right;padding-right: 5px" class="col-md-12 form-control form-control-sm" id="qty_' + num + '" name="qty_' + num + '" onkeyup="check_qty(event, '+ num +');" size="5" autocomplete="off" /></td>'
            + '<td><input type="text" style="text-align: right;padding-right: 5px" class="col-md-12 form-control form-control-sm" id="line_amt_' + num + '" name="line_amt_' + num + '" onkeyup="check_qty(event, '+ num +');" size="5" readonly /></td>'
            + '<td style="text-align: center">'
                +'<span class="fa fa-minus-circle fa-lg" style="cursor: pointer;" onclick="remove_item('+ num +');"></span>'
            + '</td>'
            + '</tr>');
    }

    function select_doctor(){
        
    }

    function generate_product(){
        if($('#doctor_id').val() === ""){
            $('#doctor_id').focus();
            $.alert({
                title: 'Alert',
                icon: 'fa fa-warning',
                type: 'green',
                content: 'Please select doctor'
            });
        } else {
            $("#product_name").autocomplete({
                source: "{{ url('invoice/product') }}",
                minLength: 1,
                select: function (event, ui) {
                    console.log('aaa ' +ui);
                    $("#product_name").val(ui.item.label);
                    $("#product_id").val(ui.item.id);

                    gen_item();

                    var num = parseFloat($('#item_count').val());
                    /*check Item is already entered*/
                    var i, check = 1;
                    var j = 0;
                    var load_staus = true;
                    for (i = 1; i <= parseFloat($('#item_count').val()); i++) {
                        j++;

                        if($('#pro_id_' + i).val() == ui.item.id && i != num){
                            load_staus = false;
                            check = 0;
                            $("#pro_name_" + num).val('');
                            $("#pro_name_" + i).css('border', '1px solid red');
                            $("#qty_" + i).css('border', '1px solid red');
                            $("#price_" + i).css('border', '1px solid red');
                            $("#pro_name_" + i).focus();
                            $.alert({
                                title: 'Alert',
                                icon: 'fa fa-warning',
                                type: 'green',
                                content: 'This Product is already added'
                            });
                            remove_item(num);
                            break;
                        }
                        if (j == parseFloat($('#item_count').val())) {
                            check = 0;
                        }
                    }

                    if (check == 0 && load_staus) {
                        $("#pro_name_" + num).val(ui.item.label);
                        $("#pro_id_" + num).val(ui.item.id);
                        var pro_type = "";
                        if(ui.item.uom == '1'){
                            pro_type = 'volume_or_kg';
                        } else if(ui.item.uom == '2'){
                            pro_type = 'volume_or_kg';
                        } else {
                            pro_type = 'other';
                        }
                        $("#pro_uom_" + num).val(pro_type);

                        load_price(num, ui.item.id);

                    }
                }
            });
        }
    }

    /* GENERATE PRODUCT VIA AUTOCOMPLETE */
    function load_product(num){
        if($('#inv_type').val() === ""){
            $('#inv_type').focus();
            $.alert({
                title: 'Alert',
                icon: 'fa fa-warning',
                type: 'green',
                content: 'Please select invoice type'
            });
        } else {
            $("#pro_name_" + num).autocomplete({
                source: "{{ url('/invoice/product') }}",
                minLength: 1,
                select: function (event, ui) {
                    /*check Item is already entered*/
                    var i, check = 1;
                    var j = 0;
                    var load_staus = true;
                    for (i = 1; i <= parseFloat($('#item_count').val()); i++) {
                        j++;

                        if($('#pro_id_' + i).val() == ui.item.id && i != num){
                            load_staus = false;
                            check = 0;
                            $("#pro_name_" + num).val('');
                            $("#pro_name_" + i).css('border', '1px solid red');
                            $("#qty_" + i).css('border', '1px solid red');
                            $("#price_" + i).css('border', '1px solid red');
                            $("#pro_name_" + i).focus();
                            $.alert({
                                title: 'Alert',
                                icon: 'fa fa-warning',
                                type: 'green',
                                content: 'This Product is already added'
                            });
                            remove_item(num);
                            break;
                        }
                        if (j == parseFloat($('#item_count').val())) {
                            check = 0;
                        }
                    }
                    if (check == 0 && load_staus) {
                        $("#pro_name_" + num).val(ui.item.label);
                        $("#pro_id_" + num).val(ui.item.id);
                        $("#pro_uom_" + num).val(ui.item.uom);

                        load_price(num, ui.item.id);

                    }
                }
            });
        }
    }

    /* LOAD PRODUCT PRICE */
    function load_price(num,item_id){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "GET",
            url: "{{ url('/invoice/price') }}",
            data: {
                item_id: $('#pro_id_'+ num).val()
            },
            success: function (data) {
                if (data.length > 0) {
                    var price = data[0].price;
                    $('#price_' + num).val(price);
                    $('#prc_' + num).val(price);
                    $('#qty_' + num).focus();
                } else {
                    $('#price_' + num).val(0);
                    $('#prc_' + num).val(0);
                }
                $("#product_name").val("");
                $("#product_id").val("");
            }
        });
    }

    /*REMOVE GENERATED ROW*/
    function remove_item(num) {
        if (parseFloat($('#item_count').val()) != 1) {
            $('#tr_' + num).remove();
            var num = parseFloat($('#item_count').val()) - 1;
            $('#item_count').val(num);
        }
        calc_amount();
    }

    function check_qty(evt, i) {
        var keyCode;
        if ("which" in evt) {// NN4 & FF &amp; Opera
            keyCode = evt.which;
        } else if ("keyCode" in evt) {// Safari & IE4+
            keyCode = evt.keyCode;
        } else if ("keyCode" in window.event) {// IE4+
            keyCode = window.event.keyCode;
        } else if ("which" in window.event) {
            keyCode = evt.which;
        } else {
            //alert("the browser don't support");
        }
        if (keyCode == 16) {// press Enter
            gen_item();
        }
        if ($('#inv_type').val() == "") {
            $('#qty_' + i).val(0);
            $('#inv_type').focus();
            $.alert({
                title: 'Alert',
                icon: 'fa fa-warning',
                type: 'green',
                content: 'Please select invoice type'
            });
        }
        if (!$('#qty_' + i).val().match(/^(\d+)$/) && $('#qty_' + i).val() != "" && ($('#pro_uom_' + i).val() == 'other')) {
            var qty = parseFloat(document.getElementById('qty_' + i).value).toFixed(0);

            if (isNaN(qty)) {
                qty = 0;
            }
            $('#qty_' + i).val(qty);
            $.alert({
                title: 'Alert',
                icon: 'fa fa-warning',
                type: 'green',
                content: 'Enter valid Quantity'
            });
        } else if (!$('#qty_' + i).val().match(/^\d+(\.\d{0,2})?$/) && $('#qty_' + i).val() != "" && ($('#pro_uom_' + i).val() == 'volume_or_kg')) {
            var qty = parseFloat(document.getElementById('qty_' + i).value).toFixed(2);

            if (isNaN(qty)) {
                qty = 0;
            }
            $('#qty_' + i).val(qty);
            $.alert({
                title: 'Alert',
                icon: 'fa fa-warning',
                type: 'green',
                content: 'Enter valid Weight Or Volume'
            });
        }
        calc_amount();
    }

    Number.prototype.formatMoney = function (c, d, t) {
        var n = this,
                c = isNaN(c = Math.abs(c)) ? 2 : c,
                d = d == undefined ? "." : d,
                t = t == undefined ? "," : t,
                s = n < 0 ? "-" : "",
                i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "",
                j = (j = i.length) > 3 ? j % 3 : 0;
        return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
    };

    function calc_amount(){
        var i = 1;
        var tot_amount = 0;
        for (i = 1; i <= parseFloat($('#item_count').val()); i++) {

            var line_amount = 0;
            var line_amount_qty = 0;
            /** CALC BOXES AMOUNT */
            if (document.getElementById('pro_id_' + i) && $('#qty_' + i).val().match(/^(\d+)$/) && $('#qty_' + i).val() != "" && $('#pro_uom_' + i).val() == 'other') {
                // line_amount_qty = 0;
                line_amount_qty = parseFloat($('#qty_' + i).val()) * parseFloat($('#price_' + i).val());

                tot_amount += line_amount_qty;
            } else if(document.getElementById('pro_id_' + i) && $('#qty_' + i).val().match(/^\d+(\.\d{0,2})?$/) && $('#qty_' + i).val() != "" && $('#pro_uom_' + i).val() == 'volume_or_kg') {
                // line_amount_qty = 0;
                line_amount_qty = parseFloat($('#qty_' + i).val()) * parseFloat($('#price_' + i).val());

                tot_amount += line_amount_qty;
            }
            line_amount += line_amount_qty;
            $('#line_amt_' + i).val(line_amount.formatMoney(2, '.', ','));
        }
        $('#tot_amount').val(tot_amount.formatMoney(2, '.', ','));
    }

    function invoice_validation() {
        valid = true;
        if ($('#inv_type').val() == "") {
            valid = false;
            $('#inv_type').focus();
            $.alert({
                title: 'Alert',
                icon: 'fa fa-warning',
                type: 'green',
                content: 'Please select invoice type'
            });
        } else {
            var m = 1;
            for (m = 1; m <= parseInt($('#item_count').val()); m++) {
                if (document.getElementById('pro_name_' + m) && ($('#pro_id_' + m).val() == "")) {
                    valid = false;
                    $('#pro_name_' + m).focus();
                    $.alert({
                        title: 'Alert',
                        icon: 'fa fa-warning',
                        type: 'green',
                        content: 'Select Product'
                    });
                    break;
                } else if (document.getElementById('pro_id_' + m) && ($('#qty_' + m).val() === '0' || $('#qty_' + m).val() === '')) {
                    valid = false;
                    $('#qty_' + m).focus();
                    $.alert({
                        title: 'Alert',
                        icon: 'fa fa-warning',
                        type: 'green',
                        content: 'Enter Valid Quantity'
                    });
                    break;
                }
            }
        }
        return valid;
    }

    function form_submit(button_id, form_id) {
        if (invoice_validation()) {

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
