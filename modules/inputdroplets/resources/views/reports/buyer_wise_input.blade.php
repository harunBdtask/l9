@php
  $tableHeadColorClass = 'tableHeadColor';
  if (isset($type) || request()->has('type') || request()->route('type')) {
    $tableHeadColorClass = '';
  }
@endphp
@extends('inputdroplets::layout')
@section('title', 'Buyer Wise Input Report')
@section('content')
  <div class="padding buyer-wise-input-report-page">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
            <h2>Buyer Wise Input Report
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
          <div class="box-body input-color-wise">
            <div class="form-group">
              <div class="row m-b">
                <div class="col-sm-2">
                  <label>Buyer</label>
                  {!! Form::select('buyer_id', [], null, ['class' => 'form-control form-control-sm']) !!}
                </div>
              </div>
            </div>

            <div id="parentTableFixed" class="table-responsive">
              <table class="reportTable" id="fixTable">
                <thead>
                  <tr>
                    <th>Order/Style</th>
                    <th>PO</th>
                    <th>Order Quantity</th>
                    <th>Cutting Production</th>
                    <th>Cutting WIP</th>
                    <th>Print Send</th>
                    <th>Print Recieve</th>
                    <th>Print Rejection</th>
                    <th>Print/Embr.WIP</th>
                    <th>Current Cutting Inventory</th>
                    <th>Today's Input Qty</th>
                    <th>Total Input Qty</th>
                  </tr>
                </thead>
                <tbody class="color-wise-input">

                </tbody>
              </table>
            </div>
            <div class="loader"></div>

          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('scripts')
  <script src="{{ asset('/protracker/custom.js') }}"></script>
  <script>
    $(function() {
      const buyerSelectDom = $('[name="buyer_id"]');
      const reportDom = $('.color-wise-input');

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

      $(document).on('change', '[name="buyer_id"]', function (e) {
        e.preventDefault();
        reportDom.empty();
        var buyer_id = $(this).val();
        if (buyer_id) {
          getBuyerWiseInputData(buyer_id, 1);
        }
      });

      $(document).on('click', '.buyer-wise-input-report-page .pagination a', function (event) {
        event.preventDefault();
        $('li').removeClass('active');
        $(this).parent('li').addClass('active');
        var page = $(this).attr('href').split('page=')[1];
        var buyer_id = buyerSelectDom.val();
        if (buyer_id) {
          getBuyerWiseInputData(buyer_id, page);
        }
      });

      function getBuyerWiseInputData(buyer_id, page) {
        $('.loader').html(loader);
        $.ajax({
          type: 'GET',
          url: '/get-buyer-wise-sewing-line-input',
          data: {
            'buyer_id': buyer_id,
            'page': page,
          },
          success: function (response) {
            var pdf_url = '/buyer-wise-sewing-line-input-report-download/pdf/'+buyer_id +'/' + page;
            var excel_url = '/buyer-wise-sewing-line-input-report-download/excel/'+buyer_id +'/' + page;
            $('.loader').empty();
            if (response.status == 200) {
              reportDom.html(response.html);
              $('#buyer-wise-pdf').attr('href', pdf_url);
              $('#buyer-wise-xls').attr('href', excel_url);
            }
            if (response.status == 500) {
              reportDom.html(response.html);
              $('#buyer-wise-pdf').attr('href', '');
              $('#buyer-wise-xls').attr('href', '');
            }
          }
        });
      }

    });
  </script>
@endsection
