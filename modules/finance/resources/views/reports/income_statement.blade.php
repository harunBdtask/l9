@php
    use SkylarkSoft\GoRMG\Finance\Models\Account;
@endphp

@extends('finance::layout')
@section('title', 'Income Statement')
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
            <h2>INCOME STATEMENT</h2>
        </div>
        <div class="box-body b-t">
            <div class="row">
                <form action="{{ url('finance/income-statement') }}" method="GET">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Start Date</label>
                            <input type="date" name="start_date" class="form-control form-control-sm" value="{{ request('start_date') ?? \Carbon\Carbon::today()->startOfMonth()->format('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
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
                                <th class='text-left' nowrap>HEAD OF ACCOUNTS</th>
                                <th class='text-left' nowrap>ACCOUNT CODE</th>
                                <th class="text-right" nowrap>AMOUNT</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach($accounts_by_type as $type => $accounts)
                                <tr>
                                    <td colspan="2" nowrap class='text-left'><strong>{{  strtoupper($type) }}</strong></td>
                                    <td></td>
                                </tr>
                                @foreach($accounts as $account)
                                    <tr>
                                        <td class='text-left'>{{ $account->name }}</td>
                                        <td class='text-left' nowrap>{{ $account->code }}</td>
                                        <td class='text-right' nowrap>
                                            @if(in_array($account->type_id, [Account::REVENUE_OP, Account::REVENUE_NOP]))
                                                {{ number_format(abs($account->balance), 2) }}
                                            @elseif($account->balance < 0)
                                                {{ '('.number_format(abs($account->balance), 2).')' }}
                                            @else
                                                {{ number_format($account->balance, 2) }}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="2" nowrap class='text-left'><strong>{{ strtoupper('Total of '.$type) }}</strong></td>
                                    <td class="text-right" nowrap>
                                        <strong>
                                            @if(in_array($account->type_id, [Account::REVENUE_OP, Account::REVENUE_NOP]))
                                                {{ number_format(abs($accounts->sum('balance')), 2) }}
                                            @elseif($accounts->sum('balance') < 0)
                                                {{ '('.number_format(abs($accounts->sum('balance')), 2).')' }}
                                            @else
                                                {{ number_format($accounts->sum('balance'), 2) }}
                                            @endif
                                        </strong>
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="2" nowrap class='text-left'>
                                    <strong>{{ strtoupper('Net Profit/Loss') }}</strong>
                                </td>
                                <td class="text-right" nowrap>
                                    <strong>
                                        @if($net_profit < 0)
                                            {{ '('.number_format(abs($net_profit), 2).')' }}
                                        @else
                                            {{ number_format($net_profit, 2) }}
                                        @endif
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
