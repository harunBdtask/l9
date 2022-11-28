@php
$tableHeadColorClass = 'tableHeadColor';
if (isset($type) || request()->has('type') || request()->route('type')) {
$tableHeadColorClass = '';
}
@endphp
@extends('printembrdroplets::layout')
@section('title', 'Cutting Wise Print Send Receive Report')
@section('content')
<div class="padding">
  <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header text-center">
          <h2>Cutting Wise Print Send Receive Report || {{ date("jS F, Y") }} <span class="pull-right"><a
                download-type="pdf" class="cutting-no-wise-print-send-receive-report-dwnld-btn"><i
                  style="color: #DC0A0B" class="fa fa-file-pdf-o"></i></a> | <a download-type="xls"
                class="cutting-no-wise-print-send-receive-report-dwnld-btn"><i style="color: #0F733B"
                  class="fa fa-file-excel-o"></i></a></span></h2>
        </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body print-cutting-wise">
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
                {!! Form::select('purchase_order_id', [], null, ['class' => 'form-control form-control-sm']) !!}
              </div>
              <div class="col-sm-2">
                <label>Color</label>
                {!! Form::select('color_id', [], null, ['class' => 'form-control form-control-sm']) !!}
              </div>
              <div class="col-sm-2">
                <label>Cutting No</label>
                {!! Form::select('cutting_no', [], null, ['class' => 'form-control form-control-sm']) !!}
              </div>
            </div>
          </div>

          <div id="parentTableFixed" class="table-responsive">
            <table class="reportTable" id="fixTable">
              <thead>
                <tr>
                  <th>Color Name</th>
                  <th>Size Name</th>
                  <th>PO Quantity</th>
                  <th>Cutting Production</th>
                  <th>Cutting WIP</th>
                  <th>Bundle Send</th>
                  <th>Total Send</th>
                  <th>Bundle Recieved</th>
                  <th>Total Recieved</th>
                  <th>Fabric Rejection</th>
                  <th>Print Rejection</th>
                  <th>Total Rejection</th>
                  <th>Print WIP/Short</th>
                </tr>
              </thead>
              <tbody class="cutting-no-wise-print-report">
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
      const poSelectDom = $('[name="purchase_order_id"]');
      const colorSelectDom = $('[name="color_id"]');
      const cuttingNoSelectDom = $('[name="cutting_no"]');
      const cuttingNoWiseReportDom = $('.cutting-no-wise-print-report');

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

      $(document).on('change', '[name="purchase_order_id"]', function (e) {
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
        e.preventDefault();
        cuttingNoWiseReportDom.empty();
        var buyer_id = buyerSelectDom.val();
        var purchase_order_id = poSelectDom.val();
        var color_id = colorSelectDom.val();
        var cutting_no = cuttingNoSelectDom.val();

        if (buyer_id && purchase_order_id && color_id && cutting_no) {
          $('.loader').html(loader);

          $.ajax({
            type: 'GET',
            url: '/cutting-no-wise-color-print-send-receive-report-post/' + buyer_id + '/' + purchase_order_id + '/' + color_id + '/' + cutting_no,
            success: function (response) {
              $('.loader').empty();
              if (Object.keys(response).length > 0) {

                var torder_qty = 0;
                var tcutting_qty = 0;
                var tcutting_wip = 0;
                var tbundle_send = 0;
                var ttotal_send = 0;
                var tbundle_received = 0;
                var ttotal_received = 0;
                var tfabric_rejection = 0;
                var tprint_rejection = 0;
                var ttotal_rejection = 0;
                var tprint_wip_short = 0;

                $.each(response, function (index, report) {

                  torder_qty += parseInt(report.order_qty);
                  tcutting_qty += parseInt(report.cutting_qty);
                  tcutting_wip += parseInt(report.cutting_wip);
                  tbundle_send += parseInt(report.bundle_send);
                  ttotal_send += parseInt(report.total_send);
                  tbundle_received += parseInt(report.bundle_received);
                  ttotal_received += parseInt(report.total_received);
                  tfabric_rejection += parseInt(report.fabric_rejection);
                  tprint_rejection += parseInt(report.print_rejection);
                  ttotal_rejection += parseInt(report.total_rejection);
                  tprint_wip_short += parseInt(report.print_wip_short);

                  var resultRows = '<tr><td>' + report.color_name + '</td><td>' + report.size_name + '</td><td>' + report.order_qty + '</td><td>' + report.cutting_qty + '</td><td>' + report.cutting_wip + '</td><td>' + report.bundle_send + '</td><td>' + report.total_send + '</td><td>' + report.bundle_received + '</td><td>' + report.total_received + '</td><td>' + report.fabric_rejection + '</td><td>' + report.print_rejection + '</td><td>' + report.total_rejection + '</td><td>' + report.print_wip_short + '</td></tr>';

                  cuttingNoWiseReportDom.append(resultRows);
                });

                var totalRow = '<tr style="font-weight: bold"><td colspan="2"><b>Total</b></td><td>' + torder_qty + '</td><td>' + tcutting_qty + '</td><td>' + tcutting_wip + '</td><td>' + tbundle_send + '</td><td>' + ttotal_send + '</td><td>' + tbundle_received + '</td><td>' + ttotal_received + '</td><td>' + tfabric_rejection + '</td><td>' + tprint_rejection + '</td><td>' + ttotal_rejection + '</td><td>' + tprint_wip_short + '</td></tr>';
                cuttingNoWiseReportDom.append(totalRow);

              } else {
                var resultRows = '<tr><td colspan="13" class="text-danger text-center" >Not found</td></tr>';
                cuttingNoWiseReportDom.append(resultRows);
              }
            }
          });
        }
      });

      // cutting no wise print send receive report download
      $(document).on('click', '.cutting-no-wise-print-send-receive-report-dwnld-btn', function () {
        var buyer_id = buyerSelectDom.val();
        var purchase_order_id = poSelectDom.val();
        var color_id = colorSelectDom.val();
        var cutting_no = cuttingNoSelectDom.val();
        var type = $(this).attr("download-type");
        if (cutting_no && purchase_order_id && buyer_id && type) {
          window.location = '/cutting-no-wise-print-send-receive-report-download/' + type + '/' + buyer_id + '/' + purchase_order_id + '/' + color_id + '/' + cutting_no;
        } else {
          alert('Please view report first');
        }
      });
    });
</script>
@endsection
