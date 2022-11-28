@extends('basic-finance::layout')
@section('title','Chart Of Accounts')
@section('styles')
    <style type="text/css">
        .addon-btn-primary {
            padding: 0;
            margin: 0;
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
    </style>
@endsection

@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <div class="row">
                    <div class="col-sm-3"><h2>Chart Of Accounts</h2></div>
                    <div class="col-sm-7"></div>
                    <div class="col-sm-2">
                        <div style="align:right; margin-left: -5%">
                            <a class="btn btn-sm btn-info" href="{{ url('basic-finance/accounts/v2') }}">Chart Of Account List View</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-body b-t">
                <div id="accounts"></div>
            </div>
        </div>
    </div>
    <script src="{{ mix('/js/basic-finance/basic-finance.js') }}"></script>
@endsection
