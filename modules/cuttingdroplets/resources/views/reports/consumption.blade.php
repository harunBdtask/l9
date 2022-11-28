@extends('cuttingdroplets::layout')
@php
  $tableHeadColorClass = 'tableHeadColor';
  if (isset($type) || request()->has('type') || request()->route('type')) {
    $tableHeadColorClass = '';
  }
@endphp
@section('title', 'Consumption Report')
@section('content')
  <div class="padding">
    <div class="box">
      <div class="box-header">
        @php
          $currentPage = $bundleCardGenerationDetails ? $bundleCardGenerationDetails->currentPage() : 1;
        @endphp
        <h2>Consumption Report
          <span class="pull-right">
                        <a href="{{url('consumption-report-download?type=pdf&current_page='.$currentPage.'&buyer_id='.$buyer_id.'&order_id='.$order_id)}}">
                            <i style="color: #DC0A0B" class="fa fa-file-pdf-o"></i>
                        </a>
                        |
                        <a href="{{url('consumption-report-download?type=xls&current_page='.$currentPage.'&buyer_id='.$buyer_id.'&order_id='.$order_id)}}">
                            <i style="color: #0F733B" class="fa fa-file-excel-o"></i>
                        </a>
                    </span>
        </h2>
      </div>
      <div class="box-divider m-a-0"></div>
      <div class="box-body">
        <div class="flash-message print-delete">
          @foreach (['danger', 'warning', 'success', 'info'] as $msg)
            @if(Session::has('alert-' . $msg))
              <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</p>
            @endif
          @endforeach
        </div>
        {!! Form::open(['url' => 'consumption-report', 'method' => 'GET', 'autocomplete' => 'off']) !!}
        <div class="form-group">
          <div class="col-sm-3">
            <label>Buyer</label>
            {!! Form::select('buyer_id', $buyers ?? [], request('buyer_id') ?? null, ['class' => 'form-control form-control-sm']) !!}
          </div>
          <div class="col-md-3">
            <label>Style</label>
            {!! Form::select('order_id', $orders ?? [], request('order_id') ?? null, ['class' => 'form-control form-control-sm']) !!}
          </div>
          <div class="col-md-2">
            <label>&nbsp;</label>
            <button class="btn btn-sm white m-b form-control form-control-sm" type="submit" style="height: 28px; line-height: 14px">GO
            </button>
          </div>
        </div>
        {!! Form::close() !!}
        <div id="parentTableFixed" class="table-responsive">
          <table class='reportTable' id="fixTable">
            @includeIf('cuttingdroplets::reports.tables.consumption_report_table')
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('scripts')
  <script>
    $(function () {
      let buyerSelectDom = $('[name="buyer_id"]');
      let orderSelectDom = $('[name="order_id"]');
      let orders;

      $('.date-field').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        clearBtn: true
      });

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
              buyer_id: buyerId
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
        placeholder: 'Select Style',
        allowClear: true
      });

      $(document).on('change', 'select[name="buyer_id"]', function () {
        let orderId = orderSelectDom.val();
        if (orderId) {
          orderSelectDom.val('').change();
        }
      });
    });

  </script>
@endsection
