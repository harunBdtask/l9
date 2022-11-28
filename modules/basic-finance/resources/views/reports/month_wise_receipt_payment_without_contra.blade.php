@extends('basic-finance::layout')

@section('styles')
    <style type="text/css">
        td {
            padding-right: 5px;
            padding-left: 5px;
        }

        th {
            padding-right: 8px;
            padding-left: 8px;
        }

        tbody tr:hover {
            background-color: lightcyan;
        }

        .addon-btn-primary {
            padding: 0;
            margin: 0px;
            background: #0275d8;
        }

        .addon-btn-primary:hover {
            background: #025aa5;
        }

        select.c-select {
            min-height: 2.375rem;
        }

        input[type=date].form-control, input[type=time].form-control, input[type=datetime-local].form-control, input[type=month].form-control {
            line-height: 1rem;
        }

        .reportTable th.text-left, .reportTable td.text-left {
            text-align: left;
            padding-left: 5px;
        }

        .reportTable th.text-right, .reportTable td.text-right {
            text-align: right;
            padding-right: 5px;
        }

        .reportTable th.text-center, .reportTable td.text-center {
            text-align: center;
        }
    </style>
@endsection

@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Actual Month Wise Receipt Payment Report</h2>
            </div>
            <div class="box-body b-t">
                <div class="row">
                    <form action="{{url('basic-finance/month-wise-receipt-payment-report-without-contra-voucher')}}">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Start Date</label>
                                {!! Form::month('start_date', request('start_date'), ['class'=>'form-control', 'id' => 'start_date']) !!}
{{--                                <input type="date" name="start_date" class="form-control" value="{{ \Carbon\Carbon::parse(request('start_date'))->format('Y-m')?? \Carbon\Carbon::today()->startOfMonth()->format('Y-m') }}">--}}
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>End Date</label>
                                {!! Form::month('end_date', request('end_date'), ['class'=>'form-control', 'id' => 'end_date']) !!}
{{--                                <input type="date" name="end_date" class="form-control" value="{{ \Carbon\Carbon::parse(request('end_date'))->format('Y-m') ?? \Carbon\Carbon::today()->endOfMonth()->format('Y-m') }}">--}}
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button class="form-control btn btn-xs btn-primary">Search</button>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button class="form-control btn btn-xs btn-danger print">Print</button>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button class="form-control btn btn-xs btn-success excel">Excel</button>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button class="form-control btn btn-xs btn-success pdf">PDF</button>
                            </div>
                        </div>
                    </form>
                </div>
                @includeIf('basic-finance::tables.month_wise_receipt_payment_table_without_contra')
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript">
        $('.print').click(function (e) {
            e.preventDefault();

            let url = window.location.toString();

            if (url.includes('?')) {
                url += '&type=print';
            } else {
                url += '?type=print';
            }

            printPage(url);
        });

        $('.excel').click(function (e) {
            e.preventDefault();

            let url = window.location.toString();

            if (url.includes('?')) {
                url += '&type=excel';
            } else {
                url += '?type=excel';
            }

            window.open(url, '_blank');
        });

        $('.pdf').click(function (e) {
            e.preventDefault();

            let url = window.location.toString();

            if (url.includes('?')) {
                url += '&type=pdf';
            } else {
                url += '?type=pdf';
            }

            window.open(url, '_blank');
        });

        function closePrint() {
            document.body.removeChild(this.__container__);
        }

        function setPrint() {
            this.contentWindow.__container__ = this;
            this.contentWindow.onbeforeunload = closePrint;
            this.contentWindow.onafterprint = closePrint;
            this.contentWindow.focus(); // Required for IE
            this.contentWindow.print();
        }

        function printPage(sURL) {
            var oHiddFrame = document.createElement("iframe");
            oHiddFrame.onload = setPrint;
            oHiddFrame.style.visibility = "hidden";
            oHiddFrame.style.position = "fixed";
            oHiddFrame.style.right = "0";
            oHiddFrame.style.bottom = "0";
            oHiddFrame.src = sURL;
            document.body.appendChild(oHiddFrame);
        }
    </script>
@endsection
