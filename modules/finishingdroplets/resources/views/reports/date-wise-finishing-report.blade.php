@php
    $tableHeadColorClass = 'tableHeadColor';
    if (isset($type) || request()->has('type') || request()->route('type')) {
      $tableHeadColorClass = '';
    }
@endphp
@extends('finishingdroplets::layout')
@section('title', 'Date Wise Finishing Report')
@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
            <h2>Date Wise Finishing Report || {{ date("D\ - F d- Y") }}  <span class="pull-right"><a href="{{url('date-wise-finishing-report-download/pdf/'.$from_date.'/'.$to_date)}}"><i style="color: #DC0A0B" class="fa fa-file-pdf-o"></i></a> | <a href="{{url('date-wise-finishing-report-download/xls/'.$from_date.'/'.$to_date)}}"><i style="color: #0F733B" class="fa fa-file-excel-o"></i></a></span>
        </h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body">

              <form action="{{ url('/date-wise-finishing-report-post') }}" method="get">
                @csrf
                <div class="form-group">
                  <div class="row m-b">
                    <div class="col-sm-2">
                        <label>From Date</label>
                        <input type="date" name="from_date" class="form-control form-control-sm" required="required"  value="{{ $from_date ?? date('Y-m-d') }}">
                        @if($errors->has('from_date'))
                          <span class="text-danger">{{ $errors->first('from_date') }}</span>
                        @endif
                    </div>
                     <div class="col-sm-2">
                        <label>To Date</label>
                        <input type="date" name="to_date" class="form-control form-control-sm" required="required" value="{{ $to_date ?? date('Y-m-d') }}">

                        @if($errors->has('to_date'))
                          <span class="text-danger">{{ $errors->first('to_date') }}</span>
                        @endif
                    </div>
                    <div class="col-sm-2">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn btn-sm btn-info form-control form-control-sm">Search</button>
                    </div>
                  </div>
                </div>
              </form>

              <table class="reportTable">
                <thead>
                  <tr>
                    <th colspan="2" style="font-size: 14px; font-weight: bold">Section-1 : Buyer Wise Report</th>
                  </tr>
                  <tr>
                    <th>Buyer</th>
                    <th>Finishing Received</th>
                  </tr>
                </thead>
              <tbody class="color-wise-report">
               @if(!empty($buyer_wise_report))
                  @php
                      $total_finished_qty = 0;
                  @endphp
                  @foreach($buyer_wise_report as $buyer)
                    @php
                       $total_finished_qty += $buyer['finished_qty'];
                    @endphp
                    <tr>
                      <td>{{ $buyer['buyer'] }}</td>
                      <td>{{ $buyer['finished_qty'] }}</td>
                    </tr>
                  @endforeach
                  <tr style="font-weight:bold;">
                    <td>Total</td>
                    <td>{{ $total_finished_qty }}</td>
                  </tr>
                @else
                  <tr>
                    <td colspan="2" class="text-danger text-center">Not found</td>
                  </tr>
                @endif
              </tbody>
            </table>

            <!-- line wise report -->
            <table class="reportTable">
              <thead>
                <tr>
                  <th colspan="4" style="font-size: 14px; font-weight: bold">Section-2 : Order Wise Report</th>
                </tr>
                <tr>
                  <th>Buyer</th>
                  <th>Style/Order No</th>
                  <th>PO</th>
                  <th>Finishing Received</th>
                </tr>
              </thead>
              <tbody>
             @if(!empty($order_wise_report))
                  @php
                      $total_order_finished_qty = 0;
                  @endphp
                  @foreach($order_wise_report as $order_wise)
                    @php
                       $total_order_finished_qty += $order_wise['order_finished_qty'];
                    @endphp
                    <tr>
                      <td>{{ $order_wise['buyer'] }}</td>
                      <td>{{ $order_wise['style'] }}</td>
                      <td>{{ $order_wise['order'] }}</td>
                      <td>{{ $order_wise['order_finished_qty'] }}</td>
                    </tr>
                  @endforeach
                  <tr style="font-weight:bold;">
                    <td colspan="3">Total</td>
                    <td>{{ $total_order_finished_qty }}</td>
                  </tr>
                @else
                  <tr>
                    <td colspan="4" class="text-danger text-center">Not found</td>
                  </tr>
                @endif
              </tbody>
            </table>
            <!-- buyer order wise report -->
            <table class="reportTable">
              <thead>
                <tr>
                  <th colspan="5" style="font-size: 14px; font-weight: bold">Section-3 : Color Wise Report</th>
                </tr>
                <tr>
                  <th>Buyer</th>
                  <th>Style/Order No</th>
                  <th>PO</th>
                  <th>Color</th>
                  <th>Finishing Received</th>
                </tr>
              </thead>
              <tbody>
              @if(!empty($color_wise_report))
                  @php
                      $total_color_finished_qty = 0;
                  @endphp
                  @foreach($color_wise_report as $color_wise)
                    @php
                       $total_color_finished_qty += $color_wise['color_finished_qty'];
                    @endphp
                    <tr>
                      <td>{{ $color_wise['buyer'] }}</td>
                      <td>{{ $color_wise['style'] }}</td>
                      <td>{{ $color_wise['order'] }}</td>
                      <td>{{ $color_wise['color'] }}</td>
                      <td>{{ $color_wise['color_finished_qty'] }}</td>
                    </tr>
                  @endforeach
                  <tr style="font-weight:bold;">
                    <td colspan="4">Total</td>
                    <td>{{ $total_color_finished_qty }}</td>
                  </tr>
                @else
                  <tr>
                    <td colspan="5" class="text-danger text-center">Not found</td>
                  </tr>
                @endif
              </tbody>
            </table>

            <!-- color order wise report -->
            <table class="reportTable">
              <thead>
                <tr>
                  <th colspan="6" style="font-size: 14px; font-weight: bold">Section-4 : Size Wise Report</th>
                </tr>
                <tr>
                  <th>Buyer</th>
                  <th>Style/Order No</th>
                  <th>PO</th>
                  <th>Color</th>
                  <th>Size</th>
                  <th>Finishing Received</th>
                </tr>
              </thead>
              <tbody>
              @if(!empty($size_wise_report))
                  @php
                      $total_size_finished_qty = 0;
                  @endphp
                  @foreach($size_wise_report as $size_wise)
                    @php
                       $total_size_finished_qty += $size_wise['size_finished_qty'];
                    @endphp
                    <tr>
                      <td>{{ $size_wise['buyer'] }}</td>
                      <td>{{ $size_wise['style'] }}</td>
                      <td>{{ $size_wise['order'] }}</td>
                      <td>{{ $size_wise['color'] }}</td>
                      <td>{{ $size_wise['size'] }}</td>
                      <td>{{ $size_wise['size_finished_qty'] }}</td>
                    </tr>
                  @endforeach
                  <tr style="font-weight:bold;">
                    <td colspan="5">Total</td>
                    <td>{{ $total_size_finished_qty }}</td>
                  </tr>
                @else
                  <tr>
                    <td colspan="6" class="text-danger text-center">Not found</td>
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
