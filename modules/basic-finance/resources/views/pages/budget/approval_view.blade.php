@extends(\SkylarkSoft\GoRMG\BasicFinance\PackageConst::VIEW_NAMESPACE."::layout")
@section('title', 'Approval View')
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

        .form-control-plaintext {
            border: 0px;
        }

        tbody tr:hover {
            background-color: lightcyan;
        }
    </style>
@endsection

@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Budget Approval</h2>
                <div class="pull-right">
                    <a class="btn btn-xs btn-primary print"
                       style="margin-top: -40px;"
                       href="{{ url('basic-finance/budget-approvals/'.collect($approval)->first()['budget_id'].'/print')."?date=".collect($approval)->first()['date'] }}">
                        Print
                    </a>
                </div>
            </div>
            <div class="box-body b-t">
                <div class="row">
                    <div class="col-lg-12">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Budget Code</th>
                                <td>{{collect($approval)->first()['code']}}</td>
                                <th>Budget Date</th>
                                <td>{{\Carbon\Carbon::make(collect($approval)->first()['budget_date'])->toFormattedDateString()}}</td>
                            </tr>
                            <tr>
                                <th>Approval Date</th>
                                <td>{{\Carbon\Carbon::make(collect($approval)->first()['date'])->toFormattedDateString()}}</td>
                                <th></th>
                                <td></td>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <table class="reportTable">
                            <thead class="thead-light">
                            <tr>
                                <th>SL No</th>
                                <th>Head Of Accounts</th>
                                <th>Previous Month Amount</th>
                                <th>Budget Amount</th>
                                <th>Approved Amount</th>
                                <th>B.Remarks</th>
                                <th>Approved Remarks</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($approval as $key=>$data)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{$data['account']}}</td>
                                    <td>{{$data['prev_month_amount']}}</td>
                                    <td>{{$data['budget_amount']}}</td>
                                    <td>{{$data['approved_amount']}}</td>
                                    <td>{{$data['budget_remarks']}}</td>
                                    <td>{{$data['approved_remarks']}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tbody>
                            <tr>
                                <th></th>
                                <th>Total</th>
                                <th>{{ number_format(collect($approval)->sum('prev_month_amount'), 2) }}</th>
                                <th>{{ number_format(collect($approval)->sum('budget_amount'), 2) }}</th>
                                <th>{{ number_format(collect($approval)->sum('approved_amount'), 2) }}</th>
                                <th></th>
                                <th></th>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
