<!DOCTYPE html>

<html>
<head>
    <title>PO Wise Cutting QC Report</title>
    @include('reports.downloads.includes.pdf-styles')
</head>
<body>
@include('reports.downloads.includes.pdf-header')
<main>
<h4 align="center">PO Wise Cutting Production Report || {{ date("D\ - F d- Y") }}</h4>
<table class="reportTable" style="border: 1px solid black;border-collapse: collapse;">
  <thead>
    <tr>
      <th>Color Name</th>
      <th>Size Name</th>
      <th>Order Quantity</th>
      <th>Today's Cutting</th>
      <th>Total Cutting</th>
      <th>Left Quantity</th>
      <th>Extra Cuttting (%)</th>
    </tr>
  </thead>
  <tbody class="color-wise-report">                
    @if($result_data['report_size_wise'])      
      @foreach($result_data['report_size_wise'] as $order)        
        <tr><td>{{ $order['color'] }}</td><td>{{ $order['size'] }}</td><td>{{ $order['size_order_qty'] }}</td><td>{{ $order['today_cutting'] }}</td><td>{{ $order['total_cutting'] }}</td><td>{{ $order['left_qty'] }}</td><td>{{ $order['extra_cutting_ratio'] }} </td></tr>
        });
      @endforeach  
        <tr><td colspan="2"><b>Total</b></td><td>{{ $result_data['total_report']['total_order_cutting'] }}</td><td>{{ $result_data['total_report']['total_today_cutting'] }}</td><td>{{ $result_data['total_report']['total_total_cutting'] }}</td><td>{{ $result_data['total_report']['total_left_qty'] }}</td><td></td></tr>; 
    @else
      <tr><td colspan="7" class="text-danger text-center" >Not found</td></tr>
    @endif            
  </tbody>     
</table>
</main>
@include('reports.downloads.includes.pdf-footer')
</body>
</html>