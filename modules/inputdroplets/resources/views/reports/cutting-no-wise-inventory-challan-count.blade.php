@php
$tableHeadColorClass = 'tableHeadColor';
if (isset($type) || request()->has('type') || request()->route('type')) {
$tableHeadColorClass = '';
}
@endphp
@extends('inputdroplets::layout')
@section('title', 'Cutting Wise Inventory/Challan ID')
@section('content')
<div class="padding">
  <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header text-center">
          <h2>Cutting Wise Inventory/Challan ID || {{ date("jS F, Y") }} <span class="pull-right"><a download-type="pdf"
                class="cutting-no-wise-inventory-challan-dwnld-btn"><i style="color: #DC0A0B"
                  class="fa fa-file-pdf-o"></i></a> | <a download-type="xls"
                class="cutting-no-wise-inventory-challan-dwnld-btn"><i style="color: #0F733B"
                  class="fa fa-file-excel-o"></i></a></span></h2>
        </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body inventory-report-cutting-no">
          <form>
            <div class="form-group">
              <div class="row m-b">
                <div class="col-sm-2">
                  <label>Buyer</label>
                  {!! Form::select('buyer_id', [], null, ['class' => 'form-control form-control-sm']) !!}
                </div>
                <div class="col-sm-2">
                  <label>Style</label>
                  {!! Form::select('order_id', [], null, ['class' => 'form-control form-control-sm']) !!}
                </div>
                <div class="col-sm-2">
                  <label>PO</label>
                  {!! Form::select('po_id', [], null, ['class' => 'form-control form-control-sm']) !!}
                </div>
                <div class="col-sm-2">
                  <label>Color</label>
                  {!! Form::select('color_id', [], null, ['class' => 'form-control form-control-sm']) !!}
                </div>
                <div class="col-sm-2">
                  <label>Cutting No.</label>
                  {!! Form::select('cutting_no', [], null, ['class' => 'form-control form-control-sm']) !!}
                </div>
              </div>
            </div>
          </form>

          <div id="parentTableFixed" class="table-responsive">
            <table class="reportTable" id="fixTable">
              <thead>
                <tr>
                  <th>Serial</th>
                  <th>Challan No</th>
                </tr>
              </thead>
              <tbody class="inventory-challan-list">
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
      const colorSelectDom = $('[name="color_id"]');
      const cuttingNoSelectDom = $('[name="cutting_no"]');
      const reportDom = $('.inventory-challan-list');

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
        reportDom.empty();
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
        reportDom.empty();
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
        reportDom.empty();
      });

      $(document).on('change', '[name="color_id"]', function (e) {
        let cuttingNo = cuttingNoSelectDom.val();
        if (cuttingNo) {
          cuttingNoSelectDom.val('').change();
        }
        reportDom.empty();
      });

      $(document).on('change', '[name="cutting_no"]', function (e) {
        e.preventDefault();
        reportDom.empty();
        if (poSelectDom.val() && colorSelectDom.val() && cuttingNoSelectDom.val()) {
          generateReport();
        }
      });

      function generateReport() {
        reportDom.empty();
        let data = {
          '_token': $('meta[name="csrf-token"]').attr('content'),
          'purchase_order_id': poSelectDom.val(),
          'color_id': colorSelectDom.val(),
          'cutting_no': cuttingNoSelectDom.val(),
        }

        $('.loader').html(loader);
        $.ajax({
          type: 'GET',
          url: '/cutting-no-wise-inventory-challan-post',
          data: data,
          success: function (response) {
            $('.loader').empty();
            var resultRows;
            if (Object.keys(response).length > 0) {
              $.each(response, function (index, challan_no) {
                  resultRows += '<tr><td>' + ++index + '</td><td>' + challan_no + '</td></tr>';
              });
            } else {
              resultRows = '<tr><td colspan="2" class="text-center text-danger">Not found</td></tr>';
            }
            reportDom.html(resultRows);
          }
        });
      }
      
      $(document).on('click', '.cutting-no-wise-inventory-challan-dwnld-btn', function () {
        var purchase_order_id = poSelectDom.val();
        var color_id = colorSelectDom.val();
        var cutting_no = cuttingNoSelectDom.val();
        var type = $(this).attr("download-type");
        if (purchase_order_id && type) {
            window.location = `/cutting-no-wise-inventory-challan-report-download/${type}/${purchase_order_id}/${color_id}/${cutting_no}`;
        } else {
            alert('Please view report first');
        }
      });
    });
</script>
@endsection