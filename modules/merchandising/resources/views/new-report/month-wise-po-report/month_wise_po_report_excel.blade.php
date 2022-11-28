<div class="row m-t">
    <div class="col-sm-12">
        <table class="reportTable" style="width: 100%">
            <thead>
            <tr><td colspan="7" style="background-color: aliceblue; height: 30px; " ><h3>{{ factoryName() }}</h3></td></tr>
            <tr><td class="text-left" colspan="7" style="background-color: aliceblue; height: 30px; text-align: left;" ><h4>{{ factoryAddress() }}</h4></td></tr>
            <tr><td class="text-center" colspan="7" style="background-color: aliceblue; height: 30px;" >{{ "Month Wise PO Report "}}</td></tr>
            </thead>
        </table>
        <table class="reportTable" style="width: 100%">
            <thead style="background-color: aliceblue;">
            <tr>
                <td style="border: 1px solid black;" class="text-center">Season</td>
                <td style="border: 1px solid black;" class="text-center">Buyer</td>
                <td style="border: 1px solid black;" class="text-center">Style</td>
                <td style="border: 1px solid black;" class="text-center">PO</td>
                <td style="border: 1px solid black;" class="text-center">PO Quantity</td>
                <td style="border: 1px solid black;" class="text-center">Total FOB Value</td>
                <td style="border: 1px solid black;" class="text-center">Exfactory Date</td>
            </tr>
            </thead>
            <tbody>

            @if(count($reportData)>0)
                @foreach($reportData as $data)
                    <tr>
                        <td style="border: 1px solid black;" class="text-center">{{$data['season']}}</td>
                        <td style="border: 1px solid black;" class="text-center">{{$data['buyer']}}</td>
                        <td style="border: 1px solid black;" class="text-center">{{$data['style']}}</td>
                        <td style="border: 1px solid black;" class="text-right">{{$data['po']}}</td>
                        <td style="border: 1px solid black;" class="text-right">{{$data['po_qty']}}</td>
                        <td style="border: 1px solid black;" class="text-right">{{"$".number_format($data['value'],2)}}</td>
                        <td style="border: 1px solid black;" class="text-right">{{$data['ex_factory_date']}}</td>
                    </tr>
                @endforeach
                <tr style="background-color: lightgray;">
                    <td style="border: 1px solid black;" colspan="4"><b>Total</b></td>
                    <td style="border: 1px solid black;" class="text-right">{{ number_format(collect($reportData)->sum('po_qty') ,2)}}</td>
                    <td style="border: 1px solid black;" class="text-right">{{ "$".number_format(collect($reportData)->sum('value') ,2)}}</td>
                    <td></td>
                </tr>
            @else
                <tr style="background-color: lightgray;">
                    <td colspan="6" style="border: 1px solid black;" class="text-danger">No Data Found</td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
</div>
