@php
  $tableHeadColorClass = 'tableHeadColor';
  if (isset($type) || request()->has('type') || request()->route('type')) {
    $tableHeadColorClass = '';
  }
@endphp
@extends('sewingdroplets::layout')
@section('title', 'Style/Order. Wise Sewing Output Report')
@section('content')
  <div class="padding buyer-wise-sewing-report-page">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
            <h2>Style/Order. Wise Sewing Output Report
              <span class="pull-right">
                <a id="buyer-wise-pdf">
                  <i style="color: #DC0A0B" class="fa fa-file-pdf-o"></i>
                </a>
                |
                <a id="buyer-wise-xls">
                  <i style="color: #0F733B" class="fa fa-file-excel-o"></i>
                </a>
              </span>
            </h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body color-sewing-output">
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
              </div>
            </div>

            <div id="parentTableFixed" class="table-responsive">
              <table class="reportTable" id="fixTable">
                <thead>
                <tr>
                  <th>Order/Style</th>
                  <th>PO</th>
                  <th>Order Qty</th>
                  <th>Cutt. Qty</th>
                  <th>WIP In<br/>Cutt./Pt./Embr.</th>
                  <th>Print Sent</th>
                  <th>Print Rcv</th>
                  <th>Print WIP</th>
                  <th>Today's Input</th>
                  <th>Total Input</th>
                  <th>Today's Output</th>
                  <th>Total Output</th>
                  <th>Sewing Rejection</th>
                  <th>Total Rejection</th>
                  <th>In_line WIP</th>
                  <th>Cut 2 Sewing Ratio (%)</th>
                </tr>
                </thead>
                <tbody class="color-wise-sewing-output-report">

                </tbody>
              </table>
            </div>
            <div class="loader-buyer-wise-report"></div>

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
    const buyerSelectDom = $('[name="buyer_id"]');
    const orderSelectDom = $('[name="order_id"]');
    const loaderDom = $('.loader-buyer-wise-report');
    const reportDom = $('.color-wise-sewing-output-report');
    const pdfBtn = $('#buyer-wise-pdf');
    const excelBtn = $('#buyer-wise-xls');

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
        reportDom.empty();
      });

      $(document).on('change', '[name="order_id"]', function (e) {
        let buyerId = buyerSelectDom.val();
        let orderId = $(this).val();
        reportDom.empty();
        if (buyerId && orderId) {
          generateReport(buyerId, orderId, 1)
        }
      });

      function generateReport(buyerId, orderId, page) {
        loaderDom.html(loader);
        $.ajax({
            type: 'GET',
            url: '/get-buyer-wise-sewing-output-data',
            data: {
              'buyer_id': buyerId,
              'order_id': orderId,
              'page': page,
            },
            success: function (response) {
                loaderDom.empty();
                var pdf_url = '/buyer-wise-sewing-output-report-download/pdf/' + buyerId + '/' + orderId + '/' + page;
                var excel_url = '/buyer-wise-sewing-output-report-download/excel/' + buyerId + '/' + orderId + '/' + page;
                if (response.status == 200) {
                    reportDom.html(response.html);
                    pdfBtn.attr('href', pdf_url);
                    excelBtn.attr('href', excel_url);
                }
                if (response.status == 500) {
                    reportDom.html(response.html);
                    pdfBtn.attr('href', '');
                    excelBtn.attr('href', '');
                }
            }
        });
      }
      
      $(document).on('click', '.bookingno-select-sewing-output .pagination a', function (event) {
        event.preventDefault();
        $('li').removeClass('active');
        $(this).parent('li').addClass('active');
        var myurl = $(this).attr('href');
        var page = $(this).attr('href').split('page=')[1];
        var buyerId = buyerSelectDom.val();
        var orderId = orderSelectDom.val();
        if (buyerId && orderId) {
          reportDom.empty();
          generateReport(buyerId, orderId, page)
        }
      });
  });
  
</script>
@endsection
