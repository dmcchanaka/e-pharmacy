@if (count($received_stk)>0)
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered first">
                <thead class="thead-custom">
                    <tr>
                        <th style="text-align: center">PRODUCT</th>
                        <th style="text-align: center">COST PRICE</th>
                        <th style="text-align: center">RECEIVED QTY</th>
                        <th style="text-align: center">REMAINING QTY</th>
                        <th style="text-align: center">VALUE</th>
                    </tr>
                </thead>
                <tbody>
                    @php $totalValue = 0; @endphp
                    @foreach ($received_stk as $item)
                    @php $totalValue += $item->rs_remaining_qty * $item->cost_price; @endphp
                        <tr>
                            <td>{{$item->pro_name}}</td>
                            <td style="text-align: right">{{$item->cost_price}}</td>
                            <td style="text-align: right">{{number_format($item->rs_qty,0)}}</td>
                            <td style="text-align: right">{{number_format($item->rs_remaining_qty,0)}}</td>
                            <td style="text-align: right">{{number_format($item->rs_remaining_qty * $item->cost_price,2)}}</td>
                        </tr>
                    @endforeach
                    <tr style="background-color: #b4b4b4">
                        <td style="font-size:13px" colspan="4"><strong> TOTAL</strong></td>
                        <td style="text-align: right;font-size:13px"><strong>{{number_format($totalValue,2)}}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@else
<div class="card">
    <div class="card-body">
        <div style="text-align:center"><label class="col-form-label" style="text-align:center;color:red">No Record Found</label></div>
    </div>
</div>
@endif