@php
$tableHeadColorClass = 'tableHeadColor';
if (isset($type) || request()->has('type') || request()->route('type')) {
$tableHeadColorClass = '';
}
@endphp
@extends('inputdroplets::layout')
@section('title', 'Booking No, Purchase Order & Color Wise Report')
@section('content')
<div class="padding">
  <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header text-center">
          <h2>Style, Purchase Order &amp; Color Wise Report
            <span class="pull-right">
              <a download-type="pdf" class="order-color-size-wise-sewing-input-dwnld-btn">
                <i style="color: #DC0A0B" class="fa fa-file-pdf-o"></i>
              </a>
              |
              <a download-type="xls" class="order-color-size-wise-sewing-input-dwnld-btn">
                <i style="color: #0F733B" class="fa fa-file-excel-o"></i>
              </a>
            </span>
          </h2>
        </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <div class="form-group">
            <div class="row m-b">
              <div class="col-sm-2">
                <label>Buyer</label>
                {!! Form::select('buyer_id', [], null, ['class' => 'order-color-size-wise-buyer form-control
                form-control-sm']) !!}
              </div>
              <div class="col-sm-2">
                <label>Order/Style</label>
                {!! Form::select('order_id', [], null, ['class' => 'order-color-size-wise-booking form-control
                form-control-sm']) !!}
              </div>
              <div class="col-sm-2">
                <label>Garments Item</label>
                {!! Form::select('item_id', [], null, ['class' => 'order-color-size-wise-booking form-control
                form-control-sm']) !!}
              </div>
              <div class="col-sm-2">
                <label>PO</label>
                {!! Form::select('po_id', [], null, ['class' => 'order-color-size-wise-po form-control
                form-control-sm']) !!}
              </div>
              <div class="col-sm-2">
                <label>Color</label>
                {!! Form::select('color_id', [], null, ['class' => 'order-color-size-wise-color form-control
                form-control-sm']) !!}
              </div>
            </div>
          </div>

          <div id="parentTableFixed" class="table-responsive">
            <table class="reportTable" id="fixTable">
              <thead>
                <tr class="order-wise-input-report-head">
                  <th>PO</th>
                  <th>OQ</th>
                  <th>Tdy Cutt.</th>
                  <th>T. Cutt.</th>
                  <th>Cutt Rej.</th>
                  <th>Left Qty</th>
                  <th>Tdy Print Sent</th>
                  <th>T. Print Sent</th>
                  <th>Tdy Print Recv.</th>
                  <th>T. Print Recv.</th>
                  <th>Print Rej.</th>
                  <th>Tdy Embr Sent</th>
                  <th>T. Embr Sent</th>
                  <th>Tdy Embr Recv.</th>
                  <th>T. Embr Recv.</th>
                  <th>Embr Rej.</th>
                  <th>Tdy Input</th>
                  <th>T. Input</th>
                  <th>Tdy Output</th>
                  <th>T. Output</th>
                  <th>T. Sewing Rej.</th>
                  <th>Sewing Balance</th>
                </tr>
                <tr class="po-wise-input-report-head" style="display: none">
                  <th>Color</th>
                  <th>OQ</th>
                  <th>Tdy Cutt.</th>
                  <th>T. Cutt.</th>
                  <th>Cutt Rej.</th>
                  <th>Left Qty</th>
                  <th>Tdy Print Sent</th>
                  <th>T. Print Sent</th>
                  <th>Tdy Print Recv.</th>
                  <th>T. Print Recv.</th>
                  <th>Print Rej.</th>
                  <th>Tdy Embr Sent</th>
                  <th>T. Embr Sent</th>
                  <th>Tdy Embr Recv.</th>
                  <th>T. Embr Recv.</th>
                  <th>Embr Rej.</th>
                  <th>Tdy Input</th>
                  <th>T. Input</th>
                  <th>Tdy Output</th>
                  <th>T. Output</th>
                  <th>T. Sewing Rej.</th>
                  <th>Sewing Balance</th>
                </tr>
                <tr class="color-wise-input-report-head" style="display: none">
                  <th>Size</th>
                  <th>OQ</th>
                  <th>Tdy Cutt.</th>
                  <th>T. Cutt.</th>
                  <th>Cutt Rej.</th>
                  <th>Left Qty</th>
                  <th>Tdy Print Sent</th>
                  <th>T. Print Sent</th>
                  <th>Tdy Print Recv.</th>
                  <th>T. Print Recv.</th>
                  <th>Print Rej.</th>
                  <th>Tdy Embr Sent</th>
                  <th>T. Embr Sent</th>
                  <th>Tdy Embr Recv.</th>
                  <th>T. Embr Recv.</th>
                  <th>Embr Rej.</th>
                  <th>Tdy Input</th>
                  <th>T. Input</th>
                  <th>Tdy Output</th>
                  <th>T. Output</th>
                  <th>T. Sewing Rej.</th>
                  <th>Sewing Balance</th>
                </tr>
              </thead>
              <tbody class="order-color-size-wise-input-report">

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
  $(function () {
    const buyerSelectDom = $('[name="buyer_id"]');
    const orderSelectDom = $('[name="order_id"]');
    const garmentItemSelectDom = $('[name="item_id"]');
    const poSelectDom = $('[name="po_id"]');
    const colorSelectDom = $('[name="color_id"]');
    const orderWiseReportHead = $('.order-wise-input-report-head');
    const poWiseReportHead = $('.po-wise-input-report-head');
    const colorWiseReportHead = $('.color-wise-input-report-head');
    const orderColorSizeWiseReportDom = $('.order-color-size-wise-input-report');
    const domLoader = $('.loader');
    let orders;
    let items;

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

    garmentItemSelectDom.select2({
      ajax: {
        url: function (params) {
          return `/utility/get-items-for-select2-search`
        },
        data: function (params) {
          const orderId = orderSelectDom.val();
          return {
            search: params.term,
            order_id: orderId,
          }
        },
        processResults: function (data, params) {
          items = data;
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
      placeholder: 'Select Item',
      allowClear: true
    });

    poSelectDom.select2({
      ajax: {
        url: '/utility/get-pos-for-select2-search',
        data: function (params) {
          const orderId = orderSelectDom.val();
          const itemId = garmentItemSelectDom.val();
          return {
            order_id: orderId,
            item_id: itemId,
            search: params.term
          }
        },
        processResults: function (data, params) {
          let resData = [{id: 'all', text: "ALL PO"}];
          data.results.map(data => {
            resData.push(data);
          });
          return {
            results: resData,
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
          const orderId = orderSelectDom.val();
          const itemId = garmentItemSelectDom.val();
          const purchaseOrderId = poSelectDom.val();
          return {
            order_id: orderId,
            garments_item_id: itemId,
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

    $(document).on('change', '[name="buyer_id"]', function (e) {
        let orderId = orderSelectDom.val();
        let poId = poSelectDom.val();
        let colorId = colorSelectDom.val();
        if (orderId) {
            orderSelectDom.val('').change();
        }
        if (poId) {
            poSelectDom.val('').change();
        }
        if (colorId) {
            colorSelectDom.val('').change();
        }
        orderColorSizeWiseReportDom.empty();
    });

    function generateStyleWiseReport() {
      let buyer_id = buyerSelectDom.val();
      let order_id = orderSelectDom.val();

      if (buyer_id && order_id) {
        poWiseReportHead.hide();
        colorWiseReportHead.hide();
        orderWiseReportHead.show();
        domLoader.html(loader);
        $.ajax({
          type: 'GET',
          url: '/get-style-wise-input/' + order_id,
          success: function (response) {
            domLoader.empty();
            if (Object.keys(response).length > 0) {
              var tr;
              var left_qty = 0;
              var total_po_quantity = 0;
              $.each(response.po_wise_production_report, function (index, report) {
                var po_no = report.purchase_order.po_no ? report.purchase_order.po_no : 'PO';
                var po_quantity = report.purchase_order.po_quantity ? report.purchase_order.po_quantity : 0;
                total_po_quantity += Number(po_quantity);
                left_qty = po_quantity - report.cutting_qty;

                tr += [
                    '<tr>',
                    '<td>' + po_no + '</td>',
                    '<td>' + po_quantity + '</td>',
                    '<td>' + report.todays_cutting + '</td>',
                    '<td>' + report.cutting_qty + '</td>',
                    '<td>' + report.cutting_rejection + '</td>',
                    '<td>' + left_qty + '</td>',
                    '<td>' + report.todays_print_sent + '</td>',
                    '<td>' + report.print_sent + '</td>',
                    '<td>' + report.todays_print_received + '</td>',
                    '<td>' + report.print_received + '</td>',
                    '<td>' + report.print_rejection + '</td>',
                    '<td>' + report.todays_embr_sent + '</td>',
                    '<td>' + report.embr_sent + '</td>',
                    '<td>' + report.todays_embr_received + '</td>',
                    '<td>' + report.embr_received + '</td>',
                    '<td>' + report.embr_rejection + '</td>',
                    '<td>' + report.todays_input + '</td>',
                    '<td>' + report.input_qty + '</td>',
                    '<td>' + report.todays_sewing_output + '</td>',
                    '<td>' + report.sewing_output_qty + '</td>',
                    '<td>' + report.sewing_rejection + '</td>',
                    '<td>' + report.sewing_balance + '</td>',
                    '</tr>'
                ].join('');
              });
              orderColorSizeWiseReportDom.append(tr);

              var total_data = response.total_data;
              var total_left_qty = total_po_quantity - total_data.total_cutting;
              var totalRow = '<tr style="font-weight:bold">' +
                  '<td>Total</td>' +
                  '<td>' + total_po_quantity + '</td>' +
                  '<td>' + total_data.todays_cutting + '</td>' +
                  '<td>' + total_data.total_cutting + '</td>' +
                  '<td>' + total_data.total_cutting_rejection + '</td>' +
                  '<td>' + total_left_qty + '</td>' +
                  '<td>' + total_data.todays_sent + '</td>' +
                  '<td>' + total_data.total_sent + '</td>' +
                  '<td>' + total_data.todays_received + '</td>' +
                  '<td>' + total_data.total_received + '</td>' +
                  '<td>' + total_data.total_print_rejection + '</td>' +
                  '<td>' + total_data.todays_embr_sent + '</td>' +
                  '<td>' + total_data.total_embr_sent + '</td>' +
                  '<td>' + total_data.todays_embr_received + '</td>' +
                  '<td>' + total_data.total_embr_received + '</td>' +
                  '<td>' + total_data.total_embr_rejection + '</td>' +
                  '<td>' + total_data.todays_input + '</td>' +
                  '<td>' + total_data.total_input + '</td>' +
                  '<td>' + total_data.todays_sewing_output + '</td>' +
                  '<td>' + total_data.total_sewing_output + '</td>' +
                  '<td>' + total_data.total_sewing_rejection + '</td>' +
                  '<td>' + total_data.total_sewing_balance + '</td>' +
                  '</tr>';
              orderColorSizeWiseReportDom.append(totalRow);
            } else {
              var resultRows = '<tr><td colspan="21" class="text-danger text-center">Not found</td></tr>';
              orderColorSizeWiseReportDom.append(resultRows);
            }
          },
          error: function (jqXHR, exception) {
            var resultRows = '<tr><td colspan="15" class="text-danger text-center">Not found</td></tr>';
            orderColorSizeWiseReportDom.append(resultRows);
          }
        });
      }
    }

    $(document).on('change', '[name="order_id"]', function (e) {
      let orderId = $(this).val();
      let poId = poSelectDom.val();
      let colorId = colorSelectDom.val();
      let itemId = garmentItemSelectDom.val();

      if (itemId) {
        garmentItemSelectDom.val('').change();
      }
      if (poId) {
        poSelectDom.val('').change();
      }
      if (colorId) {
        colorSelectDom.val('').change();
      }
      orderColorSizeWiseReportDom.empty();
      generateStyleWiseReport();
    });

    $(document).on('change', '[name="item_id"]', function (e) {
      let itemId = $(this).val();
      let poId = poSelectDom.val();
      let colorId = colorSelectDom.val();
      if (poId) {
          poSelectDom.val('').change();
      }
      if (colorId) {
          colorSelectDom.val('').change();
      }
      //orderColorSizeWiseReportDom.empty();
    });

    function generatePOWiseReport() {
      let order_id = orderSelectDom.val();
      let po_id = poSelectDom.val();
      let garments_item_id = garmentItemSelectDom.val();

      if (order_id && po_id && garments_item_id) {
        orderWiseReportHead.hide();
        colorWiseReportHead.hide();
        poWiseReportHead.show();
        domLoader.html(loader);
        $.ajax({
          type: 'GET',
          url: '/get-order-color-size-wise-sewing-input/' + order_id + '/' + po_id + '/' + garments_item_id,
          success: function (response) {
            domLoader.empty();
            if (Object.keys(response).length > 0) {
              var tr;
              $.each(response.color_wise_production_report, function (index, report) {
                tr += [
                    '<tr>',
                    '<td>' + report.color_name + '</td>',
                    '<td>' + report.color_wise_order_qty + '</td>',
                    '<td>' + report.todays_cutting + '</td>',
                    '<td>' + report.cutting_qty + '</td>',
                    '<td>' + report.cutting_rejection + '</td>',
                    '<td>' + report.left_qty + '</td>',
                    '<td>' + report.todays_print_sent + '</td>',
                    '<td>' + report.print_sent + '</td>',
                    '<td>' + report.todays_print_received + '</td>',
                    '<td>' + report.print_received + '</td>',
                    '<td>' + report.print_rejection + '</td>',
                    '<td>' + report.todays_embr_sent + '</td>',
                    '<td>' + report.embr_sent + '</td>',
                    '<td>' + report.todays_embr_received + '</td>',
                    '<td>' + report.embr_received + '</td>',
                    '<td>' + report.embr_rejection + '</td>',
                    '<td>' + report.todays_input + '</td>',
                    '<td>' + report.input_qty + '</td>',
                    '<td>' + report.todays_sewing_output + '</td>',
                    '<td>' + report.sewing_output_qty + '</td>',
                    '<td>' + report.sewing_rejection + '</td>',
                    '<td>' + report.sewing_balance + '</td>',
                    '</tr>'
                ].join('');
              });
              orderColorSizeWiseReportDom.append(tr);

              var total_data = response.total_data;
              var totalRow = '<tr style="font-weight:bold">' +
                  '<td>Total</td>' +
                  '<td>' + total_data.total_color_wise_order_qty + '</td>' +
                  '<td>' + total_data.total_todays_cutting + '</td>' +
                  '<td>' + total_data.total_cutting + '</td>' +
                  '<td>' + total_data.total_cutting_rejection + '</td>' +
                  '<td>' + total_data.total_left_qty + '</td>' +
                  '<td>' + total_data.total_todays_sent + '</td>' +
                  '<td>' + total_data.total_sent + '</td>' +
                  '<td>' + total_data.total_todays_received + '</td>' +
                  '<td>' + total_data.total_received + '</td>' +
                  '<td>' + total_data.total_print_rejection + '</td>' +
                  '<td>' + total_data.total_todays_embr_sent + '</td>' +
                  '<td>' + total_data.total_embr_sent + '</td>' +
                  '<td>' + total_data.total_todays_embr_received + '</td>' +
                  '<td>' + total_data.total_embr_received + '</td>' +
                  '<td>' + total_data.total_embr_rejection + '</td>' +
                  '<td>' + total_data.total_todays_input + '</td>' +
                  '<td>' + total_data.total_input + '</td>' +
                  '<td>' + total_data.total_todays_sewing_output + '</td>' +
                  '<td>' + total_data.total_sewing_output + '</td>' +
                  '<td>' + total_data.total_sewing_rejection + '</td>' +
                  '<td>' + total_data.total_sewing_balance + '</td>' +
                  '</tr>';
              orderColorSizeWiseReportDom.append(totalRow);
            } else {
              var resultRows = '<tr><td colspan="20" class="text-danger text-center">Not found</td></tr>';
              orderColorSizeWiseReportDom.append(resultRows);
            }
          },
          error: function (jqXHR, exception) {
            var resultRows = '<tr><td colspan="20" class="text-danger text-center">Not found</td></tr>';
            orderColorSizeWiseReportDom.append(resultRows);
          }
        });
      }
    }

    $(document).on('change', '[name="po_id"]', function (e) {
      let colorId = colorSelectDom.val();
      if (colorId) {
        colorSelectDom.val('').change();
      }
      orderColorSizeWiseReportDom.empty();
      generatePOWiseReport();
    });

    function generateColorWiseReport() {
      var order_id = orderSelectDom.val();
      var po_id = poSelectDom.val();
      var garments_item_id = garmentItemSelectDom.val();
      let color_id = colorSelectDom.val();
      if (order_id && garments_item_id && po_id && color_id) {
        orderWiseReportHead.hide();
        poWiseReportHead.hide();
        colorWiseReportHead.show();
        domLoader.html(loader);

        $.ajax({
          type: 'GET',
          url: '/get-size-wise-sewing-input/' + order_id + '/' + po_id + '/' + color_id + '/' + garments_item_id,
          success: function (response) {
            domLoader.empty();
            if (Object.keys(response).length > 0) {
              var tr;
              $.each(response.size_wize_input_data, function (index, report) {
                tr += [
                    '<tr>',
                    '<td>' + report.size_name + '</td>',
                    '<td>' + report.size_wise_order_qty + '</td>',
                    '<td>' + report.todays_cutting_qty + '</td>' +
                    '<td>' + report.cutting_qty + '</td>' +
                    '<td>' + report.cutting_rejection + '</td>' +
                    '<td>' + report.left_qty + '</td>' +
                    '<td>' + report.todays_print_sent_qty + '</td>' +
                    '<td>' + report.print_sent_qty + '</td>' +
                    '<td>' + report.todays_print_received_qty + '</td>' +
                    '<td>' + report.print_received_qty + '</td>' +
                    '<td>' + report.print_rejection_qty + '</td>' +
                    '<td>' + report.todays_embr_sent_qty + '</td>' +
                    '<td>' + report.embr_sent_qty + '</td>' +
                    '<td>' + report.todays_embr_received + '</td>' +
                    '<td>' + report.embr_received_qty + '</td>' +
                    '<td>' + report.embr_rejection_qty + '</td>' +
                    '<td>' + report.todays_input_qty + '</td>' +
                    '<td>' + report.input_qty + '</td>' +
                    '<td>' + report.todays_output_qty + '</td>' +
                    '<td>' + report.output_qty + '</td>' +
                    '<td>' + report.sewing_rejection_qty + '</td>' +
                    '<td>' + report.sewing_balance + '</td>' +
                    '</tr>'
                ].join('');
              });
              orderColorSizeWiseReportDom.append(tr);

              var total_data = response.size_wise_total_input_data;
              var totalRow = '<tr style="font-weight:bold">' +
                  '<td>Total</td>' +
                  '<td>' + total_data.total_size_wise_order_qty + '</td>' +
                  '<td>' + total_data.total_todays_cutting + '</td>' +
                  '<td>' + total_data.total_cutting + '</td>' +
                  '<td>' + total_data.total_cutting_rejection + '</td>' +
                  '<td>' + total_data.total_left_qty + '</td>' +
                  '<td>' + total_data.total_todays_print_sent_qty + '</td>' +
                  '<td>' + total_data.total_print_sent_qty + '</td>' +
                  '<td>' + total_data.total_todays_received + '</td>' +
                  '<td>' + total_data.total_print_received_qty + '</td>' +
                  '<td>' + total_data.total_print_rejection + '</td>' +
                  '<td>' + total_data.total_todays_embr_received + '</td>' +
                  '<td>' + total_data.total_embr_sent + '</td>' +
                  '<td>' + total_data.total_todays_embr_received + '</td>' +
                  '<td>' + total_data.total_embr_rejection + '</td>' +
                  '<td>' + total_data.total_embr_rejection + '</td>' +
                  '<td>' + total_data.total_todays_input + '</td>' +
                  '<td>' + total_data.total_input + '</td>' +
                  '<td>' + total_data.total_todays_sewing_output + '</td>' +
                  '<td>' + total_data.total_sewing_output + '</td>' +
                  '<td>' + total_data.total_sewing_rejection + '</td>' +
                  '<td>' + total_data.total_sewing_balance + '</td>' +
                  '</tr>';
              orderColorSizeWiseReportDom.append(totalRow);
            } else {
              var resultRows = '<tr><td colspan="22" class="text-danger text-center">Not found</td></tr>';
              orderColorSizeWiseReportDom.append(resultRows);
            }
          },
          error: function (jqXHR, exception) {
            var resultRows = '<tr><td colspan="22" class="text-danger text-center">Not found</td></tr>';
            orderColorSizeWiseReportDom.append(resultRows);
          }
        });
      }
    }

    $(document).on('change', '[name="color_id"]', function (e) {
      orderColorSizeWiseReportDom.empty();
      generateColorWiseReport();
    });

    $(document).on('click', '.order-color-size-wise-sewing-input-dwnld-btn', function () {
      var order_id = orderSelectDom.val();
      var purchase_order_id = poSelectDom.val();
      var color_id = colorSelectDom.val();
      var garments_item_id = garmentItemSelectDom.val();
      var type = $(this).attr("download-type");
      var data = {
        order_id: (order_id === null || order_id == '' || order_id == undefined) ? '' : order_id,
        purchase_order_id: (purchase_order_id === null || purchase_order_id == '' || purchase_order_id == undefined) ? '' : purchase_order_id,
        color_id: (color_id === null || color_id == '' || color_id == undefined) ? '' : color_id,
        type: type,
      };
      if (data.order_id && data.purchase_order_id && data.color_id && type) {
        window.open(`/get-size-wise-sewing-input-download/${type}/${data.order_id}/${data.purchase_order_id}/${data.color_id}/${garments_item_id}`, '_blank')
        return true;
      } else if (data.order_id && data.purchase_order_id && type) {
        window.open('/get-order-color-size-wise-sewing-input-report-download/' + type + '/' + data.order_id + '/' + data.purchase_order_id + '/' + garments_item_id, '_blank');
        return true;
      } else if (data.order_id && type) {
        window.open('/get-style-wise-sewing-input-report-download/' + type + '/' + data.order_id, '_blank')
          return true;
      } else {
          alert('Please view report first');
          return false;
      }
    });
  });
</script>
@endsection