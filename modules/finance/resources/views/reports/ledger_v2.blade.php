@extends('finance::layout')
@section('title', 'Ledger Details')
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

        @media (min-width: 768px) {
            .modal-dialog {
                width: 600px;
                margin: 30px auto;
            }
            .modal-xl {
                width: 90%;
            max-width:1200px;
            }
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
                    {!! Form::open(['url'=>'finance/ledger-v2', 'method'=>'get']) !!}
                    @php
                        $companies=collect($companies)->prepend('Select Company','');
                        $ledger_accounts=collect($ledger_accounts)->prepend('Select Account','');
                        $cost_centers=collect($cost_centers)->prepend('All Cost Centre',0);
                    @endphp
                    <div class="form-group row custom-padding">
                        <div class="col-sm-4">
                            <label class="col-sm-4 form-control-label"><b>From Date</b></label>
                            <div class="col-sm-8">
                                {!! Form::date("start_date", request('start_date') ?? now(),['class'=>"form-control form-control-sm"]) !!}
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <label class="col-sm-4 form-control-label"><b>To Date</b></label>
                            <div class="col-sm-8">
                                {!! Form::date("end_date", request('end_date') ?? now(),['class'=>"form-control form-control-sm"]) !!}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="col-sm-4 form-control-label"><b>Company Name</b></label>
                            <div class="col-sm-8">
                                {!! Form::select("company_id", $companies ?? [],request('company_id') ?? null,["class"=>"form-control c-select select2-input", "id"=>"company_id"]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group row custom-padding">
                        <div class="col-md-4">
                            <label class="col-sm-4 form-control-label"><b>Ledger Name<span class="text-danger">*</span></b></label>
                            <div class="col-sm-8">
                                {!! Form::select("account_id", $ledger_accounts ?? [], request('account_id') ?? null,["class"=>"form-control c-select select2-input", "id"=>"account_id"]) !!}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="col-sm-4 form-control-label"><b>Ledger Type</b></label>
                            <div class="col-sm-8">
                                {!! Form::select("ledger_type_id", [1 => 'Head', 2 => 'Narration', 3 => 'Both'], request('ledger_type_id') ?? 3,["class"=>"form-control c-select select2-input", "id"=>"ledger_type_id"]) !!}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="col-sm-4 form-control-label"><b>Cost Centre</b></label>
                            <div class="col-sm-8">
                                {!! Form::select("cost_centre", $cost_centers,request('cost_centre') ?? null,["class"=>"form-control c-select select2-input", "id"=>"cost_centre"]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group row custom-padding">

                        <div class="col-md-4">
                            <label class="col-sm-4 form-control-label"><b>Currency Type</b></label>
                            <div class="col-sm-8">
                                {!! Form::select("currency_type_id", [1 => 'Home', 2 => 'FC', 3 => 'Both'], request('currency_type_id') ?? 3,["class"=>"form-control c-select select2-input", "id"=>"currency_type_id"]) !!}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-success">Search</button>
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
                                <th rowspan="2" colspan="2" style="width: 30%;">Description</th>
                                {{-- <th rowspan="2" style="width: 8%;">Unit</th> --}}
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
                                <th class="text-center">Opening Balance</th>
                                @if (request('currency_type_id') == 1 || request('currency_type_id') == 2)
                                    <th colspan="5"></th>
                                @else
                                    <th colspan="7"></th>
                                @endif
                                @php
                                    // $date = \Carbon\Carbon::today();

                                    // if (request()->has('start_date')) {
                                    //     $date = \Carbon\Carbon::parse(request('start_date'));
                                    // }
                                    // $openingBalance = $account ? $account->openingBalance($date) : 0.00;
                                @endphp
                                <th class="text-right">
                                    @if($openingBalance >= 0)
                                        <strong>{{ number_format(abs($openingBalance), 2) }} {{ $openingBalance > 0 ? 'Dr':''}}</strong>
                                    @else
                                        <strong>{{ number_format(abs($openingBalance), 2) }} Cr</strong>
                                    @endif
                                </th>
                            </tr>
                            @php
                                $totalDebit = 0;
                                $totalCredit = 0;
                                $totalBalance = 0;
                                $open = $openingBalance;
                            @endphp
                                @if(!empty($account->journalEntries))
                                    @foreach($account->journalEntries as $transaction)
                                        @php
                                            // dd($transaction->voucher);
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
                                            //voucher account
                                            $count = sizeof($transaction->voucher->details->items);
                                            $account_code = '';
                                            $account_name = '';
                                            $narration = '';
                                            $costCenterName = '';
                                        @endphp
                                        {{-- voucher account_name start --}}
                                        @foreach($transaction->voucher->details->items as $key=>$item)
                                            @if(request('account_id') != $item->account_id && $transaction->trn_type == 'dr' && $item->credit > 0)
                                                @php
                                                    $account_code =  $item->account_code;
                                                    $account_name =  $item->account_name;
                                                    $narration =  $item->narration;
                                                    $costCenterName =  $item->const_center_name;
                                                    break;
                                                @endphp
                                            @endif
                                            @if(request('account_id') != $item->account_id && $transaction->trn_type == 'cr' && $item->debit > 0)
                                                @php
                                                    $account_code =  $item->account_code;
                                                    $account_name =  $item->account_name;
                                                    $narration =  $item->narration;
                                                    $costCenterName =  $item->const_center_name;
                                                    break;
                                                @endphp
                                            @endif
                                        @endforeach
                                        {{-- voucher account_name end --}}
                                        <tr>
                                            <td>{{ $transaction->trn_date->toFormattedDateString() }}</td>
                                            @if (request('ledger_type_id') == 1)
                                                <td colspan="2">
                                                    {{-- {{ $account->name }} ( {{ $account->code }} ) --}}
                                                    {{isset($account_name) ? $account_name : '' }} ({{isset($account_code) ? $account_code : '' }})
                                                </td>
                                            @elseif (request('ledger_type_id') == 2)
                                                <td colspan="2">
                                                    {{-- {{ $transaction->particulars ?? '' }} --}}
                                                    {{isset($narration) ? $narration : '' }}
                                                </td>
                                            @else
                                                <td colspan="2">
                                                    {{-- {{ $account->name }} ( {{ $account->code }} ) <br>
                                                    {{ $transaction->particulars ?? '' }} --}}
                                                    {{isset($account_name) ? $account_name : '' }} ({{isset($account_code) ? $account_code : '' }})
                                                    <br>
                                                    {{isset($narration) ? $narration : '' }}
                                                </td>
                                            @endif
                                            {{-- <td>{{ isset($transaction->unit_id) ? $transaction->unit->unit : '--' }}</td> --}}
                                            <td>{{isset($costCenterName) ? $costCenterName : '' }}</td>
                                            <td>
                                                {{-- {{ $transaction->voucher->voucher_no }} --}}
                                                @if($count > 2)
                                                    <a data-id="{{$transaction->voucher->voucher_no}}" data-account="{{request('account_id')}}" class="voucherInfo btn btn-default btn-sm"><b>{{$transaction->voucher->voucher_no}}</b></a>
                                                @else
                                                    {{$transaction->voucher->voucher_no}}
                                                @endif
                                            </td>
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
                                            <td class="text-right">{{ number_format(abs($balance), 2) }} {{$balance>0?'Dr':($balance<0?'Cr':'')}}</td>
                                        </tr>
                                        @php
                                            $open = $balance;
                                        @endphp
                                    @endforeach
                                @endif
                            <tr style="background-color: lavender">
                                @if (request('currency_type_id') == 1 || request('currency_type_id') == 2)
                                    <td colspan="5"></td>
                                @else
                                    <td colspan="7"></td>
                                @endif
                                <th class="text-center">Total</th>
                                <th class="text-right">{{ number_format($totalDebit, 2) ?? 0.00 }}</th>
                                <th class="text-right">{{ number_format($totalCredit, 2) ?? 0.00 }}</th>
                                @php
                                    $totalBalance = ($totalDebit + $openingBalance) - $totalCredit
                                @endphp
                                <th class="text-right">{{ number_format(abs($totalBalance), 2) ?? 0.00 }} {{$totalBalance>0?'Dr':($totalBalance<0?'Cr':'')}}</th>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<!-- modal button -->
<div class="modal fade" id="voucher-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="voucherModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form>
                @csrf
                <div class="modal-body" id="voucherDetails">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('scripts')
    <script type="text/javascript">
    const voucherDetails = $('#voucherDetails');
    //voucherInfo
    $('body').on('click', '.voucherInfo', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            // alert(id);
            var account = $(this).data('account');
            $('#voucherModalLabel').text("Voucher Details");
            voucherDetails.html('');
            $('#voucher-modal').modal('show');
            $.ajax({
                url: "/finance/ledger-voucher-details",
                type: "get",
                dataType: "html",
                data: {
                    'voucher_no': id,
                    'account': account,
                },
                beforeSend() {
                    $('html,body').css('cursor', 'wait');
                    $("html").css({'background-color': 'black', 'opacity': '0.5'});
                    $(".loader").show();
                },
                complete() {
                    $('html,body').css('cursor', 'default');
                    $("html").css({'background-color': '', 'opacity': ''});
                    $(".loader").hide();
                },
                success(data) {
                    // console.log(data);
                    voucherDetails.html(data);
                },
                error(errors) {
                    alert("Something Went Wrong");
                }
            })
        });
    </script>
@endsection
