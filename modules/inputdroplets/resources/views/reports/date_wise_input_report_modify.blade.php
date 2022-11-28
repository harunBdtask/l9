@php
  $tableHeadColorClass = 'tableHeadColor';
  if (isset($type) || request()->has('type') || request()->route('type')) {
    $tableHeadColorClass = '';
  }
@endphp
@extends('inputdroplets::layout')
@section('title', 'Date Wise Sewing input')
@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
            <h2>Date Wise Sewing input || {{ date("jS F, Y") }}
              <span class="pull-right">
                <span class="hidden-print" style="list-style: none;display: inline-block; cursor: pointer;"
                      onclick="generate()" title="Download this pdf"> <i style="color: #DC0A0B" class="fa fa-file-pdf-o"></i></span>
                  |
                  <a href="{{url('/date-wise-sewing-input-download/xls/'.($date ?? null))}}">
                      <i style="color: #0F733B" class="fa fa-file-excel-o"></i></a>
                </span>
            </h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body">

            <form action="{{ url('/date-wise-sewing-input') }}" method="get">
              <div class="form-group">
                <div class="row m-b">
                  <div class="col-sm-3">
                    <input type="date" name="date" class="form-control form-control-sm" required="required"
                           value="{{ $date ?? null }}">
                  </div>
                  <div class="col-sm-2">
                    <button type="submit" class="btn btn-sm white form-control form-control-sm">Search</button>
                  </div>
                </div>
              </div>
            </form>

            @include('inputdroplets::reports.tables.date_wise_input_report_modify_body')

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
        textColor: [0, 0, 0],
        margin: {top: 80},
        headStyles: {
          fillColor: [168, 245, 255],
          textColor: [0, 0, 0],
        },
        bodyStyles: {lineColor: [0, 0, 0]},
        columnStyles: {
          0: {
            cellWidth: 80,
          },
          1: {
            cellWidth: 80,
          },
          2: {
            cellWidth: 120,
          },
          3: {
            cellWidth: 80,
          },
          4: {
            cellWidth: 80,
          },
          5: {
            cellWidth: 80,
          },
          6: {
            cellWidth: 80,
          },
          7: {
            cellWidth: 50,
          },
          8: {
            cellWidth: 130,
          },
          9: {
            cellWidth: 50,
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
        //hiding page no
        /*doc.text('Page ' + String(i) + ' of ' + String(pageCount2), 1190 - 320, 1207 - 20, null, null, "right");*/
      }

      for (var i = 1; i <= pageCount; i++) {

        doc.setPage(i);

        const format1 = "YYYY-MM-DD";
        doc.setFontSize(14);
        doc.setTextColor(0, 0, 0);
        doc.setFontStyle('bold');
        doc.addImage(Imagedata, 'JPEG', 30, 15, 140, 40);
        doc.text(360, 20, factoryName);
        doc.text(325, 60, "Date Wise Sewing input " + '(' + moment().format("MMMM Do YYYY") + ')');
      }

      for (var i = 1; i <= pageCount; i++) {
        doc.setPage(i);
        var p = 10;
        doc.setFontSize(12);
        doc.setTextColor(0, 0, 0);
        doc.setFontStyle('normal');
        doc.text(300, 40, factoryAddress);
      }

      doc.addPage();

      doc.autoTable({
        html: '#fixTable2',
        theme: 'grid',
        startY: 80,
        width: 'auto',
        textColor: [0, 0, 0],
        margin: {top: 80},
        headStyles: {
          fillColor: [168, 245, 255],
          textColor: [0, 0, 0],
        },
        bodyStyles: {lineColor: [0, 0, 0]},
        columnStyles: {
          0: {
            cellWidth: 300,
          },
          1: {
            cellWidth: 250,
          },
          2: {
            cellWidth: 250,
          }
        },
        styles: {
          minCellHeight: 10,
        }

      });


      const pageCount2 = doc.internal.getNumberOfPages();
      for (var i = 1; i <= pageCount2; i++) {
        // Go to page i
        doc.setPage(i);
        //Print Page 1 of 4 for example
        doc.setFontSize(10);
        doc.setTextColor(0, 0, 0);
        doc.setFont("times");
        doc.setFontType("italic");
        doc.text('© Copyright goRMG ERP Product of Skylark Soft Limited', 700 - 320, 1207 - 20, null, null, "left");

        //hiding page no
        /*doc.text('Page ' + String(i) + ' of ' + String(pageCount2), 1190 - 320, 1207 - 20, null, null, "right");*/

      }

      for (var i = 1; i <= pageCount2; i++) {

        doc.setPage(i);
        const format1 = "YYYY-MM-DD";
        doc.setFontSize(14);
        doc.setTextColor(0, 0, 0);
        doc.setFontStyle('bold');
        doc.addImage(Imagedata, 'JPEG', 30, 15, 140, 40);
        doc.text(360, 20, factoryName);
        doc.text(325, 60, "Date Wise Sewing input " + '(' + moment().format("MMMM Do YYYY") + ')');
      }

      for (var i = 1; i <= pageCount2; i++) {
        doc.setPage(i);
        var p = 10;
        doc.setFontSize(12);
        doc.setTextColor(0, 0, 0);
        doc.setFontStyle('normal');
        doc.text(300, 40, factoryAddress);
      }

      doc.save('Date_Wise_Sewing_input.pdf');

    }

  </script>
@endsection
