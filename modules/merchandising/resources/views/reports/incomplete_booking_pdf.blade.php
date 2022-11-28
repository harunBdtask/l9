<!DOCTYPE html>

<html>
<head>
    <title>Incomplete Booking</title>
    @include('merchandising::reports.downloads.includes.pdf_style')
    <style>
        table {
            border-collapse: collapse;
        }
    </style>
</head>

<body>
<main>
    <h4 align="center">Incomplete Booking</h4>
    @include('merchandising::reports.downloads.includes.pdf_header')
    @if(isset($orders_list) && $orders_list->count() > 0)
        <table class="reportTable reportTableCustom">
            <thead>
            <tr>
                <th>Order / Style No</th>
                <th>Booking No</th>
                <th>Buyer</th>
                <th>Dealing Merchant</th>
                <th>Team</th>
                <th>Order Qty</th>
                <th>Shipment Date</th>
                <th>Company</th>
                <th>Created Time</th>
            </tr>
            </thead>
            <tbody>
            @foreach($orders_list as $orders_data)
                <tr>
                    <td style="background: red;color: white;font-weight: bold">{{ $orders_data->getOriginal('order_style_no')}}</td>
                    <td style="background: red;color: white;font-weight: bold;padding: 3px">{{ $orders_data->booking_no ?? 'N/A'}}</td>
                    <td>{{isset($orders_data->buyer->name) ? $orders_data->buyer->name : 'N/A'}}</td>
                    <td>{{isset($orders_data->dealing_merchants->first_name) || isset($orders_data->dealing_merchants->last_name) ? $orders_data->dealing_merchants->first_name .' '.$orders_data->dealing_merchants->last_name : 'N/A'}}</td>
                    <td>{{ $orders_data->team->team_name ?? 'N/A'}}</td>
                    <td>{{$orders_data->total_quantity}}</td>
                    <td style="background: red;color: white;font-weight: bold">{{date('d M Y',strtotime($orders_data->order_shipment_date))}}</td>
                    <td style="background: #0F733B;color: #fff;font-weight: bold;letter-spacing: 1px">{{$orders_data->factory->factory_short_name}}</td>
                    <td>{{date('d M Y',strtotime($orders_data->created_at))}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
</main>
@include('merchandising::reports.downloads.includes.pdf_footer')
</body>
</html>
