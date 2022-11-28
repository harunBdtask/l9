@php
$tableHeadColorClass = 'tableHeadColor';
if (isset($type) || request()->has('type') || request()->route('type')) {
$tableHeadColorClass = '';
}
@endphp
@extends('misdroplets::layout')
@section('title', 'Audit Report')
@section('styles')
<style>
  .select2-container--default .select2-selection--single {
    height: 35px !important;
    border-radius: 0px !important;
    border-color: rgba(120, 130, 140, 0.2) !important;
  }

  .select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 40px !important;
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
@endsection
@section('content')
<div class="padding">
  <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header text-center">
          <h2>Audit Report
            @php
            $currentPage = $reports && $reports->count() ? $reports->currentPage() : 1;
            @endphp
            <span class="pull-right">

              <span class="hidden-print" style="list-style: none;display: inline-block; cursor: pointer;"
                onclick="generate()" title="Download this pdf"> <i style="color: #DC0A0B"
                  class="fa fa-file-pdf-o"></i></span>

              {{--<a href="{{ url('/audit-report-download/pdf?buyer_id='.($buyer_id ?? '').'&order_id='.($order_id ?? '').'&from_date='.$from_date.'&to_date='.$to_date.'&current_page='.$currentPage) }}"
              class="">
              <i style="color: #DC0A0B" class="fa fa-file-pdf-o"></i>
              </a>--}}
              |
              <a href="{{ url('/audit-report-download/xls?buyer_id='.($buyer_id ?? '').'&order_id='.($order_id ?? '').'&from_date='.$from_date.'&to_date='.$to_date.'&current_page='.$currentPage) }}"
                class="">
                <i style="color: #0F733B" class="fa fa-file-excel-o"></i>
              </a>
            </span>
          </h2>
        </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body order-wise-print">
          <form action="{{ url('/audit-report') }}" method="get">
            <div class="form-group">
              <div class="row m-b">
                <div class="col-sm-2">
                  <label>Buyer</label>
                  {!! Form::select('buyer_id', $buyers, $buyer_id ?? null, ['class' => 'form-control form-control-sm
                  select2-input', 'placeholder' => 'All Buyer', 'onchange' =>
                  'this.form.submit();']) !!}

                  @if($errors->has('buyer_id'))
                  <span class="text-danger">{{ $errors->first('buyer_id') }}</span>
                  @endif
                </div>
                @php
                if (old('buyer_id')) {
                  $orders = \SkylarkSoft\GoRMG\Merchandising\Models\Order::where('buyer_id', old('buyer_id'))->pluck('style_name', 'id');
                }
                if (old('order_id')) {
                  $order_id = old('order_id');
                }
                @endphp
                <div class="col-sm-2">
                  <label>Style/Order</label>
                  {!! Form::select('order_id', $orders ?? [], $order_id ?? null, ['class' =>
                  'form-control form-control-sm select2-input', 'placeholder' => 'All Style/Order', 'onchange' =>
                  'this.form.submit();']) !!}

                  @if($errors->has('order_id'))
                  <span class="text-danger">{{ $errors->first('order_id') }}</span>
                  @endif
                </div>
                <div class="col-sm-2">
                  <label>From Date</label>
                  {!! Form::date('from_date', $from_date ?? null, ['class' => 'form-control form-control-sm',
                  'placeholder' => 'From date', 'onchange' => 'this.form.submit();']) !!}

                  @if($errors->has('from_date'))
                  <span class="text-danger">{{ $errors->first('from_date') }}</span>
                  @endif
                </div>
                <div class="col-sm-2">
                  <label>To Date</label>
                  {!! Form::date('to_date', $to_date ?? null, ['class' => 'form-control form-control-sm',
                  'placeholder' => 'To date', 'onchange' => 'this.form.submit();']) !!}

                  @if($errors->has('to_date'))
                  <span class="text-danger">{{ $errors->first('to_date') }}</span>
                  @endif
                </div>
                {{--<div class="col-sm-2">
                                        <label>&nbsp;</label>
                                        <button type="submit" class="btn btn-sm white form-control form-control-sm">Search</button>
                                    </div>--}}
              </div>
            </div>
          </form>
          <div class="row">
            <div id="parentTableFixed" class="table-responsive">
              <table class="reportTable" id="fixTable" style="border-collapse: collapse;">
                @include('misdroplets::reports.tables.audit_report_table')
              </table>

            </div>
            @if($reports && $reports->count() && $print)
            <div class="col-md-12 text-center">
              {{ $reports->appends($search_data)->links() }}
            </div>
            @endif
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
<script>
  $(document).ready(function () {
            $("#fixTable").tableHeadFixer();
        });

        var tfoot = document.getElementById('tfoot');

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
            doc.line(3, 70, 900,70);

            console.log('tfoot');
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
                        cellWidth: 40,
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
                        cellWidth: 30,
                    },
                    10: {
                        cellWidth: 30,
                    },
                    11: {
                        cellWidth: 30,
                    },
                    12: {
                        cellWidth: 30,
                    },
                    13: {
                        cellWidth: 30,
                    },
                    14: {
                        cellWidth: 30,
                    },
                    15: {
                        cellWidth: 30,
                    },
                    16: {
                        cellWidth: 30,
                    },
                    17: {
                        cellWidth: 30,
                    },
                    18: {
                        cellWidth: 30,
                    },
                    19: {
                        cellWidth: 30,
                    },
                    20: {
                        cellWidth: 30,
                    },
                    21: {
                        cellWidth: 25,
                    },
                    22: {
                        cellWidth: 25,
                    },
                    23: {
                        cellWidth: 30,
                    },
                    24: {
                        cellWidth: 30,
                    },
                    25: {
                        cellWidth: 30,
                    },

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
                doc.text(360, 60, "Audit Report" + '(' + moment().format("MMMM Do YYYY") + ')');
            }

            for (var i = 1; i <= pageCount; i++) {
                doc.setPage(i);
                var p = 10;
                doc.setFontSize(12);
                doc.setTextColor(0, 0, 0);
                doc.setFontStyle('normal');
                doc.text(360, 40, factoryAddress);
            }

            doc.save('audit_report.pdf');
        }
</script>
@endsection