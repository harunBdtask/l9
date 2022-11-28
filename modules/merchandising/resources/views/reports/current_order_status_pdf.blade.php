<!DOCTYPE html>

<html>
<head>
    <title>Order Current Status</title>
    @include('merchandising::reports.downloads.includes.pdf_style')
    <style>
        table {
            border-collapse: collapse;
        }
        .bg-yes {
            background: #3496130d;
            color: #00a65a;
            display: block;
            font-weight: bold;
        }

        .bg-no {
            background: #f9f2f4;
            color: #a94442;
            display: block;
            font-weight: bold;
        }
    </style>
</head>

<body>
@include('merchandising::download.include.report-header')
@include('merchandising::download.include.report-footer')
<main>
    <h4 align="center">Order Current Status</h4>
    @if(isset($orders_list) && $orders_list->count() > 0)
        <table class="reportTable reportTableCustom">
            <thead>
            <tr>
                <th>Buyer</th>
                <th>Booking No</th>
                <th>Style No</th>
                <th>Dealing Merchant</th>
                <th>No Of PO Created</th>
                <th>Is Budget Created</th>
                <th>Is Fabric Booked</th>
                <th>Knitting details Given</th>
                <th>Shipment Date</th>
                <th>Company</th>
            </tr>
            </thead>
            <tbody>
            @foreach($orders_list as $orders_data)
                <tr>
                    <td>{{$orders_data['buyer']}}</td>
                    <td>{{$orders_data['booking_no']}}</td>
                    <td>{{$orders_data['style_no']}}</td>
                    <td>{{$orders_data['dealing_merchant']}}</td>
                    <td>{{$orders_data['is_po_created']}}</td>
                    <td>{!! $orders_data['is_budget_created'] !!}</td>
                    <td>{!! $orders_data['is_budget_fabric_booked'] !!}</td>
                    <td>{!! $orders_data['is_budget_knitting_info'] !!}</td>
                    <td>{{date('d M Y',strtotime($orders_data['order_shipment_date']))}}</td>
                    <td>{!! $orders_data['factory'] !!}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
</main>
</body>
</html>
