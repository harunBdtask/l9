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
                <h2>TRIAL BALANCE</h2>
            </div>
            <div class="box-body b-t">
                <div class="row">
                    <form action="{{ url('basic-finance/trial-balance') }}" method="GET">
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
                                <button class="form-control form-control-sm btn btn-info">Search</button>
                            </div>
                        </div>
                        <div class="col-md-4"></div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button class="form-control form-control-sm btn white print">Print</button>
                            </div>
                        </div>
                    </form>
                    <div class="col-md-2">
                        <label>&nbsp;</label>
                        <form action="{{url('/basic-finance/trial-balance-tree/')}}">
                            <button class="form-control form-control-sm white"> Trial Balance Tree View</button>
                        </form>
                    </div>

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
                            @php $openingDebitTotalBalance = $openingCreditTotalaBalance = $transactionDebitTotalBalance = $transactionCreditTotalBalance = $closingDebitTotalBalance = $closingCreditTotalBalance = 0; @endphp
                            @foreach($accounts as $account)
                                @php
                                    $openingDebitBalance = $account->openingBalance >= 0 ? $account->openingBalance : 0.00;
                                    $openingCreditBalance = $account->openingBalance < 0 ? abs($account->openingBalance) : 0.00;
                                    $transactionDebitBalance = $account->transactionBalance >= 0 ? $account->transactionBalance : 0.00;
                                    $transactionCreditBalance = $account->transactionBalance < 0 ? abs($account->transactionBalance) : 0.00;
                                    $closingDebitBalance = $account->closingBalance >= 0 ? $account->closingBalance : 0.00;
                                    $closingCreditBalance = $account->closingBalance < 0 ? abs($account->closingBalance) : 0.00;
                                    $openingDebitTotalBalance = $openingDebitTotalBalance +  $openingDebitBalance;
                                    $openingCreditTotalaBalance = $openingCreditTotalaBalance +  $openingCreditBalance;
                                    $transactionDebitTotalBalance = $transactionDebitTotalBalance +  $transactionDebitBalance;
                                    $transactionCreditTotalBalance = $transactionCreditTotalBalance +  $transactionCreditBalance;
                                    $closingDebitTotalBalance = $closingDebitTotalBalance +  $closingDebitBalance;
                                    $closingCreditTotalBalance = $closingCreditTotalBalance + $closingCreditBalance;
                                @endphp
                                <tr>
                                    <td class="text-left">{{ $account->code }}</td>
                                    <td class="text-left">{{ $account->name }}</td>
                                    <td class="text-right">{{ number_format($openingDebitBalance,2) }}</td>
                                    <td class="text-right">{{ number_format($openingCreditBalance,2) }}</td>
                                    <td class="text-right">{{ number_format($transactionDebitBalance,2) }}</td>
                                    <td class="text-right">{{ number_format($transactionCreditBalance,2) }}</td>
                                    <td class="text-right">{{ number_format($closingDebitBalance,2) }}</td>
                                    <td class="text-right">{{ number_format($closingCreditBalance,2) }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="text-left" colspan="2"><b>{{ 'Total' }}</b></td>
                                <td class="text-right"><b>{{ number_format($openingDebitTotalBalance,2) }}</b></td>
                                <td class="text-right"><b>{{ number_format($openingCreditTotalaBalance,2) }}</b></td>
                                <td class="text-right"><b>{{ number_format($transactionDebitTotalBalance,2) }}</b></td>
                                <td class="text-right"><b>{{ number_format($transactionCreditTotalBalance,2) }}</b></td>
                                <td class="text-right"><b>{{ number_format($closingDebitTotalBalance,2) }}</b></td>
                                <td class="text-right"><b>{{ number_format($closingCreditTotalBalance,2) }}</b></td>
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


//Controller code old
// $openingAccounts = Journal::with('account')
//            ->whereDate('trn_date', '<', $this->startDate)
//            ->orderBy('account_id')
//            ->get()
//            ->groupBy('account_id')
//            ->map(function ($entries) {
//                $openingAccount = $entries->first()->account;
//                $openingAccount->balance = $entries->sum(function ($item) {
//                    return $item->trn_type == 'cr' ? (-1) * $item->trn_amount : $item->trn_amount;
//                });
//                return $openingAccount;
//            });
//        $transactionAccounts = Journal::with('account')
//            ->whereDate('trn_date', '>=', $this->startDate)
//            ->whereDate('trn_date', '<', $this->endDate)
//            ->orderBy('account_id')
//            ->get()
//            ->groupBy('account_id')
//            ->map(function ($entries) {
//                $transactionAccount = $entries->first()->account;
//                $transactionAccount->balance = $entries->sum(function ($item) {
//                    return $item->trn_type == 'cr' ? (-1) * $item->trn_amount : $item->trn_amount;
//                });
//                return $transactionAccount;
//            });
//
//        $closingAccounts = Journal::with('account')
//            ->whereDate('trn_date', '<', $this->endDate)
//            ->orderBy('account_id')
//            ->get()
//            ->groupBy('account_id')
//            ->map(function ($entries) {
//                $closingAccount = $entries->first()->account;
//                $closingAccount->balance = $entries->sum(function ($item) {
//                    return $item->trn_type == 'cr' ? (-1) * $item->trn_amount : $item->trn_amount;
//                });
//                return $closingAccount;
//            });
//
//        $openingAccountsCode = $transactionAccountsCode = $closingAccountsCode = [];
//        $openingAccountsName = $transactionAccountsName = $closingAccountsName = [];
//        $accounts['openingBalanceDebitData'] = [];
//        $accounts['openingBalanceCreditData'] = [];
//        $accounts['transactionBalanceDebitData'] = [];
//        $accounts['transactionBalanceCreditData'] = [];
//        $accounts['closingBalanceDebitData'] = [];
//        $accounts['closingBalanceCreditData'] = [];
//
//        if (isset($openingAccounts)) {
//            foreach ($openingAccounts as $openingAccount) {
//                array_push($openingAccountsCode, $openingAccount->code);
//                array_push($openingAccountsName, $openingAccount->name);
//            }
//
//            if (isset($transactionAccounts)) {
//                foreach ($transactionAccounts as $transactionAccount) {
//                    array_push($transactionAccountsCode, $transactionAccount->code);
//                    array_push($transactionAccountsName, $transactionAccount->name);
//                }
//            }
//            if (isset($closingAccounts)) {
//                foreach ($closingAccounts as $closingAccount) {
//                    array_push($closingAccountsCode, $closingAccount->code);
//                    array_push($closingAccountsName, $closingAccount->name);
//                }
//            }
//            $accountsCode = array_merge($openingAccountsCode, $transactionAccountsCode, $closingAccountsCode);
//            $accountsName = array_merge($openingAccountsName, $transactionAccountsName, $closingAccountsName);
//
//            $accounts['accountsCode'] = collect($accountsCode)->unique()->toArray();
//            $accounts['accountsName'] = collect($accountsName)->unique()->toArray();
//
//            if (isset($openingAccounts)) {
//                foreach ($openingAccounts as $openingAccount) {
//                    if (in_array($openingAccount->code, $openingAccountsCode, TRUE)) {
//                        array_push($accounts['openingBalanceDebitData'], $openingAccount->balance >= 0.00 ? abs($openingAccount->balance) : 0.00);
//                        array_push($accounts['openingBalanceCreditData'], $openingAccount->balance < 0.00 ? abs($openingAccount->balance) : 0.00);
//                    }
//                    if (in_array($openingAccount->code, $transactionAccountsCode, FALSE)) {
//                        array_push($accounts['transactionBalanceDebitData'], 0.00);
//                        array_push($accounts['transactionBalanceCreditData'], 0.00);
//                    }
//                }
//            }
//        }
//        if (isset($transactionAccounts)) {
//            foreach ($transactionAccounts as $transactionAccount) {
//                if (in_array($transactionAccount->code, $transactionAccountsCode, TRUE)) {
//                    array_push($accounts['transactionBalanceDebitData'], $transactionAccount->balance >= 0.00 ? abs($transactionAccount->balance) : 0.00);
//                    array_push($accounts['transactionBalanceCreditData'], $transactionAccount->balance < 0.00 ? abs($transactionAccount->balance) : 0.00);
//                }
//                if (in_array($transactionAccount->code, $openingAccountsCode, FALSE)) {
//                    array_push($accounts['openingBalanceDebitData'], 0.00);
//                    array_push($accounts['openingBalanceCreditData'], 0.00);
//                }
//            }
//        }
//        if (isset($closingAccounts)) {
//            foreach ($closingAccounts as $closingAccount) {
//                if (in_array($closingAccount->code, $closingAccountsCode, TRUE)) {
//                    array_push($accounts['closingBalanceDebitData'], $closingAccount->balance >= 0.00 ? abs($closingAccount->balance) : 0.00);
//                    array_push($accounts['closingBalanceCreditData'], $closingAccount->balance < 0.00 ? abs($closingAccount->balance) : 0.00);
//                }
//                if (in_array($closingAccount->code, $openingAccountsCode, FALSE)) {
//                    array_push($accounts['openingBalanceDebitData'], 0.00);
//                    array_push($accounts['openingBalanceCreditData'], 0.00);
//                }
//                if (in_array($closingAccount->code, $transactionAccountsCode, FALSE)) {
//                    array_push($accounts['transactionBalanceDebitData'], 0.00);
//                    array_push($accounts['transactionBalanceCreditData'], 0.00);
//                }
//            }
//        }
