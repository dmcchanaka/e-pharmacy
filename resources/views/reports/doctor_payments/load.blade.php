@if (count($doctorPayments)>0)
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered first">
                <thead class="thead-custom">
                    <tr>
                        <th style="text-align: center">DOCTOR</th>
                        <th style="text-align: center">CONSULT COUNT</th>
                        <th style="text-align: center">AMOUNT</th>
                    </tr>
                </thead>
                <tbody>
                    @php $totalValue = 0;$totalCount = 0; @endphp
                    @foreach ($doctorPayments as $item)
                    @php $totalValue += $item->doc_consult_fee;$totalCount += $item->doc_consult_count; @endphp
                        <tr>
                            <td>{{$item->doctor_name}}</td>
                            <td style="text-align: right">{{number_format($item->doc_consult_count,0)}}</td>
                            <td style="text-align: right">{{number_format($item->doc_consult_fee,2)}}</td>
                        </tr>
                    @endforeach
                    <tr style="background-color: #b4b4b4">
                        <td style="font-size:13px" colspan="1"><strong> TOTAL</strong></td>
                        <td style="text-align: right;font-size:13px"><strong>{{number_format($totalCount,0)}}</strong></td>
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