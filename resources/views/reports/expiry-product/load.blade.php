@if (count($received_stk)>0)
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered first">
                <thead class="thead-custom">
                    <tr>
                        <th style="text-align: center">PRODUCT</th>
                        <th style="text-align: center">WHOLESALE PRICE</th>
                        <th style="text-align: center">EXPIRY DATE</th>
                        <th style="text-align: center">STATE</th>
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
                            <td style="text-align: center">{{$item->expiry_date}}</td>
                            <td style="text-align: center">
                                @if($item->Days < 0)
                                <span style="color: #842029; background-color:#f8d7da;border-color: #f5c2c7;border-radius:3px;padding: 3px">EXPIRED</span>
                                @elseif ($item->Days > 0)
                                <span style="color: #664d03; background-color:#fff3cd;border-color: #ffecb5;border-radius:3px;padding: 3px">TO BE EXPIRED</span>
                                @endif
                            </td>
                            <td style="text-align: right">{{number_format($item->rs_remaining_qty,0)}}</td>
                            <td style="text-align: right">{{number_format($item->rs_remaining_qty * $item->cost_price,2)}}</td>
                        </tr>
                    @endforeach
                    <tr style="background-color: #b4b4b4">
                        <td style="font-size:13px" colspan="5"><strong> TOTAL</strong></td>
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