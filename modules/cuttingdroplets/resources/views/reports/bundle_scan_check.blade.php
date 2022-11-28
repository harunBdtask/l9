@extends('cuttingdroplets::layout')
@section('title', 'Bundle Card Scan Check')
@section('styles')
<style type="text/css">
  .Scanned {
    color: green;
    font-size: 11px !important;
  }

  .Not {
    color: red;
  }
</style>
@endsection
@section('content')
<div class="padding">
  <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header text-center">
          <h2>Bundle Card Scan Check
            <span class="pull-right">
              <a download-type="pdf" class="bundlecard-scan-check-report-dwnld-btn">
                <i style="color: #DC0A0B" class="fa fa-file-pdf-o"></i>
              </a>
              |
              <a download-type="xls" class="bundlecard-scan-check-report-dwnld-btn">
                <i style="color: #0F733B" class="fa fa-file-excel-o"></i>
              </a>
            </span>
          </h2>
        </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form>
            <div class="form-group">
              <div class="row m-b">
                <div class="col-sm-2">
                  <label>Buyer</label>
                  {!! Form::select('buyer_id', $buyers ?? [], null, ['class' => 'form-control form-control-sm', 'placeholder' => 'Select
                  a Buyer']) !!}
                </div>
                <div class="col-sm-2">
                  <label>Style</label>
                  {!! Form::select('order_id', [], null, ['class' => 'form-control form-control-sm', 'placeholder' => 'Select Booking'])
                  !!}
                </div>
                <div class="col-sm-2">
                  <label>PO</label>
                  {!! Form::select('purchase_order_id', [], null, ['class' => 'form-control form-control-sm', 'placeholder' => 'Select a
                  PO']) !!}
                </div>
                <div class="col-sm-2">
                  <label>Color</label>
                  {!! Form::select('color_id', [], null, ['class' => 'form-control form-control-sm', 'placeholder' => 'Select a Color'])
                  !!}
                </div>
                <div class="col-sm-2">
                  <label>Cutting No.</label>
                  {!! Form::select('cutting_no', [], null, ['class' => 'form-control form-control-sm', 'placeholder' => 'Select a
                  Cutting No']) !!}
                </div>
              </div>
            </div>
          </form>
          <div id="parentTableFixed" class="table-responsive">
            <table id="fixTable" class="reportTable">
              <thead style="font-size: 12px !important">
                <tr>
                  <th>SL</th>
                  <th>OP Barcode</th>
                  <th>RP Barcode</th>
                  <th>Size</th>
                  <th title="Bundle No">B. No</th>
                  <th title="Cutting Scan">Cutt. Scan</th>
                  <th title="Cutting Date">Cutt. DT.</th>
                  <th title="Print Sent">Print Sent</th>
                  <th title="Print/Embr Sent Date">P. Sent DT.</th>
                  <th title="Print/Embr Received">P. Rcv</th>
                  <th title="Print/Embr Received Date">P. Rcv. DT.</th>
                  <th>Input/Tag</th>
                  <th title="Input/Tag Date">Input/Tag DT.</th>
                  <th>Sewing</th>
                  <th title="Sewing Date">Sewing DT.</th>
                  <th>Washing</th>
                  <th>Wash. Date</th>
                  <th>Qty</th>
                </tr>
              </thead>
              <tbody class="bundle-checked-list" style="font-size: 12px !important">
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
<script type="text/javascript">
  $(function () {
      const buyerSelectDom = $('[name="buyer_id"]');
      const orderSelectDom = $('[name="order_id"]');
      const poSelectDom = $('[name="purchase_order_id"]');
      const colorSelectDom = $('[name="color_id"]');
      const cuttingNoSelectDom = $('[name="cutting_no"]');
      const reportDom = $('.bundle-checked-list');

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
              buyer_id: buyerId
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

      $(document).on('change', '[name="purchase_order_id"]', function (e) {
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
        reportDom.empty();
      });

      $(document).on('change', '[name="cutting_no"]', function (e) {
        e.preventDefault();
        reportDom.empty();
        let po_id = poSelectDom.val();
        let color_id = colorSelectDom.val();
        let cutting_no = $(this).val();
        if (po_id && color_id && cutting_no) {
          $('.loader').html(loader);
          $.ajax({
            type: 'GET',
            url: '/bundle-scan-check-data',
            data: {
              purchase_order_id: po_id,
              color_id: color_id,
              cutting_no: cutting_no,
            },
            success: function (response) {
              $('.loader').empty();
              if (Object.keys(response).length > 0) {
                var i = 0;
                var resultRows
                $.each(response, function (index, report) {
                  resultRows += [
                    '<tr>',
                    '<td>' + report.sl + '</td>',
                    '<td>0' + report.barcode + '</td>',
                    '<td>1' + report.barcode + '</td>',
                    '<td>' + report.size + '</td>',
                    '<td>' + report.bundle_no + '</td>',
                    '<td class="' + report.cutting + '">' + report.cutting + '</td>',
                    '<td>' + report.cutting_date + '</td>',
                    '<td class="' + report.print_sent + '">' + report.print_sent + '</td>',
                    '<td>' + report.print_sent_datetime + '</td>',
                    '<td class="' + report.print_received + '">' + report.print_received + '</td>',
                    '<td>' + report.print_received_datetime + '</td>',
                    '<td class="' + report.cutting_inventory + '">' + report.cutting_inventory + '</td>',
                    '<td>' + report.cutting_inventory_datetime + '</td>',
                    '<td class="' + report.sewingoutput + '">' + report.sewingoutput + '</td>',
                    '<td>' + report.sewingoutput_datetime + '</td>',
                    '<td class="' + report.washing_sent + '">' + report.washing_sent + '</td>',
                    '<td>' + report.washing_sent_datetime + '</td>',
                    '<td>' + report.quantity + '</td>',
                    '</tr>'].join('');
                });
              } else {
                resultRows = '<tr><td colspan="18" class="text-danger text-center" >Not found</td></tr>';
              }
              reportDom.html(resultRows);
            }, error: function () {
              $('.loader').empty();
            }
          });
        }
      });

      // inventory scan check report download
      $(document).on('click', '.bundlecard-scan-check-report-dwnld-btn', function () {
        var purchase_order_id = poSelectDom.val();
        var color_id = colorSelectDom.val();
        var cutting_no = cuttingNoSelectDom.val();
        var type = $(this).attr("download-type");
        if (purchase_order_id && color_id && cutting_no && type) {
          let href = window.location.protocol + "//" + window.location.host + "/bundlecard-scan-check-report-download?type=" + type + '&purchase_order_id=' + purchase_order_id + '&color_id=' + color_id + '&cutting_no=' + cutting_no;
          window.open(href, '_blank');
        } else {
          alert('Please view report first');
        }
      });
    });

</script>
@endsection
