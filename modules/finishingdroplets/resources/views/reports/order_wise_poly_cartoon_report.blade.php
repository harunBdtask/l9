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
            <h2>All Order's Poly &amp; Cartoon Summary || {{ date("D\ - F d- Y") }}</h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body">
            <div id="parentTableFixed" class="table-responsive">
              <table class="reportTable" id="fixTable">
                <thead>
                  <tr>
                    <th>Buyer</th>
                    <th>Style/Order No</th>
                    <th>PO</th>
                    <th>Order Qty</th>
                    <th>Cut. Production</th>
                    <th>Today's Input</th>
                    <th>Total Input</th>
                    <th>Today's Output</th>
                    <th>Total Output</th>
                    <th>Total Rejection</th>
                    <th>Today's Poly</th>
                    <th>Total Poly</th>
                    <th>%Poly</th>
                    <th>Today's Cartoon</th>
                    <th>Total Cartoon</th>
                    <th>Today Pcs</th>
                    <th>Total Pcs</th>
                  </tr>
                </thead>
                <tbody>
                  @if($order_wise_report)
                    @php
                      $total_order_qty = 0;
                      $total_cutting_qty = 0;
                      $total_today_input = 0;
                      $total_total_input = 0;
                      $total_today_output = 0;
                      $total_total_output = 0;
                      $total_total_rejection = 0;
                      $total_poly = 0;
                      $total_cartoon = 0;
                      $total_todays_poly = 0;
                      $total_todays_cartoon = 0;
                      $today_pcs = 0;
                      $total_pcs = 0;
                    @endphp
                    @foreach($order_wise_report as $report)
                      @php
                          $total_order_qty += $report->order_qty;
                          $total_cutting_qty += $report->cutting_qty;
                          $total_today_input += $report->today_input;
                          $total_total_input += $report->total_input;
                          $total_today_output += $report->today_output;
                          $total_total_output += $report->total_output;
                          $total_total_rejection += $report->total_rejection;
                          $total_todays_poly += $report->today_poly;
                          $total_todays_cartoon += $report->today_cartoon;
                          $total_poly += $report->poly_qty;
                          $total_cartoon += $report->cartoon_qty;
                          $today_pcs += $report->today_pcs;
                          $total_pcs += $report->total_pcs;
                      @endphp
                      <tr>
                        <td>{{ $report->buyer ?? '' }}</td>
                        <td>{{ $report->order ?? '' }}</td>
                         <td>{{ $report->purchase_order ?? '' }}</td>
                        <td>{{ $report->order_qty }}</td>
                        <td>{{ $report->cutting_qty }}</td>
                        <td>{{ $report->today_input }}</td>
                        <td>{{ $report->total_input }}</td>
                        <td>{{ $report->today_output }}</td>
                        <td>{{ $report->total_output }}</td>
                        <td>{{ $report->total_rejection }}</td>
                        <td>{{ $report->today_poly }}</td>
                        <td>{{ $report->poly_qty }}</td>
                        <td>{{ $report->poly_ratio }}%</td>
                        <td>{{ $report->today_cartoon }}</td>
                        <td>{{ $report->cartoon_qty }}</td>
                        <td>{{ $report->today_pcs }}</td>
                        <td>{{ $report->total_pcs }}</td>
                      </tr>
                    @endforeach
                        <tr style="font-weight: bold">
                          <td colspan="3">Total</td>
                          <td>{{ $total_order_qty }}</td>
                          <td>{{ $total_cutting_qty }}</td>
                          <td>{{ $total_today_input }}</td>
                          <td>{{ $total_total_input }}</td>
                          <td>{{ $total_today_output }}</td>
                          <td>{{ $total_total_output }}</td>
                          <td>{{ $total_total_rejection }}</td>
                          <td>{{ $total_todays_poly }}</td>
                          <td>{{ $total_poly }}</td>
                          <td>{{ '' }}</td>
                          <td>{{ $total_todays_cartoon }}</td>
                          <td>{{ $total_cartoon }}</td>
                          <td>{{ $today_pcs }}</td>
                          <td>{{ $total_pcs }}</td>
                        </tr>
                  @else
                    <tr>
                      <td colspan="17" class="text-danger text-center">Not found<td>
                    </tr>
                  @endif
                </tbody>
                <tfoot>
                @if($order_wise_report->total() > 10)
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
