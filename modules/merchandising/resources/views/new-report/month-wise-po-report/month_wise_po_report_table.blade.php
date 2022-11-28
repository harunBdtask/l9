<table class="reportTable"
       style="width: 100%; background-image: linear-gradient(to right,#fff, rgba(179,229,252,0.78));">
    <thead style="background-color: aliceblue;">
    <tr>
        <th>Season</th>
        <th>Buyer</th>
        <th>Style</th>
        <th>PO</th>
        <th>PO Quantity</th>
        <th>Total FOB Value</th>
        <th>Exfactory Date</th>
    </tr>
    </thead>
    <tbody>

    @foreach($reportData as $data)
        <tr>
            <td>{{$data['season']}}</td>
            <td>{{$data['buyer']}}</td>
            <td>{{$data['style']}}</td>
            <td>{{$data['po']}}</td>
            <td class="text-right">{{number_format($data['po_qty'],2)}}</td>
            <td class="text-right">{{"$".number_format($data['value'],2)}}</td>
            <td class="text-center">{{$data['ex_factory_date']}}</td>
        </tr>
    @endforeach
    <tr style="background-color: lightgray;">
        <td colspan="4"><b>Total</b></td>
        <td class="text-right">{{ number_format(collect($reportData)->sum('po_qty') ,2)}}</td>
        <td class="text-right">{{ "$".number_format(collect($reportData)->sum('value') ,2)}}</td>
        <td></td>
    </tr>
    </tbody>
</table>
<table></table>
<table class="borderless">
    <tr>
        <td style="color: white;">.</td>
    </tr>
    <tr>
        <td style="color: white;">.</td>
    </tr>
</table>
<table class="borderless">
    <tbody>
    <tr>
        <td class="text-center"><u>Prepared By</u></td>
        <td class='text-center'><u>Checked By</u></td>
        <td class="text-center"><u>Approved By</u></td>
    </tr>
    </tbody>
</table>
