<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        .vertical-spacing {
            height: 3em;
        }

        .reportTable {
            margin-bottom: 1rem;
            width: 100%;
            max-width: 100%;
            border-collapse: collapse;
        }

        .reportTable th,
        .reportTable td {
            /*border: 1px solid #ccc;*/
            border: 1px solid rgba(120, 130, 140, 0.2) !important;
        }
    </style>

</head>
<body>
@include('merchandising::reports.downloads.includes.pdf_style')
@include('merchandising::reports.downloads.includes.pdf_header')
<div class="padding">
    <div class="box knit-card">
        <div class="box-header">
            @if(strtotime(Request::get('from_date')))
                <h4 style="text-align: center;margin: 0px;">Date Wise Fabric Status</h4>
                <p style="text-align: center;">Report Between : {{date('d-M-Y',strtotime(Request::get('from_date')))}} To {{date('d-M-Y',strtotime(Request::get('to_date')))}}</p>
            @else
                <h4 style="text-align: center;margin: 0px;">Monthly Fabric Status</h4>
                <p style="text-align: center;">Report Month : {{date('M-Y',strtotime(Request::get('month')))}}</p>
            @endif
            <div class="clearfix"></div>
        </div>
        <div class="box-body b-t">
            <div class="table-responsive">
                <table class="reportTable">
                    <thead>
                    <tr>
                        <th>Buyer</th>
                        <th>Booking No</th>
                        <th>Fab. Composition</th>
                        <th>Fabric Type</th>
                        <th>Color</th>
                        <th>GSM</th>
                        <th>Total Qty</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        $grand_total_fabric = 0;
                        $buyer = '';
                        $booking_no = '';
                    @endphp
                    @foreach($report_data as $data)
                        <tr>
                            @if($buyer != $data->buyer->name)
                                <td rowspan="{{$report_data->where('buyer_id',$data->buyer_id )->count()}}">{{$data->buyer->name}}</td>
                            @endif
                            @if($booking_no != $data->order->booking_no)
                                <td rowspan="{{$report_data->where('order_id',$data->order_id )->count()}}">{{$data->order->booking_no}}</td>
                            @endif
                            <td>{{$data->fabric_composition}}</td>
                            <td>{{$data->fabric_type->fabric_type_name}}</td>
                            <td>{{$data->color->name}}</td>
                            <td>{{$data->fabric_gsm}}</td>
                            <td>{{$data->total_fabric_qty}} Kg</td>
                        </tr>
                        @php
                            $grand_total_fabric += $data->total_fabric_qty;
                            $buyer = $data->buyer->name;
                            $booking_no = $data->order->booking_no;
                        @endphp
                    @endforeach
                    <tr>
                        <td colspan="6"><b>Total</b></td>
                        <td><b>{{$grand_total_fabric}} Kg</b></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@include('merchandising::reports.downloads.includes.pdf_footer')
</body>
</html>