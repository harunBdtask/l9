@extends('sewingdroplets::layout')
@push('style')
<style>
  .select2-container--default .select2-selection--single {
    height: 35px !important;
    border-radius: 0px !important;
    border-color: rgba(120, 130, 140, 0.2) !important;
  }

  .select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 35px !important;
    /*width: 120px !important;*/
  }

  .select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 33px;
  }

  #parentTableFixed {
    height: 400px !important;
  }

  .box-header {
    padding-top: .60rem !important;
    padding-bottom: .60rem !important;
  }

  @media screen and (-webkit-min-device-pixel-ratio: 0) {
    input[type=date].form-control form-control-sm {
      line-height: 1;
    }
  }
</style>
@endpush
@section('title', 'Floor & Line Wise Input, Output Report')
@section('content')
<div class="padding">
  <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header text-center">
          <h2>Floor &amp; Line Wise Input, Output Report
            <span class="pull-right">
              @php
              $floorId = $floor_id;
              $fromDate = $from_date;
              $toDate = $to_date;
              @endphp
              {{--<span  onclick="generate()" , id="pdf"><a class="hidden-print btn btn-xs" title="Download this pdf">
                        <i style="color: #DC0A0B" class="fa fa-file-pdf-o"></i>&nbsp;PDF</a></span>--}}

              {{--<a href="{{
                  $floor_id
                  ? url("floor-line-wise-sewing-report-download?type=pdf&floor_id=$floor_id&from_date=$fromDate&to_date=$toDate")
                  : '#'}}">
              <i style="color: #DC0A0B" class="fa fa-file-pdf-o"></i>
              </a>--}}
              <span class="hidden-print" style="list-style: none;display: inline-block; cursor: pointer;"
                onclick="generate()" title="Download this pdf"> <i style="color: #DC0A0B"
                  class="fa fa-file-pdf-o"></i></span>

              |
              <a href="{{ $floor_id
                  ? url("floor-line-wise-sewing-report-download?type=xls&floor_id=$floor_id&from_date=$fromDate&to_date=$toDate")
                  : '#'}}">
                <i style="color: #0F733B" class="fa fa-file-excel-o"></i>
              </a>
            </span>
          </h2>
        </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form action="{{ url('/floor-line-wise-sewing-report') }}" method="get">
            <div class="form-group">
              <div class="row m-b">
                <div class="col-sm-2">
                  <label>Floor</label>
                  {!! Form::select('floor_id', $floors, $floor_id ?? null, ['class' => 'select2-input form-control
                  form-control-sm c-select', 'id' => 'floor', 'required', 'onchange' => 'this.form.submit();']) !!}

                  @if($errors->has('floor_id'))
                  <span class="text-danger">{{ $errors->first('floor_id') }}</span>
                  @endif
                </div>
                <div class="col-sm-3">
                  <label>From Date</label>
                  {!! Form::date('from_date', $from_date , ['class' => 'form-control form-control-sm', 'required']) !!}
                </div>
                <div class="col-sm-3">
                  <label>To Date</label>
                  {!! Form::date('to_date', $to_date, ['class' => 'form-control form-control-sm', 'required', 'onchange'
                  => 'this.form.submit();']) !!}
                  @if(session()->has('error'))
                  <span class="text-danger">{{ session()->get('error') }}</span>
                  @endif
                </div>
              </div>
            </div>
          </form>
          <div id="parentTableFixed" class="table-responsive">
            @include('sewingdroplets::reports.tables.floor_line_wise_sewing_report_table_for_view')
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('scripts')
<script src="{{ asset('/js/tableHeadFixer.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js"></script>
<script src="{{ asset('/modules/skeleton/flatkit/assets/jspdf/jspdftable.js') }}"></script>
{{--for csv--}}
<script src="https://rawcdn.githack.com/FuriosoJack/TableHTMLExport/v2.0.0/src/tableHTMLExport.js"></script>
<script type="text/javascript" src="https://oss.sheetjs.com/sheetjs/xlsx.full.min.js"></script>
{{--end csv--}}
<script>
  $(document).ready(function () {
      $("#fixTable").tableHeadFixer();
    });

    function generate() {
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
        textColor: [0, 0, 0],
        margin: {top: 80},
        headStyles: {
          fillColor: [168, 245, 255],
          textColor: [0, 0, 0],
        },
        bodyStyles: {lineColor: [0, 0, 0]},
        columnStyles: {
          0: {
            cellWidth: 100,
          },
          1: {
            cellWidth: 40,
          },
          2: {
            cellWidth: 100,
          },
          3: {
            cellWidth: 100,
          },
          4: {
            cellWidth: 65,
          },
          5: {
            cellWidth: 100,
          },
          6: {
            cellWidth: 50,
          },
          7: {
            cellWidth: 60,
          },
          8: {
            cellWidth: 50,
          },
          9: {
            cellWidth: 60,
          },
          10: {
            cellWidth: 55,
          },
          11: {
            cellWidth: 40,
          },
          12: {
            cellWidth: 60,
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
        //every header hr line
        /*doc.line(3, 70, 900,70);*/
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
        doc.text(285, 60, "Floor & Line Wise Input, Output Report " + '(' + moment().format("MMMM Do YYYY") + ')');
      }

      for (var i = 1; i <= pageCount; i++) {
        doc.setPage(i);
        var p = 10;
        doc.setFontSize(12);
        doc.setTextColor(0, 0, 0);
        doc.setFontStyle('normal');
        doc.text(360, 40, factoryAddress);
      }


      doc.save('floor_line_wise_input_Output_report.pdf');
    }
</script>
@endsection