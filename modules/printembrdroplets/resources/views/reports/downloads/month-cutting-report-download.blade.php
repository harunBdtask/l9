<!DOCTYPE html>

<html>
<head>
    <title>Report</title>
    @include('reports.downloads.includes.pdf-styles')
</head>
<body>
@include('reports.downloads.includes.pdf-header')
<main>

<h4 align="center">Month Wise Cutting Production Report  @if(isset($from_date) && isset($to_date)) || {{ date("jS F, Y", strtotime($from_date)).' To '. date("jS F, Y", strtotime($to_date)) }} @endif
</h4>
<table class="reportTable" style="border: 1px solid black;border-collapse: collapse;">
  <thead>
    <tr><th colspan="6">PO Wise Cutting Production Summary </th></tr>
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
    @if(!empty($report_data))
      @php
        $torder_quantity = 0;
        $tcutting_quantity = 0;
      @endphp
      @foreach($report_data as $report)
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
        <tr style="font-weight: bold">
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
    @if(!empty($report_data))
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
        <tr>
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