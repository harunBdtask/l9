@php
  $tableHeadColorClass = 'tableHeadColor';
  if (isset($type) || request()->has('type') || request()->route('type')) {
    $tableHeadColorClass = '';
  }
@endphp
@extends('inputdroplets::layout')
@section('title', 'Input Closing Report')
@section('content')
<div class="padding">
  <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header text-center">
          <h2>Input Closing Report || {{ date("jS F, Y") }}
            <span class="pull-right">
              <span style="list-style: none;display: inline-block" onclick="generatePDF()" , id="pdf"><i
                  style="cursor: pointer; color: #DC0A0B" class="fa fa-file-pdf-o"></i>&nbsp;</span>
              | <a download-type="xls" class="input-closing-report-dwnld-btn"><i style="color: #0F733B"
                  class="fa fa-file-excel-o"></i></a></span></h2>
        </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body input-challan-closing">
          <form>
            <div class="form-group">
              <div class="row m-b">
                <div class="col-sm-2">
                  <label>Buyer</label>
                  {!! Form::select('buyer_id', [], null, ['class' => 'form-control form-control-sm']) !!}
                </div>
                <div class="col-sm-2">
                  <label>Order/Style</label>
                  {!! Form::select('order_id', [], null, ['class' => 'form-control form-control-sm']) !!}
                </div>
                <div class="col-sm-2">
                  <label>PO</label>
                  {!! Form::select('po_id', [], null, ['class' => 'form-control form-control-sm']) !!}
                </div>
                <div class="col-sm-2">
                  <label>Color</label>
                  {!! Form::select('color_id', [], null, ['class' => 'inputclos-color-select form-control form-control-sm']) !!}
                </div>
              </div>
            </div>
          </form>

          <div id="parentTableFixed" class="table-responsive">
            <table class="reportTable" id="fixTable">
              <thead>
                <tr style="background-color: #cbffb5;">
                  <th>Size</th>
                  <th>Order Quantity</th>
                  <th>Cutting Production</th>
                  <th>WIP In Cutting/Print/Embr.</th>
                  <th>Today's Input to Line</th>
                  <th>Total Input to Line</th>
                  <th>Today's Output</th>
                  <th>Total Sewing Output</th>
                  <th>Total Rejection</th>
                  <th>In_line WIP</th>
                  <th>Cut 2 Sewing Ratio(%)</th>
                </tr>
              </thead>
              <tbody class="input-closing-report">
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js"></script>
<script src="{{ asset('/modules/skeleton/flatkit/assets/jspdf/jspdftable.js') }}"></script>
<script src="{{ asset('protracker/custom.js') }}"></script>
<script>
  $(function() {
    const buyerSelectDom = $('[name="buyer_id"]');
    const orderSelectDom = $('[name="order_id"]');
    const poSelectDom = $('[name="po_id"]');
    const colorSelectDom = $('[name="color_id"]');
    const reportDom = $('.input-closing-report');

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
        reportDom.empty();
      });

      $(document).on('change', '[name="order_id"]', function (e) {
        let orderId = $(this).val();
        let poId = poSelectDom.val();
        let colorId = colorSelectDom.val();
        if (poId) {
          poSelectDom.val('').change();
        }
        if (colorId) {
          colorSelectDom.val('').change();
        }
        reportDom.empty();
      });

      $(document).on('change', '[name="po_id"]', function (e) {
        let colorId = colorSelectDom.val();
        if (colorId) {
          colorSelectDom.val('').change();
        }
        reportDom.empty();
      });

      $(document).on('change', '[name="color_id"]', function (e) {
        reportDom.empty();
        if (orderSelectDom.val() && poSelectDom.val() && colorSelectDom.val()) {
          generateReport();
        }
      });

      function generateReport() {
        $('.loader').html(loader);
        $.ajax({
          type: 'GET',
          url: '/input-closing-view/' + orderSelectDom.val() + '/' + poSelectDom.val() + '/' + colorSelectDom.val(),
          success: function (response) {
            $('.loader').empty();
            if (Object.keys(response.report_size_wise).length > 0) {
              $.each(response.report_size_wise, function (index, report) {
                  var resultRows = '<tr><td>' + report.size + '</td><td>' + report.size_order_qty + '</td><td>'
                      + report.size_cutting_qty + '</td><td>' + report.wip + '</td><td>' + report.today_input + '</td><td>' + report.total_input + '</td><td>'
                      + report.today_output + '</td><td>' + report.total_output + '</td><td>' + report.rejection + '</td><td>'
                      + report.in_line_wip + '</td><td>' + report.cutt_sewing_ratio + '%</td></tr>';

                  reportDom.append(resultRows);
              });

              var totalData = response.total_report;
              var totalRow = '<tr style="font-weight:bold"><td>Total</td><td>' + totalData.total_size_order + '</td><td>' + totalData.total_size_cutting + '</td><td>'
                  + totalData.total_wip + '</td><td>' + totalData.total_today_input + '</td><td>' + totalData.total_total_input + '</td><td>' + totalData.total_today_output +
                  '</td><td>' + totalData.total_total_output + '</td><td>' + totalData.total_rejection + '</td><td>' + totalData.total_in_line_wip + '</td><td></td></tr>';
              reportDom.append(totalRow);

            } else {
              var resultRows = '<tr><td colspan="13" class="text-danger text-center" >Not found</td></tr>';
              reportDom.append(resultRows);
            }
            $('.loader').empty();
          }
        });
      }

      $(document).on('click', '.input-closing-report-dwnld-btn', function () {
          var buyer_id = buyerSelectDom.val();
          var order_id = orderSelectDom.val();
          var purchase_order_id = poSelectDom.val();
          var color_id = colorSelectDom.val();
          var type = $(this).attr("download-type");
          if (buyer_id && order_id && purchase_order_id && color_id && type) {
              window.location = '/input-closing-report-download/' + type + '/' + buyer_id + '/' + order_id + '/' + purchase_order_id + '/' + color_id;
          } else {
              alert('Please view report first');
          }
      });
  });

  function generatePDF() {
    var d = new Date();
    var doc = new jsPDF('p', 'pt', [1200, 900]);
    var Imagedata = '{{imageEncode()}}';
    var factoryName = '{{sessionFactoryName()}}';
    var factoryAddress = '{{sessionFactoryAddress()}}';
    doc.setFontSize(14);
    doc.setTextColor(0, 0, 0);

    doc.autoTable({
        html: '#fixTable',
        theme: 'grid',
        startY: 80,
        width: 'auto',
        textColor:[0,0,0],
        margin: {top: 80},
        headStyles: {
            fillColor: [168, 245, 255],
            textColor:[0,0,0],
        },
        bodyStyles: {lineColor: [0, 0, 0]},
        columnStyles: {
            0: {
                cellWidth: 76,
            },
            1: {
                cellWidth: 76,
            },
            2: {
                cellWidth: 76,
            },
            3: {
                cellWidth: 76,
            },
            4: {
                cellWidth: 76,
            },
            5: {
                cellWidth: 76,
            },
            6: {
                cellWidth: 76,
            },
            7: {
                cellWidth: 76,
            },
            8: {
                cellWidth: 76,
            },
            9: {
                cellWidth: 76,
            },
            10: {
                cellWidth: 76,
            }

        },
        styles: {
            minCellHeight: 20,
        }

    });

    const pageCount = doc.internal.getNumberOfPages();
  // For each page, print the page number and the total pages

    for (var i = 1; i <= pageCount; i++) {
        // Go to page i
        doc.setPage(i);
        //Print Page 1 of 4 for example
        doc.setFontSize(10);
        doc.setTextColor(0, 0, 0);
        doc.setFont("times");
        doc.setFontType("italic");
        doc.text('© Copyright goRMG ERP Product of Skylark Soft Limited', 700 - 320, 1207 - 20, null, null, "left");
        doc.text('Page ' + String(i) + ' of ' + String(pageCount), 1190 - 320, 1207 - 20, null, null, "right");

    }

    for (var i = 1; i <= pageCount; i++) {

        doc.setPage(i);

        const format1 = "YYYY-MM-DD";
        doc.setFontSize(14);
        doc.setTextColor(0, 0, 0);
        doc.setFontStyle('bold');
        doc.addImage(Imagedata, 'JPEG', 30, 15, 140, 40);
        doc.text(380, 20, factoryName);
        doc.text(330, 60, "Input Closing Report "+'('+moment().format("MMMM Do YYYY")+')');
    }

    for (var i = 1; i <= pageCount; i++) {
        doc.setPage(i);
        var p = 10;
        doc.setFontSize(12);
        doc.setTextColor(0, 0, 0);
        doc.setFontStyle('normal');
        doc.text(360, 40, factoryAddress);
    }


    doc.save('Input_Closing_Report.pdf');
  }
</script>
@endsection