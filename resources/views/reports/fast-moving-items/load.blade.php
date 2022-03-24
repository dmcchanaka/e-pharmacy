@if (count($daily_sales)>0)
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered first">
                <thead class="thead-custom">
                    <tr>
                        <th style="text-align: center">PRODUCT</th>
                        <th style="text-align: center">WHOLESALE PRICE</th>
                        <th style="text-align: center">RETAILER PRICE</th>
                        <th style="text-align: center">SOLD QTY</th>
                        <th style="text-align: center">WHOLESALE AMOUNT</th>
                        <th style="text-align: center">RETAILER AMOUNT</th>
                        <th style="text-align: center">PROFIT</th>
                    </tr>
                </thead>
                <tbody>
                    @php 
                        $totalSoldQty = 0;
                        $totalWholeSaleAmt = 0;
                        $totalRetailAmt = 0;
                        $totalProfit = 0;
                    @endphp
                    @foreach ($daily_sales as $item)
                    @php 
                        $totalSoldQty += $item->sold_qty;
                        $totalWholeSaleAmt += $item->wholesale_amt;
                        $totalRetailAmt += $item->retailer_amt;
                        $totalProfit += ($item->retailer_amt - $item->wholesale_amt);
                    @endphp
                        <tr>
                            <td>{{$item->pro_name}}</td>
                            <td style="text-align: right">{{$item->ip_buying_price}}</td>
                            <td style="text-align: right">{{$item->ip_price}}</td>
                            <td style="text-align: right">{{number_format($item->sold_qty,0)}}</td>
                            <td style="text-align: right">{{number_format($item->wholesale_amt,2)}}</td>
                            <td style="text-align: right">{{number_format($item->retailer_amt,2)}}</td>
                            <td style="text-align: right;background-color:aqua">{{number_format($item->retailer_amt - $item->wholesale_amt,2)}}</td>
                        </tr>
                    @endforeach
                    <tr style="background-color: #b4b4b4">
                        <td style="font-size:13px" colspan="3"><strong> TOTAL</strong></td>
                        <td style="text-align: right;font-size:13px"><strong>{{number_format($totalSoldQty,0)}}</strong></td>
                        <td style="text-align: right;font-size:13px"><strong>{{number_format($totalWholeSaleAmt,2)}}</strong></td>
                        <td style="text-align: right;font-size:13px"><strong>{{number_format($totalRetailAmt,2)}}</strong></td>
                        <td style="text-align: right;font-size:13px"><strong>{{number_format($totalProfit,2)}}</strong></td>
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