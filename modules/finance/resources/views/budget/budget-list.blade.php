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
    </style>
@endsection

@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Budget</h2>
            </div>
            <div class="box-body b-t">
                <a class="btn btn-sm white m-b" href="{{ url('finance/budget/create') }}">
                    <i class="glyphicon glyphicon-plus"></i> New Budget
                </a>
                <div class="pull-right m-b-1">
                    <form action="{{ url('/budget') }}" method="GET">
                        <div class="pull-left" style="margin-right: 10px;">
                            <input type="text" class="form-control form-control-sm" name="q" value="{{ request('q') ?? '' }}"
                                   placeholder="Enter search key">
                        </div>
                        <div class="pull-right">
                            <input type="submit" class="btn btn-sm white" value="Search">
                        </div>
                    </form>
                </div>

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

                <table class="reportTable">
                    <thead class="thead-light">
                    <tr>
                        <th class="text-center">Budget ID</th>
                        <th class="text-left">Budget Date</th>
                        <th class="text-left">Month</th>
                        <th class="text-left">Total Amount</th>
                        <th class="text-left">Approved Amount</th>
                        <th class="text-left">Status</th>
                        <th class="text-left">Created By</th>
                        <th class="text-left">Created Date</th>
                        <th class="text-left">Status</th>
                    </tr>
                    <tr>
                        <td class="text-center" colspan="9">No data found</td>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection
