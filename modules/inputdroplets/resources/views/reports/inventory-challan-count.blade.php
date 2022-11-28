@php
  $tableHeadColorClass = 'tableHeadColor';
  if (isset($type) || request()->has('type') || request()->route('type')) {
    $tableHeadColorClass = '';
  }
@endphp
@extends('inputdroplets::layout')
@section('title', 'Challan List')
@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
            <h2>Challan List || {{ date("jS F, Y") }} <span class="pull-right"><a download-type="pdf" class="inventory-challan-dwnld-btn"><i style="color: #DC0A0B" class="fa fa-file-pdf-o"></i></a> | <a download-type="xls" class="inventory-challan-dwnld-btn"><i style="color: #0F733B" class="fa fa-file-excel-o"></i></a></span></h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body challans-count">
            <form>
              <div class="form-group">
                <div class="row m-b">
                    <div class="col-sm-2">
                      <label>Buyer</label>
                      {!! Form::select('buyer_id', [], null, ['class' => 'buyer-challan-list form-control form-control-sm']) !!}
                    </div>
                    <div class="col-sm-2">
                      <label>Order/Style</label>
                      {!! Form::select('order_id', [], null, ['class' => 'style-challan-list form-control form-control-sm']) !!}
                    </div>
                    <div class="col-sm-2">
                      <label>PO</label>
                      {!! Form::select('po_id', [], null, ['class' => 'order-challan-list form-control form-control-sm']) !!}
                    </div>
                  </div>
                </div>
            </form>

              <div id="parentTableFixed" class="table-responsive">
                <table class="reportTable" id="fixTable">
                  <thead>
                    <tr>
                      <th>Serial</th>
                      <th>Challan No.</th>
                    </tr>
                  </thead>
                  <tbody class="challan-list-count">
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
  <script src="{{ asset('protracker/custom.js') }}"></script>
  <script>
    $(function() {
      const buyerSelectDom = $('[name="buyer_id"]');
      const orderSelectDom = $('[name="order_id"]');
      const poSelectDom = $('[name="po_id"]');
      const reportDom = $('.challan-list-count');

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
        reportDom.empty();
      });

      $(document).on('change', '[name="order_id"]', function (e) {
        let orderId = $(this).val();
        let poId = poSelectDom.val();
        if (poId) {
          poSelectDom.val('').change();
        }
        reportDom.empty();
      });

      $(document).on('change', '[name="po_id"]', function (e) {
        reportDom.empty();
        if (poSelectDom.val()) {
          generateReport()
        }
      });

      function generateReport() {
        var purchase_order_id = poSelectDom.val();
        $('.loader').html(loader);
        $.ajax({
          type: 'GET',
          url: '/inventory-challan-count-post/' + purchase_order_id,
          success: function (response) {
            var resultRows;
            if (Object.keys(response).length > 0) {
              $.each(response, function (index, challan_no) {
                  resultRows += '<tr><td>' + ++index + '</td><td>' + challan_no + '</td></tr>';
              });
            } else {
              resultRows = '<tr><td colspan="2" class="text-center text-danger">Not found</td></tr>';
            }
            $('.loader').empty();
            reportDom.html(resultRows);
          },
        });
      }

      $(document).on('click', '.inventory-challan-dwnld-btn', function () {
        var purchase_order_id = poSelectDom.val();
        var type = $(this).attr("download-type");
        if (purchase_order_id && type) {
          window.location = '/inventory-challan-count-report-download/' + type + '/' + purchase_order_id;
        } else {
          alert('Please view report first');
        }
      });
    });
  </script>
@endsection
