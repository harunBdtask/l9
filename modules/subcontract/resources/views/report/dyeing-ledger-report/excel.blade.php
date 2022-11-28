<table class="reportTable">
    <thead>
    <tr>
        <th>Date</th>
        <th>Party Name</th>
        <th>Order No</th>
        <th>Challan No</th>
        <th>Operation</th>
        <th>Fabric Description</th>
        <th>Fabric Type</th>
        <th>Color(Order)</th>
        <th>Fabric Dia</th>
        <th>Dia Type</th>
        <th>Gsm</th>
        <th>Received Qty</th>
    </tr>
    </thead>
    <tbody>
    @foreach(collect($reportData)->groupBy('date') as $key => $dateWiseData)
        @php
            $dateIndex = 0;
            $dateWiseCount = $dateWiseData->count();
        @endphp
        @foreach($dateWiseData->groupBy('party_name') as $partyWiseData)
            @php
                $partyIndex = 0;
                $partyWiseCount = $partyWiseData->count();
            @endphp
            @foreach($partyWiseData->groupBy('order_no') as $orderWiseData)
                @php
                    $orderIndex = 0;
                    $orderWiseCount = $orderWiseData->count();
                @endphp
                @foreach($orderWiseData->groupBy('challan_no') as $challanNoWiseData)
                    @php
                        $challanNoIndex = 0;
                        $challanNoWiseCount = $challanNoWiseData->count();
                    @endphp
                    @foreach($challanNoWiseData as $order)
                        @if($dateIndex++ === 0 && $partyIndex++ === 0 && $orderIndex++ === 0 && $challanNoIndex++ === 0)
                            <tr>
                                <td rowspan="{{ $dateWiseCount }}">{{ $order['date'] }}</td>
                                <td rowspan="{{ $partyWiseCount }}">{{ $order['party_name'] }}</td>
                                <td rowspan="{{ $orderWiseCount }}">{{ $order['order_no'] }}</td>
                                <td rowspan="{{ $challanNoWiseCount }}">{{ $order['challan_no'] }}</td>
                                <td>{{ $order['operation'] }}</td>
                                <td>{{ $order['fabric_description'] }}</td>
                                <td>{{ $order['fabric_type'] }}</td>
                                <td>{{ $order['color'] }}</td>
                                <td>{{ $order['fabric_dia'] }}</td>
                                <td>{{ $order['dia_type'] }}</td>
                                <td>{{ $order['gsm'] }}</td>
                                <td>{{ $order['total_receive_qty'] }}</td>
                            </tr>
                        @else
                            @if($partyIndex++ === 0 && $orderIndex++ === 0 && $challanNoIndex++ === 0)

                                <tr>
                                    <td rowspan="{{ $partyWiseCount }}">{{ $order['party_name'] }}</td>
                                    <td rowspan="{{ $orderWiseCount }}">{{ $order['order_no'] }}</td>
                                    <td rowspan="{{ $challanNoWiseCount }}">{{ $order['challan_no'] }}</td>
                                    <td>{{ $order['operation'] }}</td>
                                    <td>{{ $order['fabric_description'] }}</td>
                                    <td>{{ $order['fabric_type'] }}</td>
                                    <td>{{ $order['color'] }}</td>
                                    <td>{{ $order['fabric_dia'] }}</td>
                                    <td>{{ $order['dia_type'] }}</td>
                                    <td>{{ $order['gsm'] }}</td>
                                    <td>{{ $order['total_receive_qty'] }}</td>
                                </tr>
                            @else
                                @if($orderIndex++ === 0 && $challanNoIndex++ === 0)
                                    <tr>
                                        <td rowspan="{{ $orderWiseCount }}">{{ $order['order_no'] }}</td>
                                        <td rowspan="{{ $challanNoWiseCount }}">{{ $order['challan_no'] }}</td>
                                        <td>{{ $order['operation'] }}</td>
                                        <td>{{ $order['fabric_description'] }}</td>
                                        <td>{{ $order['fabric_type'] }}</td>
                                        <td>{{ $order['color'] }}</td>
                                        <td>{{ $order['fabric_dia'] }}</td>
                                        <td>{{ $order['dia_type'] }}</td>
                                        <td>{{ $order['gsm'] }}</td>
                                        <td>{{ $order['total_receive_qty'] }}</td>
                                    </tr>
                                @else
                                    @if($challanNoIndex++ === 0)
                                        <tr>
                                            <td rowspan="{{ $challanNoWiseCount }}">{{ $order['challan_no'] }}</td>
                                            <td>{{ $order['operation'] }}</td>
                                            <td>{{ $order['fabric_description'] }}</td>
                                            <td>{{ $order['fabric_type'] }}</td>
                                            <td>{{ $order['color'] }}</td>
                                            <td>{{ $order['fabric_dia'] }}</td>
                                            <td>{{ $order['dia_type'] }}</td>
                                            <td>{{ $order['gsm'] }}</td>
                                            <td>{{ $order['total_receive_qty'] }}</td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td>{{ $order['operation'] }}</td>
                                            <td>{{ $order['fabric_description'] }}</td>
                                            <td>{{ $order['fabric_type'] }}</td>
                                            <td>{{ $order['color'] }}</td>
                                            <td>{{ $order['fabric_dia'] }}</td>
                                            <td>{{ $order['dia_type'] }}</td>
                                            <td>{{ $order['gsm'] }}</td>
                                            <td>{{ $order['total_receive_qty'] }}</td>
                                        </tr>
                                    @endif
                                @endif
                            @endif
                        @endif
                    @endforeach
                @endforeach
            @endforeach
        @endforeach
    @endforeach
    </tbody>
</table>
