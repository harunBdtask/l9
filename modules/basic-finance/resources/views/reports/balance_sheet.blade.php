@extends('basic-finance::layout')
@section('title','Basic Finance')

@section('styles')
<style type="text/css">
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
    .select2-selection {
        min-height: 2.375rem;
    }
    .select2-selection__rendered, .select2-selection__arrow {
        margin: 4px;
    }
    .invalid, .invalid+.select2 .select2-selection {
        border-color: red !important;
    }
    td {
        padding-right: 8px;
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
            <h2>BALANCE SHEET</h2>
        </div>
        <div class="box-body b-t">
            <div class="row">
                <form action="{{ url('basic-finance/balance-sheet') }}" method="GET">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Start Date</label>
                            <input type="date" name="start_date" class="form-control" value="{{ request('start_date') ?? \Carbon\Carbon::today()->startOfMonth()->format('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>End Date</label>
                            <input type="date" name="end_date" class="form-control" value="{{ request('end_date') ?? \Carbon\Carbon::today()->endOfMonth()->format('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button class="form-control btn btn-xs white">Search</button>
                        </div>
                    </div>
                    <div class="col-md-4"></div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button class="form-control btn btn-xs white print">Print</button>
                        </div>
                    </div>
{{--                    <div class="col-md-1">--}}
{{--                        <div class="form-group">--}}
{{--                            <label>&nbsp;</label>--}}
{{--                            <button class="form-control btn btn-xs white excel">Excel</button>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                </form>
            </div>

            @includeIf('basic-finance::tables.balance_sheet_table')
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $('.print').click(function(e) {
            e.preventDefault();

            var url = window.location.toString();

            if (url.includes('?')) {
                url += '&print=true';
            } else {
                url += '?print=true';
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

        function closePrint () {
            document.body.removeChild(this.__container__);
        }

        function setPrint () {
            this.contentWindow.__container__ = this;
            this.contentWindow.onbeforeunload = closePrint;
            this.contentWindow.onafterprint = closePrint;
            this.contentWindow.focus(); // Required for IE
            this.contentWindow.print();
        }

        function printPage (sURL) {
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
