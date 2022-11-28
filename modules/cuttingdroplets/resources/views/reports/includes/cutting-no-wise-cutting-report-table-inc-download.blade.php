<table class="reportTable" style="border: 1px solid black;border-collapse: collapse;">
    <thead>
        @if(isset($type) && $type == 'xls')
        <tr>
            <th colspan="4">
                {{ sessionFactoryName() }}
            </th>
        </tr>
        <tr>
            <th colspan="4">
                Cutting Wise Cutting Production Report
            </th>
        </tr>
        @endif
        <tr>
            <th colspan="4">
                Buyer: {{ $buyer }}, &nbsp;&nbsp;
                Style: {{ $style ?? '' }}, &nbsp;&nbsp;
                PO: {{ $po_no }}, &nbsp;&nbsp;
                Color: {{ $color }}, &nbsp;&nbsp;
                Cutting No: {{ $cutting_no }}
            </th>
        </tr>
        <tr style="text-align: center;">
            <th>Size Name</th>
            <th>Total Bundle</th>
            <th>Cutting Quantity</th>
            <th>Cutting Date</th>
        </tr>
    </thead>
    <tbody>
    @if(!empty($order_size_details))
        @php
            $total_buldle = 0;
            $total_qty = 0;
        @endphp
        @foreach($order_size_details as $report)
            @php
                $total_buldle += $report['count_bundle'];
                $total_qty += $report['size_cutting_qty'];
            @endphp
            <tr style="text-align: center;">
                <td>{{ $report['name'] }}</td>
                <td>{{$report['count_bundle'] }}</td>
                <td>{{$report['size_cutting_qty'] }}</td>
                <td>{{$report['cutting_date'] }}</td>
            </tr>
        @endforeach
        <tr style="font-weight: bold;text-align: center;">
            <td><b>Total</b></td>
            <td>{{$total_buldle}}</td>
            <td>{{$total_qty}}</td>
            <td></td>
        </tr>
    @else
        <tr class="tr-height">
            <td colspan="4" style="font-weight: bold; text-align: center;">Not found
            </td>
        </tr>
    @endif
    </tbody>
</table>