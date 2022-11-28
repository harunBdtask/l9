<table class="reportTable">
    <thead>
    <tr>
        <td colspan="11" class="text-right"><b>Total Receive Qty</b></td>
        <td><b>{{ round($reportData->sum('received_qty')) }}</b></td>
        <td colspan="6" class="text-right"><b>Total Batch Qty</b></td>
        <td><b>{{ round($reportData->sum('total_batch_qty')) }}</b></td>
        <td colspan="1" class="text-right"><b>Total Grey Stock</b></td>
        <td><b>{{ round($reportData->sum('received_qty')) - round($reportData->sum('total_batch_qty')) }}</b></td>
        <td colspan="3" class="text-right"><b>Total Balance</b></td>
        <td><b>{{ $reportData->sum('delivery_balance') }}</b></td>
        <td colspan="4" class="text-right"><b></b></td>
    </tr>
    <tr>
        <th>Date</th>
        <th>Party Name</th>
        <th>Order No</th>
        <th>Operation</th>
        <th>Fabric Description</th>
        <th>Fabric Type</th>
        <th>Color(Order)</th>
        <th>Fabric Dia</th>
        <th>Dia Type</th>
        <th>Gsm</th>
        <th>Received Qty</th>
        <th>Total Received Qty</th>
        <th>Batch Date</th>
        <th>Batch No</th>
        <th>Fabric Dia</th>
        <th>Dia Type</th>
        <th>GSM</th>
        <th>Color</th>
        <th>Batch Qty</th>
        <th>Grey Stock</th>
        <th>Delivery Date</th>
        <th>Grey Delivery</th>
        <th>Finish Delivery Qty</th>
        <th>Balance</th>
        <th>Rate</th>
        <th>Currency</th>
        <th>Total Value</th>
        <th>Shade(%)</th>
        <th>Remarks</th>
    </tr>
    </thead>
    <tbody>
    @foreach($reportData->groupBy(['order_no']) as $orderWiseData)
        @php
            $orderLoopCount = 0;
            $rowspan = $orderWiseData->sum('total_rows');
        @endphp
        @foreach($orderWiseData as $data)
            @php
                $batchLoopCount = 0;
                $batchRowSpan = $data['total_rows'];
            @endphp
            @foreach(collect($data['batch_details'])->sortByDesc('total_rows')->values() as $batchDetail)
                @php
                    $deliveryLoopCount = 0;
                    $deliveryRowSpan = $batchDetail['delivery_count'];
                @endphp
                @foreach(collect($batchDetail['delivery_details'])->sortByDesc('delivery_count')->values() as $deliverDetail)
                    <tr>
                        @if($orderLoopCount == 0)
                            <td rowspan="{{ $rowspan }}">{{ $data['date'] }}</td>
                            <td rowspan="{{ $rowspan }}">{{ $data['party_name'] }}</td>
                        @endif
                        @if($orderLoopCount == 0)
                            <td rowspan="{{ $rowspan }}">{{ $data['order_no'] }}</td>
                        @endif
                        @if($batchLoopCount == 0)
                            <td rowspan="{{ $batchRowSpan }}">{{ $data['operation'] }}</td>
                            <td rowspan="{{ $batchRowSpan }}">{{ $data['fabric_description'] }}</td>
                            <td rowspan="{{ $batchRowSpan }}">{{ $data['fabric_type'] }}</td>
                            <td rowspan="{{ $batchRowSpan }}">{{ $data['color'] }}</td>
                            <td rowspan="{{ $batchRowSpan }}">{{ $data['fabric_dia'] }}</td>
                            <td rowspan="{{ $batchRowSpan }}">{{ $data['dia_type'] }}</td>
                            <td rowspan="{{ $batchRowSpan }}">{{ $data['gsm'] }}</td>
                            <td rowspan="{{ $batchRowSpan }}">{{ $data['received_qty'] }}</td>
                        @endif
                        @if($orderLoopCount == 0)
                            <td rowspan="{{ $rowspan }}">{{ $orderWiseData->sum('received_qty') }}</td>
                        @endif

                        @if($deliveryLoopCount == 0)
                            <td rowspan="{{ $deliveryRowSpan }}">{{ $batchDetail['batch_date'] }}</td>
                            <td rowspan="{{ $deliveryRowSpan }}">{{ $batchDetail['batch_no'] }}</td>
                            <td rowspan="{{ $deliveryRowSpan }}">{{ $batchDetail['fabric_dia'] }}</td>
                            <td rowspan="{{ $deliveryRowSpan }}">{{ $batchDetail['dia_type'] }}</td>
                            <td rowspan="{{ $deliveryRowSpan }}">{{ $batchDetail['gsm'] }}</td>
                            <td rowspan="{{ $deliveryRowSpan }}">{{ $batchDetail['color'] }}</td>
                            <td rowspan="{{ $deliveryRowSpan }}">{{ $batchDetail['batch_qty'] }}</td>
                        @endif
                        @if($batchLoopCount == 0)
                            <td rowspan="{{ $batchRowSpan }}">{{ $data['grey_stock_qty'] }}</td>
                        @endif
                        <td>{{ $deliverDetail['delivery_date'] }}</td>
                        <td>{{ $deliverDetail['grey_delivery'] }}</td>
                        <td>{{ $deliverDetail['finish_delivery_qty'] }}</td>
                        @if($deliveryLoopCount == 0)
                            <td rowspan="{{ $deliveryRowSpan }}">{{ $batchDetail['delivery_balance'] }}</td>
                        @endif
                        <td>{{ $deliverDetail['rate'] }}</td>
                        <td>{{ $deliverDetail['currency'] }}</td>
                        <td></td>
                        <td>{{ $deliverDetail['shade'] }}</td>
                        <td>{{ $deliverDetail['remarks'] }}</td>
                    </tr>
                    @php
                        $batchLoopCount++;
                        $orderLoopCount++;
                        $deliveryLoopCount++;
                    @endphp
                @endforeach
            @endforeach
        @endforeach
        <tr>
            <td colspan="18">TOTAL</td>
            <td>{{ $orderWiseData->sum('total_batch_qty') }}</td>
            <td colspan="10"></td>
        </tr>
    @endforeach


    {{--    @foreach(collect($reportData)->groupBy('date') as $key => $dateWiseData)--}}
    {{--        @php--}}
    {{--            $dateIndex = 0;--}}
    {{--            $dateWiseCount = $dateWiseData->count();--}}
    {{--        @endphp--}}
    {{--        @foreach($dateWiseData->groupBy('party_name') as $partyWiseData)--}}
    {{--            @php--}}
    {{--                $partyIndex = 0;--}}
    {{--                $partyWiseCount = $partyWiseData->count();--}}
    {{--            @endphp--}}
    {{--            @foreach($partyWiseData->groupBy('order_no') as $orderWiseData)--}}
    {{--                @php--}}
    {{--                    $orderIndex = 0;--}}
    {{--                    $orderWiseCount = $orderWiseData->count();--}}
    {{--                    $orderWiseReceiveQty = $orderWiseData->unique('id')->sum('total_receive_qty');--}}
    {{--                @endphp--}}
    {{--                @foreach($orderWiseData->groupBy('operation') as $operationWiseData)--}}
    {{--                    @php--}}
    {{--                        $operationIndex = 0;--}}
    {{--                        $operationWiseCount = $operationWiseData->count();--}}
    {{--                    @endphp--}}
    {{--                    @foreach($operationWiseData->groupBy('fabric_description') as $fabricDescriptionWiseData)--}}
    {{--                        @php--}}
    {{--                            $fabricDescriptionIndex = 0;--}}
    {{--                            $fabricDescriptionWiseCount = $fabricDescriptionWiseData->count();--}}
    {{--                            $totalBatchQty = $fabricDescriptionWiseData->unique('batch_no')->sum('batch_qty');--}}
    {{--                        @endphp--}}
    {{--                        @foreach($fabricDescriptionWiseData->groupBy('batch_no') as $batchNOWiseData)--}}
    {{--                            @php--}}
    {{--                                $batchNOWiseIndex = 0;--}}
    {{--                                $batchNOWiseCount = $batchNOWiseData->count();--}}
    {{--                                $totalDeliveryQty = $batchNOWiseData->sum('grey_delivery');--}}
    {{--                            @endphp--}}
    {{--                            @foreach($batchNOWiseData as $order)--}}
    {{--                                @php--}}
    {{--                                    $greyStock = $order['total_receive_qty'] - $totalBatchQty;--}}
    {{--                                    $balance = $order['batch_qty'] - $totalDeliveryQty;--}}
    {{--                                @endphp--}}
    {{--                                <tr>--}}
    {{--                                    @if($dateIndex++ === 0)--}}
    {{--                                        <td rowspan="{{ $dateWiseCount }}">{{ $order['date'] }}</td>--}}
    {{--                                    @endif--}}
    {{--                                    @if($partyIndex++ === 0)--}}
    {{--                                        <td rowspan="{{ $partyWiseCount }}">{{ $order['party_name'] }}</td>--}}
    {{--                                    @endif--}}
    {{--                                    @if($orderIndex === 0)--}}
    {{--                                        <td rowspan="{{ $orderWiseCount }}">{{ $order['order_no'] }}</td>--}}
    {{--                                    @endif--}}
    {{--                                    @if($operationIndex++ === 0)--}}
    {{--                                        <td rowspan="{{ $operationWiseCount }}">{{ $order['operation'] }}</td>--}}
    {{--                                    @endif--}}
    {{--                                    @if($fabricDescriptionIndex === 0)--}}
    {{--                                        <td rowspan="{{ $fabricDescriptionWiseCount }}">{{ $order['fabric_description'] }}</td>--}}
    {{--                                        <td rowspan="{{ $fabricDescriptionWiseCount }}">{{ $order['fabric_type'] }}</td>--}}
    {{--                                        <td rowspan="{{ $fabricDescriptionWiseCount }}">{{ $order['color'] }}</td>--}}
    {{--                                        <td rowspan="{{ $fabricDescriptionWiseCount }}">{{ $order['fabric_dia'] }}</td>--}}
    {{--                                        <td rowspan="{{ $fabricDescriptionWiseCount }}">{{ $order['dia_type'] }}</td>--}}
    {{--                                        <td rowspan="{{ $fabricDescriptionWiseCount }}">{{ $order['gsm'] }}</td>--}}
    {{--                                        <td rowspan="{{ $fabricDescriptionWiseCount }}">{{ $order['total_receive_qty'] }}</td>--}}
    {{--                                    @endif--}}
    {{--                                    @if($orderIndex++ === 0)--}}
    {{--                                        <td rowspan="{{ $orderWiseCount }}">{{ $orderWiseReceiveQty }}</td>--}}
    {{--                                    @endif--}}
    {{--                                    @if($batchNOWiseIndex === 0)--}}
    {{--                                        <td rowspan="{{ $batchNOWiseCount }}">{{ $order['batch_date'] }}</td>--}}
    {{--                                        <td rowspan="{{ $batchNOWiseCount }}">{{ $order['batch_no'] }}</td>--}}
    {{--                                        <td rowspan="{{ $batchNOWiseCount }}">{{ $order['batch_fabric_dia'] }}</td>--}}
    {{--                                        <td rowspan="{{ $batchNOWiseCount }}">{{ $order['batch_dia_type'] }}</td>--}}
    {{--                                        <td rowspan="{{ $batchNOWiseCount }}">{{ $order['batch_gsm'] }}</td>--}}
    {{--                                        <td rowspan="{{ $batchNOWiseCount }}">{{ $order['batch_color'] }}</td>--}}
    {{--                                        <td rowspan="{{ $batchNOWiseCount }}">{{ $order['batch_qty'] }}</td>--}}
    {{--                                    @endif--}}
    {{--                                    @if($fabricDescriptionIndex++ === 0)--}}
    {{--                                        <td rowspan="{{ $fabricDescriptionWiseCount }}">{{ $greyStock }}</td>--}}
    {{--                                    @endif--}}
    {{--                                    <td>{{ $order['delivery_date'] }}</td>--}}
    {{--                                    <td>{{ $order['grey_delivery'] }}</td>--}}
    {{--                                    <td>{{ $order['finish_delivery_qty'] }}</td>--}}
    {{--                                    @if($batchNOWiseIndex++ === 0)--}}
    {{--                                        <td rowspan="{{ $batchNOWiseCount }}">{{ $balance }}</td>--}}
    {{--                                    @endif--}}
    {{--                                    <td>{{ $order['rate'] }}</td>--}}
    {{--                                    <td>{{ $order['currency'] }}</td>--}}
    {{--                                    <td>{{ $order['total_value'] }}</td>--}}
    {{--                                    <td>{{ $order['shade'] }}</td>--}}
    {{--                                    <td>{{ $order['remarks'] }}</td>--}}
    {{--                                </tr>--}}
    {{--                                --}}{{--                                @if($dateIndex++ === 0 && $partyIndex++ === 0 && $orderIndex++ === 0--}}
    {{--                                --}}{{--                                    && $operationIndex++ === 0 && $fabricDescriptionIndex++ === 0 && $batchNOWiseIndex++ === 0)--}}
    {{--                                --}}{{--                                    <tr>--}}
    {{--                                --}}{{--                                        <td rowspan="{{ $dateWiseCount }}">{{ $order['date'] }}</td>--}}
    {{--                                --}}{{--                                        <td rowspan="{{ $partyWiseCount }}">{{ $order['party_name'] }}</td>--}}
    {{--                                --}}{{--                                        <td rowspan="{{ $orderWiseCount }}">{{ $order['order_no'] }}</td>--}}
    {{--                                --}}{{--                                        <td rowspan="{{ $operationWiseCount }}">{{ $order['operation'] }}</td>--}}
    {{--                                --}}{{--                                        <td rowspan="{{ $fabricDescriptionWiseCount }}">{{ $order['fabric_description'] }}</td>--}}
    {{--                                --}}{{--                                        <td rowspan="{{ $fabricDescriptionWiseCount }}">{{ $order['fabric_type'] }}</td>--}}
    {{--                                --}}{{--                                        <td rowspan="{{ $fabricDescriptionWiseCount }}">{{ $order['color'] }}</td>--}}
    {{--                                --}}{{--                                        <td rowspan="{{ $fabricDescriptionWiseCount }}">{{ $order['fabric_dia'] }}</td>--}}
    {{--                                --}}{{--                                        <td rowspan="{{ $fabricDescriptionWiseCount }}">{{ $order['dia_type'] }}</td>--}}
    {{--                                --}}{{--                                        <td rowspan="{{ $fabricDescriptionWiseCount }}">{{ $order['gsm'] }}</td>--}}
    {{--                                --}}{{--                                        <td rowspan="{{ $fabricDescriptionWiseCount }}">{{ $order['total_receive_qty'] }}</td>--}}
    {{--                                --}}{{--                                        <td rowspan="{{ $orderWiseCount }}">{{ $orderWiseReceiveQty }}</td>--}}
    {{--                                --}}{{--                                        <td rowspan="{{ $batchNOWiseCount }}">{{ $order['batch_date'] }}</td>--}}
    {{--                                --}}{{--                                        <td rowspan="{{ $batchNOWiseCount }}">{{ $order['batch_no'] }}</td>--}}
    {{--                                --}}{{--                                        <td rowspan="{{ $batchNOWiseCount }}">{{ $order['batch_fabric_dia'] }}</td>--}}
    {{--                                --}}{{--                                        <td rowspan="{{ $batchNOWiseCount }}">{{ $order['batch_dia_type'] }}</td>--}}
    {{--                                --}}{{--                                        <td rowspan="{{ $batchNOWiseCount }}">{{ $order['batch_gsm'] }}</td>--}}
    {{--                                --}}{{--                                        <td rowspan="{{ $batchNOWiseCount }}">{{ $order['batch_color'] }}</td>--}}
    {{--                                --}}{{--                                        <td rowspan="{{ $batchNOWiseCount }}">{{ $order['batch_qty'] }}</td>--}}
    {{--                                --}}{{--                                        <td rowspan="{{ $fabricDescriptionWiseCount }}">{{ $greyStock }}</td>--}}
    {{--                                --}}{{--                                        <td>{{ $order['delivery_date'] }}</td>--}}
    {{--                                --}}{{--                                        <td>{{ $order['grey_delivery'] }}</td>--}}
    {{--                                --}}{{--                                        <td>{{ $order['finish_delivery_qty'] }}</td>--}}
    {{--                                --}}{{--                                        <td rowspan="{{ $batchNOWiseCount }}">{{ $balance }}</td>--}}
    {{--                                --}}{{--                                        <td>{{ $order['rate'] }}</td>--}}
    {{--                                --}}{{--                                        <td>{{ $order['currency'] }}</td>--}}
    {{--                                --}}{{--                                        <td>{{ $order['total_value'] }}</td>--}}
    {{--                                --}}{{--                                        <td>{{ $order['shade'] }}</td>--}}
    {{--                                --}}{{--                                        <td>{{ $order['remarks'] }}</td>--}}
    {{--                                --}}{{--                                    </tr>--}}
    {{--                                --}}{{--                                @else--}}
    {{--                                --}}{{--                                    @if($partyIndex++ === 0 && $orderIndex++ === 0 && $operationIndex++ === 0--}}
    {{--                                --}}{{--                                        && $fabricDescriptionIndex++ === 0 && $batchNOWiseIndex++ === 0)--}}

    {{--                                --}}{{--                                        <tr>--}}
    {{--                                --}}{{--                                            <td rowspan="{{ $partyWiseCount }}">{{ $order['party_name'] }}</td>--}}
    {{--                                --}}{{--                                            <td rowspan="{{ $orderWiseCount }}">{{ $order['order_no'] }}</td>--}}
    {{--                                --}}{{--                                            <td rowspan="{{ $operationWiseCount }}">{{ $order['operation'] }}</td>--}}
    {{--                                --}}{{--                                            <td rowspan="{{ $fabricDescriptionWiseCount }}">{{ $order['fabric_description'] }}</td>--}}
    {{--                                --}}{{--                                            <td rowspan="{{ $fabricDescriptionWiseCount }}">{{ $order['fabric_type'] }}</td>--}}
    {{--                                --}}{{--                                            <td rowspan="{{ $fabricDescriptionWiseCount }}">{{ $order['color'] }}</td>--}}
    {{--                                --}}{{--                                            <td rowspan="{{ $fabricDescriptionWiseCount }}">{{ $order['fabric_dia'] }}</td>--}}
    {{--                                --}}{{--                                            <td rowspan="{{ $fabricDescriptionWiseCount }}">{{ $order['dia_type'] }}</td>--}}
    {{--                                --}}{{--                                            <td rowspan="{{ $fabricDescriptionWiseCount }}">{{ $order['gsm'] }}</td>--}}
    {{--                                --}}{{--                                            <td rowspan="{{ $fabricDescriptionWiseCount }}">{{ $order['total_receive_qty'] }}</td>--}}
    {{--                                --}}{{--                                            <td rowspan="{{ $orderWiseCount }}">{{ $orderWiseReceiveQty }}</td>--}}
    {{--                                --}}{{--                                            <td rowspan="{{ $batchNOWiseCount }}">{{ $order['batch_date'] }}</td>--}}
    {{--                                --}}{{--                                            <td rowspan="{{ $batchNOWiseCount }}">{{ $order['batch_no'] }}</td>--}}
    {{--                                --}}{{--                                            <td rowspan="{{ $batchNOWiseCount }}">{{ $order['batch_fabric_dia'] }}</td>--}}
    {{--                                --}}{{--                                            <td rowspan="{{ $batchNOWiseCount }}">{{ $order['batch_dia_type'] }}</td>--}}
    {{--                                --}}{{--                                            <td rowspan="{{ $batchNOWiseCount }}">{{ $order['batch_gsm'] }}</td>--}}
    {{--                                --}}{{--                                            <td rowspan="{{ $batchNOWiseCount }}">{{ $order['batch_color'] }}</td>--}}
    {{--                                --}}{{--                                            <td rowspan="{{ $batchNOWiseCount }}">{{ $order['batch_qty'] }}</td>--}}
    {{--                                --}}{{--                                            <td rowspan="{{ $fabricDescriptionWiseCount }}">{{ $greyStock }}</td>--}}
    {{--                                --}}{{--                                            <td>{{ $order['delivery_date'] }}</td>--}}
    {{--                                --}}{{--                                            <td>{{ $order['grey_delivery'] }}</td>--}}
    {{--                                --}}{{--                                            <td>{{ $order['finish_delivery_qty'] }}</td>--}}
    {{--                                --}}{{--                                            <td rowspan="{{ $batchNOWiseCount }}">{{ $balance }}</td>--}}
    {{--                                --}}{{--                                            <td>{{ $order['rate'] }}</td>--}}
    {{--                                --}}{{--                                            <td>{{ $order['currency'] }}</td>--}}
    {{--                                --}}{{--                                            <td>{{ $order['total_value'] }}</td>--}}
    {{--                                --}}{{--                                            <td>{{ $order['shade'] }}</td>--}}
    {{--                                --}}{{--                                            <td>{{ $order['remarks'] }}</td>--}}
    {{--                                --}}{{--                                        </tr>--}}
    {{--                                --}}{{--                                    @else--}}
    {{--                                --}}{{--                                        @if($orderIndex++ === 0 && $operationIndex++ === 0 && $fabricDescriptionIndex++ === 0 && $batchNOWiseIndex++ === 0)--}}
    {{--                                --}}{{--                                            <tr>--}}
    {{--                                --}}{{--                                                <td rowspan="{{ $orderWiseCount }}">{{ $order['order_no'] }}</td>--}}
    {{--                                --}}{{--                                                <td rowspan="{{ $operationWiseCount }}">{{ $order['operation'] }}</td>--}}
    {{--                                --}}{{--                                                <td rowspan="{{ $fabricDescriptionWiseCount }}">{{ $order['fabric_description'] }}</td>--}}
    {{--                                --}}{{--                                                <td rowspan="{{ $fabricDescriptionWiseCount }}">{{ $order['fabric_type'] }}</td>--}}
    {{--                                --}}{{--                                                <td rowspan="{{ $fabricDescriptionWiseCount }}">{{ $order['color'] }}</td>--}}
    {{--                                --}}{{--                                                <td rowspan="{{ $fabricDescriptionWiseCount }}">{{ $order['fabric_dia'] }}</td>--}}
    {{--                                --}}{{--                                                <td rowspan="{{ $fabricDescriptionWiseCount }}">{{ $order['dia_type'] }}</td>--}}
    {{--                                --}}{{--                                                <td rowspan="{{ $fabricDescriptionWiseCount }}">{{ $order['gsm'] }}</td>--}}
    {{--                                --}}{{--                                                <td rowspan="{{ $fabricDescriptionWiseCount }}">{{ $order['total_receive_qty'] }}</td>--}}
    {{--                                --}}{{--                                                <td rowspan="{{ $orderWiseCount }}">{{ $orderWiseReceiveQty }}</td>--}}
    {{--                                --}}{{--                                                <td rowspan="{{ $batchNOWiseCount }}">{{ $order['batch_date'] }}</td>--}}
    {{--                                --}}{{--                                                <td rowspan="{{ $batchNOWiseCount }}">{{ $order['batch_no'] }}</td>--}}
    {{--                                --}}{{--                                                <td rowspan="{{ $batchNOWiseCount }}">{{ $order['batch_fabric_dia'] }}</td>--}}
    {{--                                --}}{{--                                                <td rowspan="{{ $batchNOWiseCount }}">{{ $order['batch_dia_type'] }}</td>--}}
    {{--                                --}}{{--                                                <td rowspan="{{ $batchNOWiseCount }}">{{ $order['batch_gsm'] }}</td>--}}
    {{--                                --}}{{--                                                <td rowspan="{{ $batchNOWiseCount }}">{{ $order['batch_color'] }}</td>--}}
    {{--                                --}}{{--                                                <td rowspan="{{ $batchNOWiseCount }}">{{ $order['batch_qty'] }}</td>--}}
    {{--                                --}}{{--                                                <td rowspan="{{ $fabricDescriptionWiseCount }}">{{ $greyStock }}</td>--}}
    {{--                                --}}{{--                                                <td>{{ $order['delivery_date'] }}</td>--}}
    {{--                                --}}{{--                                                <td>{{ $order['grey_delivery'] }}</td>--}}
    {{--                                --}}{{--                                                <td>{{ $order['finish_delivery_qty'] }}</td>--}}
    {{--                                --}}{{--                                                <td rowspan="{{ $batchNOWiseCount }}">{{ $balance }}</td>--}}
    {{--                                --}}{{--                                                <td>{{ $order['rate'] }}</td>--}}
    {{--                                --}}{{--                                                <td>{{ $order['currency'] }}</td>--}}
    {{--                                --}}{{--                                                <td>{{ $order['total_value'] }}</td>--}}
    {{--                                --}}{{--                                                <td>{{ $order['shade'] }}</td>--}}
    {{--                                --}}{{--                                                <td>{{ $order['remarks'] }}</td>--}}
    {{--                                --}}{{--                                            </tr>--}}
    {{--                                --}}{{--                                        @else--}}
    {{--                                --}}{{--                                            @if($operationIndex++ === 0 && $fabricDescriptionIndex++ === 0 && $batchNOWiseIndex++ === 0)--}}
    {{--                                --}}{{--                                                <tr>--}}
    {{--                                --}}{{--                                                    <td rowspan="{{ $operationWiseCount }}">{{ $order['operation'] }}</td>--}}
    {{--                                --}}{{--                                                    <td rowspan="{{ $fabricDescriptionWiseCount }}">{{ $order['fabric_description'] }}</td>--}}
    {{--                                --}}{{--                                                    <td rowspan="{{ $fabricDescriptionWiseCount }}">{{ $order['fabric_type'] }}</td>--}}
    {{--                                --}}{{--                                                    <td rowspan="{{ $fabricDescriptionWiseCount }}">{{ $order['color'] }}</td>--}}
    {{--                                --}}{{--                                                    <td rowspan="{{ $fabricDescriptionWiseCount }}">{{ $order['fabric_dia'] }}</td>--}}
    {{--                                --}}{{--                                                    <td rowspan="{{ $fabricDescriptionWiseCount }}">{{ $order['dia_type'] }}</td>--}}
    {{--                                --}}{{--                                                    <td rowspan="{{ $fabricDescriptionWiseCount }}">{{ $order['gsm'] }}</td>--}}
    {{--                                --}}{{--                                                    <td rowspan="{{ $fabricDescriptionWiseCount }}">{{ $order['total_receive_qty'] }}</td>--}}
    {{--                                --}}{{--                                                    <td rowspan="{{ $batchNOWiseCount }}">{{ $order['batch_date'] }}</td>--}}
    {{--                                --}}{{--                                                    <td rowspan="{{ $batchNOWiseCount }}">{{ $order['batch_no'] }}</td>--}}
    {{--                                --}}{{--                                                    <td rowspan="{{ $batchNOWiseCount }}">{{ $order['batch_fabric_dia'] }}</td>--}}
    {{--                                --}}{{--                                                    <td rowspan="{{ $batchNOWiseCount }}">{{ $order['batch_dia_type'] }}</td>--}}
    {{--                                --}}{{--                                                    <td rowspan="{{ $batchNOWiseCount }}">{{ $order['batch_gsm'] }}</td>--}}
    {{--                                --}}{{--                                                    <td rowspan="{{ $batchNOWiseCount }}">{{ $order['batch_color'] }}</td>--}}
    {{--                                --}}{{--                                                    <td rowspan="{{ $batchNOWiseCount }}">{{ $order['batch_qty'] }}</td>--}}
    {{--                                --}}{{--                                                    <td rowspan="{{ $fabricDescriptionWiseCount }}">{{ $greyStock }}</td>--}}
    {{--                                --}}{{--                                                    <td>{{ $order['delivery_date'] }}</td>--}}
    {{--                                --}}{{--                                                    <td>{{ $order['grey_delivery'] }}</td>--}}
    {{--                                --}}{{--                                                    <td>{{ $order['finish_delivery_qty'] }}</td>--}}
    {{--                                --}}{{--                                                    <td rowspan="{{ $batchNOWiseCount }}">{{ $balance }}</td>--}}
    {{--                                --}}{{--                                                    <td>{{ $order['rate'] }}</td>--}}
    {{--                                --}}{{--                                                    <td>{{ $order['currency'] }}</td>--}}
    {{--                                --}}{{--                                                    <td>{{ $order['total_value'] }}</td>--}}
    {{--                                --}}{{--                                                    <td>{{ $order['shade'] }}</td>--}}
    {{--                                --}}{{--                                                    <td>{{ $order['remarks'] }}</td>--}}
    {{--                                --}}{{--                                                </tr>--}}
    {{--                                --}}{{--                                            @else--}}
    {{--                                --}}{{--                                                @if($fabricDescriptionIndex++ === 0 && $batchNOWiseIndex++ === 0)--}}
    {{--                                --}}{{--                                                    <tr>--}}
    {{--                                --}}{{--                                                        <td rowspan="{{ $fabricDescriptionWiseCount }}">{{ $order['fabric_description'] }}</td>--}}
    {{--                                --}}{{--                                                        <td rowspan="{{ $fabricDescriptionWiseCount }}">{{ $order['fabric_type'] }}</td>--}}
    {{--                                --}}{{--                                                        <td rowspan="{{ $fabricDescriptionWiseCount }}">{{ $order['color'] }}</td>--}}
    {{--                                --}}{{--                                                        <td rowspan="{{ $fabricDescriptionWiseCount }}">{{ $order['fabric_dia'] }}</td>--}}
    {{--                                --}}{{--                                                        <td rowspan="{{ $fabricDescriptionWiseCount }}">{{ $order['dia_type'] }}</td>--}}
    {{--                                --}}{{--                                                        <td rowspan="{{ $fabricDescriptionWiseCount }}">{{ $order['gsm'] }}</td>--}}
    {{--                                --}}{{--                                                        <td rowspan="{{ $fabricDescriptionWiseCount }}">{{ $order['total_receive_qty'] }}</td>--}}
    {{--                                --}}{{--                                                        <td rowspan="{{ $batchNOWiseCount }}">{{ $order['batch_date'] }}</td>--}}
    {{--                                --}}{{--                                                        <td rowspan="{{ $batchNOWiseCount }}">{{ $order['batch_no'] }}</td>--}}
    {{--                                --}}{{--                                                        <td rowspan="{{ $batchNOWiseCount }}">{{ $order['batch_fabric_dia'] }}</td>--}}
    {{--                                --}}{{--                                                        <td rowspan="{{ $batchNOWiseCount }}">{{ $order['batch_dia_type'] }}</td>--}}
    {{--                                --}}{{--                                                        <td rowspan="{{ $batchNOWiseCount }}">{{ $order['batch_gsm'] }}</td>--}}
    {{--                                --}}{{--                                                        <td rowspan="{{ $batchNOWiseCount }}">{{ $order['batch_color'] }}</td>--}}
    {{--                                --}}{{--                                                        <td rowspan="{{ $batchNOWiseCount }}">{{ $order['batch_qty'] }}</td>--}}
    {{--                                --}}{{--                                                        <td rowspan="{{ $fabricDescriptionWiseCount }}">{{ $greyStock }}</td>--}}
    {{--                                --}}{{--                                                        <td>{{ $order['delivery_date'] }}</td>--}}
    {{--                                --}}{{--                                                        <td>{{ $order['grey_delivery'] }}</td>--}}
    {{--                                --}}{{--                                                        <td>{{ $order['finish_delivery_qty'] }}</td>--}}
    {{--                                --}}{{--                                                        <td rowspan="{{ $batchNOWiseCount }}">{{ $balance }}</td>--}}
    {{--                                --}}{{--                                                        <td>{{ $order['rate'] }}</td>--}}
    {{--                                --}}{{--                                                        <td>{{ $order['currency'] }}</td>--}}
    {{--                                --}}{{--                                                        <td>{{ $order['total_value'] }}</td>--}}
    {{--                                --}}{{--                                                        <td>{{ $order['shade'] }}</td>--}}
    {{--                                --}}{{--                                                        <td>{{ $order['remarks'] }}</td>--}}
    {{--                                --}}{{--                                                    </tr>--}}
    {{--                                --}}{{--                                                @else--}}
    {{--                                --}}{{--                                                    @if($batchNOWiseIndex++ === 0)--}}
    {{--                                --}}{{--                                                        <tr>--}}
    {{--                                --}}{{--                                                            <td rowspan="{{ $batchNOWiseCount }}">{{ $order['batch_date'] }}</td>--}}
    {{--                                --}}{{--                                                            <td rowspan="{{ $batchNOWiseCount }}">{{ $order['batch_no'] }}</td>--}}
    {{--                                --}}{{--                                                            <td rowspan="{{ $batchNOWiseCount }}">{{ $order['batch_fabric_dia'] }}</td>--}}
    {{--                                --}}{{--                                                            <td rowspan="{{ $batchNOWiseCount }}">{{ $order['batch_dia_type'] }}</td>--}}
    {{--                                --}}{{--                                                            <td rowspan="{{ $batchNOWiseCount }}">{{ $order['batch_gsm'] }}</td>--}}
    {{--                                --}}{{--                                                            <td rowspan="{{ $batchNOWiseCount }}">{{ $order['batch_color'] }}</td>--}}
    {{--                                --}}{{--                                                            <td rowspan="{{ $batchNOWiseCount }}">{{ $order['batch_qty'] }}</td>--}}
    {{--                                --}}{{--                                                            <td>{{ $order['delivery_date'] }}</td>--}}
    {{--                                --}}{{--                                                            <td>{{ $order['grey_delivery'] }}</td>--}}
    {{--                                --}}{{--                                                            <td>{{ $order['finish_delivery_qty'] }}</td>--}}
    {{--                                --}}{{--                                                            <td rowspan="{{ $batchNOWiseCount }}">{{ $balance }}</td>--}}
    {{--                                --}}{{--                                                            <td>{{ $order['rate'] }}</td>--}}
    {{--                                --}}{{--                                                            <td>{{ $order['currency'] }}</td>--}}
    {{--                                --}}{{--                                                            <td>{{ $order['total_value'] }}</td>--}}
    {{--                                --}}{{--                                                            <td>{{ $order['shade'] }}</td>--}}
    {{--                                --}}{{--                                                            <td>{{ $order['remarks'] }}</td>--}}
    {{--                                --}}{{--                                                        </tr>--}}
    {{--                                --}}{{--                                                    @else--}}
    {{--                                --}}{{--                                                        <tr>--}}
    {{--                                --}}{{--                                                            <td>{{ $order['delivery_date'] }}</td>--}}
    {{--                                --}}{{--                                                            <td>{{ $order['grey_delivery'] }}</td>--}}
    {{--                                --}}{{--                                                            <td>{{ $order['finish_delivery_qty'] }}</td>--}}
    {{--                                --}}{{--                                                            <td>{{ $order['rate'] }}</td>--}}
    {{--                                --}}{{--                                                            <td>{{ $order['currency'] }}</td>--}}
    {{--                                --}}{{--                                                            <td>{{ $order['total_value'] }}</td>--}}
    {{--                                --}}{{--                                                            <td>{{ $order['shade'] }}</td>--}}
    {{--                                --}}{{--                                                            <td>{{ $order['remarks'] }}</td>--}}
    {{--                                --}}{{--                                                        </tr>--}}
    {{--                                --}}{{--                                                    @endif--}}
    {{--                                --}}{{--                                                @endif--}}
    {{--                                --}}{{--                                            @endif--}}
    {{--                                --}}{{--                                        @endif--}}
    {{--                                --}}{{--                                    @endif--}}
    {{--                                --}}{{--                                @endif--}}
    {{--                            @endforeach--}}
    {{--                        @endforeach--}}
    {{--                    @endforeach--}}
    {{--                @endforeach--}}
    {{--            @endforeach--}}
    {{--        @endforeach--}}
    {{--    @endforeach--}}
    </tbody>
</table>
