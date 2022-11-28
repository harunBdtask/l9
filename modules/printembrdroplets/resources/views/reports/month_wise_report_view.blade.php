@php
  $tableHeadColorClass = 'tableHeadColor';
  if (isset($type) || request()->has('type') || request()->route('type')) {
    $tableHeadColorClass = '';
  }
@endphp
@extends('printembrdroplets::layout')
@section('title', 'Month Wise Wise Print Send Receive Report')
@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
            <h2>Month Wise Wise Print Send Receive Report @if(isset($from_date) && isset($to_date))
                || {{ date("jS F, Y", strtotime($from_date)).' To '. date("jS F, Y", strtotime($to_date)) }} @endif
            </h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body">

            <table class="reportTable">
              <thead>
              <tr>
                <th>Buyer</th>
                <th>Style/Order No</th>
                <th>PO</th>
                <th>Colour Name</th>
                <th>Size Name</th>
                <th>Send Quantity</th>
              </tr>
              </thead>
              <tbody class="color-wise-report">
              @if(count($report_data) > 1)
                @foreach($report_data as $report)
                  <tr>
                    <td>{{ $report['buyer'] }}</td>
                    <td>{{ $report['style'] }}</td>
                    <td>{{ $report['order'] }}</td>
                    <td>{{ $report['color'] }}</td>
                    <td>{{ $report['size'] }}</td>
                    <td>{{ $report['send_qty'] }}</td>
                  </tr>
                @endforeach
              @else
                <tr>
                  <td colspan="6" class="text-danger text-center">Not found
                  <td>
                </tr>
              @endif
              </tbody>
            </table>

            <table class="reportTable">
              <thead>
              <tr>
                <th>Buyer</th>
                <th>Style/Order No</th>
                <th>PO</th>
                <th>Send Quantity</th>
              </tr>
              </thead>
              <tbody class="color-wise-report">
              @if(count($bundle_details) > 1)
                @foreach($bundle_details as $report)
                  <tr>
                    <td>{{ $report->buyer->name }}</td>
                    <td>{{ $report->order->order_style_no }}</td>
                    <td>{{ $report->purchaseOrder->po_no }}</td>
                    <td>{{ $report->bundleCards->sum('quantity') }}</td>
                  </tr>
                @endforeach
              @else
                <tr>
                  <td colspan="4" class="text-danger text-center">Not found
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
