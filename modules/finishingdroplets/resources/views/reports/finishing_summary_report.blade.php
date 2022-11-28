@extends('finishingdroplets::layout')
@php
$tableHeadColorClass = 'tableHeadColor';
if (isset($type) || request()->has('type') || request()->route('type')) {
$tableHeadColorClass = '';
}
@endphp
@section('styles')
<style>
  .select2-container .select2-selection--single {
    height: 40px;
    border-radius: 0px;
    line-height: 50px;
    border: 1px solid #e7e7e7;
  }

  .reportTable .select2-container .select2-selection--single {
    border: 1px solid #e7e7e7;
  }

  .select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 40px;
    width: 100%;
  }

  .select2-container--default .select2-selection--single .select2-selection__arrow {
    top: 8px;
  }

  .error+.select2-container .select2-selection--single {
    border: 1px solid red;
  }

  .select2-container--default .select2-selection--multiple {
    min-height: 40px !important;
    border-radius: 0px;
    width: 100%;
  }
</style>
@endsection
@section('title', 'Finishing Summary Report')
@section('content')
<div class="padding">
  <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header text-center">
          <h2>Finishing Summary Report
            <span class="pull-right">
              <span class="hidden-print" style="list-style: none;display: inline-block; cursor: pointer;"
                onclick="generate()" title="Download this pdf"> <i style="color: #DC0A0B"
                  class="fa fa-file-pdf-o"></i></span> |
              <a
                href="{{ url('/finishing-summary-report-download?type=excel&&buyer_id='.$buyer_id.'&order_id='.$order_id) }}"><i
                  style="color: #0F733B" class="fa fa-file-excel-o"></i></a>
            </span>
          </h2>
        </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body color-sewing-output">
          <div class="form-group">
            {!! Form::open(['url' => '/finishing-summary-report','method' => 'GET', 'id' => 'finishing-summary-report-form']) !!}
            <div class="row m-b">
              <div class="col-sm-3">
                <label>Buyer<dfn class="text-warning">*</dfn></label>
                {!! Form::select('buyer_id', $buyers, $buyer_id, ['class' => 'form-control form-control-sm', 'required']) !!}
              </div>
              <div class="col-sm-3">
                <label>Style<dfn class="text-warning">*</dfn></label>
                {!! Form::select('order_id', $orders, $order_id, ['class' => 'form-control form-control-sm ', 'required']) !!}
              </div>
              <div class="col-sm-2">
                <label>&nbsp;</label>
                <input type="submit" style="background-color: rgb(167, 227, 249); color: #000000"
                  class="form-control form-control-sm btn btn-sm btn-info" value="Search">
              </div>
            </div>
            {!! Form::close() !!}
          </div>

          <div id="parentTableFixed" class="table-responsive">
            <table class="reportTable" id="fixTable">
              @includeIf('finishingdroplets::reports.tables.finishing_summary_report_table')
            </table>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js"></script>
<script src="{{ asset('/modules/skeleton/flatkit/assets/jspdf/jspdftable.js') }}"></script>
<script>
  $(function () {
    const buyerSelectDom = $('[name="buyer_id"]');
    const orderSelectDom = $('[name="order_id"]');
    buyerSelectDom.change(() => {
        orderSelectDom.empty().val('').change();
    });

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

  });


  function generate() {
      var d = new Date();
      var doc = new jsPDF('p', 'pt', [1200, 900]);
      var Imagedata = '{{imageEncode()}}';
      var factoryName = '{{sessionFactoryName()}}';
      var factoryAddress = '{{sessionFactoryAddress()}}';
      doc.setFontSize(14);
      doc.setTextColor(0, 0, 0);
      // hr line on header
      /*doc.line(3, 70, 900,70);*/


      doc.autoTable({
          html: '#fixTable',
          theme: 'grid',
          startY: 80,
          width: 'auto',
          textColor: [0, 0, 0],
          margin: {top: 80},

          headStyles: {
              fillColor: [168, 245, 255],
              textColor: [0, 0, 0],
              fontSize: 8
          },
          bodyStyles: {lineColor: [0, 0, 0]},
          columnStyles: {
              0: {
                  cellWidth: 45,
              },
              1: {
                  cellWidth: 45,
              },
              2: {
                  cellWidth: 40,
              },
              3: {
                  cellWidth: 35,
              },
              4: {
                  cellWidth: 45,
              },
              5: {
                  cellWidth: 40,
              },
              6: {
                  cellWidth: 40,
              },
              7: {
                  cellWidth: 35,
              },
              8: {
                  cellWidth: 30,
              },
              9: {
                  cellWidth: 35,
              },
              10: {
                  cellWidth: 35,
              },
              11: {
                  cellWidth: 30,
              },
              12: {
                  cellWidth: 35,
              },
              13: {
                  cellWidth: 35,
              },
              14: {
                  cellWidth: 35,
              },
              15: {
                  cellWidth: 35,
              },
              16: {
                  cellWidth: 40,
              },
              17: {
                  cellWidth: 40,
              },
              18: {
                  cellWidth: 40,
              },
              19: {
                  cellWidth: 35,
              },
              20: {
                  cellWidth: 30,
              },
              21: {
                  cellWidth: 30,
              },
              22: {
                  cellWidth: 25,
              }

          },

          styles: {
              minCellHeight: 20,
              fontSize: 8,
          }

      });

      const pageCount = doc.internal.getNumberOfPages();
      // For each page, print the page number and the total pages

      for (var i = 1; i <= pageCount; i++) {
          // Go to page i
          doc.setPage(i);
          doc.line(3, 70, 900, 70);
          //Print Page 1 of 4 for example
          doc.setFontSize(10);
          doc.setTextColor(0, 0, 0);
          doc.setFont("times");
          doc.setFontType("italic");
          doc.text('© Copyright goRMG ERP Product of Skylark Soft Limited', 700 - 320, 1207 - 20, null, null, "left");
          //hiding page no
          /*doc.text('Page ' + String(i) + ' of ' + String(pageCount), 1190 - 320, 1207 - 20, null, null, "right");*/

      }

      for (var i = 1; i <= pageCount; i++) {

          doc.setPage(i);

          const format1 = "YYYY-MM-DD";
          doc.setFontSize(14);
          doc.setTextColor(0, 0, 0);
          doc.setFontStyle('bold');
          doc.addImage(Imagedata, 'JPEG', 30, 15, 140, 40);
          doc.text(380, 20, factoryName);
          doc.text(320, 60, "Finishing Summary Report" + '(' + moment().format("MMMM Do YYYY") + ')');
      }

      for (var i = 1; i <= pageCount; i++) {
          doc.setPage(i);
          var p = 10;
          doc.setFontSize(12);
          doc.setTextColor(0, 0, 0);
          doc.setFontStyle('normal');
          doc.text(360, 40, factoryAddress);
      }

      doc.save('Finishing_Summary_Report.pdf');
  }

</script>
@endsection