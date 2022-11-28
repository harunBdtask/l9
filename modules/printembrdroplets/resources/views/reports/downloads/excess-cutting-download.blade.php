<!DOCTYPE html>

<html>
<head>
    <title>Production Report</title>
    @include('reports.downloads.includes.pdf-styles')
</head>
<body>
@include('reports.downloads.includes.pdf-header')
<main>
    <h4 align="center">PO Wise Excess Cutting Production Report || {{ date("D\ - F d- Y") }}</h4>
    <table class="reportTable" style="border: 1px solid black;border-collapse: collapse;">
        <thead>
            <tr>
                <th>SL</th>
                <th>Buyer</th>
                <th>Style/Order No</th>
                <th>PO</th>
                <th>Order Qty</th>
                <th>Today's Cutting</th>
                <th>Total Cutting</th>
                <th>Xtra Qty</th>
                <th>Xtra Cutting(%)</th>
            </tr>
        </thead>
        <tbody>
           @if(!empty($orders) && count($orders) > 0)
              @foreach($orders as $order)                   
                @if($order->extra > 0)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $order->buyer }}</td>
                  <td>{{ $order->style }}</td>
                  <td>{{ $order->order_no }}</td>
                  <td>{{ $order->total_quantity }}</td>
                  <td>{{ $order->todays_cutting }}</td>
                  <td>{{ $order->cutting_qtys }}</td>
                  <td>{{ $order->left_qty }}</td>
                  <td>{{ abs($order->extra).'%' ?? '' }}</td>
                </tr>
                @endif
                @endforeach
                  <tr style="font-weight: bold;">
                    @if($order->extra > 0)
                     <td colspan="4">Total</td>
                     <td>{{ $orders->sum('order_qty') }}</td>
                     <td>{{ $orders->sum('todays_cutting') }}</td>
                     <td>{{ $orders->sum('cutting_qtys') }}</td>
                     <td>{{ $orders->sum('left_qty') }}</td>
                     <td>{{ '' }}</td>
                    @endif 
                  </tr>
              @else
                <tr>
                  <td colspan="9" class="text-danger text-center">Not found<td>
                </tr>
              @endif
        </tbody>
    </table>
</main>
@include('reports.downloads.includes.pdf-footer')
</body>
</html>
          