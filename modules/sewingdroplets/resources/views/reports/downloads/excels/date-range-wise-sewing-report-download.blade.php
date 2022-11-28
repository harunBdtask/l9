<table>
  <thead>
  <tr>
    <td colspan="7">{{ factoryName() }}</td>
  </tr>
  </thead>
</table>

<table class="reportTable">
  <thead>
  <tr>
    <th colspan="6">Section-1 : Line, Buyer, Order &amp; PO Wise
      Sewing Output &amp; Rejection Status
    </th>
    <th>{{request()->from_date}}</th>
    <th>{{request()->to_date}}</th>
  </tr>
  <tr>
    <th>Unit</th>
    <th>Line</th>
    <th>Buyer</th>
    <th>Order/Style</th>
    <th>PO</th>
    <th>Sewing Output</th>
    <th>Rejection</th>
  </tr>
  </thead>
  <tbody class="color-wise-report">
  @if(!empty($sewing_output_summary))
    @php
      $t_sewing_output = 0;
      $t_sewing_rejection = 0;
    @endphp
    @foreach($sewing_output_summary->groupBy('line_id') as $groupByLine)
      @foreach($groupByLine->groupBy('purchase_order_id') as $groupByOrder)
        @php
          $floor_no = $groupByLine->first()['floor_no'] ?? '';
          $line_no = $groupByLine->first()['line_no'] ?? '';
          $buyer_name = $groupByOrder->first()['buyer_name'] ?? '';
          $style_name = $groupByOrder->first()['style_name'] ?? '';
          $order_no = $groupByOrder->first()['order_no'] ?? '';
          $sewing_output = 0;
          $sewing_rejection = 0;
        @endphp
        @foreach($groupByOrder as $report)
          @php
            $sewing_output += $report['sewing_output'];
            $sewing_rejection += $report['sewing_rejection'];
            $t_sewing_output += $report['sewing_output'];
            $t_sewing_rejection += $report['sewing_rejection'];
          @endphp
        @endforeach
        <tr>
          <td>{{ $floor_no }}</td>
          <td>{{ $line_no }}</td>
          <td>{{ $buyer_name }}</td>
          <td>{{ $style_name }}</td>
          <td>{{ $order_no }}</td>
          <td>{{ $sewing_output }}</td>
          <td>{{ $sewing_rejection }}</td>
        </tr>
      @endforeach
    @endforeach
    <tr>
      <th colspan="5">Total</th>
      <th>{{ $t_sewing_output }}</th>
      <th>{{ $t_sewing_rejection }}</th>
    </tr>
  @else
    <tr>
      <td colspan="7" class="text-danger text-center">Not found
      <td>
    </tr>
  @endif
  </tbody>
</table>

<!-- line wise report -->
<table class="reportTable">
  <thead>
  <tr>
    <th colspan="4">Section-2 : Line Wise Summary</th>
  </tr>
  </thead>
  <thead>
  <tr>
    <th>Unit</th>
    <th>Line</th>
    <th>Output</th>
    <th>Rejection</th>
  </tr>
  </thead>
  <tbody>
  @if(!empty($line_wise_summary_report))
    @php
      $total_line_output = 0;
      $total_line_rejection = 0;
    @endphp
    @foreach($line_wise_summary_report as $line_report)
      @php
        $total_line_output += $line_report['sewing_output'];
        $total_line_rejection += $line_report['sewing_rejection'];
      @endphp
      <tr>
        <td>{{ $line_report['floor_no'] ?? '' }}</td>
        <td>{{ $line_report['line_no'] ?? ''}}</td>
        <td>{{ $line_report['sewing_output'] ?? 0 }}</td>
        <td>{{ $line_report['sewing_rejection'] ?? 0 }}</td>
      </tr>
    @endforeach
    <tr>
      <td colspan="2">Total</td>
      <td>{{ $total_line_output }}</td>
      <td>{{ $total_line_rejection }}</td>
    </tr>
  @else
    <tr>
      <td colspan="4" class="text-danger text-center">Not found
      <td>
    </tr>
  @endif
  </tbody>
</table>

<!-- buyer order wise report -->
<table class="reportTable">
  <thead>
  <tr>
    <th colspan="5">Section-3 : Buyer, Order &amp; PO Wise Sewing
      Output &amp; Rejection Status
    </th>
  </tr>
  </thead>
  <thead>
  <tr>
    <th>Buyer</th>
    <th>Order/tyle</th>
    <th>PO</th>
    <th>Output</th>
    <th>Rejection</th>
  </tr>
  </thead>
  <tbody>
  @if(!empty($sewing_output_summary))
    @php
      $total_buyer_output = 0;
      $total_buyer_rejection = 0;
    @endphp
    @foreach($sewing_output_summary->groupBy('order_id') as $reportByOrder)
      @php
        $buyer_name = $reportByOrder->first()['buyer_name'] ?? '';
        $style_name = $reportByOrder->first()['style_name'] ?? '';
        $order_no = $reportByOrder->first()['order_no'] ?? '';
        $sewing_output = 0;
        $sewing_rejection = 0;
      @endphp
      @foreach($reportByOrder as $order_report)
        @php
          $sewing_output += $order_report['sewing_output'];
          $sewing_rejection += $order_report['sewing_rejection'];
          $total_buyer_output += $order_report['sewing_output'];
          $total_buyer_rejection += $order_report['sewing_rejection'];
        @endphp
      @endforeach
      <tr>
        <td>{{ $buyer_name }}</td>
        <td>{{ $style_name }}</td>
        <td>{{ $order_no }}</td>
        <td>{{ $sewing_output }}</td>
        <td>{{ $sewing_rejection }}</td>
      </tr>
    @endforeach
    <tr>
      <td colspan="3">Total</td>
      <td>{{ $total_buyer_output }}</td>
      <td>{{ $total_buyer_rejection }}</td>
    </tr>
  @else
    <tr>
      <td colspan="4" class="text-danger text-center">Not found
      <td>
    </tr>
  @endif
  </tbody>
</table>

<!-- color order wise report -->
<table class="reportTable">
  <thead>
  <tr>
    <th colspan="6">Section-4 : Buyer, Order, PO &amp; Colour Wise Sewing
      Output Status
    </th>
  </tr>
  </thead>
  <thead>
  <tr>
    <th>Buyer</th>
    <th>Order/Style</th>
    <th>PO</th>
    <th>Color</th>
    <th>Output</th>
    <th>Rejection</th>
  </tr>
  </thead>
  <tbody>
  @if(!empty($sewing_output_summary))
    @php
      $total_color_output = 0;
      $total_color_rejection = 0;
    @endphp
    @foreach($sewing_output_summary->groupBy('order_id') as $groupByOrder)
      @foreach($groupByOrder->groupBy('color_id') as $groupByColor)
        @php
          $buyer_name = $groupByOrder->first()['buyer_name'] ?? '';
          $style_name = $groupByOrder->first()['style_name'] ?? '';
          $order_no = $groupByOrder->first()['order_no'] ?? '';
          $color = $groupByColor->first()['color'] ?? '';
          $sewing_output = 0;
          $sewing_rejection = 0;
        @endphp
        @foreach($groupByColor as $color_report)
          @php
            $sewing_output += $color_report['sewing_output'];
            $sewing_rejection += $color_report['sewing_rejection'];
            $total_color_output += $color_report['sewing_output'];
            $total_color_rejection += $color_report['sewing_rejection'];
          @endphp
        @endforeach
        <tr>
          <td>{{ $buyer_name }}</td>
          <td>{{ $style_name }}</td>
          <td>{{ $order_no }}</td>
          <td>{{ $color }}</td>
          <td>{{ $sewing_output }}</td>
          <td>{{ $sewing_rejection }}</td>
        </tr>
      @endforeach
    @endforeach
    <tr>
      <td colspan="4">Total</td>
      <td>{{ $total_color_output }}</td>
      <td>{{ $total_color_rejection }}</td>
    </tr>
  @else
    <tr>
      <td colspan="6" class="text-danger text-center">Not found
      <td>
    </tr>
  @endif
  </tbody>
</table>
