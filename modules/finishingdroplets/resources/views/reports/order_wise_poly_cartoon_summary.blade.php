@php
  $tableHeadColorClass = 'tableHeadColor';
  if (isset($type) || request()->has('type') || request()->route('type')) {
    $tableHeadColorClass = '';
  }
@endphp
@extends('finishingdroplets::layout')
@section('title', "All Order's Poly & Cartoon Summary")
@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
            <h2>All Order's Poly &amp; Cartoon Summary || {{ date("jS F, Y") }} <span class="pull-right"><a href="{{url('all-orders-poly-cartoon-report-download/pdf/')}}"><i style="color: #DC0A0B" class="fa fa-file-pdf-o"></i></a> | <a href="{{url('all-orders-poly-cartoon-report-download/xls/')}}"><i style="color: #0F733B" class="fa fa-file-excel-o"></i></a></span></h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body">
            <div id="parentTableFixed" class="table-responsive">
              <table class="reportTable" id="fixTable">
                <thead>
                <tr>
                  <th>Buyer</th>
                  <th>Style/ Order No</th>
                  <th>PO</th>
                  <th>Order Qty</th>
                  <th>Cut. Production</th>
                  <th>Today's Input</th>
                  <th>Total Input</th>
                  <th>Today's Output</th>
                  <th>Total Output</th>
                  <th>Today's Poly</th>
                  <th>Total Poly</th>
                  <th>%Poly</th>
                  <th>Today's Cartoon</th>
                  <th>Total Cartoon</th>
                  <th>Today Pcs</th>
                  <th>Total Pcs</th>
                  <th>Total Rejection</th>
                </tr>
                </thead>
                <tbody>
                @if(isset($order_wise_report))
                  @php
                    $total_order_qty = 0;
                    $total_rejection_qty = 0;
                    $todays_todays_pcs = 0;
                    $total_total_pcs = 0;
                  @endphp
                  @foreach($order_wise_report as $report)
                    @php
                      $total_order_qty += $report->purchaseOrder->po_quantity;
                      $total_rejection_qty += $report->total_cutting_rejection + $report->total_print_rejection + $report->total_sewing_rejection + $report->total_washing_rejection;

                      $poly_percentage = ($report->todays_poly > 0 && $report->total_cutting > 0) ? number_format(($report->todays_poly / $report->total_cutting) * 100, 2) : 0;
                    @endphp
                    <tr>
                      <td>{{ $report->buyer->name ?? '' }}</td>
                      <td>{{ $report->order->order_style_no ?? '' }}</td>
                      <td>{{ $report->purchaseOrder->po_no ?? '' }}</td>
                      <td>{{ $report->purchaseOrder->po_quantity }}</td>
                      <td>{{ $report->total_cutting }}</td>
                      <td>{{ $report->todays_input }}</td>
                      <td>{{ $report->total_input }}</td>
                      <td>{{ $report->todays_sewing_output }}</td>
                      <td>{{ $report->total_sewing_output }}</td>
                      <td>{{ $report->todays_poly }}</td>
                      <td>{{ $report->total_poly }}</td>
                      <td>{{ $poly_percentage }}%</td>
                      <td>{{ $report->todays_cartoon }}</td>
                      <td>{{ $report->total_cartoon }}</td>
                      <td>{{ $report->todays_pcs }}</td>
                      <td>{{ $report->total_pcs }}</td>
                      <td>{{ $report->total_cutting_rejection + $report->total_print_rejection + $report->total_sewing_rejection + $report->total_washing_rejection }}</td>
                    </tr>
                  @endforeach
                  <tr style="font-weight: bold">
                    <td colspan="3">Total</td>
                    <td>{{ $total_order_qty }}</td>
                    <td>{{ $order_wise_report->sum('total_cutting') }}</td>
                    <td>{{ $order_wise_report->sum('todays_input') }}</td>
                    <td>{{ $order_wise_report->sum('total_input') }}</td>
                    <td>{{ $order_wise_report->sum('todays_sewing_output') }}</td>
                    <td>{{ $order_wise_report->sum('total_sewing_output') }}</td>
                    <td>{{ $order_wise_report->sum('todays_poly') }}</td>
                    <td>{{ $order_wise_report->sum('total_poly') }}</td>
                    <td>{{ '' }}</td>
                    <td>{{ $order_wise_report->sum('todays_cartoon') }}</td>
                    <td>{{ $order_wise_report->sum('total_cartoon') }}</td>
                    <td>{{ $order_wise_report->sum('todays_pcs') }}</td>
                    <td>{{ $order_wise_report->sum('total_pcs') }}</td>
                    <td>{{ $total_rejection_qty }}</td>
                  </tr>
                  </tr>
                @else
                  <tr>
                    <td colspan="17" class="text-danger text-center">Not found<td>
                  </tr>
                @endif
                </tbody>
                <tfoot>
                @if($order_wise_report->total() > 15)
                  <tr>
                    <td colspan="17" align="center">{{ $order_wise_report->appends(request()->except('page'))->links() }}</td>
                  </tr>
                @endif
                </tfoot>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
