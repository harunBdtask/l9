@extends('printembrdroplets::layout')
@section('title', 'Date Wise Print/Embr. Received And Delivery Summary')
@section('content')
<div class="padding">
  <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header text-center">
          <h2>Date Wise Print/Embr. Received And Delivery Summary
            <span class="pull-right">
              {{--<a href="{{url('date-wise-print-send-report-download?type=pdf&from_date='.($from_date ?? null).'&to_date='.($to_date ?? null))}}">
              <i style="color: #DC0A0B" class="fa fa-file-pdf-o"></i>
              </a> --}}
              <span style="list-style: none;display: inline-block" onclick="generate()" , id="pdf"><i
                  style="cursor: pointer; color: #DC0A0B" class="fa fa-file-pdf-o"></i>&nbsp;</span>
              |
              <a
                href="{{url('date-wise-print-rcv-production-delivery-report-download?type=xls&from_date='.($from_date ?? null).'&to_date='.($to_date ?? null))}}">
                <i style="color: #0F733B" class="fa fa-file-excel-o"></i>
              </a>
            </span>
          </h2>
        </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          {!! Form::open(['url' => '/date-wise-print-rcv-production-delivery-report', 'method' => 'get']) !!}
          <div class="form-group">
            <div class="row m-b">
              <div class="col-sm-3">
                <label>From Date</label>
                <input type="date" name="from_date" class="form-control form-control-sm" value="{{ $from_date }}"
                  required="required">
                @if($errors->has('from_date'))
                <span class="text-danger">{{ $errors->first('from_date') }}</span>
                @endif
              </div>
              <div class="col-sm-3">
                <label>To Date</label>
                <input type="date" name="to_date" class="form-control form-control-sm" value="{{ $to_date }}"
                  required="required">
                @if($errors->has('to_date'))
                <span class="text-danger">{{ $errors->first('to_date') }}</span>
                @endif
                @if(Session::has('error'))
                <span class="text-danger">{{ Session::get('error') }}</span>
                @endif
              </div>
              <div class="col-sm-2">
                <label>&nbsp;</label>
                <button type="submit" class="btn btn-sm white form-control form-control-sm">Search</button>
              </div>
            </div>
          </div>
          {!! Form::close() !!}
          @include('printembrdroplets::reports.tables.date_wise_print_factory_production_delivery_table')
        </div>
      </div>
    </div>
  </div>
</div>

<style type="text/css">
  @media screen and (-webkit-min-device-pixel-ratio: 0) {
    input[type=date].form-control form-control-sm {
      line-height: .75;
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
            cellWidth: 84,
          },
          1: {
            cellWidth: 84,
          },
          2: {
            cellWidth: 84,
          },
          3: {
            cellWidth: 70,
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
            cellWidth: 80,
          },
          8: {
            cellWidth: 80,
          },
          9: {
            cellWidth: 80,
          },
          10: {
            cellWidth: 45,
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
        textColor: [0, 0, 0],
        margin: {top: 80},
        headStyles: {
          fillColor: [168, 245, 255],
          textColor: [0, 0, 0],
        },
        bodyStyles: {lineColor: [0, 0, 0]},
        columnStyles: {
          0: {
            cellWidth: 60,
          },
          1: {
            cellWidth: 85,
          },
          2: {
            cellWidth: 85,
          },
          3: {
            cellWidth: 55,
          },
          4: {
            cellWidth: 85,
          },
          5: {
            cellWidth: 38,
          },
          6: {
            cellWidth: 50,
          },
          7: {
            cellWidth: 80,
          },
          8: {
            cellWidth: 80,
          },
          9: {
            cellWidth: 70,
          },
          10: {
            cellWidth: 65,
          },
          11: {
            cellWidth: 50,
          },
          12: {
            cellWidth: 50,
          }


        },
        styles: {
          minCellHeight: 20,
        }

      });


      doc.autoTable({
        html: '#fixTable3',
        theme: 'grid',
        startY: doc.lastAutoTable.finalY + 50,
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
            cellWidth: 93,
          },
          1: {
            cellWidth: 93,
          },
          2: {
            cellWidth: 100,
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
            cellWidth: 100,
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
        /*
                    doc.text('Page ' + String(i) + ' of ' + String(pageCount), 1190 - 320, 1207 - 20, null, null, "right");
        */

      }

      for (var i = 1; i <= pageCount; i++) {

        doc.setPage(i);

        const format1 = "YYYY-MM-DD";
        doc.setFontSize(14);
        doc.setTextColor(0, 0, 0);
        doc.setFontStyle('bold');
        doc.addImage(Imagedata, 'JPEG', 30, 15, 140, 40);
        doc.text(380, 20, factoryName);
        doc.text(250, 60, "Date Wise Print/Embr. Received And Delivery Summary " + '(' + moment().format("MMMM Do YYYY") + ')');
      }

      for (var i = 1; i <= pageCount; i++) {
        doc.setPage(i);
        var p = 10;
        doc.setFontSize(12);
        doc.setTextColor(0, 0, 0);
        doc.setFontStyle('normal');
        doc.text(360, 40, factoryAddress);
      }


      doc.save('Date_Wise_Print_Embr_Receive_production_Delivery__Summary.pdf');
    }


</script>
@endsection