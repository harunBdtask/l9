@php
  $tableHeadColorClass = 'tableHeadColor';
  if (isset($type) || request()->has('type') || request()->route('type')) {
    $tableHeadColorClass = '';
  }
@endphp
@extends('sewingdroplets::layout')
@section('title', 'Daily Input Output Report Summary')
@section('content')
  <div class="padding buyer-wise-sewing-report-page">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
            <h2>Daily Input Output Report Summary
              <span class="pull-right">
                                <a href="{{ $order_id ? url('/daily-input-output-report-download/pdf/'.$buyer_id.'/'.$order_id) : '#' }}"><i
                                      style="color: #DC0A0B" class="fa fa-file-pdf-o"></i></a> |
                                <a href="{{ $order_id ? url('/daily-input-output-report-download/xls/'.$buyer_id.'/'.$order_id) : '#' }}"><i
                                      style="color: #0F733B" class="fa fa-file-excel-o"></i></a>
                            </span>
            </h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body color-sewing-output">
            <div class="form-group">
              {!! Form::open(['url' => '/daily-input-output-report','method' => 'GET', 'id' => 'daily-input-output-report-summary-form']) !!}
              <div class="row m-b">
                <div class="col-sm-2">
                  <label>Buyer</label>
                  {!! Form::select('buyer_id', $buyers, $buyer_id, ['class' => 'form-control form-control-sm']) !!}
                </div>
                <div class="col-sm-2">
                  <label>Style/Order</label>
                  {!! Form::select('order_id', $orders, $order_id, ['class' => 'form-control form-control-sm']) !!}
                </div>
                <div class="col-sm-2">
                  <label>&nbsp;</label>
                  <button type="submit" class="btn btn-sm btn-primary form-control form-control-sm">Submit</button>
                </div>
              </div>
              {!! Form::close() !!}
            </div>

            <div id="parentTableFixed" class="table-responsive">
              <table class="reportTable" id="fixTable">
                @includeIf('sewingdroplets::reports.tables.daily_input_output_report_table')
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
    $(function() {
      const buyerSelectDom = $('[name="buyer_id"]');
      const orderSelectDom = $('[name="order_id"]');

      buyerSelectDom.select2({
        ajax: {
          url: '/utility/get-buyers-for-select2-search',
          data: function (params) {
            return {
              search: params.term,
            }
          },
          processResults: function (data, params) {
            return {
              results: data.results,
              pagination: {
                more: false
              }
            }
          },
          cache: true,
          delay: 250
        },
        placeholder: 'Select Buyer',
        allowClear: true
      });

      orderSelectDom.select2({
        ajax: {
          url: function (params) {
            return `/utility/get-styles-for-select2-search`
          },
          data: function (params) {
            const buyerId = buyerSelectDom.val();
            return {
              search: params.term,
              buyer_id: buyerId,
            }
          },
          processResults: function (data, params) {
            return {
              results: data.results,
              pagination: {
                more: false
              }
            }
          },
          cache: true,
          delay: 250
        },
        placeholder: 'Select Style',
        allowClear: true
      });

      $(document).on('change', '[name="buyer_id"]', function (e) {
        let orderId = orderSelectDom.val();
        if (orderId) {
          orderSelectDom.val('').change();
        }
      });
    })
  </script>
@endsection
