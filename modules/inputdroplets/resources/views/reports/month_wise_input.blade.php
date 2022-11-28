@php
  $tableHeadColorClass = 'tableHeadColor';
  if (isset($type) || request()->has('type') || request()->route('type')) {
    $tableHeadColorClass = '';
  }
@endphp
@extends('inputdroplets::layout')
@section('styles')
  <style>
    .select2-container--default .select2-selection--single {
      height: 37px !important;
      border-radius: 0px !important;
      border-color: rgba(120, 130, 140, 0.2) !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
      line-height: 35px !important;
      width: 120px !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
      height: 33px;
    }

    @media screen and (-webkit-min-device-pixel-ratio: 0) {
      input[type=date].form-control form-control-sm {
        line-height: 1;
      }
    }
  </style>
@endsection
@section('title', 'Date Range/Month Wise Sewing Input Report')
@section('content')
  <div class="padding month-wise-input-report-page">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
            <h2>Date Range/Month Wise Sewing Input Report <span class="pull-right"><a
                    href="{{$line_id ? url('/month-wise-sewing-input-download/pdf/'.($from_date ?? null).'/'.($to_date ?? null).'/'.($line_id ?? null)) : '#' }}"><i
                      style="color: #DC0A0B" class="fa fa-file-pdf-o"></i></a> | <a
                    href="{{$line_id ? url('/month-wise-sewing-input-download/xls/'.($from_date ?? null).'/'.($to_date ?? null).'/'.($line_id ?? null)) : '#'}}"><i
                      style="color: #0F733B" class="fa fa-file-excel-o"></i></a></span>
            </h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body">
            <form action="{{ url('/date-range-or-month-wise-sewing-input') }}" method="get">
              <div class="form-group">
                <div class="row m-b">
                  <div class="col-sm-2">
                    <label>Floor</label>
                    {!! Form::select('floor_id', $floors, $floor_id ?? null, ['class' => 'select2-input month-floor-dropdown form-control form-control-sm c-select', 'required', 'id' => 'floor', 'placeholder' => 'Select a floor']) !!}

                    @if($errors->has('floor_id'))
                      <span class="text-danger">{{ $errors->first('floor_id') }}</span>
                    @endif
                  </div>

                  <div class="col-sm-2">
                    <label>Line</label>
                    {!! Form::select('line_id', $lines ?? [], $line_id ?? null, ['class' => 'month-lines-dropdown form-control form-control-sm select2-input', 'required', 'id' => 'line_id', 'placeholder' => 'Select a Line']) !!}

                    @if($errors->has('line_id'))
                      <span class="text-danger">{{ $errors->first('line_id') }}</span>
                    @endif
                  </div>
                  <div class="col-sm-3">
                    <label>From Date</label>
                    <input type="date" name="from_date" class="form-control form-control-sm" value="{{ $from_date ?? '' }}"
                           required="required">

                    @if($errors->has('from_date'))
                      <span class="text-danger">{{ $errors->first('from_date') }}</span>
                    @endif
                  </div>
                  <div class="col-sm-3">
                    <label>To Date</label>
                    <input type="date" name="to_date" class="form-control form-control-sm" value="{{ $to_date ?? '' }}"
                           required="required">

                    @if($errors->has('to_date'))
                      <span class="text-danger">{{ $errors->first('to_date') }}</span>
                    @endif
                    @if(Session::has('error'))
                      <span class="text-danger">{{ Session::get('error') }}</span>
                    @endif
                  </div>
                  <div class="col-sm-2">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-sm white form-control form-control-sm">Search</button>
                  </div>
                </div>
              </div>
            </form>

            <!-- challan no wise -->
            <table class="reportTable">
              <thead>
              <tr>
                <th colspan="6">Section-1 : Challan No. Wise Sewing Input Status</th>
              </tr>
              <tr>
                <th>Line</th>
                <th>Challan No.</th>
                <th>Buyer</th>
                <th>Order/Style</th>
                <th>PO</th>
                <th>Input Qty</th>
              </tr>
              </thead>
              <tbody class="color-wise-report">
              @if(!empty($date_wise_input))
                @php
                  $total_input = 0;
                @endphp
                @foreach($date_wise_input as $report)
                  @php
                    $po_wise_input = $report->quantity_sum - $report->total_rejection_sum - $report->print_rejection_sum;
                    $total_input += $po_wise_input;
                  @endphp
                  <tr>
                    <td>{{ $report->line->line_no ?? '' }}</td>
                    <td>{{ $report->challan_no ?? '' }}</td>
                    <td>{{ $report->order->buyer->name ?? '' }}</td>
                    <td>{{ $report->order->style_name ?? '' }}</td>
                    <td>{{ $report->purchaseOrder->po_no ?? '' }}</td>
                    <td>{{  $po_wise_input }}</td>
                  </tr>
                @endforeach
                <tr style="font-weight:bold;">
                  <td colspan="5">Total</td>
                  <td>{{ $total_input }}</td>
                </tr>
              @else
                <tr>
                  <td colspan="5" class="text-danger text-center">Not found
                  <td>
                </tr>
              @endif
              </tbody>
            </table>

            <!-- line wise -->
            <table class="reportTable">
              <thead>
              <tr>
                <th colspan="2">Section-2 : Line Wise Input Status</th>
              </tr>
              <tr>
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
                    $input_line = $report_line_wise->sum('quantity_sum') - $report_line_wise->sum('total_rejection_sum') - $report_line_wise->sum('print_rejection_sum');
                    $total_input_line += $input_line;
                  @endphp
                  <tr>
                    <td>{{ $line_unique->line->line_no ?? '' }}</td>
                    <td>{{ $input_line }}</td>
                  </tr>
                @endforeach
                <tr style="font-weight:bold;">
                  <td>Total</td>
                  <td>{{ $total_input_line }}</td>
                </tr>
              @else
                <tr>
                  <td colspan="2" class="text-danger text-center">Not found
                  </td>
                </tr>
              @endif
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('scripts')
<script src="{{ asset('protracker/custom.js') }}"></script>
<script>
  $(function() {
    const floorDom = $('[name="floor_id"]');
    const lineDom = $('[name="line_id"]');

// month input get line after floor select
    $(document).on('change', '[name="floor_id"]', function (e) {
      e.preventDefault();
      var floor_id = $(this).val();
      lineDom.empty();
      if (floor_id) {
        $.ajax({
          type: 'GET',
          url: '/get-lines-for-dropdown/' + floor_id,
          success: function (response) {
            var linesDropdown = '<option value="">Select a Lines</option>';
            if (Object.keys(response.data).length > 0) {
              $.each(response.data, function (index, val) {
                linesDropdown += '<option value="' + index + '">' + val + '</option>';
              });
              lineDom.html(linesDropdown);
            }
          }
        });
      }
    });
  });
</script>
@endsection
