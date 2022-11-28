@extends('finishingdroplets::layout')
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
@section('title', 'Date Wise Finishing Summary Report')

@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header text-center">
                        <h2>Date Wise Finishing Summary Report
                            <span class="pull-right">
                                <a href="{{ isset($current_date) ? url('/date-wise-finishing-summary-report-download?type=pdf&current_date='.$current_date.'&buyer_id='.$buyer_id.'&order_id='.$order_id) : '#' }}"><i
                                            style="color: #DC0A0B" class="fa fa-file-pdf-o"></i></a> |
                                <a href="{{ isset($current_date) ? url('/date-wise-finishing-summary-report-download?type=excel&current_date='.$current_date.'&buyer_id='.$buyer_id.'&order_id='.$order_id) : '#' }}"><i
                                            style="color: #0F733B" class="fa fa-file-excel-o"></i></a>
                            </span>
                        </h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body color-sewing-output">
                        <div class="form-group">
                            {!! Form::open(['url' => '/date-wise-finishing-summary-report','method' => 'GET', 'id' => 'date-wise-finishing-summary-report-form']) !!}
                            <div class="row m-b">
                                <div class="col-sm-3">
                                    <label>Date<dfn class="text-warning">*</dfn></label>
                                    {!! Form::date('current_date', $current_date ?? null, ['class' => 'form-control form-control-sm form-date-input']) !!}
                                    @if($errors->has('current_date'))
                                        <span class="text-danger">{{ $errors->first('current_date') }}</span>
                                    @endif
                                </div>
                                <div class="col-sm-3">
                                    <label>Buyer<dfn class="text-warning">*</dfn></label>
                                    {!! Form::select('buyer_id', $buyers, $buyer_id, ['class' => 'form-control form-control-sm']) !!}
                                </div>
                                <div class="col-sm-3">
                                    <label>Style/Order<dfn class="text-warning">*</dfn></label>
                                    {!! Form::select('order_id', $orders, $order_id, ['class' => 'form-control form-control-sm']) !!}
                                </div>
                                <div class="col-sm-3">
                                    <label>&nbsp;</label>
                                    <input type="submit" class="form-control form-control-sm btn btn-primary" value="Search">
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>

                        <div id="parentTableFixed" class="table-responsive">
                            <table class="reportTable" id="fixTable">
                                @includeIf('finishingdroplets::reports.tables.date_wise_finishing_summary_report_table')
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
<script src="{{ asset('protracker/custom.js') }}"></script>
<script>
  $(function () {
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
      placeholder: 'Select Style/Order',
      allowClear: true
    });
  });
</script>
@endsection
