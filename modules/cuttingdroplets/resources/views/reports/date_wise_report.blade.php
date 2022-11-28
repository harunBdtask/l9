@extends('cuttingdroplets::layout')
@section('title', 'Date Wise Cutting Production Summary')
@section('content')
<div class="padding">
  <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header text-center">
          <h2>Date Wise Cutting Production Summary
            <span class="pull-right">
              {{--<a href="{{ url('/date-wise-report-download/pdf/'.$date) }}" >
              <i style="color: #DC0A0B" class="fa fa-file-pdf-o"></i>
              </a>--}}
              <span style="list-style: none;display: inline-block" onclick="generate()" id="pdf"><i
                  style="cursor: pointer; color: #DC0A0B" class="fa fa-file-pdf-o"></i>&nbsp;</span>
              |
              <a href="{{ url('/date-wise-report-download/xls/'.$date) }}">
                <i style="color: #0F733B" class="fa fa-file-excel-o"></i>
              </a>
            </span>
          </h2>
        </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form action="{{ url('/date-wise-cutting-report') }}" method="get">
            <div class="form-group">
              <div class="row m-b">
                <div class="col-sm-3">
                  <label>Report Date</label>
                  <input type="date" name="date" class="form-control form-control-sm" required="required"
                    value="{{ $date }}">
                </div>
                <div class="col-sm-2">
                  <label>&nbsp;</label>
                  <button type="submit" class="btn btn-sm btn-info form-control form-control-sm">Search</button>
                </div>
              </div>
            </div>
          </form>
          @include('cuttingdroplets::reports.tables.date_wise_cutting_summary')
        </div>
      </div>
    </div>
  </div>
</div>
<style type="text/css">
  @media screen and (-webkit-min-device-pixel-ratio: 0) {
    input[type=date].form-control form-control-sm {
      line-height: 1;
    }
  }
</style>
@endsection


@section('scripts')
<script src="{{ asset('/js/tableHeadFixer.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js"></script>
<script src="{{ asset('/modules/skeleton/flatkit/assets/jspdf/jspdftable.js') }}"></script>
<script>
  $(document).ready(function() {
          $("#fixTable").tableHeadFixer();

          $('.date-field').datepicker({
              format: 'yyyy-mm-dd',
              autoclose: true
          });
      });


      function generate() {
          var d = new Date();
          var doc = new jsPDF('p', 'pt', [1200, 900]);
          var Imagedata = '{{imageEncode()}}';
          var factoryName = '{{sessionFactoryName()}}';
          var factoryAddress = '{{sessionFactoryAddress()}}';
          doc.setFontSize(12);
          doc.setTextColor(0, 0, 0);

          doc.autoTable({
              html: '#fixTable1',
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
                      cellWidth: 50,
                  },
                  1: {
                      cellWidth: 150,
                  },
                  2: {
                      cellWidth: 150,
                  },
                  3: {
                      cellWidth: 150,
                  },
                  4: {
                      cellWidth: 100,
                  },
                  5: {
                      cellWidth: 100,
                  },
                  6: {
                      cellWidth: 100,
                  },


              },
              styles: {
                  minCellHeight: 20,
              }

          });

          doc.autoTable({
              html: '#fixTable2',
              theme: 'grid',
              startY: doc.lastAutoTable.finalY + 30,
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
                      cellWidth: 30,
                  },
                  1: {
                      cellWidth: 30,
                  },
                  2: {
                      cellWidth: 150,
                  },
                  3: {
                      cellWidth: 100,
                  },
                  4: {
                      cellWidth: 100,
                  },
                  5: {
                      cellWidth: 100,
                  },
                  6: {
                      cellWidth: 120,
                  },
                  7: {
                      cellWidth: 70,
                  },
                  8: {
                      cellWidth: 100,
                  }


              },
              styles: {
                  minCellHeight: 20,
              }

          });


          doc.autoTable({
              html: '#fixTable3',
              theme: 'grid',
              startY: doc.lastAutoTable.finalY + 30,
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
                      cellWidth: 160,
                  },
                  1: {
                      cellWidth: 160,
                  },
                  2: {
                      cellWidth: 160,
                  },
                  3: {
                      cellWidth: 160,
                  },
                  4: {
                      cellWidth: 160,
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
              doc.text(285, 60, "Date Wise Cutting Production Summary "+'('+moment().format("MMMM Do YYYY")+')');
          }

          for (var i = 1; i <= pageCount; i++) {
              doc.setPage(i);
              var p = 10;
              doc.setFontSize(12);
              doc.setTextColor(0, 0, 0);
              doc.setFontStyle('normal');
              doc.text(360, 40, factoryAddress);
          }




          doc.save('date-wise-report.pdf');
      }
</script>
@endsection
