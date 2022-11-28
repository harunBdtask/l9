@foreach($reports as $key => $items)
    <div class="col-md-12" style="margin-bottom: 25px;">
        <table class="reportTable">
            <thead>
            <tr>
                <td colspan="17" style="text-align: left; padding: 5px; font-weight: bold;">
                    {{ $items[0]['yarn_count'] }} @if(!empty($items[0]['yarn_count'])),@endif
                    {{ $items[0]['yarn_composition'] }} @if(!empty($items[0]['yarn_composition'])),@endif
                    {{ $items[0]['yarn_type'] }} @if(!empty($items[0]['yarn_type'])),@endif
                    {{ $items->first()['lot'] }},
                    {{ $items->first()['supplier'] }}
                </td>
            </tr>
            <tr>
                <th rowspan="2">SL</th>
                <th rowspan="2">Trans Date</th>
                <th rowspan="2">Trans Ref No</th>
                <th rowspan="2">Trans Type</th>
                <th rowspan="2">Purpose</th>
                <th colspan="3">Receive</th>
                <th colspan="3">Issue</th>
                <th colspan="3">Transfer</th>
                <th colspan="3">Balance</th>
            </tr>
            <tr>
                <th>Quantity</th>
                <th>Rate</th>
                <th>Amount</th>
                <th>Quantity</th>
                <th>Rate</th>
                <th>Amount</th>
                <th>Quantity</th>
                <th>Rate</th>
                <th>Amount</th>
                <th>Quantity</th>
                <th>Rate</th>
                <th>Amount</th>
            </tr>
            </thead>
            <tbody>
            @foreach($items as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item['trans_date'] }}</td>
                    <td>{{ $item['receive_no'] }}</td>
                    <td>{{ $item['trans_type'] }}</td>
                    <td>{{ $item['purpose'] }}</td>
                    @if($item['trans_type'] == 'Receive' || $item['trans_type'] == 'Receive Return')
                        <td>{{ $item['quantity'] }}</td>
                        <td>{{ number_format($item['rate'], 2) }}</td>
                        <td>{{ number_format($item['amount'], 2, ".", "") }}</td>
                    @else
                        <td></td>
                        <td></td>
                        <td></td>
                    @endif

                    @if($item['trans_type'] == 'Yarn Issue' || $item['trans_type'] == 'Issue Return')
                        <td>{{ $item['quantity'] }}</td>
                        <td>{{ number_format($item['rate'], 2) }}</td>
                        <td>{{ number_format((double)$item['amount'], 2, ".", "") }}</td>
                    @else
                        <td></td>
                        <td></td>
                        <td></td>
                    @endif

                    @if($item['trans_type'] == 'Yarn Transfer')
                        <td>{{ $item['quantity'] }}</td>
                        <td>{{ number_format($item['rate'], 2) }}</td>
                        <td>{{ number_format($item['amount'], 2, ".", "") }}</td>
                    @else
                        <td></td>
                        <td></td>
                        <td></td>
                    @endif

                    <td>{{ $item['balance'] }}</td>
                    <td>{{ number_format($item['rate'], 2) }}</td>
                    <td>{{ number_format($item['balance_amount'], 2, ".", "") }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endforeach
