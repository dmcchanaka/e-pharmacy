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
                                            @foreach ($doctors as $item)
                                            <option value="{{$item->doctor_id}}">{{$item->doctor_name}}</option>
                                            @endforeach
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
                                    <th style="text-align: center">Stock</th>
                                    <th style="text-align: center">Qty/KG/Litre</th>
                                    <th style="text-align: center">Amount</th>
                                    <th style="text-align: center">&nbsp</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="6">
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
                                <input type="hidden" id="item_count" name="item_count" value="1" />
                                <input type="hidden" id="delete_count" name="delete_count" value="1"/>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td style="padding-top: 20px;text-align: right;font-weight:bold">Gross Amount</td>
                                    <td colspan="1" style="text-align: right;padding-right: 5px">
                                        <input type="text" style="text-align: right;padding-right: 5px;font-weight:bold" class="col-md-12 form-control form-control-sm" id="tot_amount" name="tot_amount" size="10" readonly />
                                    </td>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td style="padding-top: 20px;text-align: right;font-weight:bold">Dicount %</td>
                                    <td colspan="1" style="text-align: right;padding-right: 5px">
                                        <input type="text" style="text-align: right;padding-right: 5px;font-weight:bold" class="col-md-12 form-control form-control-sm" id="discount" name="discount" size="10" onkeyup="calc_amount()" />
                                        <input type="hidden" style="text-align: right;padding-right: 5px;font-weight:bold" class="col-md-12 form-control form-control-sm" id="discount_amt" name="discount_amt" size="10" />
                                    </td>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td style="padding-top: 20px;text-align: right;font-weight:bold">Net Amount</td>
                                    <td colspan="1" style="text-align: right;padding-right: 5px">
                                        <input type="text" style="text-align: right;padding-right: 5px;font-weight:bold" class="col-md-12 form-control form-control-sm" id="net_amount" name="net_amount" size="10" readonly />
                                    </td>
                                    <td>&nbsp;</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <h3 style="background-color: #b4b4b4;padding:5px;border-radius:3px">Other charges</h3><hr/>
                    <div class="row">
                        <div class="col-md-2 col-sm-12 col-xs-12 form-group"></div>
                        <div class="col-md-8 col-sm-12 col-xs-12 form-group text-center">
                            <div class="row">
                                <div class="col-md-1">
                                </div>
                                <div class="col-md-10">
                                    <table class="table table-striped table-bordered table-hover dataTables-example">
                                        <thead>
                                            <tr>
                                                <th style="text-align: center">&nbsp</th>
                                                <th style="text-align: center">Fee Type</th>
                                                <th style="text-align: center">Amount</th>
                                                <th style="text-align: center">&nbsp</th>
                                            </tr>
                                        </thead>
                                        <body>
                                            <tr>
                                                <td style="text-align: center;padding-top:15px">&nbsp;</td>
                                                <td>
                                                    <input type="text" name="doc_free" id="doc_fee" class="col-md-12 form-control form-control-sm" value="Consultation fee" readonly />
                                                </td>
                                                <td><input type="text" style="text-align: right;padding-right: 5px" class="col-md-12 form-control form-control-sm" id="consultation_amt" name="consultation_amt" size="5" value="0.00" onkeyup="check_consult_fee(event);" /></td>
                                                <td style="text-align: center;padding-top:15px">&nbsp;</td>
                                            </tr>
                                        </body>
                                        <tbody id="other_fees">
                                            <tr id="tr_other_1">
                                                <td style="text-align: center;padding-top:15px">
                                                    <span class="fa fa-plus fa-lg" style="cursor: pointer" onclick="gen_other_item();"></span>
                                                </td>
                                                <td>
                                                    <select id="other_type_1" name="other_type_1" class="form-control form-control-sm" onchange="select_pay_type('1')">
                                                        <option value="0">SELECT</option>
                                                        @foreach ($otherFees as $other)
                                                        <option value="{{$other->fee_id}}">{{$other->fee_description}}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td><input type="text" style="text-align: right;padding-right: 5px" class="col-md-12 form-control form-control-sm" id="other_amt_1" name="other_amt_1" size="5" onkeyup="check_other_fee(event, '1');" /></td>
                                                <td style="text-align: center;padding-top:15px">
                                                    <span class="fa fa-minus-circle fa-lg" style="cursor: pointer;" onclick="remove_other_item('1');"></span>
                                                </td>
                                            </tr>
                                        </tbody>
                                        <input type="hidden" id="other_item_count" name="other_item_count" value="1" />
                                        <input type="hidden" id="delete_other_item_count" name="delete_other_item_count" value="1" />
                                    </table>
                                </div>
                                <div class="col-md-1">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12 col-xs-12 form-group"></div>
                    </div>
                    <div class="form-group mt-sm-1 mb-sm-1">
                        <div class="">
                            <input type="hidden" id="submit_type" name="submit_type" value="0" />
                            <button class="btn btn-secondary btn-sm" type="reset">Reset</button>
                            <button type="button" id="add" class="btn btn-primary btn-sm" onclick="form_submit('add', 'invoice_form', 0)">Print</button>
                            <button type="button" id="without_print" class="btn btn-danger btn-sm" onclick="form_submit('without_print', 'invoice_form', 1)">Without Print</button>
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
    $(document).ready(function(){
        generate_product();
        document.getElementById("product_name").focus();
    });

    function removeWhiteSpace(string){
        finalstr = string.replace(/ /g, '');
        return finalstr;
    }

    /* SUBMIT USING SPACE KEY */
    document.body.onkeyup = function(e){
        if(e.keyCode == 32 || e.keyCode == 18){ //32 => space, 18 => alt
            e.preventDefault();
  	        e.stopImmediatePropagation();

            try {
                removeWhiteSpaces();
            } finally {
                if(e.keyCode == 32){
                    form_submit('add', 'invoice_form', 0);
                } else if(e.keyCode == 18){
                    form_submit('without_print', 'invoice_form', 1);
                }
            }
        }
    }

    function removeWhiteSpaces(){
        //cunsult fee
        var cunsultFeeStr = document.getElementById('consultation_amt').value;
        consultFee = removeWhiteSpace(cunsultFeeStr);
        var finalConsultFee = parseFloat(consultFee).toFixed(2);
        $('#consultation_amt').val(finalConsultFee);

        //other charges
        var otherItemCount = $('#other_item_count').val();
        if(otherItemCount>0){
            for(var i = 1; i <= otherItemCount; i++){
                if(typeof $('#other_type_' + i).val() != 'undefined'){
                   var otherFeeStr = document.getElementById('other_amt_' + i).value;
                
                    otherFee = removeWhiteSpace(otherFeeStr);

                    var finalOtherFee = parseFloat(otherFee).toFixed(2);
                    if (isNaN(finalOtherFee)) {
                        finalOtherFee = 0;
                    }
                    $('#other_amt_' + i).val(finalOtherFee);    
                }
            }
        }
    }

    function check_consult_fee(evt){
        evt.preventDefault();
        evt.stopImmediatePropagation();
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

        var cunsultFeeStr = document.getElementById('consultation_amt').value;
        consultFee = removeWhiteSpace(cunsultFeeStr);
        var finalConsultFee = consultFee;
        $('#consultation_amt').val(finalConsultFee);


        if (!$('#consultation_amt').val().match(/^\d+(\.\d{0,2})?$/) && $('#consultation_amt').val() != "") {
            var consultation_amt = parseFloat(document.getElementById('consultation_amt').value).toFixed(2);

            if (isNaN(consultation_amt)) {
                consultation_amt = 0;
            }
            $('#consultation_amt').val(consultation_amt.formatMoney(2, '.', ','));
            $.alert({
                title: 'Alert',
                icon: 'fa fa-warning',
                type: 'green',
                content: 'Enter valid Consultant Fee',
                buttons: {
                    ok: {
                        text: 'ok', // With spaces and symbols
                        keys: ['enter'],
                        action: function () {
                        }
                    }
                }
            });
        }
        if (keyCode == 32 && $('#consultation_amt').val() > 0) {// press Space
            form_submit('add', 'invoice_form', 0);
        } else if(keyCode == 18 && $('#consultation_amt').val() > 0){
            form_submit('without_print', 'invoice_form', 1);
        }
        calc_amount();
    }

    function check_other_fee(evt, num){
        evt.preventDefault();
        evt.stopImmediatePropagation();
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

        var otherFeeStr = document.getElementById('other_amt_' + num).value;
        otherFee = removeWhiteSpace(otherFeeStr);
        var finalOtherFee = otherFee;
        $('#other_amt_' + num).val(finalOtherFee);


        if (!$('#other_amt_' + num).val().match(/^\d+(\.\d{0,2})?$/) && $('#other_amt_' + num).val() != "") {
            var other_amt = parseFloat(document.getElementById('other_amt_' + num).value).toFixed(2);

            if (isNaN(other_amt)) {
                other_amt = 0;
            }
            $('#other_amt_' + num).val(other_amt);
            $.alert({
                title: 'Alert',
                icon: 'fa fa-warning',
                type: 'green',
                content: 'Enter valid Other Fee',
                buttons: {
                    ok: {
                        text: 'ok', // With spaces and symbols
                        keys: ['enter'],
                        action: function () {
                        }
                    }
                }
            });
        }
        if (keyCode == 32 && $('#other_amt_' + num).val() > 0) {// press Space
            form_submit('add', 'invoice_form', 0);
        } else if(keyCode == 18 && $('#other_amt_' + num).val() > 0){
            form_submit('without_print', 'invoice_form', 1);
        }
        calc_amount();
    }

    function load_other_fees(num){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "GET",
            url: "{{ url('/invoice/other_fees') }}",
            data: {
            },
            success: function (data) {
                $('#other_type_'+ num).empty();
                $('#other_type_'+ num).append('<option value="s">SELECT</option>');
                for(var x = 0; x < data.length; x++){
                    $('#other_type_'+ num).append('<option value='+data[x].fee_id+'>'+data[x].fee_description+'</option>');
                }
            }
        });
    }

    function select_pay_type(num){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "GET",
            url: "{{ url('/invoice/other_fees_by_id') }}",
            data: {
                other_type: $('#other_type_'+ num).val()
            },
            success: function (data) {
                if(typeof data.fee_amt != 'undefind'){
                    $('#other_amt_'+ num).val(data.fee_amt);
                } else {
                    $('#other_amt_'+ num).val('');
                }
                $('#other_amt_'+ num).focus();
                calc_amount();
            }
        });
    }

    /* GENERATE OTHER NEW ROW*/
    function gen_other_item(){
        var item_count = parseInt($('#other_item_count').val());
        var next_count = item_count + 1;
        $('#other_item_count').val(next_count);
        var delete_count = parseInt($('#delete_other_item_count').val());
        var delete_next_count = delete_count + 1;
        $('#delete_other_item_count').val(delete_next_count);
        
        $('#other_fees').append('<tr class="even pointer" id="tr_other_' + next_count + '">'
            + '<td style="text-align: center;padding-top:15px">'
                +'<span class="fa fa-plus fa-lg" style="cursor: pointer" onclick="gen_other_item();"></span>'
            + '</td>'
            + '<td>'
                + '<select id="other_type_' + next_count + '" name="other_type_' + next_count + '" class="form-control form-control-sm" onchange="select_pay_type(' + next_count + ')">'
                    + '<option value="0">SELECT</option>'
                + '</select>'
            + '</td>'
            + '<td><input type="text" style="text-align: right;padding-right: 5px" class="col-md-12 form-control form-control-sm" id="other_amt_' + next_count + '" name="other_amt_' + next_count + '" size="5" onkeyup="check_other_fee(event, '+ next_count +');" /></td>'
            + '<td style="text-align: center;padding-top:15px">'
                + '<span class="fa fa-minus-circle fa-lg" style="cursor: pointer" onclick="remove_other_item(' + next_count + ');"></span>'
            + '</td>'
        + '</tr>');
        load_other_fees(next_count);
    }
    /**/
    /* GENERATE NEW ROW */
    function gen_item(){
        var item_count = parseInt($('#item_count').val());
        var num = item_count + 1;
        $('#item_count').val(num);
        var delete_count = parseInt($('#delete_count').val());
        var delete_next_count = delete_count + 1;
        $('#delete_count').val(delete_next_count);
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
            + '<td>'
                +'<input type="text" style="text-align: right;padding-right: 5px" class="col-md-12 form-control form-control-sm" id="stock_' + num + '" name="stock_' + num + '" size="5" readonly />'
                +'<input type="hidden" style="text-align: right;padding-right: 5px" class="col-md-12 form-control form-control-sm" id="stk_' + num + '" name="stk_' + num + '" size="5" />'
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
                            document.getElementById("product_name").focus();
                            $.alert({
                                title: 'Alert',
                                icon: 'fa fa-warning',
                                type: 'green',
                                content: 'This Product is already added',
                                buttons: {
                                    ok: {
                                        text: 'ok', // With spaces and symbols
                                        keys: ['enter', 'a'],
                                        action: function () {
                                            document.getElementById("product_name").focus();
                                        }
                                    }
                                }
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
                        load_stock(num, ui.item.id);
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

    /* LOAD PRODUCT STOCK */
    function load_stock(num,item_id){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "GET",
            url: "{{ url('/invoice/stock') }}",
            data: {
                item_id: $('#pro_id_'+ num).val()
            },
            success: function (data) {
                if(data.stock < 20){
                    document.getElementById("stock_" + num).style.backgroundColor  = "yellow";
                }
                if (data.stock > 0) {
                    var stock = data.stock;
                    $('#stock_' + num).val(stock);
                    $('#stk_' + num).val(stock);
                } else {
                    $('#stock_' + num).val(0);
                    $('#stk_' + num).val(0);
                }
                $("#product_name").val("");
                $("#product_id").val("");
            }
        });
    }

    /*REMOVE GENERATED ROW*/
    function remove_item(num) {
        var delete_count = parseInt($('#delete_count').val());
        var delete_next_count = delete_count - 1;
        $('#delete_count').val(delete_next_count);
        $('#tr_'+num).empty();
        if(delete_next_count == 0){
            gen_item();
        }
        calc_amount();
    }

    function remove_other_item(num){
        var delete_count = parseInt($('#delete_other_item_count').val());
        var delete_next_count = delete_count - 1;
        $('#delete_other_item_count').val(delete_next_count);
        $('#tr_other_'+num).empty();
        if(delete_next_count == 0){
            gen_other_item();
        }
        calc_amount();
    }

    function check_qty(evt, i) {
        evt.preventDefault();
        evt.stopImmediatePropagation();
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
        if (!$('#qty_' + i).val().match(/^(\d+)$/) && $('#qty_' + i).val() != "") {
            var qty = parseFloat(document.getElementById('qty_' + i).value).toFixed(0);

            if (isNaN(qty)) {
                qty = 0;
            }
            $('#qty_' + i).val(qty);
            if(qty === 0){
                $.alert({
                    title: 'Alert',
                    icon: 'fa fa-warning',
                    type: 'green',
                    content: 'Enter valid Quantity',
                    buttons: {
                        ok: {
                            text: 'ok', // With spaces and symbols
                            keys: ['enter'],
                            action: function () {
                            }
                        }
                    }
                });
            } else {
                document.getElementById("product_name").focus();
                form_submit('add', 'invoice_form', 0);
            }
        } else if(parseInt($('#qty_' + i).val()) > parseInt($('#stock_' + i).val()) && parseInt($('#stock_' + i).val())>0){
            var qty = parseFloat(document.getElementById('stock_' + i).value).toFixed(0);

            if (isNaN(qty)) {
                qty = 0;
            }
            $('#qty_' + i).val(qty);
            $.alert({
                title: 'Alert',
                icon: 'fa fa-warning',
                type: 'green',
                content: 'Stock is not enough',
                buttons: {
                    ok: {
                        text: 'ok', // With spaces and symbols
                        keys: ['enter'],
                        action: function () {
                        }
                    }
                }
            });
        } else if(parseInt($('#stock_' + i).val())===0){
            var qty = parseFloat(document.getElementById('stock_' + i).value).toFixed(0);

            if (isNaN(qty)) {
                qty = 0;
            }
            $('#qty_' + i).val(qty);
            $.alert({
                title: 'Alert',
                icon: 'fa fa-warning',
                type: 'green',
                content: 'Stock is not enough',
                buttons: {
                    ok: {
                        text: 'ok', // With spaces and symbols
                        keys: ['enter'],
                        action: function () {
                        }
                    }
                }
            });
            remove_item(i);
        }
        if (keyCode == 13 && $('#qty_' + i).val() > 0) {// press Enter
            document.getElementById("product_name").focus();
            generate_product();
        } else if(keyCode == 18 && $('#qty_' + i).val() > 0){
            document.getElementById("product_name").focus();
            generate_product();
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
            if(typeof($('#qty_' + i).val()) != 'undefind'){
                if (document.getElementById('pro_id_' + i) && $('#qty_' + i).val().match(/^(\d+)$/) && $('#qty_' + i).val() != "") {
                    // line_amount_qty = 0;
                    line_amount_qty = parseFloat($('#qty_' + i).val()) * parseFloat($('#price_' + i).val());

                    tot_amount += line_amount_qty;
                }
                line_amount += line_amount_qty;
                $('#line_amt_' + i).val(line_amount.formatMoney(2, '.', ','));
            }
        }
        $('#tot_amount').val(tot_amount.formatMoney(2, '.', ','));

        var discountAmt = 0;
        var discount = parseFloat(document.getElementById('discount').value).toFixed(2);

        if (isNaN(discount)) {
            discount = 0;
            $('#discount').val(discount);
        }

        /*CALC NET AMOUNT*/
        if(discount > 0){
            discountAmt = (tot_amount / 100) * discount;
            $('#discount_amt').val(discountAmt.formatMoney(2, '.', ','));
        }
        var netAmount = 0;
        netAmount = tot_amount - discountAmt;

        /*CONSULTATION FEE*/
        var consultFee = parseFloat(document.getElementById('consultation_amt').value).toFixed(2);
        if (isNaN(consultFee)) {
            consultFee = 0;
            $('#consultation_amt').val(consultFee.formatMoney(2, '.', ','));
        }
        var j = 1;
        var tot_other_amount = 0;
        for (j = 1; j <= parseFloat($('#other_item_count').val()); j++) {
            if (document.getElementById('other_type_' + j) && $('#other_amt_' + j).val() != "") {
                tot_other_amount += parseFloat($('#other_amt_' + j).val());

                if (isNaN(tot_other_amount)) {
                    tot_other_amount = 0;
                    $('#other_amt_' + j).val(tot_other_amount.formatMoney(2, '.', ','));
                }
            }
        }
        netAmount = parseFloat(netAmount) + parseFloat(consultFee) + parseFloat(tot_other_amount);

        $('#net_amount').val(netAmount.formatMoney(2, '.', ','));
    }

    function invoice_validation() {
        valid = true;
        if ($('#doctor_id').val() == "") {
            valid = false;
            $('#doctor_id').focus();
            $.alert({
                title: 'Alert',
                icon: 'fa fa-warning',
                type: 'green',
                content: 'Please select doctor'
            });
        } else if((parseFloat($('#net_amount').val()) <= 0) || ($('#net_amount').val() == '')) {
            valid = false;
            $.alert({
                title: 'Alert',
                icon: 'fa fa-warning',
                type: 'green',
                content: 'Your gross amount is zero.Please check again'
            });
        }
        // else if(parseInt($('#item_count').val()) === 1){
        //     valid = false;
        //     $.alert({
        //         title: 'Alert',
        //         icon: 'fa fa-warning',
        //         type: 'green',
        //         content: 'Please enter atleast one item',
        //         buttons: {
        //             ok: {
        //                 text: 'ok', // With spaces and symbols
        //                 keys: ['enter', 'a'],
        //                 action: function () {
        //                     document.getElementById("product_name").focus();
        //                 }
        //             }
        //         }
        //     });
        // } 
        else {
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
                        content: 'Enter Valid Quantity',
                        buttons: {
                            ok: {
                                text: 'ok', // With spaces and symbols
                                keys: ['enter'],
                                action: function () {
                                }
                            }
                        }
                    });
                    break;
                }
            }
        }
        return valid;
    }

    function form_submit(button_id, form_id, btn_type) {
        $('#submit_type').val(btn_type);
        if (invoice_validation()) {

            $.confirm({
            title: 'Confirm?',
                content: 'Are you sure do you want submit this record',
                type: 'green',
                buttons: {
                    Okey: {
                        text: (btn_type == 0)?'save & Print': 'save',
                        btnClass: 'btn-blue',
                        keys: ['enter'],
                        action: function () {
                            document.getElementById(button_id).style.display = "none";
                            document.forms[form_id].submit();
                        }
                    },
                    cancel: {
                        text: 'cancel',
                        btnClass: 'btn-red',
                        keys: ['esc'],
                        action: function () {

                        }
                    }
                }
            });
        }
    }

</script>
@endsection
