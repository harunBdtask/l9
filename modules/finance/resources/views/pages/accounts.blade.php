@extends('finance::layout')
@section('title', 'Chart Of Accounts')
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

        thead th {
            background-color: #94dafb !important;
        }

        tbody td {
            padding: 30px;
        }

        tbody tr:nth-child(odd) {
            background-color: aliceblue;
            /*color: #fff;*/
        }

        tbody tr:hover {
            background-color: cyan;
        }
    </style>
@endsection

@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Chart Of Accounts</h2>
            </div>
            <div class="box-body b-t">
                <div style="margin-bottom: 15px">
                    <a class="btn btn-sm white" href="{{ url('finance/accounts/create') }}">
                        <em class="glyphicon glyphicon-plus"></em> New Account
                    </a>
                    <div class="pull-right" style="width: 40%">
                        <form id="search-form" method="GET">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <select style="border: none; background: none;" name="key" id="key">
                                        <option
                                            value="account_code" {{ request('key') == 'account_code' ? 'selected' : '' }}>
                                            A/C Code
                                        </option>
                                        <option
                                            value="account_type" {{ request('key') == 'account_type' ? 'selected' : '' }}>
                                            Type of Account
                                        </option>
                                        <option
                                            value="parent_account" {{ request('key') == 'parent_account' ? 'selected' : '' }}>
                                            Parent Name
                                        </option>
                                        <option
                                            value="group_account" {{ request('key') == 'group_account' ? 'selected' : '' }}>
                                            Group Name
                                        </option>
                                        <option
                                            value="control_account" {{ request('key') == 'control_account' ? 'selected' : '' }}>
                                            Control Ledger
                                        </option>
                                        <option
                                            value="ledger_account" {{ request('key') == 'ledger_account' ? 'selected' : '' }}>
                                            Ledger A/C
                                        </option>
                                    </select>
                                </div>
                                <input type="text" class="form-control form-control-sm" name="value" id="value-field"
                                       value="{{ request('value') }}">
                                <div class="input-group-addon addon-btn-primary">
                                    <button class="btn btn-sm btn-primary">Search</button>
                                </div>
                            </div>
                        </form>
                    </div>
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

                <div id="parentTableFixed" class="table-responsive">
                    <table class="reportTable" id="fixTable">
                        <thead class="thead-light">
                        <tr>
                            <th class="text-center">A/C Code</th>
                            <th class="text-center">Type of Account</th>
                            <th class="text-center">Parent Name</th>
                            <th class="text-center">Group Name</th>
                            <th class="text-center">Control Ledger</th>
                            <th class="text-center">Ledger A/C</th>
                            <th class="text-center">Status</th>
                            {{--                        <th class="text-left">Sub Ledger</th>--}}
                            <th>Actions</th>
                        </tr>
                        </thead>

                        <tbody id="accounts-table-body"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            let perPage = 30;
            let page = 0;

            $('#parentTableFixed').scroll(function () {
                console.log(Math.ceil($(this).scrollTop() + $(this).innerHeight()), $(this)[0].scrollHeight);
                console.log("scroll", Math.ceil($(this).scrollTop() + $(this).innerHeight()) + 1 >= $(this)[0].scrollHeight);
                if (Math.ceil($(this).scrollTop() + $(this).innerHeight()) + 1 >= $(this)[0].scrollHeight) {
                    page++;
                    fetchAccountList();
                }
            });


            function fetchAccountList(search) {
                let key = $('#key').val();
                const queryString = new URLSearchParams({page, perPage});

                $.ajax({
                    url: `/finance/fetch-account-list?${queryString}`,
                    type: 'GET',
                    data: search,
                    dataType: 'html',
                    success: function (response) {
                        $('#accounts-table-body').append(response);
                    }
                })
            }

            $(document).on("submit", "#search-form", function (e) {
                e.preventDefault();
                const form = $(this).serializeArray();
                page = 1;
                $('#accounts-table-body').empty();
                fetchAccountList(form);
            })

            $(document).on('click', '#delete_account', function () {

                if (confirm('Are you sure?')) {
                    const url = $(this).attr('data-url');

                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        success: function (response) {
                            location.reload();
                        }
                    })
                }
            });

            $(document).on("change", "#key", function () {
                $("#value-field").val("");
            })
        });
    </script>
@endsection
