@extends('finishingdroplets::layout')

@section('title', 'Date Wise Iron, Poly & Packing Summary')
@section('content')
<div class="padding">
  <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header text-center">
          <h2>Date Wise Iron, Poly & Packing Summary
            <span class="pull-right">
              {{--<a href="{{ url('date-wise-iron-poly-packing-summary-report-download/pdf/'.$from_date.'/'.$to_date) }}">
              <i style="color: #DC0A0B" class="fa fa-file-pdf-o"></i></a>--}}

              <span style="list-style: none;display: inline-block" onclick="generate()" , id="pdf"><i
                  style="cursor: pointer; color: #DC0A0B" class="fa fa-file-pdf-o"></i>&nbsp;</span>
              |
              <a href="{{ url('date-wise-iron-poly-packing-summary-report-download/xls/'.$from_date.'/'.$to_date) }}">
                <i style="color: #0F733B" class="fa fa-file-excel-o"></i></a></span>
          </h2>
        </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form action="{{ url('/date-wise-iron-poly-packing-summary') }}" method="GET">
            <div class="form-group">
              <div class="row m-b">
                <div class="col-sm-3">
                  <label>From Date</label>
                  <input type="date" name="from_date" class="form-control form-control-sm" required="required"
                    value="{{ $from_date ?? date('Y-m-d') }}">
                  @if($errors->has('from_date'))
                  <span class="text-danger">{{ $errors->first('from_date') }}</span>
                  @endif
                </div>
                <div class="col-sm-3">
                  <label>To Date</label>
                  <input type="date" name="to_date" class="form-control form-control-sm" required="required"
                    value="{{ $to_date ?? date('Y-m-d') }}">

                  @if($errors->has('to_date'))
                  <span class="text-danger">{{ $errors->first('to_date') }}</span>
                  @endif
                  @if(Session::has('error'))
                  <span class="text-danger">{{ Session::get('error') }}</span>
                  @endif
                </div>
                <div class="col-sm-2">
                  <label>&nbsp;</label>
                  <button type="submit" class="btn btn-sm btn-info form-control form-control-sm">Search</button>
                </div>
              </div>
            </div>
          </form>

          @include('finishingdroplets::reports.tables.date_range_wise_poly_cartoon_report')
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js"></script>
<script src="{{ asset('/modules/skeleton/flatkit/assets/jspdf/jspdftable.js') }}"></script>
<script>
  function generate() {
          var d = new Date();
          var doc = new jsPDF('p', 'pt', [1200, 900]);
          var Imagedata = '{{imageEncode()}}';
          var factoryName = '{{sessionFactoryName()}}';
          var factoryAddress = '{{sessionFactoryAddress()}}';
          doc.setFontSize(14);
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
                      cellWidth: 93,
                  },
                  1: {
                      cellWidth: 93,
                  },
                  2: {
                      cellWidth: 93,
                  },
                  3: {
                      cellWidth: 93,
                  },
                  4: {
                      cellWidth: 93,
                  },
                  5: {
                      cellWidth: 93,
                  },
                  6: {
                      cellWidth: 93,
                  },
                  7: {
                      cellWidth: 93,
                  },
                  8: {
                      cellWidth: 93,
                  },

              },
              styles: {
                  minCellHeight: 20,
              }

          });

          doc.autoTable({
              html: '#fixTable2',
              theme: 'grid',
              startY: doc.lastAutoTable.finalY + 50,
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
                      cellWidth: 84,
                  },
                  1: {
                      cellWidth: 84,
                  },
                  2: {
                      cellWidth: 84,
                  },
                  3: {
                      cellWidth: 84,
                  },
                  4: {
                      cellWidth: 84,
                  },
                  5: {
                      cellWidth: 84,
                  },
                  6: {
                      cellWidth: 84,
                  },
                  7: {
                      cellWidth: 84,
                  },
                  8: {
                      cellWidth: 84,
                  },
                  9: {
                      cellWidth: 84,
                  },

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
              doc.text(285, 60, "Date Wise Iron, Poly & Packing Summary "+'('+moment().format("MMMM Do YYYY")+')');
          }

          for (var i = 1; i <= pageCount; i++) {
              doc.setPage(i);
              var p = 10;
              doc.setFontSize(12);
              doc.setTextColor(0, 0, 0);
              doc.setFontStyle('normal');
              doc.text(360, 40, factoryAddress);
          }


          doc.save('Date_Wise_Iron_Poly_Packing_Summary.pdf');
      }
</script>

@endsection