@php
  $tableHeadColorClass = 'tableHeadColor';
  if (isset($type) || request()->has('type') || request()->route('type')) {
    $tableHeadColorClass = '';
  }
@endphp
@extends('sewingdroplets::layout')
@section('styles')
  <style>
    #loader {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(226, 226, 226, 0.75) no-repeat center center;
      width: 100%;
      z-index: 1000;
    }

    .spin-loader {
      position: relative;
      top: 46%;
      left: 5%;
    }
  </style>
@endsection
@section('title', 'Line & Date Wise Sewing Input Output Average')
@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
            <h2>Line &amp; Date Wise Sewing Input Output Average
              <span class="pull-right">
                                <a download-type="pdf" class="line-date-wise-output-avg-report-dwnld-btn">
                                  <i style="color: #DC0A0B" class="fa fa-file-pdf-o"></i>
                                </a>
                                |
                                <a download-type="xls" class="line-date-wise-output-avg-report-dwnld-btn">
                                  <i style="color: #0F733B" class="fa fa-file-excel-o"></i>
                                </a>
                            </span>
            </h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body line-date-avg">
            <div class="form-group">
              <div class="row m-b">
                <div class="col-sm-2">
                  <label>Buyer</label>
                  {!! Form::select('buyer_id', [], null, ['class' => 'form-control form-control-sm']) !!}
                </div>
                <div class="col-sm-2">
                  <label>Style/Order</label>
                  {!! Form::select('order_id', [], null, ['class' => 'form-control form-control-sm']) !!}
                </div>
                <div class="col-sm-2">
                  <label>PO</label>
                  {!! Form::select('purchase_order_id', [], null, ['class' => 'form-control form-control-sm']) !!}
                </div>
              </div>
            </div>

            <div id="parentTableFixed" class="table-responsive">
              <table class="reportTable line-date-wise-avg-report-table {{ $tableHeadColorClass }}" id="fixTable">
                <thead>
                  <tr style="background-color: #cbffb5;">
                    <th>PO</th>
                    <th>Floor No.</th>
                    <th>Line No.</th>
                    <th>Color</th>
                    <th>Total Sewing Input</th>
                    <th>Total Sewing Output</th>
                    <th>Date</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <th class="text-center text-danger" colspan="7">No Data Found</th>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div id="loader">
      <div class="text-center spin-loader"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i></div>
    </div>
  </div>
@endsection
@section('scripts')
  <script>
    $(function() {
      const buyerSelectDom = $('[name="buyer_id"]');
      const orderSelectDom = $('[name="order_id"]');
      const poSelectDom = $('[name="purchase_order_id"]');
      const reportDom = $('.line-date-wise-avg-report-table');
      const loader = $('#loader');

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

      poSelectDom.select2({
        ajax: {
          url: '/utility/get-pos-for-select2-search',
          data: function (params) {
            const orderId = orderSelectDom.val();
            return {
              order_id: orderId,
              search: params.term
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
        placeholder: 'Select PO',
        allowClear: true
      });

      $(document).on('change', '[name="buyer_id"]', function (e) {
        let orderId = orderSelectDom.val();
        let poId = poSelectDom.val();
        if (orderId) {
          orderSelectDom.val('').change();
        }
        if (poId) {
          poSelectDom.val('').change();
        }
      });

      $(document).on('change', '[name="order_id"]', function (e) {
        let orderId = $(this).val();
        let poId = poSelectDom.val();
        if (poId) {
          poSelectDom.val('').change();
        }
        reportDom.empty();
        if (orderId) {
          generateReport(orderId)
        }
      });

      $(document).on('change', '[name="purchase_order_id"]', function (e) {
        let orderId = orderSelectDom.val();
        let poId = $(this).val();
        reportDom.empty();
        if (orderId && poId) {
          generateReport(orderId, poId)
        }
      });

      function generateReport(order_id, purchase_order_id = '') {
        loader.show();
        $.ajax({
          type: "GET",
          url: '/line-date-wise-output-avg-report',
          data: {
            'order_id' : order_id,
            'purchase_order_id' : purchase_order_id,
          }
        }).done(function (response) {
          loader.hide();
          if (response.status === 'success') {
            reportDom.html(response.html);
            console.log(response.message);
            fixTableHead();
          }
          if (response.status === 'error') {
            alert('Something went wrong!');
            console.log(response.message);
          }
        }).fail(function (response) {
          loader.hide();
          alert('Something went wrong!');
        });
      }

      function fixTableHead() {
        $(document).find("#fixTable").tableHeadFixer();
      }
    })

    // line date wise sewing output report download
    $(document).on('click', '.line-date-wise-output-avg-report-dwnld-btn', function () {
      var order_id = $('select[name="order_id"]').val();
      var purchase_order_id = $('select[name="purchase_order_id"]').val();
      var type = $(this).attr("download-type");
      if (order_id && purchase_order_id && type) {
        window.location = '/line-date-wise-output-avg-report-download/' + type + '/' + order_id + '/' + purchase_order_id;
      } else if (order_id && type) {
        window.location = '/line-date-wise-output-avg-report-download/' + type + '/' + order_id;
      } else {
        alert('Please view report first');
      }
    });
  </script>
@endsection
