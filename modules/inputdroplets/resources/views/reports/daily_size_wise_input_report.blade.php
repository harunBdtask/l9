@php
    $tableHeadColorClass = 'tableHeadColor';
    if (isset($type) || request()->has('type') || request()->route('type')) {
      $tableHeadColorClass = '';
    }
@endphp
@extends('inputdroplets::layout')
@section('title', 'Daily Size wise Input')
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header text-center">
                        <h2>Daily Size Wise Input || {{ date("jS F, Y") }}
                            <span class="pull-right">
                <a href="{{url('report/daily-size-wise-input/pdf?date='.(request('date') ?? date('Y-m-d')))}}">
                    <em style="color: #DC0A0B" class="fa fa-file-pdf-o"></em></a>
                  |
                  <a href="{{url('report/daily-size-wise-input/xls?date='.(request('date') ?? date('Y-m-d')))}}">
                      <em style="color: #0F733B" class="fa fa-file-excel-o"></em></a>
                </span>
                        </h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body">

                        <form action="{{ url('report/daily-size-wise-input') }}" method="get">
                            <div class="form-group">
                                <div class="row m-b">
                                    <div class="col-sm-3">
                                        <input type="date" name="date" class="form-control form-control-sm"
                                               required="required"
                                               value="{{ request('date') ?? date('Y-m-d') }}">
                                    </div>
                                    <div class="col-sm-1">
                                        <button type="submit" class="btn btn-sm btn-info form-control form-control-sm">
                                            Search
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <!-- challan no wise -->
                        @include('inputdroplets::reports.tables.daily_size_wise_input_table')
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
                doc.text(380, 20, factoryName);
                doc.text(330, 60, "Daily Size Wise Input " + '(' + moment().format("MMMM Do YYYY") + ')');
            }

            for (var i = 1; i <= pageCount; i++) {
                doc.setPage(i);
                var p = 10;
                doc.setFontSize(12);
                doc.setTextColor(0, 0, 0);
                doc.setFontStyle('normal');
                doc.text(360, 40, factoryAddress);
            }

            doc.addPage();

            doc.save('Daily_Size_wise_Input.pdf');
        }

    </script>
@endsection
