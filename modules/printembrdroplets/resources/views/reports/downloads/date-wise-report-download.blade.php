<!DOCTYPE html>

<html>
<head>
    <title>Report</title>
    @include('reports.downloads.includes.pdf-styles')
</head>
<body>
@include('reports.downloads.includes.pdf-header')
<main>

<h4 align="center">Date Wise Cutting Production Summary || {{ date("jS F, Y", strtotime($date)) }}</h4>

<table class="reportTable" style="border: 1px solid black;border-collapse: collapse;">
  <thead>
    <tr><th colspan="6">Order Wise Cutting Production Summary </th></tr>
    <tr>
        <th>SL</th>
        <th>Buyer</th>
        <th>Style/Order No</th>
        <th>PO</th>                   
        <th>Order Quantity</th>
        <th>Cutting Production</th>
    </tr>
  </thead>
  <tbody class="color-wise-report">
    @if(!empty($result))
      @php
        $torder_quantity = 0;
        $tcutting_quantity = 0;
      @endphp
      @foreach($result as $report)
        @php
          $torder_quantity += $report['order_quantity'];
          $tcutting_quantity += $report['cutting_quantity'];
        @endphp
        <tr>
          <td>{{ $loop->iteration }}
          <td>{{ $report['buyer'] }}</td>
          <td>{{ $report['style'] }}</td>
          <td>{{ $report['order'] }}</td>                     
          <td>{{ $report['order_quantity'] }}</td>
          <td>{{ $report['cutting_quantity'] }}</td>
        </tr>
      @endforeach
        <tr style="font-weight:bold;">
          <td colspan="4">Total</td>
          <td>{{ $torder_quantity }}</td>
          <td>{{ $tcutting_quantity }}</td>
        </tr>               
    @else
      <tr>
        <td colspan="6" class="text-danger text-center">Not found<td>
      </tr>
    @endif
  </tbody>     
</table>

<!-- color wise -->
<table class="reportTable">
  <thead>
    <tr><th colspan="6">Color Wise Cutting Production Summary </th></tr>
    <tr>
      <th>SL</th> 
      <th>Buyer</th>
      <th>Style</th>
      <th>PO</th>                  
      <th>Color</th>
      <th>Cutting Production</th>                        
    </tr>
  </thead>
  <tbody class="">
    @if(!empty($color_result))
      @php
        $tcutting_quantity_color = 0;                   
      @endphp
      @foreach($color_result as $color)
        @php
          $tcutting_quantity_color += $color['cutting_quantity_color'];
        @endphp
        <tr> 
          <td>{{ $loop->iteration }}</td>                    
          <td>{{ $color['buyer'] }}</td>
          <td>{{ $color['style'] }}</td>
          <td>{{ $color['order'] }}</td>                      
          <td>{{ $color['color'] }}</td>
          <td>{{ $color['cutting_quantity_color'] }}</td>
        </tr>
      @endforeach
        <tr style="font-weight:bold;">
          <td colspan="5">Total</td>
          <td>{{ $tcutting_quantity_color }}</td>
        </tr>               
    @else
      <tr>
        <td colspan="6" class="text-danger text-center">Not found<td>
      </tr>
    @endif
  </tbody>     
</table>

<!--table wise target summary-->
<table class="reportTable">
  <thead>
    <tr>
      <th colspan="4"><b>Cutting Target Wise Cutting Production Summary</b></th>
    </tr>
  </thead>
  <thead>
    <tr>
      <th>Table</th>
      <th>Target/Day</th>
      <th>Cutting Production</th>
      <th>Achievement</th>                  
    </tr>
  </thead>
  <tbody>
    @if(!empty($result_table))
      @php
        $ttoday_target = 0;
        $total_cutting = 0;                   
      @endphp
      @foreach($result_table as $tble)
        @php
          $ttoday_target += $tble['today_target'];
          $total_cutting += $tble['cutting_quantity_table'];
          $achive = 0;
          if($tble['cutting_quantity_table'] > 0 && $tble['today_target'] > 0) {
            $achive = (( $tble['today_target'] - $tble['cutting_quantity_table']) * 100) /$tble['today_target'];
          }                    
        @endphp 
        <tr>
          <td>{{ $tble['cutting_table_no'] }}</td>
          <td>{{ $tble['today_target'] }}</td>
          <td>{{ $tble['cutting_quantity_table'] }}</td>
          <td>{{ $achive }} %</td>              
        </tr>           
      @endforeach
      <tr style="font-weight:bold;">
        <td>Total</td>
        <td>{{ $ttoday_target }}</td>
        <td>{{ $total_cutting }}</td>
        <td></td>
      </tr>
  @else
      <tr>
        <td colspan="4" class="text-danger text-center">Not found<td>
      </tr>    
  @endif  
  </tbody>
</table>

<!--table wise cutting summary-->   
<table class="reportTable">
  <thead>
    <tr><th colspan="3">Table Wise Cutting Production Summary</th></tr>
    <tr>
      <th>Table</th>
      <th>Bundle Qty</th>
      <th>Cutting Production</th>
    </tr>
  </thead>
  <tbody class="color-wise-report">
    @if(!empty($result_table))
      @php
        $tbundle_qty_table = 0;
        $tcutting_quantity_table = 0;
      @endphp
      @foreach($result_table as $table)
        @php
          $tbundle_qty_table += $table['bundle_qty_table'];
          $tcutting_quantity_table += $table['cutting_quantity_table'];
        @endphp
        <tr>                     
          <td>{{ $table['cutting_table_no'] }}</td>
          <td>{{ $table['bundle_qty_table'] }}</td>
          <td>{{ $table['cutting_quantity_table'] }}</td>                      
        </tr>
      @endforeach
        <tr style="font-weight:bold;">
          <td>Total</td>
          <td>{{ $tbundle_qty_table }}</td>
          <td>{{ $tcutting_quantity_table }}</td>
        </tr>               
    @else
      <tr>
        <td colspan="5" class="text-danger text-center">Not found<td>
      </tr>
    @endif
  </tbody>     
</table>

</main>
@include('reports.downloads.includes.pdf-footer')
</body>
</html>