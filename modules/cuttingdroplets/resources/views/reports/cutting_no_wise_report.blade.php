@php
$tableHeadColorClass = 'tableHeadColor';
if (isset($type) || request()->has('type') || request()->route('type')) {
$tableHeadColorClass = '';
}
@endphp
@extends('cuttingdroplets::layout')
@section('title', 'Cutting Wise Cutting Production Report')
@section('content')
<div class="padding">
  <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header text-center">
          <h2>Cutting Wise Cutting Production Report
            <span class="pull-right">
              <a download-type="pdf" class="cutting-no-wise-cutting-report-dwnld-btn">
                <i style="color: #DC0A0B" class="fa fa-file-pdf-o"></i>
              </a>
              |
              <a download-type="xls" class="cutting-no-wise-cutting-report-dwnld-btn">
                <i style="color: #0F733B" class="fa fa-file-excel-o"></i>
              </a>
            </span>
          </h2>
        </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body cutting-no">
          @include('partials.response-message')
          <form>
            <div class="form-group cutting-no-wise-cutting-report">
              <div class="row m-b">
                <div class="col-sm-2">
                  <label>Buyer</label>
                  {!! Form::select('buyer_id', [], null, ['class' => 'form-control form-control-sm', 'placeholder' => 'Select Buyer'])
                  !!}
                </div>
                <div class="col-sm-2">
                  <label>Style</label>
                  {!! Form::select('order_id', [], null, ['class' => 'form-control form-control-sm', 'placeholder' => 'Select Style'])
                  !!}
                </div>
                <div class="col-sm-2">
                  <label>PO</label>
                  {!! Form::select('po_id', [], null, ['class' => 'form-control form-control-sm', 'placeholder' => 'Select PO']) !!}
                </div>
                <div class="col-sm-2">
                  <label>Color</label>
                  {!! Form::select('color_id', [], null, ['class' => 'form-control form-control-sm', 'placeholder' => 'Select Color'])
                  !!}
                </div>
                <div class="col-sm-2">
                  <label>Cutting No</label>
                  {!! Form::select('cutting_no', [], null, ['class' => 'form-control form-control-sm']) !!}
                </div>
              </div>
            </div>
          </form>
          <div id="parentTableFixed" class="table-responsive">
            <table class="reportTable" id="fixTable">
              <thead>
                <tr>
                  <th>Size Name</th>
                  <th>Total Bundle</th>
                  <th>Cutting Quantity</th>
                  <th>Cutting Date</th>
                </tr>
              </thead>
              <tbody class="cutting-no-wise-report">
                <span class="loader"></span>
              </tbody>
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
      const poSelectDom = $('[name="po_id"]');
      const colorSelectDom = $('[name="color_id"]');
      const cuttingNoSelectDom = $('[name="cutting_no"]');
      const cuttingNoWiseReportDom = $('.cutting-no .cutting-no-wise-report');

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

      colorSelectDom.select2({
        ajax: {
          url: '/utility/get-colors-for-po-select2-search',
          data: function (params) {
            const purchaseOrderId = poSelectDom.val();
            return {
              purchase_order_id: purchaseOrderId,
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
        placeholder: 'Select Color',
        allowClear: true
      });

      cuttingNoSelectDom.select2({
        ajax: {
          url: '/get-cutting-nos-by-po-color',
          data: function (params) {
            let poId = poSelectDom.val();
            let colorId = colorSelectDom.val();
            return {
              purchase_order_id: poId,
              color_id: colorId,
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
        placeholder: 'Cutting No',
        allowClear: true
      });

      $(document).on('change', '[name="buyer_id"]', function (e) {
        let orderId = orderSelectDom.val();
        let poId = poSelectDom.val();
        let colorId = colorSelectDom.val();
        let cuttingNo = cuttingNoSelectDom.val();
        if (orderId) {
          orderSelectDom.val('').change();
        }
        if (poId) {
          poSelectDom.val('').change();
        }
        if (colorId) {
          colorSelectDom.val('').change();
        }
        if (cuttingNo) {
          cuttingNoSelectDom.val('').change();
        }
        cuttingNoWiseReportDom.empty();
      });

      $(document).on('change', '[name="order_id"]', function (e) {
        let orderId = $(this).val();
        let poId = poSelectDom.val();
        let colorId = colorSelectDom.val();
        let cuttingNo = cuttingNoSelectDom.val();
        if (poId) {
          poSelectDom.val('').change();
        }
        if (colorId) {
          colorSelectDom.val('').change();
        }
        if (cuttingNo) {
          cuttingNoSelectDom.val('').change();
        }
        cuttingNoWiseReportDom.empty();
      });

      $(document).on('change', '[name="po_id"]', function (e) {
        let colorId = colorSelectDom.val();
        let cuttingNo = cuttingNoSelectDom.val();
        if (colorId) {
          colorSelectDom.val('').change();
        }
        if (cuttingNo) {
          cuttingNoSelectDom.val('').change();
        }
        cuttingNoWiseReportDom.empty();
      });

      $(document).on('change', '[name="color_id"]', function (e) {
        let cuttingNo = cuttingNoSelectDom.val();
        if (cuttingNo) {
          cuttingNoSelectDom.val('').change();
        }
        cuttingNoWiseReportDom.empty();
      });

      $(document).on('change', '[name="cutting_no"]', function (e) {
        cuttingNoWiseReportDom.empty();
      });

      $(document).on('change', '.cutting-no [name="cutting_no"]', function (e) {
        e.preventDefault();
        cuttingNoWiseReportDom.empty();
        let buyer_id = buyerSelectDom.val();
        let po_id = poSelectDom.val();
        let color_id = colorSelectDom.val();
        let cutting_no = $(this).val();
        if (buyer_id && po_id && color_id && cutting_no) {
          $('.loader').html(loader);
          $.ajax({
            type: 'GET',
            url: '/get-cutting-no-wise-cutting-report',
            data: {
              purchase_order_id: po_id,
              color_id: color_id,
              cutting_no: cutting_no,
            },
            success: function (response) {
              $('.loader').empty();
              let tr;
              let total_buldle = 0;
              let total_qty = 0;
              let ct_date = '';
              $.each(response, function (index, report) {
                total_buldle += report.count_bundle;
                total_qty += Number.parseInt(report.size_cutting_qty);
                ct_date = report.cutting_date;

                tr += [
                  '<tr>',
                  '<td>' + report.name + '</td>',
                  '<td>' + report.count_bundle + '</td>',
                  '<td>' + report.size_cutting_qty + '</td>',
                  '<td>' + report.cutting_date + '</td>',
                  '</tr>',
                ].join();
              });
              cuttingNoWiseReportDom.html(tr);

              let totalRow = '<tr style="font-weight: bold">' +
                  '<td><b>Total</b></td>' +
                  '<td>' + total_buldle + '</td>' +
                  '<td>' + total_qty + '</td>' +
                  '<td></td>' +
                  '</tr>';
              cuttingNoWiseReportDom.append(totalRow);
            },
            error: function (error) {
              $('.loader').empty();
              let tr = '<tr class="tr-height">' +
                  '<td colspan="4" class="text-danger tr-height text-center">Not found</td>' +
                  '</tr>';
              cuttingNoWiseReportDom.html(tr);
            }
          });
        }
      });

      // cutting no wise cutting report download
      $(document).on('click', '.cutting-no-wise-cutting-report-dwnld-btn', function () {
        let cutting_no = cuttingNoSelectDom.val();
        let buyer_id = buyerSelectDom.val();
        let po_id = poSelectDom.val();
        let color_id = colorSelectDom.val();
        let type = $(this).attr("download-type");
        if (po_id && type) {
          let href = window.location.protocol + "//" + window.location.host + "/cutting-no-wise-cutting-report-download?type=" + type + '&purchase_order_id=' + po_id + '&color_id=' + color_id + '&cutting_no=' + cutting_no;
          window.open(href, '_blank');
        } else {
          alert('Please view report first');
        }
      });
    });
</script>
@endsection
