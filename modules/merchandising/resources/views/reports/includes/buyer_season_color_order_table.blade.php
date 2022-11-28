<div class="row">
    <div style="width: 50%">
        <table class="borderless">
            <tbody>
            <tr>
                <td><b>Company :</b></td>
                <td>{{ $company ?? '' }}</td>
            </tr>
            <tr>
                <td><b>Buyer : </b></td>
                <td>{{ $buyer ?? ''}}</td>
            </tr>
            @if($season)
                <tr>
                    <td><b>Season :</b></td>
                    <td>{{ $season }}</td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
</div>
<table class="reportTable">
    <thead style="background-color: aliceblue">
    <tr>
        <th>SL</th>
        <th>SEASON</th>
        <th>BUYER</th>
        <th>STYLE</th>
        <th>MERCHANDISER</th>
        @if(!isset($type))
            <th>IMAGE</th>
        @endif
        <th>PO</th>
        <th>COLOR</th>
        <th>PRODUCT DEPT</th>
        <th>PO QTY</th>
        <th>UOM</th>
        <th>PO FOB</th>
        <th>ACT SHIP DATE</th>
        <th>FAC SHIP DATE</th>
        <th>PO VALUE</th>
        <th>REMARKS</th>
        <th>CREATED</th>
    </tr>
    </thead>
    <tbody>
    @if(count($reports)>0)
        @php($i=0)
        @foreach($reports as $key=>$purchaseReport)
            <tr>
                <td>{{ str_pad(++$i, 2, '0', STR_PAD_LEFT) }}</td>
                <td>{{ $purchaseReport['season'] }}</td>
                <td class="text-left">{{ $purchaseReport['buyer'] }}</td>
                <td class="text-left">{{ $purchaseReport['style'] }}</td>
                <td class="text-left">{{ $purchaseReport['dealing_merchant'] }}</td>
                @if(!isset($type))
                    @if($key!=0 && $reports[$key]['image'] === $reports[$key-1]['image'])
                        <td>...</td>
                    @else
                        <td>
                            @if($purchaseReport['image'] && File::exists('storage/'.$purchaseReport['image']))
                                <img
                                    src="{{asset('storage/'. $purchaseReport['image'])}}"
                                    alt="style image"
                                    height="50" width="50">
                            @else
                                <img src="{{ asset('images/no_image.jpg') }}" height="50" width="50"
                                     alt="no image">
                            @endif
                        </td>
                    @endif
                @endif
                <td>{{ $purchaseReport['po'] }}</td>
                <td class="text-left">{{ $purchaseReport['color'] }}</td>
                <td>{{ $purchaseReport['product_dept'] }}</td>
                <td>{{ $purchaseReport['po_qty'] }}</td>
                <td>{{ $purchaseReport['uom'] }}</td>
                <td class="text-right">
                    ${{ number_format($purchaseReport['po_fob'], 2) }}</td>
                <td>{{ $purchaseReport['actual_ship_date'] }}</td>
                <td>{{ $purchaseReport['factory_ship_date'] }}</td>
                <td class="text-right">
                    ${{ number_format($purchaseReport['po_fob_value'], 2) }}</td>
                <td>{{ $purchaseReport['remarks'] }}</td>
                <td class="text-left">{{ $purchaseReport['created_by'] }} <br> {{ $purchaseReport['created_at'] }}</td>
            </tr>
        @endforeach
        <tr style="background-color: gainsboro">
            <td class="text-right" style="font-weight: bold" colspan="{{!isset($type) ? 9 : 8}}">Total</td>
            <th>{{$total_po_qty}}</th>
            <th></th>
            <td class="text-right" style="font-weight: bold">${{$total_po_fob}}</td>
            <th colspan="2"></th>
            <td class="text-right" style="font-weight: bold">${{$total_po_fob_value}}</td>
            <th></th>
            <th></th>
        </tr>
    @else
        <tr>
            <td colspan="{{!isset($type) ? 20 : 19}}">No Data Available</td>
        </tr>
    @endif
    </tbody>
</table>
<br>
