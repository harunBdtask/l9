@extends('finance::layout')
@section('title', 'Balance Sheet')
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
                <form action="{{ url('finance/balance-sheet') }}" method="GET">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Start Date</label>
                            <input type="date" name="start_date" class="form-control form-control-sm" value="{{ request('start_date') ?? \Carbon\Carbon::today()->startOfMonth()->format('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>End Date</label>
                            <input type="date" name="end_date" class="form-control form-control-sm" value="{{ request('end_date') ?? \Carbon\Carbon::today()->endOfMonth()->format('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button class="form-control form-control-sm btn white">Search</button>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button class="form-control form-control-sm btn white print">Print</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <table class="reportTable">
                        <thead class="thead-light">
                            <tr>
                                <th class="text-left">HEAD OF ACCOUNT</th>
                                <th class="text-right">BALANCE</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th colspan="2" class="text-left">ASSET</th>
                            </tr>
                            @foreach($assets as $account)
                                <tr>
                                    <td class="text-left">&nbsp;&nbsp;{{ $account->name }}</td>
                                    <td class="text-right">
                                        {{ $account->balance >= 0 ? number_format($account->balance, 2) : '('.number_format(abs($account->balance), 2).')' }}
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <th class="text-left">Total of Asset</th>
                                <td class="text-right">
                                    @php
                                        $totalAsset = $assets->sum('balance');
                                    @endphp
                                    <strong>
                                        {{ $totalAsset >= 0 ? number_format($totalAsset, 2) : '('.number_format(abs($totalAsset), 2).')' }}
                                    </strong>
                                </td>
                            </tr>

                            <tr>
                                <th class="text-left" colspan="2">LIABILITY</th>
                            </tr>
                            @foreach($liabilities as $account)
                                <tr>
                                    <td class="text-left">&nbsp;&nbsp;{{ $account->name }}</td>
                                    <td class="text-right">
                                        {{ $account->balance >= 0 ? '('.number_format(abs($account->balance), 2).')' : number_format(abs($account->balance), 2) }}
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <th class="text-left">Total of Liabililty</th>
                                <td class="text-right">
                                    @php
                                        $totalLiability = $liabilities->sum('balance');
                                        $totalLiability = $totalLiability > 0 ? (-1*$totalLiability) : abs($totalLiability);
                                    @endphp
                                    <strong>
                                        {{ $totalLiability >= 0 ? number_format($totalLiability, 2) : '('.number_format(abs($totalLiability), 2).')' }}
                                    </strong>
                                </td>
                            </tr>

                            <tr>
                                <th colspan="2" class="text-left">EQUITY</th>
                            </tr>
                            @foreach($equities as $account)
                                <tr>
                                    <td class="text-left">&nbsp;&nbsp;{{ $account->name }}</td>
                                    <td class="text-right">
                                        {{ $account->balance >= 0 ? '('.number_format(abs($account->balance), 2).')' :  number_format(abs($account->balance), 2) }}
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <th class="text-left">Net Profit/Loss</th>
                                <td class="text-right"><strong>{{ number_format($net_profit, 2) }}</strong></td>
                            </tr>
                            <tr>
                                <th class="text-left">Total of Equity</th>
                                <td class="text-right">
                                    @php
                                        $totalEquity = $equities->sum('balance');
                                        $totalEquity = $totalEquity > 0 ? (-1*$totalEquity) : abs($totalEquity);

                                        $totalEquity += $net_profit;
                                    @endphp
                                    <strong>
                                        {{ $totalEquity >= 0 ? number_format($totalEquity, 2) : '('.number_format(abs($totalEquity), 2).')' }}
                                    </strong>
                                </td>
                            </tr>
                            <tr>
                                <th class="text-left">Total of Liability & Equity</th>
                                <td class="text-right">
                                    @php

                                        $liabilityEquity = $totalLiability + $totalEquity;
                                    @endphp
                                    <strong>
                                        {{ $liabilityEquity >= 0 ? number_format($liabilityEquity, 2) : '('.number_format(abs($liabilityEquity), 2).')' }}
                                    </strong>
                                </td>
                            </tr>
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
