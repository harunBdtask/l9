@php
    $reports =request('type')==='images' ? collect($reports)->chunk(10) : collect($reports)->chunk(15);
@endphp
@if(count($reports)>0)
    @foreach($reports as $report)
        <table class="reportTable">
            <thead style="background-color: aliceblue">
            <tr>
                <th>SL</th>
                <th>SEASON</th>
                <th>BUYER</th>
                <th style="width: 8%">STYLE</th>
                @if(request('type')==='images')
                    <th>IMAGE</th>
                @endif
                <th>PO</th>
                <th>PRODUCT DEPT</th>
                <th>PO QTY</th>
                <th>UOM</th>
                <th>PO FOB</th>
                <th>FAC FOB</th>
                <th style="width: 10%;">ASSIGN FAC</th>
                <th>ACT SHIP DATE</th>
                <th>FAC SHIP DATE</th>
                <th>PO VALUE</th>
                <th>FAC VALUE</th>
                <th>REMARKS</th>
            </tr>
            </thead>
            <tbody>
            @php
                $i=0;
                $report = collect($report)->groupBy('season');
            @endphp
            @foreach($report as $seasonReport)
                @foreach($seasonReport as $purchaseReport)
                    <tr>
                        <td>{{ str_pad(++$i, 2, '0', STR_PAD_LEFT) }}</td>
                        @if($loop->first)
                            <td rowspan="{{count($seasonReport)}}">{{ $purchaseReport['season'] }}</td>
                            <td rowspan="{{count($seasonReport)}}">{{ $purchaseReport['buyer'] }}</td>
                        @endif
                        <td>{{ $purchaseReport['style'] }}</td>
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
                        <td>{{ $purchaseReport['po'] }}</td>
                        <td>{{ $purchaseReport['product_dept'] }}</td>
                        <td>{{ $purchaseReport['po_qty'] }}</td>
                        <td>{{ $purchaseReport['uom'] }}</td>
                        <td class="text-right">${{ number_format((float)$purchaseReport['po_fob'], 2) }}</td>
                        <td class="text-right">${{ number_format((float)$purchaseReport['factory_fob'], 2) }}</td>
                        <td>{{ $purchaseReport['assigning_factory'] }}</td>
                        <td>{{ $purchaseReport['actual_ship_date'] }}</td>
                        <td>{{ $purchaseReport['factory_ship_date'] }}</td>
                        <td class="text-right">${{ number_format((float)$purchaseReport['po_fob_value'], 2) }}</td>
                        <td class="text-right">${{ number_format((float)$purchaseReport['factory_fob_value'], 2) }}</td>
                        <td>{{ $purchaseReport['remarks'] }}</td>
                    </tr>
                @endforeach
            @endforeach
            <tr style="background-color: gainsboro">
                @if(request('type')==='images')
                    <td class="text-right" style="font-weight: bold" colspan="7">Total</td>
                @else
                    <td class="text-right" style="font-weight: bold" colspan="6">Total</td>
                @endif
                <th>{{collect($seasonReport)->sum('po_qty')}}</th>
                <th></th>
                <td class="text-right" style="font-weight: bold">${{number_format(collect($seasonReport)->sum('po_fob'),2)}}</td>
                <td class="text-right" style="font-weight: bold">${{number_format(collect($seasonReport)->sum('factory_fob'),2)}}</td>
                <th colspan="3"></th>
                <td class="text-right" style="font-weight: bold">${{number_format(collect($seasonReport)->sum('po_fob_value'),2)}}</td>
                <td class="text-right" style="font-weight: bold">
                    ${{number_format(collect($seasonReport)->sum('factory_fob_value'),2)}}</td>
                <th></th>
            </tr>
            </tbody>
        </table>
        <br>
    @endforeach
@endif
