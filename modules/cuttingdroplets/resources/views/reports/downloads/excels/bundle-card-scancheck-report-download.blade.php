<table>
    <thead>
    <tr>
        <th colspan="16">
            Factory: {{ factoryName() }}
        </th>
    </tr>
     <tr>
        <th colspan="16">
            Buyer: {{ $buyer }} &nbsp; | &nbsp;
            Order/Style: {{ $style }} &nbsp; | &nbsp;
            PO: {{ $order_no }} &nbsp; | &nbsp;
            Color: {{ $color }} &nbsp; | &nbsp; 
            Cutting No: {{ $cutting_no }}
        </th>
    </tr>
    <tr>
        <th>SL</th> 
        <th>OP Barcode</th>
        <th>RP Barcode</th>
        <th>Cutting Scan</th>
        <th>Cutting Date</th>
        <th>Print Sent</th>
        <th>Print Sent Date</th>
        <th>Print Rcv.</th>
        <th>Print Rcv. Date</th>
        <th>Input/Tag</th>
        <th>Input/Tag Date</th>
        <th>Sewing</th>
        <th>Sewing Date</th>
        <th>Washing</th>
        <th>Wash. Date</th>
        <th>Qty</th>
    </tr>
    </thead>
    <tbody>
    @if(!empty($bundle_cards))
        @foreach($bundle_cards as $report)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $report['barcode'] }}</td>
                <td>1{{ $report['barcode'] }}</td>
                <td class="{{ $report['cutting'] }}">{{ $report['cutting'] }}</td>
                <td>{{ $report['cutting_date'] }}</td>
                <td class="{{ $report['print_sent'] }}">{{ $report['print_sent'] }}</td>
                <td>{{ $report['print_sent_datetime'] }}</td>
                <td class="{{ $report['print_received'] }}">{{ $report['print_received'] }}</td>
                <td>{{ $report['print_received_datetime'] }}</td>
                <td class="{{ $report['cutting_inventory'] }}">{{ $report['cutting_inventory'] }}</td>
                <td>{{ $report['cutting_inventory_datetime'] }}</td>
                <td class="{{ $report['sewingoutput'] }} ">{{ $report['sewingoutput'] }}</td>
                <td>{{ $report['sewingoutput_datetime'] }}</td>
                <td class="{{ $report['washing_sent'] }} ">{{ $report['washing_sent'] }}</td>
                <td>{{ $report['washing_sent_datetime'] }}</td>
                <td>{{ $report['quantity'] }} </td>
            </tr>
        @endforeach
    @endif
    </tbody>
</table>