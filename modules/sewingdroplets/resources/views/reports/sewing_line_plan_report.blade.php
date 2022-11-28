@extends('sewingdroplets::layout')
@php
  $tableHeadColorClass = 'tableHeadColor';
  if (isset($type) || request()->has('type') || request()->route('type')) {
    $tableHeadColorClass = '';
  }
@endphp
@section('styles')
  <style>
    .select2-container .select2-selection--single {
      height: 40px;
      border-radius: 0px;
      line-height: 50px;
      border: 1px solid #e7e7e7;
    }

    .reportTable .select2-container .select2-selection--single {
      border: 1px solid #e7e7e7;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
      line-height: 40px;
      width: 100%;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
      top: 8px;
    }

    .error + .select2-container .select2-selection--single {
      border: 1px solid red;
    }

    .select2-container--default .select2-selection--multiple {
      min-height: 40px !important;
      border-radius: 0px;
      width: 100%;
    }
  </style>
@endsection
@section('title', 'Sewing Line Plan Report')
@section('content')
  <div class="padding buyer-wise-sewing-report-page">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
            <h2>Sewing Line Plan Report
              <span class="pull-right">
                {{-- <a href="{{ (isset($from_date) && isset($to_date)) ? url('/sewing-line-plan-report-download?type=pdf&from_date='.$from_date.'&to_date='.$to_date.'&buyer_id='.$buyer_id.'&order_id='.$order_id.'&floor_id='.$floor_id.'&line_id='.$floor_id) : '#' }}"><i
                      style="color: #DC0A0B" class="fa fa-file-pdf-o"></i></a> | --}}
                <a href="{{ (isset($from_date) && isset($to_date)) ? url('/sewing-line-plan-report-download?type=excel&from_date='.$from_date.'&to_date='.$to_date.'&buyer_id='.$buyer_id.'&order_id='.$order_id.'&floor_id='.$floor_id.'&line_id='.$floor_id) : '#' }}"><i
                      style="color: #0F733B" class="fa fa-file-excel-o"></i></a>
              </span>
            </h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body color-sewing-output">
            <div class="form-group">
              {!! Form::open(['url' => '/sewing-line-plan-report','method' => 'GET', 'id' => 'sewing-line-plan-report-form']) !!}
              <div class="row m-b">
                <div class="col-sm-3">
                  <label>From Date<dfn class="text-warning">*</dfn></label>
                  {!! Form::date('from_date', $from_date ?? null, ['class' => 'form-control form-control-sm form-date-input']) !!}
                  @if($errors->has('from_date'))
                    <span class="text-danger">{{ $errors->first('from_date') }}</span>
                  @endif
                </div>
                <div class="col-sm-3">
                  <label>To Date<dfn class="text-warning">*</dfn></label>
                  {!! Form::date('to_date', $to_date ?? null, ['class' => 'form-control form-control-sm form-date-input']) !!}
                  @if($errors->has('to_date'))
                    <span class="text-danger">{{ $errors->first('to_date') }}</span>
                  @endif
                </div>
                <div class="col-sm-3">
                  <label>Buyer</label>
                  {!! Form::select('buyer_id', $buyers, $buyer_id, ['class' => 'form-control form-control-sm', 'placeholder' => 'Select a Buyer']) !!}
                </div>
                <div class="col-sm-3">
                  <label>Style/Order</label>
                  {!! Form::select('order_id', $orders, $order_id, ['class' => 'form-control form-control-sm', 'placeholder' => 'Select a Style/Order']) !!}
                </div>
              </div>
              <div class="row m-b">
                <div class="col-sm-3">
                  <label>Floor</label>
                  {!! Form::select('floor_id', $floors, $floor_id, ['class' => 'form-control form-control-sm', 'placeholder' => 'Select a Floor']) !!}
                </div>
                <div class="col-sm-3">
                  <label>Line</label>
                  {!! Form::select('line_id', $lines, $line_id, ['class' => 'form-control form-control-sm', 'placeholder' => 'Select a Line']) !!}
                </div>
                <div class="col-sm-3">
                  <label>&nbsp;</label>
                  <input type="submit" class="btn btn-sm white btn-block" value="Search">
                </div>
              </div>
              {!! Form::close() !!}
            </div>

            <div id="parentTableFixed" class="table-responsive">
              <table class="reportTable" id="fixTable">
                @includeIf('sewingdroplets::reports.tables.sewing_line_plan_report_table')
              </table>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('scripts')
  <script>
    $(document).ready(function () {
      function setSelect2() {
        $(document).find('select').select2();
      }

      setSelect2();

      $(document).on('change', 'select[name="buyer_id"]', function () {
        var buyer_id = $(this).val();
        var booking_no_dom = $('select[name="order_id"]');
        booking_no_dom.val('').select2();
        if (buyer_id) {
          $.ajax({
            url: '/utility/get-orders-with-booking-no/' + buyer_id,
            type: 'GET'
          }).done(function (response) {

            $.each(booking_no_dom, function (index, domElement) {
              domElement.innerHTML = "";
              var bookingNoDropdown = '<option value="">Select a Style/Order</option>';
              if (Object.keys(response.data).length > 0) {
                $.each(response.data, function (index, order) {
                  bookingNoDropdown += '<option value="' + order.id + '">' + order.style_name + '</option>';
                });
              }
              domElement.innerHTML = bookingNoDropdown;
              domElement.value = '';
            });

            setSelect2();
          }).fail(function (response) {
            console.log(response.responseJSON);
          });
        }
      });

      $(document).on('change', 'select[name="floor_id"]', function (e) {
        var floor_id = $(this).val();
        var line_id_dom = $('select[name="line_id"]');
        line_id_dom.val('').select2();
        if (floor_id) {
          $.ajax({
            url: '/get-lines/' + floor_id,
            type: 'GET'
          }).done(function (response) {

            $.each(line_id_dom, function (index, domElement) {
              domElement.innerHTML = "";
              var lineNoDropdown = '<option value="">Select a Line</option>';
              if (Object.keys(response.data).length > 0) {
                $.each(response.data, function (index, line) {
                  lineNoDropdown += '<option value="' + line.id + '">' + line.line_no + '</option>';
                });
              }
              domElement.innerHTML = lineNoDropdown;
              domElement.value = '';
            });

            setSelect2();
          }).fail(function (response) {
            console.log(response.responseJSON);
          });
        }
      });
    });
  </script>
@endsection
