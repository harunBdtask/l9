@extends('basic-finance::layout')

@section('styles')
    <style type="text/css">
        td {
            padding-right: 5px;
            padding-left: 5px;
        }

        th {
            padding-right: 8px;
            padding-left: 8px;
        }

        tbody tr:hover {
            background-color: lightcyan;
        }

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
                <h2>Group Ledger</h2>
            </div>
            <div class="box-body b-t">
                <form action="{{url('basic-finance/group-ledger')}}">
                    <div class="row">
                        <div class="col-md-3">
                            <label class="">Head of Account</label>
                            @php
                                $accountId = request('account_id');
                            @endphp
                            <select class="form-control c-select select2-input" name="account_id" id="account_id">
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
                                <input type="date" name="start_date" class="form-control"
                                       value="{{ request('start_date') ?? \Carbon\Carbon::today()->startOfMonth()->format('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>End Date</label>
                                <input type="date" name="end_date" class="form-control"
                                       value="{{ request('end_date') ?? \Carbon\Carbon::today()->endOfMonth()->format('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label></label>
                                <button style="margin-top: 28px;" class="btn btn-info"><i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="header-section" style="padding-bottom: 0px;">
                        <div class="pull-right">
                            <table>
                                <tr>
                                    <td><a style="margin-right: 1px;" type="button"
                                           class="form-control btn pdf pull-right"><i class="fa fa-file-pdf-o"></i></a>
                                    </td>
                                    <td>
                                        <a style="margin-right: 8px;" type="button"
                                           class="form-control btn excel pull-right"><i class="fa fa-file-excel-o"></i></a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </form>
                <div class="row">
                    <div class="col-lg-5"></div>
                    <div>
                        <table style="border: 1px solid black;width: 20%;">
                            <thead>
                            <tr>
                                <td class="text-center">
                                    <span style="font-size: 12pt; font-weight: bold;">Group Ledger Details</span>
                                    <br>
                                </td>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                @includeIf('basic-finance::tables.group_ledger_table')
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript">
        $('.pdf').click(function (e) {
            e.preventDefault();

            let url = window.location.toString();

            if (url.includes('?')) {
                url += '&type=pdf';
            } else {
                url += '?type=pdf';
            }

            window.open(url, '_blank');
        });

        $('.excel').click(function (e) {
            e.preventDefault();

            let url = window.location.toString();

            if (url.includes('?')) {
                url += '&type=excel';
            } else {
                url += '?type=excel';
            }

            window.open(url, '_blank');
        });
    </script>
@endsection
