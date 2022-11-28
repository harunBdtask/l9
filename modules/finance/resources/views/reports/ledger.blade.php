@extends('finance::layout')
@section('title', 'Ledger')
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

        .invalid, .invalid + .select2 .select2-selection {
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
                <h2>LEDGER DETAILS</h2>
            </div>
            <div class="box-body b-t">
                <div class="row">
                    <form action="{{ url('finance/ledger') }}" method="GET">
                        <div class="col-md-3">
                            <label class="">Head of Account</label>
                            @php
                                $accountId = request('account_id');
                            @endphp
                            <select class="form-control form-control-sm c-select select2-input" name="account_id"
                                    id="account_id">
                                @foreach($accounts as $ac)
                                    <option value="{{ $ac->id }}" data-id="{{ $ac->id }}" data-name="{{ $ac->name }}"
                                            data-code="{{ $ac->code }}" {{ $accountId == $ac->id ? 'selected' : '' }}>
                                        {{ $ac->name.' ('.$ac->code.')' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
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
                                <th class='text-left'>TRANSACTION DATE</th>
                                <th class='text-left'>VOUCHER NO</th>
                                <th class="text-left">PARTICULARS</th>
                                <th class="text-right">DEBIT</th>
                                <th class="text-right">CREDIT</th>
                                <th class="text-right">BALANCE</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td colspan="2" class='text-left'>
                                    @php
                                        $date = \Carbon\Carbon::today();

                                        if (request()->has('start_date')) {
                                            $date = \Carbon\Carbon::parse(request('start_date'));
                                        }
                                        $balance = $account ? $account->openingBalance($date) : 0.00;
                                    @endphp

                                    {{ $date->toFormattedDateString() }}
                                </td>
                                <td class='text-left' colspan="3"><strong>Balance Forward</strong></td>

                                <td class="text-right">
                                    @if($balance >= 0)
                                        <strong>{{ number_format(abs($balance), 2).' Dr' }}</strong>
                                    @else
                                        <strong>{{ number_format(abs($balance), 2).' Cr' }}</strong>
                                    @endif
                                </td>
                            </tr>
                            @if(isset($account))
                                @forelse($account->journalEntries as $journalEntry)
                                    @php
                                        if ($journalEntry->trn_type == 'dr') {
                                            $balance += $journalEntry->trn_amount;
                                        } else {
                                            $balance -= $journalEntry->trn_amount;
                                        }
                                    @endphp

                                    <tr>
                                        <td class='text-left'>{{ $journalEntry->trn_date->toFormattedDateString() }}</td>
                                        <td class='text-left'>{{ str_pad($journalEntry->voucher_id, 8, '0', STR_PAD_LEFT) }}</td>
                                        <td class='text-left'>{{ $journalEntry->particulars ?? '' }}</td>
                                        <td class="text-right">
                                            {{ $journalEntry->trn_type == 'dr' ? number_format($journalEntry->trn_amount, 2) : '' }}
                                        </td>
                                        <td class="text-right">
                                            {{ $journalEntry->trn_type == 'cr' ? number_format($journalEntry->trn_amount, 2) : '' }}
                                        </td>
                                        <td class="text-right">
                                            @if($loop->last)
                                                @if($balance >= 0)
                                                    <strong>{{ number_format(abs($balance), 2).' Dr' }}</strong>
                                                @else
                                                    <strong>{{ number_format(abs($balance), 2).' Cr' }}</strong>
                                                @endif
                                            @else
                                                @if($balance >= 0)
                                                    {{ number_format(abs($balance), 2).' Dr' }}
                                                @else
                                                    {{ number_format(abs($balance), 2).' Cr' }}
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-danger">No transaction</td>
                                    </tr>
                                @endforelse
                            @endif
                            <tr>
                                <td class='text-center' colspan="3"><strong>TOTAL</strong></td>
                                <td class="text-right">
                                    {{
                                        isset($account) ? number_format($account->journalEntries->sum(function($item) {
                                            return $item->trn_type == 'dr' ? $item->trn_amount : 0;
                                        }), 2) : 0.00
                                    }}
                                </td>
                                <td class="text-right">
                                    {{
                                        isset($account) ? number_format($account->journalEntries->sum(function($item) {
                                            return $item->trn_type == 'cr' ? $item->trn_amount : 0;
                                        }), 2) : 0.00
                                    }}
                                </td>
                                <td class="text-right"></td>
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
