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
@section('title', "Style Wise Finishing Summary Report")
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header text-center">
                        <h2>Style Wise Finishing Summary Report
                            {{-- <span class="pull-right">
                                <a href="{{ url('/finishing-summary-report-download?type=pdf&&buyer_id='.$buyer_id.'&order_id='.$order_id) }}"><i
                                            style="color: #DC0A0B" class="fa fa-file-pdf-o"></i></a> |
                                <a href="{{ url('/finishing-summary-report-download?type=excel&&buyer_id='.$buyer_id.'&order_id='.$order_id) }}"><i
                                            style="color: #0F733B" class="fa fa-file-excel-o"></i></a>
                            </span> --}}
                        </h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body color-sewing-output">
                        <div class="form-group">
                            {!! Form::open(['url' => '/style-wise-finishing-summary-report','method' => 'GET', 'id' => 'finishing-summary-report-form']) !!}
                            <div class="row m-b">
                                <div class="col-sm-3">
                                    <label>Buyer<dfn class="text-warning">*</dfn></label>
                                    {!! Form::select('buyer_id', $buyers, $buyer_id, ['class' => 'form-control form-control-sm', 'required']) !!}
                                </div>
                                <div class="col-sm-3">
                                    <label>Style/Order<dfn class="text-warning">*</dfn></label>
                                    {!! Form::select('order_id', $orders, $order_id, ['class' => 'form-control form-control-sm', 'required']) !!}
                                </div>
                                <div class="col-sm-2">
                                    <label>&nbsp;</label>
                                    <input type="submit" style="background-color: rgb(167, 227, 249); color: #000000"
                                           class="form-control form-control-sm btn btn-sm white" value="Search">
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>

                        <div id="parentTableFixed" class="table-responsive">
                            <table class="reportTable" id="fixTable">
                                @includeIf('finishingdroplets::reports.tables.style_wise_finishing_summary_report_table')
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
      buyerSelectDom.change(() => {
        orderSelectDom.empty().val('').change();
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
      placeholder: 'Select a Style/Order',
      allowClear: true
    });
    })
  </script>
@endsection
