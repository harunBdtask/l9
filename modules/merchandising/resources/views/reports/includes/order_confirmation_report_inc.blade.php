@if($order_info_data != null)
    @php
    $i=1;
    $sum = 0;
    @endphp
    @foreach($order_info_data->getCollection() as $key => $order_info)
        @if (count($order_info->orders) > 0)
            <tr>
                <td rowspan="{{count($order_info->orders)}}">{{ $i++ }}</td>
                <td rowspan="{{count($order_info->orders)}}">{{$order_info->master_order_no}}</td>
                <td rowspan="{{count($order_info->orders)}}">{!! $order_info->style->name ?? 'NA' !!}</td>
                <td rowspan="{{count($order_info->orders)}}">{!! $order_info->order_confirmation_date ? date('d/m/Y', strtotime($order_info->order_confirmation_date)) : date('d/m/Y', strtotime($order_info->created_at)) !!}</td>
                @foreach ($order_info->orders as $key2 => $orders)
                    @if ($key2 > 0) {
            <tr>
                @endif
                @php $sum += $orders->total_quantity  @endphp
                <td>{{$orders->order_no }}</td>
                <td>{{$orders->total_quantity  }}</td>
                <td>{{date('d/m/Y', strtotime($orders->shipment_date)) }}</td>
                <td>{{$orders->ods  }}</td>
            </tr>
            @endforeach
        @endif
    @endforeach
    <tr>
        <td colspan="5"><strong>Total Order Quantity :</strong> </td>
        <td colspan=""><strong>{{$sum}} </strong></td>
    </tr>
    @if($order_info_data != null && $order_info_data->total() > 15)
        <tr>
            <td colspan="8" align="center">{{ $order_info_data->appends(request()->except('page'))->links() }}</td>
        </tr>
    @endif
@else
    <tr>
        <td colspan="8" align="center">No data</td>
    </tr>
@endif