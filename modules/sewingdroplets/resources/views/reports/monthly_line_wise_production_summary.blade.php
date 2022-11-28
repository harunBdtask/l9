@extends('sewingdroplets::layout')

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

  .select2-container .select2-selection--single {
    background-color: #ffffff !important;
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
@section('title', 'Monthly Line Wise Production Summary')
@section('content')
<div class="padding buyer-wise-sewing-report-page">
  <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header text-center">
          <h2>Monthly Line Wise Production Summary
            <span class="pull-right">
              <span style="list-style: none;display: inline-block" onclick="generatePDF()" , id="pdf"><i
                  style="cursor: pointer; color: #DC0A0B" class="fa fa-file-pdf-o"></i>&nbsp;</span>
              |
              <a
                href="{{ ($floor_id) ? url('/monthly-line-wise-production-summary-report-download?type=xls&floor_id='.$floor_id.'&line_id='.$line_id. '&year='.$year.'&month='.$month) : '#' }}"><i
                  style="color: #0F733B" class="fa fa-file-excel-o"></i></a>
            </span>
          </h2>
        </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body color-sewing-output">
          <div class="form-group">
            {!! Form::open(['url' => '/monthly-line-wise-production-summary-report','method' => 'GET']) !!}
            <div class="row m-b">
              <div class="col-sm-3">
                <label>Floor</label>
                {!! Form::select('floor_id', $floors, $floor_id, ['class' => 'form-control form-control-sm
                select2-input', 'placeholder' => 'Select Floor', 'onchange' => 'this.form.submit();']) !!}
              </div>
              <div class="col-sm-3">
                <label>Line</label>
                {!! Form::select('line_id', $lines, $line_id, ['class' => 'form-control form-control-sm select2-input',
                'placeholder' => 'Select Line', 'onchange' => 'this.form.submit();']) !!}
              </div>
              <div class="col-sm-3">
                <label>Year</label>
                {!! Form::selectYear('year', date('Y'), 2019, $year, ['class' => 'form-control form-control-sm
                select2-input', 'placeholder' => 'Select Year', 'onchange' => 'this.form.submit();']) !!}
              </div>
              <div class="col-sm-3">
                <label>Month</label>
                {!! Form::selectMonth('month', $month, ['class' => 'form-control form-control-sm select2-input',
                'placeholder' => 'Select Month', 'onchange' => 'this.form.submit();']) !!}
              </div>
            </div>
            {!! Form::close() !!}
          </div>

          <div id="parentTableFixed" class="table-responsive">
            <table class="reportTable" id="fixTable">
              @includeIf('sewingdroplets::reports.tables.monthly_line_wise_production_summary_table')
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
        textColor: [0, 0, 0],
        margin: {top: 80},
        headStyles: {
          fillColor: [168, 245, 255],
          textColor: [0, 0, 0],
        },
        bodyStyles: {lineColor: [0, 0, 0]},
        columnStyles: {
          0: {
            cellWidth: 120,
          },
          1: {
            cellWidth: 120,
          },
          2: {
            cellWidth: 120,
          },
          3: {
            cellWidth: 120,
          },
          4: {
            cellWidth: 120,
          },
          5: {
            cellWidth: 120,
          },
          6: {
            cellWidth: 120,
          },
          7: {
            cellWidth: 120,
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
        doc.text(300, 60, "Monthly Line Wise Production Summary " + '(' + moment().format("MMMM Do YYYY") + ')');
      }

      for (var i = 1; i <= pageCount; i++) {
        doc.setPage(i);
        var p = 10;
        doc.setFontSize(12);
        doc.setTextColor(0, 0, 0);
        doc.setFontStyle('normal');
        doc.text(360, 40, factoryAddress);
      }


      doc.save('Monthly_Line_Wise_Production_Summary.pdf');
    }
</script>
@endsection