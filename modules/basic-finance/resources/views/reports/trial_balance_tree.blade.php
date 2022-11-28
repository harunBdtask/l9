@extends('basic-finance::layout')
@section('title', 'Trial Balance')
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

        input[type=date].form-control form-control-sm, input[type=time].form-control form-control-sm, input[type=datetime-local].form-control form-control-sm, input[type=month].form-control form-control-sm {
            line-height: 1rem;
        }

        tbody tr:hover {
            background-color: lightcyan;
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
                <h2>TRIAL BALANCE TREE VIEW</h2>
            </div>
            <div class="box-body b-t">
                <div class="row">
                    <form action="{{ url('basic-finance/trial-balance-tree') }}" method="GET">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Start Date</label>
                                <input type="date" name="start_date" class="form-control form-control-sm"
                                       value="{{ request('start_date') ?? \Carbon\Carbon::today()->startOfMonth()->format('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>End Date</label>
                                <input type="date" name="end_date" class="form-control form-control-sm"
                                       value="{{ request('end_date') ?? \Carbon\Carbon::today()->endOfMonth()->format('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button class="form-control form-control-sm btn white">Search</button>
                            </div>
                        </div>
                        <div class="col-md-6"></div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button class="form-control form-control-sm white print">Print</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <table class="reportTable">
                            <thead class="thead-light">
                            <tr>
                                <th rowspan="2">AC Code</th>
                                <th rowspan="2">AC Head</th>
                                <th colspan="2">Opening Balance</th>
                                <th colspan="2">Transaction Balance</th>
                                <th colspan="2">Closing Balance</th>
                            </tr>
                            <tr>
                                <th>Debit [BDT]</th>
                                <th>Credit [BDT]</th>
                                <th>Debit [BDT]</th>
                                <th>Credit [BDT]</th>
                                <th>Debit [BDT]</th>
                                <th>Credit [BDT]</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($allTrialBalanceFormattedData as $singleTrialBalanceData)
                                @php
                                    $val = 25*(int)$singleTrialBalanceData['space_level'] . 'px';
                                @endphp
                                <tr>
                                    <td>{{ $singleTrialBalanceData['code'] }}</td>
                                    <td class="text-left"style="padding-left: {{$val}};">{{$singleTrialBalanceData['name']}}</td>
                                    <td class="text-right">{{number_format($singleTrialBalanceData['openingDebitBalance'],2)}}</td>
                                    <td class="text-right">{{number_format($singleTrialBalanceData['openingCreditBalance'],2)}}</td>
                                    <td class="text-right">{{number_format($singleTrialBalanceData['transactionDebitBalance'],2)}}</td>
                                    <td class="text-right">{{number_format($singleTrialBalanceData['transactionCreditBalance'],2)}}</td>
                                    <td class="text-right">{{number_format($singleTrialBalanceData['closingDebitBalance'],2)}}</td>
                                    <td class="text-right">{{number_format($singleTrialBalanceData['closingCreditBalance'],2)}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $('.print').click(function (e) {
            e.preventDefault();

            var url = window.location.toString();

            if (url.includes('?')) {
                url += '&print=true';
            } else {
                url += '?print=true';
            }

            printPage(url);
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
