<!DOCTYPE html>

<html>
<head>
    <title>Cutting Report</title>
    @include('reports.downloads.includes.pdf-styles')
</head>

<body>
@include('reports.downloads.includes.pdf-header')
<main>
    <h4 align="center">All PO's Cutting Production Report || {{ date("jS F, Y") }}</h4>   
    <table class="reportTable" style="border: 1px solid black;border-collapse: collapse;">
      <thead>
        <tr>
          <th rowspan="2">SL</th>
          <th rowspan="2">Buyer</th>
          <th colspan="7">Order Details</th>
        </tr>
        <tr>
          <th>Style</th>
          <th>PO</th>
          <th>Order Qty</th>
          <th>Today's Cutting</th>
          <th>Total Cutting</th>
          <th>Left Qty</th>
          <th>Extra Cutting (%)</th>
        </tr>
      </thead>
      <tbody>
         @foreach($orders->getCollection()->groupBy('buyer_id') as $ordersByBuyer)
          <tr>
            <td rowspan="{{ $ordersByBuyer->count() + $ordersByBuyer->groupBy('style_id')->count()*3 + 1}}">{{ $loop->iteration }}</td>
            <td rowspan="{{ $ordersByBuyer->count() + $ordersByBuyer->groupBy('style_id')->count()*3 + 1}}">{{ $ordersByBuyer->first()->buyer->name ?? '' }}</td>
          </tr>

          @foreach($ordersByBuyer->groupBy('style_id') as $ordersByStyle)
            <tr>
              <td rowspan="{{ $ordersByStyle->count() + 3 }}">{{ $ordersByStyle->first()->style->name ?? '' }}</td>
            </tr>

            @php
              $totalTodaysCutting = 0;
              $totalCuttingForStyle = 0;
              $totalLeftQty = 0;
              $totalXtra = 0;
            @endphp

            @foreach($ordersByStyle as $order)
              @php                   
                $todaysCutting = ($order->todaysCutting->sum('quantity') - $order->todaysCutting->sum('total_rejection')) ?? 0;
                $totalCutting = ($order->bundleCards->sum('quantity') - $order->bundleCards->sum('total_rejection')) ?? 0;
                $xtra = (( $totalCutting - $order->total_quantity) * 100) / $order->total_quantity ?? 0;
                $xtra = $xtra > 0 ? $xtra : 0;
                $leftQty = $order->total_quantity - $totalCutting;

                $totalTodaysCutting += $todaysCutting;
                $totalCuttingForStyle += $totalCutting;
                $totalLeftQty += $leftQty;
                //$totalXtra += $xtra; 
              @endphp
              <tr>
                <td>{{ $order->order_no }}</td>
                <td>{{ $order->total_quantity }}</td>
                <td>{{ $todaysCutting }}</td>
                <td>{{ $totalCutting }}</td>
                <td>{{ $leftQty }}</td>
                <td>{{ number_format($xtra, 2).'%' }}</td>
              </tr>
            @endforeach
            <tr>
              <td><strong>{{ 'TOTAL' }}</strong></td>
              <td><strong>{{ $ordersByStyle->sum('total_quantity') }}</strong></td>
              <td><strong>{{ $totalTodaysCutting }}</strong></td>
              <td><strong>{{ $totalCuttingForStyle }}</strong></td>
              <td><strong>{{ $totalLeftQty }}</strong></td>
              <td><strong>{{-- number_format($totalXtra, 2).'%' --}}</strong></td>
            </tr>
              <td colspan="6">&nbsp;</td>
            </tr>
          @endforeach
        @endforeach
      </tbody>
      <tfoot>
        @if($orders->total() > 15)
          <tr>
            <td colspan="9" align="center">{{ $orders->appends(request()->except('page'))->links() }}</td>
          </tr>
        @endif
      </tfoot>  
    </table>
</main>
@include('reports.downloads.includes.pdf-footer')
</body>
</html>