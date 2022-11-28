@extends('finance::layout')
@section('title', 'Budget List')
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
                <div class="pull-right">
                    <a class="btn btn-xs btn-primary print"
                       style="margin-top: -40px;"
                       href="{{ url('finance/budget/2/print') }}">
                        Print
                    </a>
                </div>
            </div>
            <div class="box-body b-t">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-2 col-form-label font-weight-bold">Budget for : </label>
                            <div class="col-sm-10">
                                <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="January 2020">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-2 col-form-label font-weight-bold">Budget ID : </label>
                            <div class="col-sm-10">
                                <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="123454321">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-2 col-form-label font-weight-bold">Budget Date : </label>
                            <div class="col-sm-10">
                                <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="January 2020">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <table class="reportTable">
                            <thead class="thead-light">
                            <tr>
                                <th>SL No</th>
                                <th>Head Of Accounts</th>
                                <th>Previous Month TK</th>
                                <th>Budget Amount</th>
                                <th>Approved Amount</th>
                                <th>B.Remarks</th>
                                <th>Approved Remarks</th>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <th></th>
                                <th>Total</th>
                                <th>Sum</th>
                                <th>Sum</th>
                                <th>Sum</th>
                                <th></th>
                                <th></th>
                            </tr>
                            {{--                    <tr>--}}
                            {{--                        <td class="text-center" colspan="9">No data found</td>--}}
                            {{--                    </tr>--}}
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
