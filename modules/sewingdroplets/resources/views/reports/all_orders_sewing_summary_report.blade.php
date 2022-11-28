@php
  $tableHeadColorClass = 'tableHeadColor';
  if (isset($type) || request()->has('type') || request()->route('type')) {
    $tableHeadColorClass = '';
  }
@endphp
@extends('sewingdroplets::layout')
@section('title', 'All PO\'s Sewing Output Summary')
@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
            @php
              $currentPage = $order_wise_report ? $order_wise_report->currentPage() : 1;
              $orderId = request('order_id');
            @endphp
            <h2>All PO's Sewing Output Summary || {{ date("jS F, Y") }}
              <span class="pull-right">
                <a href="{{ url('/all-orders-sewing-output-report-download?type=pdf'.'&page='.$currentPage.'&order_id='.$orderId.'&sustainable_material='. request('sustainable_material')) }}">
                  <em style="color: #DC0A0B" class="fa fa-file-pdf-o"></em></a>
                  |
                  <a href="{{ url('/all-orders-sewing-output-report-download?type=xls'.'&page='.$currentPage.'&order_id='.$orderId.'&sustainable_material='. request('sustainable_material')) }}">
                    <em style="color: #0F733B" class="fa fa-file-excel-o"></em>
                  </a>
                </span>
            </h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body">
            {!! Form::open(['url' => '/all-orders-sewing-output-summary', 'method' => 'get']) !!}
            <div class="form-group">
              <div class="row m-b">
                  <div class="col-sm-2">
                      {!! Form::select('buyer_id', $buyers, request('buyer_id') ?? null, ['class' => 'form-control form-control-sm']) !!}
                  </div>
                <div class="col-sm-2">
                  {!! Form::select('order_id', $orders, request('order_id') ?? null, ['class' => 'form-control form-control-sm']) !!}
                </div>
                <div class="col-sm-2">
                  {!! Form::select('sustainable_material', $sustainable_materials, request('sustainable_material'), ['class' => 'form-control form-control-sm c-select select2-input']) !!}
                </div>
                <div class="col-sm-2">
                  {!! Form::select('year', collect(years())->prepend('Select Year', ''), request('year'), ['class' => 'form-control form-control-sm c-select select2-input']) !!}
                </div>
                <div class="col-sm-2">
                    {!! Form::select('month', collect(months())->prepend('Select Month', ''), request('month'), ['class' => 'form-control form-control-sm c-select select2-input']) !!}
                </div>
                <div class="col-sm-2">
                    <button class="btn btn-sm btn-info">
                        <i class="fa fa-search"></i> Search
                    </button>
                </div>
              </div>
            </div>
            {!! Form::close() !!}

            <div id="parentTableFixed" class="table-responsive">
              <table class="reportTable" id="fixTable">
                @include('sewingdroplets::reports.tables.order-wise-report-table')
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
    const orderSelectDom = $('[name="order_id"]');
    const buyerSelectDom = $('[name="buyer_id"]');

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

      buyerSelectDom.select2({
        ajax: {
          url: function (params) {
            return `/utility/get-buyers-for-select2-search`
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
        placeholder: 'Select Buyer',
        allowClear: true
      });
  })
</script>
@endsection
