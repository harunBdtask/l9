@extends('skeleton::layout')
@section('title','Cheque Clearing Section')
@section('content')
    <style type="text/css">
        .v-align-top td, .v-algin-top th {
            vertical-align: top;
        }

        * {
            box-sizing: border-box;
            -moz-box-sizing: border-box;
        }

        .page {
            width: 210mm;
            min-height: 297mm;
            margin: 10mm auto;
            border-radius: 5px;
            background: white;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        .header-section {
            padding: 10px;
        }

        .body-section {
            padding: 10px;
            padding-top: 0px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding-left: 5px;
            padding-right: 5px;
            padding-top: 3px;
            padding-bottom: 3px;
        }

        table.borderless {
            border: none;
        }

        .borderless td, .borderless th {
            border: none;
        }

        @page {
            size: landscape;
            /*margin: 5mm;*/
            /*margin-left: 15mm;*/
            /*margin-right: 15mm;*/
        }

        @media print {
            html, body {
                width: 210mm;
                /*height: 293mm;*/
            }

            .page {
                margin: 0;
                border: initial;
                border-radius: initial;
                width: initial;
                min-height: initial;
                box-shadow: initial;
                background: initial;
                page-break-after: always;
            }
        }
    </style>
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Cheque Clearing Section</h2>
            </div>
            <div class="box-body table-responsive b-t">
                <div class="row">
                    <form action="">
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Account</label>
                                        <select name="account_id" class="form-control select2-input" id="account_id">
                                            <option value="">Select Account</option>
                                            @foreach($accounts as $key=>$account)
                                                <option
                                                    value="{{ $account->id }}" {{ $account->id == $accountId ? 'selected' : null }}>{{ $account->account_number }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Clearing Date</label>
                                        <input type="date" name="clearing_date"
                                               value="{{ empty(request('clearing_date')) ? Carbon\carbon::today()->startOfMonth() : request('clearing_date') }}"
                                               class="form-control select2-input" id="clearing_date">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>From Date</label>
                                        <input type="date" name="from_date"
                                               value="{{ empty(request('from_date')) ? Carbon\carbon::today()->startOfMonth() : request('from_date') }}"
                                               class="form-control select2-input" id="from_date">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>To Date</label>
                                        <input type="date" name="to_date"
                                               value="{{ empty(request('to_date')) ? Carbon\carbon::today()->endOfMonth() : request('to_date') }}"
                                               class="form-control select2-input" id="to_date">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Balance</label>
                                        <input type="text" name="balance"
                                               value="{{ $balance ? $balance : request('balance') }}"
                                               class="form-control" id="balance" readonly>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Clearing Amount</label>
                                        <input type="text" name="clearing_amount"
                                               value="{{ empty(request('clearing_amount')) ? '' : request('clearing_amount') }}"
                                               class="form-control" id="clearing_amount" readonly>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Balance After Clearing</label>
                                        <input type="text" name="balance_after_clearing"
                                               value="{{ empty(request('balance_after_clearing')) ? '' : request('balance_after_clearing') }}"
                                               class="form-control" id="balance_after_clearing" readonly>
                                    </div>
                                </div>
                                <div class="col-sm-1">
                                    <div class="form-group">
                                        <label></label>
                                        <button style="margin-top: 30px;" class="btn btn-info">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <button style="margin-top: 30px;" class="btn btn-info" id="clearCheque">
                                            CLEAR
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                @if($cheques)
                    <div class="">
                        <center>
                            <table style="border: 1px solid black;width: 20%;">
                                <thead>
                                <tr>
                                    <td class="text-center">
                                        <span style="font-size: 12pt; font-weight: bold;">Cheque Clear Section</span>
                                        <br>
                                    </td>
                                </tr>
                                </thead>
                            </table>
                        </center>
                        <br>
                        <div class="row">
                            @if(Session::has('success'))
                                <div class="col-md-6 col-md-offset-3 alert alert-success alert-dismissible text-center">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <small>{{ Session::get('success') }}</small>
                                </div>
                            @elseif(Session::has('failure'))
                                <div class="col-md-6 col-md-offset-3 alert alert-danger alert-dismissible text-center">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <small>{{ Session::get('failure') }}</small>
                                </div>
                            @endif
                        </div>
                        <div class="body-section" style="margin-top: 0;">
                            <div class="row">
                                <div class="col-lg-12">
                                    <table class="reportTable">
                                        <thead class="thead-light" style="background-color: cyan;">
                                        <tr>
                                            <th>#</th>
                                            <th>Cheque No.</th>
                                            <th>Paid To</th>
                                            <th class="text-right">Amount</th>
                                            <th>Issue Date</th>
                                            <th>Due Date</th>
                                            <th>Aging of unclear Cheque</th>
                                            <th>Clearing Date</th>
                                            <th>Cleared By</th>
                                            <th>Status</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($cheques as $cheque)
                                            @php
                                                $status = collect(\SkylarkSoft\GoRMG\BasicFinance\Models\ChequeBookDetail::STATUS_TYPE)
                                                                ->where('id', $cheque->status)->first()['text'];
                                                $diffInDays = null;
                                                if($cheque->cheque_due_date){
                                                $date = Carbon\Carbon::parse($cheque->cheque_due_date);
                                                $now = \Carbon\Carbon::now();
                                                $diffInDays = $date->diffInDays($now);
                                                }
                                                $clearedBy = null;
                                                if($cheque->cleared_by){
                                                $clearedBy = \SkylarkSoft\GoRMG\SystemSettings\Models\User::query()->where('id', $cheque->cleared_by)->get()->first()->screen_name;
                                                }
                                            @endphp
                                            <tr style="background-color: {{ $status == 'clear' ? 'azure' : ''}}">
                                                <td>
                                                    @php
                                                        $isChecked = ($cheque->status == 3) ? false : true;
                                                    @endphp
                                                    {!! Form::checkbox('cheque_id', $cheque->id, $isChecked, [
                                                        'class' => 'cheque_id', 'id' => 'cheque_id', 'disabled' => $isChecked, 'data-amount' => $cheque->amount,
                                                    ]) !!}
                                                </td>
                                                <td>{{ $cheque->cheque_no }}</td>
                                                <td>{{ $cheque->paid_to }}</td>
                                                <td class="text-right">{{ $cheque->amount ?? '' }}</td>
                                                <td>{{ $cheque->cheque_date ? $cheque->cheque_date->toFormattedDateString() : null }}</td>
                                                <td>{{ $cheque->cheque_due_date ? $cheque->cheque_due_date->toFormattedDateString() : null }}</td>
                                                <td>{{ $cheque->clearing_date ? null : $diffInDays }}</td>
                                                <td>{{ $cheque->clearing_date ? \Carbon\Carbon::parse($cheque->clearing_date)->format('M d, Y') : null}}</td>
                                                <td>{{ $clearedBy }} </td>
                                                <td>{{ $status }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="10" class="text-center text-danger">No Cheques Found</td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <td colspan="10"
                                                align="center">{{ $cheques->appends(request()->query())->links() }}</td>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection
@push("script-head")
    <script type="text/javascript">
        $('#clearCheque').click(function (event) {
            event.preventDefault();

            let cheques = [];
            $.each($("input[name='cheque_id']:checked").not("[disabled]"), function () {
                cheques.push($(this).val());
            });
            axios.post(`/basic-finance/multiple-cheque-clearing`, cheques).then((response) => {
                if (response.status === 201) {
                    location.reload();
                }
            }).catch((error) => {
                console.log(error);
            })
        })

        $('.cheque_id').click(function () {
            let value = 0;
            let balanceAmount = $("input[name='balance']").val();
            let clearingAmount = $("input[name='clearing_amount']");
            let balanceAfterClearing = $("input[name='balance_after_clearing']");

            $.each($("input[name='cheque_id']:checked").not("[disabled]"), function(){
                value += parseFloat($(this).attr('data-amount'));
            });

            clearingAmount.val(value);
            balanceAfterClearing.val(parseFloat(balanceAmount - clearingAmount.val()));
        });
    </script>
@endpush
