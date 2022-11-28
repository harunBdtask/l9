@extends('basic-finance::layout')

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

        .custom-padding {
            padding: 0px 40px 0 40px;
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
                    {!! Form::open(['url'=>'basic-finance/ledger-v2', 'method'=>'get']) !!}
                    @php
                        $companies=collect($companies)->prepend('Select Company','');
                        $projects=collect($projects)->prepend('All Project',0);
                        $units=collect($units)->prepend('All Unit',0);
                        $departments=collect($departments)->prepend('All Department',0);
                        $cost_centers=collect($cost_centers)->prepend('All Cost Centre',0);
                    @endphp
                    <div class="form-group row custom-padding">
                        <div class="col-sm-4">
                            <label class="col-sm-4 form-control-label"><b>From Date</b></label>
                            <div class="col-sm-8">
                                {!! Form::date("start_date", request('start_date') ?? now(),['class'=>"form-control form-control-sm", 'onchange' => 'this.form.submit();']) !!}
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <label class="col-sm-4 form-control-label"><b>To Date</b></label>
                            <div class="col-sm-8">
                                {!! Form::date("end_date", request('end_date') ?? now(),['class'=>"form-control form-control-sm", 'onchange' => 'this.form.submit();']) !!}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="col-sm-4 form-control-label"><b>Ledger Name</b></label>
                            <div class="col-sm-8">
                                {!! Form::select("account_id", $ledger_accounts ?? [], request('account_id') ?? null,["class"=>"form-control c-select select2-input", "id"=>"account_id", 'onchange' => 'this.form.submit();', 'required']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group row custom-padding">
                        <div class="col-md-4">
                            <label class="col-sm-4 form-control-label"><b>Company Name</b></label>
                            <div class="col-sm-8">
                                {!! Form::select("company_id", $companies ?? [],request('company_id') ?? null,["class"=>"form-control c-select select2-input", "id"=>"company_id", 'onchange' => 'this.form.submit();']) !!}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="col-sm-4 form-control-label"><b>Project Name</b></label>
                            <div class="col-sm-8">
                                {!! Form::select("project_id", $projects,request('project_id') ?? null,["class"=>"form-control c-select select2-input", "id"=>"project_id", 'onchange' => 'this.form.submit();']) !!}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="col-sm-4 form-control-label"><b>Unit Name</b></label>
                            <div class="col-sm-8">
                                {!! Form::select("unit_id", $units,request('unit_id') ?? null,["class"=>"form-control c-select select2-input", "id"=>"unit_id", 'onchange' => 'this.form.submit();']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group row custom-padding">
                        <div class="col-md-4">
                            <label class="col-sm-4 form-control-label"><b>Department</b></label>
                            <div class="col-sm-8">
                                {!! Form::select("department_id", $departments,request('department_id') ?? null,["class"=>"form-control c-select select2-input", "id"=>"department_id", 'onchange' => 'this.form.submit();']) !!}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="col-sm-4 form-control-label"><b>Cost Centre</b></label>
                            <div class="col-sm-8">
                                {!! Form::select("cost_centre", $cost_centers,request('cost_centre') ?? null,["class"=>"form-control c-select select2-input", "id"=>"cost_centre", 'onchange' => 'this.form.submit();']) !!}
                            </div>
                        </div>

{{--                        <div class="col-md-4">--}}
{{--                            <label class="col-sm-4 form-control-label"><b>Ledger Type</b></label>--}}
{{--                            <div class="col-sm-8">--}}
{{--                                {!! Form::select("ledger_type_id", [1 => 'Head', 2 => 'Narration', 3 => 'Both'], request('ledger_type_id') ?? 3,["class"=>"form-control c-select select2-input", "id"=>"ledger_type_id", 'onchange' => 'this.form.submit();']) !!}--}}
{{--                            </div>--}}
{{--                        </div>--}}
                        <div class="col-md-4">
                            <label class="col-sm-4 form-control-label"><b>Currency Type</b></label>
                            <div class="col-sm-8">
                                {!! Form::select("currency_type_id", [1 => 'Home', 2 => 'FC', 3 => 'Both'], request('currency_type_id') ?? 3,["class"=>"form-control c-select select2-input", "id"=>"currency_type_id", 'onchange' => 'this.form.submit();']) !!}
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <table class="reportTable">
                            <thead style="background-color: #ffe598;">
                            <tr>
                                <th rowspan="2" style="width: 7%;">Date</th>
                                <th rowspan="2" colspan="2" style="width: 20%;">Description</th>
                                <th rowspan="2" style="width: 6%;">Project</th>
                                <th rowspan="2" style="width: 6%;">Unit</th>
                                <th rowspan="2" style="width: 7%;">Departmt</th>
                                <th rowspan="2">Cost Centre</th>
                                <th rowspan="2" style="width: 8%;">Voucher No</th>
                                <th rowspan="2" style="width: 5%;">C. Rate</th>
                                @if (request('currency_type_id') == 2)
                                    <th colspan="2">Foreign Currency</th>
                                @elseif (request('currency_type_id') == 1)
                                    <th colspan="2">Home Currency [BDT]</th>
                                @else
                                    <th colspan="2">Foreign Currency</th>
                                    <th colspan="2">Home Currency [BDT]</th>
                                @endif
                                <th rowspan="2">Balance [BDT]</th>
                            </tr>
                            <tr>
                                @if (request('currency_type_id') == 2)
                                    <th>Debit</th>
                                    <th>Credit</th>
                                @elseif (request('currency_type_id') == 1)
                                    <th>Debit</th>
                                    <th>Credit</th>
                                @else
                                    <th>Debit</th>
                                    <th>Credit</th>
                                    <th>Debit</th>
                                    <th>Credit</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                            <tr style="background-color: lightyellow">
                                <th colspan="2"></th>
                                <th colspan="3" class="text-center">Opening Balance</th>
                                @if (request('currency_type_id') == 1 || request('currency_type_id') == 2)
                                    <th colspan="6"></th>
                                @else
                                    <th colspan="8"></th>
                                @endif
                                @php
                                    $date = \Carbon\Carbon::today();

                                    if (request()->has('start_date')) {
                                        $date = \Carbon\Carbon::parse(request('start_date'));
                                    }
                                    $openingBalance = $account ? $account->openingBalance($date) : 0.00;
                                @endphp
                                <th class="text-right">
                                    @if($openingBalance >= 0)
                                        <strong>{{ number_format(abs($openingBalance), 2) }}</strong>
                                    @else
                                        <strong>{{ number_format(abs($openingBalance), 2) }}</strong>
                                    @endif
                                </th>
                            </tr>
                            @php
                                $totalDebit = 0;
                                $totalCredit = 0;
                                $totalBalance = 0;
                                $open = $openingBalance;
                            @endphp
                            @if(isset($account->journalEntries))
                                @foreach($account->journalEntries as $transaction)
                                    @php
                                                $currency =  collect(\SkylarkSoft\GoRMG\Finance\Services\CurrencyService::currencies())
                                                        ->where('id', $transaction->currency_id)->first()['name'] ?? null;
                                                if (request('currency_type_id') == 2) {
                                                    $debit = $transaction->trn_type == 'dr' ? $transaction->fc : 0;
                                                    $credit = $transaction->trn_type == 'cr' ? $transaction->fc : 0;
                                                    $totalDebit += $transaction->trn_type == 'dr' ? $transaction->fc : 0;
                                                    $totalCredit += $transaction->trn_type == 'cr' ? $transaction->fc : 0;
                                                } else {
                                                    $debit = $transaction->trn_type == 'dr' ? $transaction->trn_amount : 0;
                                                    $credit = $transaction->trn_type == 'cr' ? $transaction->trn_amount : 0;
                                                    $totalDebit += $transaction->trn_type == 'dr' ? $transaction->trn_amount : 0;
                                                    $totalCredit += $transaction->trn_type == 'cr' ? $transaction->trn_amount : 0;
                                                }
                                                $balance = $open + $debit - $credit;
                                                $totalBalance += $balance;
                                    @endphp
                                    <tr>
                                        <td>{{ $transaction->trn_date->toFormattedDateString() }}</td>
{{--                                        @if (request('ledger_type_id') == 1)--}}
{{--                                            <td colspan="2">--}}
{{--                                                {{ $account->name }} ( {{ $account->code }} )--}}
{{--                                            </td>--}}
{{--                                        @elseif (request('ledger_type_id') == 2)--}}
{{--                                            <td colspan="2">--}}
{{--                                                {{ $transaction->particulars ?? '' }}--}}
{{--                                            </td>--}}
{{--                                        @else--}}
                                            <td colspan="2">
                                                {{ $account->name }} ( {{ $account->code }} ) <br>
                                                {{ $transaction->particulars ?? '' }}
                                            </td>
{{--                                        @endif--}}
                                        <td>{{ isset($transaction->project_id) ? $transaction->project->project : '--' }}</td>
                                        <td>{{ isset($transaction->unit_id) ? $transaction->unit->unit : '--' }}</td>
                                        <td>{{ isset($transaction->department_id) ? $transaction->department->department : '--' }}</td>
                                        <td>{{ isset($transaction->cost_center_id) ? $transaction->cost_center->cost_center : '--' }}</td>
                                        <td>{{ $transaction->voucher->voucher_no }}</td>
                                        <td>{{ $currency . '@' . $transaction->conversion_rate ?? 1 }}</td>
                                        @if (request('currency_type_id') == 2)
                                            <td class="text-right">{{ $transaction->trn_type == 'dr' ? number_format($transaction->fc, 2) : '' }}</td>
                                            <td class="text-right">{{ $transaction->trn_type == 'cr' ? number_format($transaction->fc, 2) : '' }}</td>
                                        @elseif (request('currency_type_id') == 1)
                                            <td class="text-right">{{ $transaction->trn_type == 'dr' ? number_format($transaction->trn_amount, 2) : '' }}</td>
                                            <td class="text-right">{{ $transaction->trn_type == 'cr' ? number_format($transaction->trn_amount, 2) : '' }}</td>
                                        @else
                                            <td class="text-right">{{ $transaction->trn_type == 'dr' ? number_format($transaction->fc, 2) : '' }}</td>
                                            <td class="text-right">{{ $transaction->trn_type == 'cr' ? number_format($transaction->fc, 2) : '' }}</td>
                                            <td class="text-right">{{ $transaction->trn_type == 'dr' ? number_format($transaction->trn_amount, 2) : '' }}</td>
                                            <td class="text-right">{{ $transaction->trn_type == 'cr' ? number_format($transaction->trn_amount, 2) : '' }}</td>
                                        @endif
                                        <td class="text-right">{{ number_format($balance, 2) }}</td>
                                    </tr>
                                    @php
                                        $open = $balance;
                                    @endphp
                                @endforeach
                            @endif
                            <tr style="background-color: lavender">
                                @if (request('currency_type_id') == 1 || request('currency_type_id') == 2)
                                    <td colspan="8"></td>
                                @else
                                    <td colspan="10"></td>
                                @endif
                                <th class="text-center">Total</th>
                                <th class="text-right">{{ number_format($totalDebit, 2) ?? 0.00 }}</th>
                                <th class="text-right">{{ number_format($totalCredit, 2) ?? 0.00 }}</th>
                                @php
                                    $totalBalance = ($totalDebit + $openingBalance) - $totalCredit
                                @endphp
                                <th class="text-right">{{ number_format($totalBalance, 2) ?? 0.00 }}</th>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

