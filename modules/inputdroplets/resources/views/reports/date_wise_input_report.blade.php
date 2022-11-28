@php
    $tableHeadColorClass = 'tableHeadColor';
    if (isset($type) || request()->has('type') || request()->route('type')) {
      $tableHeadColorClass = '';
    }
@endphp
@extends('inputdroplets::layout')
@section('title', 'Date Wise Sewing Input')
@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
            <h2>Date Wise Sewing input || {{ date("jS F, Y") }} <span class="pull-right"><a href="{{url('/date-wise-sewing-input-download/pdf/'.($date ?? null))}}"><i style="color: #DC0A0B" class="fa fa-file-pdf-o"></i></a> | <a  href="{{url('/date-wise-sewing-input-download/xls/'.($date ?? null))}}"><i style="color: #0F733B" class="fa fa-file-excel-o"></i></a></span>
        </h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body">

              <form action="{{ url('/date-wise-sewing-input') }}" method="post">
                @csrf
                <div class="form-group">
                  <div class="row m-b">
                    <div class="col-sm-2">
                        <input type="date" name="date" class="form-control form-control-sm" required="required" value="{{ $date ?? null }}">
                    </div>
                    <div class="col-sm-2">
                        <button type="submit" class="btn btn-sm white form-control form-control-sm">Search</button>
                    </div>
                  </div>
                </div>
              </form>

              <!-- challan no wise -->
              <table class="reportTable">
                <thead>
                  <tr><th colspan="9">Section-1 : Challan No. Wise Sewing Input Status</th></tr>
                  <tr>
                    <th>Unit</th>
                    <th>Line</th>
                    <th>Challan No.</th>
                    <th>Buyer</th>
                    <th>Style</th>
                    <th>PO</th>
                    <th>Color</th>
                    <th>Input Qty</th>
                    <th>Time of Input</th>
                  </tr>
                </thead>
                <tbody class="color-wise-report">
                  @if(!empty($date_wise_input))
                    @php
                      $total_input = 0;
                    @endphp
                    @foreach($date_wise_input as $report)
                      @php
                        $input = 0;
                        foreach($report->cutting_inventory as $inventory){
                           $input += $inventory->bundlecard->quantity - $inventory->bundlecard->total_rejection - $inventory->bundlecard->print_rejection;
                        }
                        $total_input += $input;
                      @endphp
                      <tr>
                        <td>{{ $report->line->floor->floor_no ?? '' }}</td>
                        <td>{{ $report->line->line_no ?? '' }}</td>
                        <td>{{ $report->challan_no ?? '' }}</td>
                        <td>{{ $report->order->buyer->name ?? '' }}</td>
                        <td>{{ $report->order->style->name ?? '' }}</td>
                        <td>{{ $report->order->order_no ?? '' }}</td>
                        <td>{{ $report->color->name ?? '' }}</td>
                        <td>{{  $input }}</td>
                        <td>{{ date('g:i A', strtotime($report->updated_at)) }}</td>
                      </tr>
                    @endforeach
                      <tr style="font-weight:bold;">
                        <td colspan="7">Total</td>
                        <td>{{ $total_input }}</td>
                        <td></td>
                      </tr>
                  @else
                    <tr>
                      <td colspan="9" class="text-danger text-center">Not found<td>
                    </tr>
                  @endif
                </tbody>
              </table>

              <!-- line wise -->
              <table class="reportTable">
                <thead>
                  <tr><th colspan="3">Section-2 : Line Wise Input Status</th></tr>
                  <tr>
                    <th>Unit</th>
                    <th>Line</th>
                    <th>Input Quantity</th>
                  </tr>
                </thead>
                <tbody class="color-wise-report">
                  @if(!empty($date_wise_input))
                    @php
                      $total_input_line = 0;
                    @endphp
                    @foreach($date_wise_input->groupBy('line_id') as $report_line_wise)
                      @php
                        $line_unique = $report_line_wise->first();
                        $input_line = 0;
                        foreach($report_line_wise as $report1){
                          foreach($report1->cutting_inventory as $inventory){
                            $input_line += $inventory->bundlecard->quantity - $inventory->bundlecard->total_rejection - $inventory->bundlecard->print_rejection;;
                          }
                        }
                        $total_input_line += $input_line;
                      @endphp
                      <tr>
                        <td>{{ $line_unique->line->floor->floor_no ?? '' }}</td>
                        <td>{{ $line_unique->line->line_no ?? '' }}</td>
                        <td>{{ $input_line }}</td>
                      </tr>
                    @endforeach
                      <tr style="font-weight:bold;">
                        <td colspan="2">Total</td>
                        <td>{{ $total_input_line }}</td>
                      </tr>
                  @else
                    <tr>
                      <td colspan="3" class="text-danger text-center">Not found<td>
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
      @media screen and (-webkit-min-device-pixel-ratio: 0){

      input[type=date].form-control form-control-sm{
        line-height: 1;
      }
      }
  </style>
@endsection
