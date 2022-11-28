@extends('sewingdroplets::layout')
@section('styles')
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

  @media screen and (-webkit-min-device-pixel-ratio: 0) {
    input[type=date].form-control form-control-sm {
      line-height: 1;
    }
  }
</style>
@endsection
@section('title', 'Date Wise Sewing Report')
@section('content')
<div class="padding">
  <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header text-center">
          <h2>Date Wise Sewing Report
            <span class="pull-right">
              {{--<a href="{{url("date-wise-sewing-output-report-download?type=pdf&floor_id=$floor_id&line_id=$line_id&from_date=$from_date&to_date=$to_date")}}">
              <i style="color: #DC0A0B" class="fa fa-file-pdf-o"></i>
              </a> --}}
              <span style="list-style: none;display: inline-block" onclick="generatePDF()" , id="pdf"><i
                  style="cursor: pointer; color: #DC0A0B" class="fa fa-file-pdf-o"></i>&nbsp;</span>
              |
              <a
                href="{{url("date-wise-sewing-output-report-download?type=xls&floor_id=$floor_id&line_id=$line_id&from_date=$from_date&to_date=$to_date")}}">
                <i style="color: #0F733B" class="fa fa-file-excel-o"></i>
              </a>
            </span>
          </h2>
        </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">

          <form action="{{ url('/date-wise-sewing-output-post') }}" method="get">
            <div class="form-group">
              <div class="row m-b">
                <div class="col-sm-2">
                  <label>Floor</label>
                  {!! Form::select('floor_id', $floors, $floor_id ?? null, ['class' => 'select2-input form-control
                  form-control-sm c-select', 'id' => 'floor', 'onchange' => 'this.form.submit();']) !!}

                  @if($errors->has('floor_id'))
                  <span class="text-danger">{{ $errors->first('floor_id') }}</span>
                  @endif
                </div>
                <div class="col-sm-2">
                  <label>Line</label>
                  {!! Form::select('line_id', $lines ?? [], $line_id ?? null, ['class' => 'select2-input form-control
                  form-control-sm c-select', 'id' => 'line', 'onchange' => 'this.form.submit();']) !!}

                  @if($errors->has('line_id'))
                  <span class="text-danger">{{ $errors->first('line_id') }}</span>
                  @endif
                </div>
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
                    value="{{ $to_date ?? date('Y-m-d') }}" onchange="this.form.submit();">

                  @if($errors->has('to_date'))
                  <span class="text-danger">{{ $errors->first('to_date') }}</span>
                  @endif
                  @if(session()->has('error'))
                  <span class="text-danger">{{ session()->get('error') }}</span>
                  @endif
                </div>
              </div>
            </div>
          </form>
          @include('sewingdroplets::reports.tables.date_range_wise_report')
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
                cellWidth: 100,
            },
            1: {
                cellWidth: 50,
            },
            2: {
                cellWidth: 150,
            },
            3: {
                cellWidth: 110,
            },
            4: {
                cellWidth: 110,
            },
            5: {
                cellWidth: 110,
            },
            6: {
                cellWidth: 110,
            },
            7: {
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
                cellWidth: 210,
            },
            1: {
                cellWidth: 210,
            },
            2: {
                cellWidth: 210,
            },
            3: {
                cellWidth: 210,
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
        textColor:[0,0,0],
        margin: {top: 80},
        headStyles: {
            fillColor: [168, 245, 255],
            textColor:[0,0,0],
        },
        bodyStyles: {lineColor: [0, 0, 0]},
        columnStyles: {
            0: {
                cellWidth: 140,
            },
            1: {
                cellWidth: 140,
            },
            2: {
                cellWidth: 140,
            },
            3: {
                cellWidth: 140,
            },
            4: {
                cellWidth: 140,
            },
            5: {
                cellWidth: 140,
            }

        },
        styles: {
            minCellHeight: 20,
        }

    });

    doc.autoTable({
        html: '#fixTable4',
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
        doc.text(300, 60, "Date Wise Sewing Report "+'('+moment().format("MMMM Do YYYY")+')');
    }

    for (var i = 1; i <= pageCount; i++) {
        doc.setPage(i);
        var p = 10;
        doc.setFontSize(12);
        doc.setTextColor(0, 0, 0);
        doc.setFontStyle('normal');
        doc.text(360, 40, factoryAddress);
    }


    doc.save('Date_Wise_Sewing_Report.pdf');
}
</script>
@endsection