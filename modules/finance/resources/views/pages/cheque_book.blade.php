@extends('finance::layout')

@section('styles')
    <style type="text/css">
        .custom-padding {
            padding: 0 200px 0 200px;
        }

        #tabular-form thead {
            background-color: #00b0ff;
        }

        #tabular-form table, #tabular-form thead, #tabular-form tbody, #tabular-form th, #tabular-form td {
            padding: 3px !important;
            vertical-align: middle !important;
            font-size: 12px;
            text-align: center;
            border-color: black;
        }

        .form-control-label {
            padding: 0 0 0 5px !important;
        }

        .form-group {
            margin-bottom: 0 !important;
        }
    </style>
@endsection

@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Cheque Book View</h2>
            </div>
            <div class="box-body b-t">
                <div class="voucher-meta">
                    <div class="col-md-4 col-md-offset-1">
                        <div class="form-group row">
                            <label class="col-sm-4 form-control-label text-right"><b>Bank :</b></label>
                            <div class="col-sm-8 form-control-label text-left">
                                <p>{{ $cheque_book->bank->name }}</p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 form-control-label text-right"><b>Cheque Book No :</b></label>
                            <div class="col-sm-8 form-control-label text-left">
                                <p>{{ $cheque_book->cheque_book_no }}</p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 form-control-label text-right"><b>Cheque No From :</b></label>
                            <div class="col-sm-8 form-control-label text-left">
                                <p>{{ $cheque_book->cheque_no_from }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2"></div>

                    <div class="col-md-4 text-right">
                        <div class="form-group row">
                            <label class="col-sm-7 form-control-label text-right"><b>Bank Account No :</b></label>
                            <div class="col-sm-5  form-control-label text-left">
                                <p>{{ $cheque_book->bankAccount->account_number }}</p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-7 form-control-label text-right"><b>Total Page :</b></label>
                            <div class="col-sm-5  form-control-label text-left">
                                <p>{{ $cheque_book->total_page }}</p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-7 form-control-label text-right"><b>Cheque No To :</b></label>
                            <div class="col-sm-5  form-control-label text-left">
                                <p>{{ $cheque_book->cheque_no_to }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <br>
                <br>
                <br>
                <br>

                <div class="table-responsive">
                    <table class="table table-bordered" id="tabular-form">
                        <thead class="thead-light">
                        <tr>
                            <th>Sl No.</th>
                            <th>Company Name</th>
                            <th>Bank Name</th>
                            <th>Account Number</th>
                            <th>Cheque No</th>
                            <th>Paid To</th>
                            <th>Amount</th>
                            <th>Issue Date</th>
                            <th>Due Date</th>
                            <th>Aging of unclear Cheque</th>
                            <th>Clearing Date</th>
                            <th>Cleared By</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody class="voucher-items">
                        @if(count($cheque_book->details))
                            @foreach($cheque_book->details as $cheque_book_detail)
                                @php
                                    $status = collect(\SkylarkSoft\GoRMG\BasicFinance\Models\ChequeBookDetail::STATUS_TYPE)
                                                    ->where('id', $cheque_book_detail->status)->first()['text'];
                                    $diffInDays = null;
                                    if($cheque_book_detail->cheque_date){
                                        $date = Carbon\Carbon::parse($cheque_book_detail->cheque_date);
                                        $now = \Carbon\Carbon::now();
                                        $diffInDays = $date->diffInDays($now);
                                    }
                                    $clearedBy = null;
                                    if($cheque_book_detail->cleared_by){
                                        $clearedBy = \SkylarkSoft\GoRMG\SystemSettings\Models\User::query()->where('id', $cheque_book_detail->cleared_by)->get()->first()->screen_name;
                                    }
                                @endphp
                                <tr>
                                    <td>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</td>
                                    <td>{{ $cheque_book->bankAccount->factory->factory_name }}</td>
                                    <td>{{ $cheque_book->bank->short_name }}</td>
                                    <td>{{ $cheque_book->bankAccount->account_number }}</td>
                                    <td>{{ $cheque_book_detail->cheque_no }}</td>
                                    <td>{{ $cheque_book_detail->paid_to }}</td>
                                    <td>{{ $cheque_book_detail->amount }}</td>
                                    <td>
                                        {{
                                            $cheque_book_detail->cheque_date
                                            ? Carbon\Carbon::parse($cheque_book_detail->cheque_date)->toFormattedDateString()
                                            : null
                                        }}
                                    </td>
                                    <td>
                                        {{
                                            $cheque_book_detail->cheque_due_date
                                            ? Carbon\Carbon::parse($cheque_book_detail->cheque_due_date)->toFormattedDateString()
                                            : null
                                        }}
                                    </td>
                                    <td>{{ $cheque_book_detail->clearing_date ? null : $diffInDays }}</td>
                                    <td>{{ $cheque_book_detail->clearing_date ? \Carbon\Carbon::parse($cheque_book_detail->clearing_date)->format('M d, Y') : null}}</td>
                                    <td>{{ $clearedBy }}</td>
                                    <td>{{ $status }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7" class="text-danger">No Data Found</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
                <br>
            </div>
        </div>
    </div>
@endsection
