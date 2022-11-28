@extends(\SkylarkSoft\GoRMG\BasicFinance\PackageConst::VIEW_NAMESPACE."::layout")
@section('title', 'Budget View')
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
    </style>
@endsection

@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Budget</h2>
                {{--                <div class="pull-right">--}}
                {{--                    <a class="btn btn-xs btn-primary print"--}}
                {{--                       style="margin-top: -40px;"--}}
                {{--                       href="{{ url('finance/budget/2/print') }}">--}}
                {{--                        Print--}}
                {{--                    </a>--}}
                {{--                </div>--}}
            </div>
            <div class="box-body b-t">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-2 col-form-label font-weight-bold">Budget Code
                                : </label>
                            <div class="col-sm-10">
                                <p>{{ $acBudget['code'] }}</p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-2 col-form-label font-weight-bold">Budget Date
                                : </label>
                            <div class="col-sm-10">
                                <p>{{ \Carbon\Carbon::parse($acBudget['date'])->toFormattedDateString() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <table class="reportTable">
                            <thead class="thead-light" style="background-color: azure;">
                            <tr>
                                <th>SL No</th>
                                <th>Head Of Accounts</th>
                                <th>Previous Month TK</th>
                                <th>Budget Amount</th>
                                <th>Remarks</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $totalPreviousMonthAmount = 0;
                            @endphp
                            @foreach($acBudget['details'] as $key => $detail)
                                @php
                                    $totalPreviousMonthAmount += $detail['previous_month_amount'];
                                @endphp
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $detail['bf_account_name'] }}</td>
                                    <td class="text-right">{{ $detail['previous_month_amount'] }}</td>
                                    <td class="text-right">{{ $detail['amount'] }}</td>
                                    <td>{{ $detail['remarks'] }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tbody style="background-color: azure;">
                                <tr>
                                    <th colspan="2">Total</th>
                                    <th class="text-right">{{ $totalPreviousMonthAmount }}</th>
                                    <th class="text-right">{{ $acBudget['total_amount'] }}</th>
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
