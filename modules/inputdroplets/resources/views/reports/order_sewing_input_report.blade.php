@php
  $tableHeadColorClass = 'tableHeadColor';
  if (isset($type) || request()->has('type') || request()->route('type')) {
    $tableHeadColorClass = '';
  }
@endphp
@extends('inputdroplets::layout')
@section('title', 'Order Wise Input Report')
@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
            @php
              $currentPage = $reports ? $reports->currentPage() : 1;
              $orderId = request('order_id');
            @endphp
            <h2>Order Wise Input Report || {{ date("jS F, Y") }}
              <span class="pull-right">
                <a href="{{ url('/order-wise-sewing-input-report-download?type=pdf'.'&page='.$currentPage.'&order_id='.request('order_id')) }}">
                  <i style="color: #DC0A0B" class="fa fa-file-pdf-o"></i></a>
                  |
                  <a href="{{ url('/order-wise-sewing-input-report-download?type=xls'.'&page='.$currentPage.'&order_id='.request('order_id')) }}">
                    <i style="color: #0F733B" class="fa fa-file-excel-o"></i>
                  </a>
                </span>
            </h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body">
            {!! Form::open(['url' => '/order-wise-sewing-input-report', 'method' => 'get']) !!}
            <div class="form-group">
              <div class="row m-b">
                <div class="col-sm-3">
                  {!! Form::select('order_id', $orders ?? [], request('order_id') ?? null, ['id' => 'cllr-buyer-select', 'class' => 'form-control form-control-sm', "onChange" => "this.form.submit()"]) !!}
                </div>
              </div>
            </div>
            {!! Form::close() !!}

            <div id="parentTableFixed" class="table-responsive">
              <table class="reportTable" id="fixTable">
                @includeIf('inputdroplets::reports.tables.order_sewing_input_report_table')
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
        $('select').select2();
        registerSelect2Async();
      }


      const registerSelect2Async = () => {

        $('#cllr-buyer-select').select2({
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
              orders = data;
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
          placeholder: 'Select Style/Order',
          allowClear: true
        })
      }

      setSelect2();
    });
  </script>
@endsection
