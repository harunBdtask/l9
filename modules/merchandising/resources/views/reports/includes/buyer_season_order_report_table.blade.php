<style>
    table, tr, td, th, tbody, thead, tfoot {
        page-break-inside: avoid !important;
    }
</style>
<div style="width: 50%;">
    <table class="borderless">
        <tbody>
        <tr>
            <td><b>Buyer :</b></td>
            <td>{{ $buyer }}</td>
        </tr>
        @if($season)
            <tr>
                <td><b>Season :</b></td>
                <td>{{ $season }}</td>
            </tr>
        @endif
        @if(request()->get('search_type'))
            <tr>
                <td><b>Type :</b></td>
                <td>{{ ucfirst(str_replace('_', ' ', request()->get('search_type'))) }}</td>
            </tr>
        @endif
        @php
            $fromDate = request()->get('from_date') ? date("F j, Y", strtotime(request()->get('from_date'))) : null;
            $toDate = request()->get('to_date') ? date("F j, Y", strtotime(request()->get('to_date'))) : null;
        @endphp
        @if($fromDate && $toDate)
            @php
                $toDate = $toDate ? ' - ' . $toDate : '';
            @endphp
            <tr>
                <td><b>Type :</b></td>
                <td>{{ $fromDate . $toDate }}</td>
            </tr>
        @endif
        </tbody>
    </table>
</div>
<table class="reportTable">
    <thead style="background-color: aliceblue">
    <tr>
        <th>SL</th>
        <th>SEASON</th>
        <th>BUYER</th>
        <th>STYLE</th>
        <th>MERCHANDISER</th>
        @if(request('type')==='images')
            <th>IMAGE</th>
        @endif
        <th>PO</th>
        <th>PRODUCT DEPT</th>
        <th>PO QTY</th>
        <th>UOM</th>
        <th>PO FOB</th>
        <th>PO RECEIVE DATE</th>
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
        @foreach($reports as $purchaseReport)
            <tr>
                <td>{{ str_pad(++$i, 2, '0', STR_PAD_LEFT) }}</td>
                <td>{{ $purchaseReport['season'] }}</td>
                <td class="text-left">{{ $purchaseReport['buyer'] }}</td>
                <td class="text-left">{{ $purchaseReport['style'] }}</td>
                <td class="text-left">{{ $purchaseReport['dealing_merchant'] }}</td>

                @if(request('type')==='images')
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
                <td class="text-left">{{ $purchaseReport['po'] }}</td>
                <td>{{ $purchaseReport['product_dept'] }}</td>
                <td>{{ $purchaseReport['po_qty'] }}</td>
                <td>{{ $purchaseReport['uom'] }}</td>
                <td class="text-right">
                    ${{ number_format($purchaseReport['po_fob'], 2) }}</td>
                <td>{{ $purchaseReport['po_receive_date'] }}</td>
                <td>{{ $purchaseReport['actual_ship_date'] }}</td>
                <td>{{ $purchaseReport['factory_ship_date'] }}</td>
                <td class="text-right">
                    ${{ number_format($purchaseReport['po_fob_value'], 2) }}</td>
                <td>{{ $purchaseReport['remarks'] }}</td>
                <td class="text-left">{{ $purchaseReport['created_by'] }} <br> {{ $purchaseReport['created_at'] }}</td>
            </tr>
        @endforeach
        <tr style="background-color: gainsboro">
            @if(request('type')==='images')
                <td class="text-right" style="font-weight: bold" colspan="8">Total</td>
            @else
                <td class="text-right" style="font-weight: bold" colspan="7">Total</td>
            @endif
            <th>{{$total_po_qty}}</th>
            <th></th>
            <td class="text-right" style="font-weight: bold">${{$total_po_fob}}</td>
            <th colspan="3"></th>
            <td class="text-right" style="font-weight: bold">${{$total_po_fob_value}}</td>
            <th></th>
            <th></th>
        </tr>
    @else
        <tr>
            @if(request('type')==='images')
                <td colspan="19">No Data Available</td>
            @else
                <td colspan="18">No Data Available</td>
            @endif
        </tr>
    @endif
    </tbody>
</table>
