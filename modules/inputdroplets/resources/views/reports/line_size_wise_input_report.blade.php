@extends('inputdroplets::layout')
@section('title', 'Line Size Wise Input Report')
@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
            <h2> Line Size Wise Input Report
              <span class="pull-right">
                <a href="{{ (!$order_id && !$date) ? '#' : url("/line-size-wise-input-report/download?type=xls&order_id=$order_id&date=$date")}}" target="_blank">
                  <i style="color: #0F733B" class="fa fa-file-excel-o"></i>
                </a>
              </span>
            </h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body">
            <div class="form-group">
              <div class="row m-b">
                {!! Form::open(['url' => '/line-size-wise-input-report', 'id' => 'line-wise-input-report-form', 'method' => 'GET']) !!}
                <div class="col-sm-3">
                  <label>Style</label>
                  {!! Form::select('order_id', $orders, $order_id ?? null, ['class' => 'form-control form-control-sm', 'onchange' => 'this.form.submit();']) !!}
                </div>
                <div class="col-sm-3">
                  <label>Date</label>
                  {!! Form::date('date', $date ?? null, ['class' => 'form-control form-control-sm', 'onchange' => 'this.form.submit();']) !!}
                </div>
                {!! Form::close() !!}
              </div>
            </div>
            <div id="parentTableFixed" class="table-responsive">
              <table class="reportTable" id="fixTable" style="border-collapse: collapse;">
                @includeIf('inputdroplets::reports.tables.line_size_wise_input_report_table')
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('scripts')
  <script src="{{ asset("protracker/custom.js") }}"></script>
  <script>
    const reportDom = $('#reportData');
    $(function() {
      const orderSelectDom = $('[name="order_id"]');
      orderSelectDom.select2({
        ajax: {
          url: function (params) {
            return `/utility/get-styles-for-select2-search`
          },
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
        placeholder: 'Select Style',
        allowClear: true
      });
    })

  </script>
@endsection