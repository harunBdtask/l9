@php
  $tableHeadColorClass = 'tableHeadColor';
  if (isset($type) || request()->has('type') || request()->route('type')) {
    $tableHeadColorClass = '';
  }
@endphp
@extends('sewingdroplets::layout')
@section('title', 'Date Range Wise Sewing Report')
@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
            <h2>Date Range Wise Sewing Report </h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body">

            <table class="reportTable">
              <thead>
              <tr>
                <th colspan="5" style="font-size: 14px; font-weight: bold">Section-1 : Line, Buyer &amp; PO Wise Sewing
                  Output &amp; Rejection Status &nbsp;&nbsp; || &nbsp;&nbsp; <span>{{ date("jS F, Y") }}
                      </span></b></b>
                </th>
              <thead>
              <tr>
                <th>Line</th>
                <th>Buyer</th>
                <th>PO</th>
                <th>Sewing Output</th>
                <th>Rejection</th>
              </tr>
              </thead>
              </tr>
              </thead>
              <tbody class="color-wise-report">
              @if(!empty($date_wise_report))
                @php
                  $torder_quantity = 0;
                  $tcutting_quantity = 0;
                @endphp
                @foreach($date_wise_report as $report)
                  @php
                    /*
                      $torder_quantity += $report['order_quantity'];
                      $tcutting_quantity += $report['cutting_quantity'];
                      */
                  @endphp
                  <tr>
                    <td>{{ $report->line }}</td>
                    <td>{{ $report->buyer }}</td>
                    <td>{{ $report->order }}</td>
                    <td>{{ $report->output_qty }}</td>
                    <td>{{ $report->sewing_rejection }}</td>
                  </tr>
                @endforeach
                <tr> {{--
                      <td colspan="3" style="font-weight:bold;">Total</td>
                      <td>{{ $torder_quantity }}</td>
                      <td>{{ $tcutting_quantity }}</td> --}}
                </tr>
              @else
                <tr>
                  <td colspan="5" class="text-danger text-center">Not found
                  <td>
                </tr>
              @endif
              </tbody>
            </table>

            <!-- line wise report -->
            <table class="reportTable">
              <thead>
              <tr>
                <th colspan="3" style="font-size: 14px; font-weight: bold">Section-2 : Line Wise Summary</th>
              </tr>
              </thead>
              <thead>
              <tr>
                <th>Line</th>
                <th>Output</th>
                <th>Rejection</th>
              </tr>
              </thead>
              <tbody>
              @if($line_wise_report)
                @php
                  $total_line_output = 0;
                  $total_line_rejection = 0;
                @endphp
                @foreach($line_wise_report as $line_repot)
                  @php
                    $total_line_output += $line_repot->line_total_qty;
                    $total_line_rejection += $line_repot->line_total_rejection;
                  @endphp
                  <tr>
                    <td>{{ $line_repot->line }}</td>
                    <td>{{ $line_repot->line_total_qty }}</td>
                    <td>{{ $line_repot->line_total_rejection }}</td>
                  </tr>
                @endforeach
                <tr>
                  <td colspan="" style="font-weight:bold;">Total</td>
                  <td>{{ $total_line_output }}</td>
                  <td>{{ $total_line_rejection }}</td>
                </tr>
              @else
                <tr>
                  <td colspan="3" class="text-danger text-center">Not found
                  <td>
                </tr>
              @endif
              </tbody>
            </table>
            <!-- buyer order wise report -->
            <table class="reportTable">
              <thead>
              <tr>
                <th colspan="4" style="font-size: 14px; font-weight: bold">Section-3 : Buyer & PO Wise Sewing Output &
                  Rejection Status
                </th>
              </tr>
              </thead>
              <thead>
              <tr>
                <th>Buyer</th>
                <th>PO</th>
                <th>Output</th>
                <th>Rejection</th>
              </tr>
              </thead>
              <tbody>
              @if($buyer_wise_report)
                @php
                  $total_buyer_output = 0;
                  $total_buyer_rejection = 0;
                @endphp
                @foreach($buyer_wise_report as $buyer_repot)
                  @php
                    $total_buyer_output += $buyer_repot->output_qty_buyer;
                    $total_buyer_rejection += $buyer_repot->sewing_rejection_buyer;
                  @endphp
                  <tr>
                    <td>{{ $buyer_repot->buyer }}</td>
                    <td>{{ $buyer_repot->order }}</td>
                    <td>{{ $buyer_repot->output_qty_buyer }}</td>
                    <td>{{ $buyer_repot->sewing_rejection_buyer }}</td>
                  </tr>
                @endforeach
                <tr>
                  <td colspan="2" style="font-weight:bold;">Total</td>
                  <td>{{ $total_buyer_output }}</td>
                  <td>{{ $total_buyer_rejection }}</td>
                </tr>
              @else
                <tr>
                  <td colspan="3" class="text-danger text-center">Not found
                  <td>
                </tr>
              @endif
              </tbody>
            </table>

            <!-- color order wise report -->
            <table class="reportTable">
              <thead>
              <tr>
                <th colspan="5" style="font-size: 14px; font-weight: bold">Section-4 : Buyer, PO & Colour Wise Sewing
                  Output Status
                </th>
              </tr>
              </thead>
              <thead>
              <tr>
                <th>Buyer</th>
                <th>PO</th>
                <th>Color</th>
                <th>Output</th>
                <th>Rejection</th>
              </tr>
              </thead>
              <tbody>
              @if($color_wise_report)
                @php
                  $total_color_output = 0;
                  $total_color_rejection = 0;
                @endphp
                @foreach($color_wise_report as $color_repot)
                  @php
                    $total_color_output += $color_repot->output_qty_color;
                    $total_color_rejection += $color_repot->sewing_rejection_color;
                  @endphp
                  <tr>
                    <td>{{ $color_repot->buyer }}</td>
                    <td>{{ $color_repot->order }}</td>
                    <td>{{ $color_repot->color }}</td>
                    <td>{{ $color_repot->output_qty_color }}</td>
                    <td>{{ $color_repot->sewing_rejection_color }}</td>
                  </tr>
                @endforeach
                <tr>
                  <td colspan="3" style="font-weight:bold;">Total</td>
                  <td>{{ $total_color_output }}</td>
                  <td>{{ $total_color_rejection }}</td>
                </tr>
              @else
                <tr>
                  <td colspan="5" class="text-danger text-center">Not found
                  <td>
                </tr>
              @endif
              </tbody>
            </table>

          </div>
        </div>
      </div>
    </div>
  </div>

  <style type="text/css">
    @media screen and (-webkit-min-device-pixel-ratio: 0) {

      input[type=date].form-control form-control-sm {
        line-height: 1;
      }
    }
  </style>
@endsection
