@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="{{asset('template/css/jquery-confirm.min.css')}}">
@endsection
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Products</h2>
    </div>
    <div class="col-lg-2">

    </div>
</div>
@include('flash-message')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-title">
                    <h5>Product Informations</h5>
                    <div class="ibox-tools animated">
                        <div class="col-lg-2" style="padding-bottom: 25px">
                            <button class="btn btn-info" type="button" onclick="toggle_product_model();">
                                <i class="fa fa-plus"></i> Add New Product
                            </button>
                        </div>
                    </div>

                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-example" id="instrument_table" >
                            <thead>
                                <tr>
                                    <th>Product Code</th>
                                    <th>Description</th>
                                    <th>Buying Price</th>
                                    <th>Retailer Price</th>
                                    <th>Status</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($product as $item)
                                    <tr>
                                    <td>{{$item->pro_code}}</td>
                                    <td>{{$item->pro_name}}</td>
                                    <td style="text-align: right">{{$item->buying_price}}</td>
                                    <td style="text-align: right">{{$item->retailer_price}}</td>
                                    <td style="text-align: center">
                                        @if ($item->deactivated_at == NULL)
                                        <span style="color: green">ACTIVE</span>
                                        @else
                                        <span style="color: red">INACTIVE</span>
                                        @endif
                                    </td>
                                    <td style="text-align: center">
                                    @if ($permission = \App\Models\User::checkUserPermission(Auth::user()->per_gp_id,Auth::user()->u_tp_id,"PRODUCT EDIT") == 1)
                                        <a href="#" onclick="edit_product({{$item->pro_id}})"><i class="fa fa-pencil fa-lg"></i></a>
                                        @endif
                                    </td>
                                    <td style="text-align: center">
                                    @if ($permission = \App\Models\User::checkUserPermission(Auth::user()->per_gp_id,Auth::user()->u_tp_id,"PRODUCT DELETE") == 1)
                                        <a href="#" onclick="delete_product({{$item->pro_id}})" data-method="delete"><i style="color: red" class="fa fa-trash-o fa-lg"></i></a>
                                        @endif
                                    </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<!----- START MODAL -->
<div class="modal inmodal" id="product-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
    <div class="modal-content animated bounceInRight">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <i class="fa fa-laptop modal-icon"></i>
                <h4 class="modal-title">Product Registration</h4>
            </div>
            <form role="form" action="{{url('add_product')}}" method="post">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Product Code</label>
                        <input type="text" placeholder="Enter Product Code" id="pro_code" name="pro_code" class="form-control" required="" autocomplete="off" />
                    </div>
                    <div class="form-group">
                        <label>Unit Of Measure</label>
                        <select class="form-control" required id="unit_of_measure" name="unit_of_measure">
                            <option value="">SELECT</option>
                            <option value="1">Tablets</option>
                            <option value="2">capsules</option>
                            <option value="3">Bottles</option>
                            <option value="4">Tubes</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Product Name</label>
                        <input type="text" placeholder="Enter Product Name" id="pro_name" name="pro_name" class="form-control" required="" autocomplete="off" />
                    </div>
                    <div class="form-group">
                        <label>Wholesale Price</label>
                        <input type="text" placeholder="Enter Buying Price" id="buying_price" name="buying_price" class="form-control" required="" autocomplete="off" />
                    </div>
                    <div class="form-group">
                        <label>Retailer Price</label>
                        <input type="text" placeholder="Enter retailer price" id="retailer_price" name="retailer_price" onkeyup="checkProductProfit()" class="form-control" required="" autocomplete="off" />
                        <span id="product_profit"></span>
                    </div>
                    <div class="form-group">
                        <label>Expiry Date</label>
                        <input type="date" id="expiry_date" name="expiry_date" class="form-control" required="" autocomplete="off" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!----- END MODAL -->
<!----- START EDIT MODAL -->
<div class="modal inmodal" id="product-edit-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
    <div class="modal-content animated bounceInRight">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <i class="fa fa-laptop modal-icon"></i>
                <h4 class="modal-title">Edit Product Information</h4>
            </div>
            <form role="form" action="{{url('update_product')}}" method="post">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Product Code</label>
                        <input type="hidden" id="edit_pro_id" name="edit_pro_id" value="" />
                        <input type="text" placeholder="Enter Product Code" id="edit_pro_code" name="edit_pro_code" class="form-control" required="" autocomplete="off" />
                    </div>
                    <div class="form-group">
                        <label>Unit Of Measure</label>
                        <select class="form-control" required id="edit_unit_of_measure" name="edit_unit_of_measure">
                            <option value="">SELECT</option>
                            <option value="1">Tablets</option>
                            <option value="2">capsules</option>
                            <option value="3">Bottles</option>
                            <option value="4">Tubes</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Product Name</label>
                        <input type="text" placeholder="Enter Product Name" id="edit_pro_name" name="edit_pro_name" class="form-control" required="" autocomplete="off" />
                    </div>
                    <div class="form-group">
                        <label>Buying Price</label>
                        <input type="text" placeholder="Enter Buying Price" id="edit_buying_price" name="edit_buying_price" class="form-control" required="" autocomplete="off" />
                    </div>
                    <div class="form-group">
                        <label>Retailer Price</label>
                        <input type="text" placeholder="Enter retailer price" id="edit_retailer_price" name="edit_retailer_price" class="form-control" required="" autocomplete="off" />
                    </div>
                    <div class="form-group">
                        <label>Expiry Date</label>
                        <input type="date" id="edit_expiry_date" name="edit_expiry_date" class="form-control" required="" autocomplete="off" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!----- END MODAL -->
@endsection
@section('js')
<script src="{{asset('template/js/jquery-confirm.min.js')}}"></script>
<script type="text/javascript">
    function toggle_product_model() {
        $('#product-modal').modal();
        $("#pro_code").val("");
        $("#unit_of_measure").val("");
        $("#pro_name").val("");
        $("#buying_price").val("");
        $("#market_price").val("");
        $("#wholesale_price").val("");
        $("#retailer_price").val("");
        $("#expiry_date").val("");
    }

    setTimeout(checkProductProfit, 3000)

    function checkProductProfit(){
        var wholesalePrice = parseFloat($('#buying_price').val());
        var retailerPrice = parseFloat($('#retailer_price').val());
        const span = document.getElementById('product_profit');
        var profit = retailerPrice - wholesalePrice;
        console.log(retailerPrice + wholesalePrice);
        if(!isNaN(profit)){
            span.textContent = 'Product profit is '+ profit;
        } 
    }

    function edit_product(id){
        $.confirm({
            title: 'Confirm?',
            content: 'Are you sure do you want to edit this item ?',
            type: 'green',
            buttons: {
                Okey: {
                    text: 'Yes',
                    btnClass: 'btn-blue',
                    action: function () {
                        $.ajax({
                            type: "GET",
                            url: "{{ url('/product/edit') }}",
                            data: {
                                "id": id,
                                "_token": "{{ csrf_token() }}"
                            },
                            success: function (data) {
                                $('#product-edit-modal').modal('show');
                                $("#edit_pro_id").val(data.pro_id);
                                $("#edit_pro_code").val(data.pro_code);
                                $("#edit_unit_of_measure").val(data.measure_of_units);
                                $("#edit_pro_name").val(data.pro_name);
                                $("#edit_buying_price").val(data.buying_price);
                                $("#edit_retailer_price").val(data.retailer_price);
                                $("#edit_expiry_date").val(data.expiry_date);
                            }
                        });
                    }
                },
                cancel: {
                    text: 'No',
                    btnClass: 'btn-red',
                    action: function () {

                    }
                }
            }
        });
    }

    function delete_product(id){
        $.confirm({
            title: 'Confirm?',
            content: 'Are you sure do you want to remove this item ?',
            type: 'green',
            buttons: {
                Okey: {
                    text: 'Yes',
                    btnClass: 'btn-blue',
                    action: function () {
                        $.ajax({
                            type: "GET",
                            url: "{{ url('/product/delete') }}",
                            data: {
                                "id": id,
                                "_token": "{{ csrf_token() }}"
                            },
                            success: function (data) {
                                location.reload();
                            }
                        });
                    }
                },
                cancel: {
                    text: 'No',
                    btnClass: 'btn-red',
                    action: function () {

                    }
                }
            }
        });
    }
</script>
@endsection
