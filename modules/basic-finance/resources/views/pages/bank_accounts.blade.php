@extends('basic-finance::layout')
@section('title', 'Bank Accounts')
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
                <h2>Bank Accounts</h2>
            </div>
            <div class="box-body b-t">
                <div style="margin-bottom: 15px">
                    @permission('permission_of_bank_accounts_add')
                    <a class="btn btn-sm white" href="{{ url('basic-finance/bank-accounts/create') }}">
                        <i class="glyphicon glyphicon-plus"></i> New Bank Account
                    </a>
                    @endpermission
                    <div class="pull-right" style="width: 40%">
                        <form action="{{ url('basic-finance/bank-accounts') }}" method="GET">
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm" name="search"
                                       value="{{ request('search') }}" placeholder="Search by Account/Bank/Branch Name">
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

                <table class="reportTable">
                    <thead class="thead-light">
                    <tr>
                        <th class="text-center">Sl No.</th>
                        <th class="text-left">Account No</th>
                        <th class="text-left">Bank Name</th>
                        <th class="text-left">Branch Name</th>
                        <th class="text-left">Currency Type</th>
                        <th class="text-left">Current Balance</th>
                        <th class="text-left">Status</th>
                        <th class="text-left">Company Name</th>
                        <th class="text-left">Opening Date</th>
                        <th>Actions</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse($bank_accounts as $bank_account)
                        @php
                            $accountTodayTransaction = null;
                        @endphp
                        <tr class="tr-height">
                            <td class="text-left">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</td>
                            <td class="text-left">{{ $bank_account->account_number }}</td>
                            <td class="text-left">{{ $bank_account->bank->account->name }}</td>
                            <td class="text-left">{{ $bank_account->branch_name ?? '--' }}</td>
                            <td class="text-left">{{ \SkylarkSoft\GoRMG\BasicFinance\Models\BankAccount::CURRENCY_TYPES[$bank_account->currency_type_id] ?? '--' }}</td>
                            <td class="text-left">{{ $accountTodayTransaction ?? '--' }}</td>
                            <td class="text-left">{{ $bank_account->status == 1 ? 'Active' : 'In-Active' }}</td>
                            <td class="text-left">{{ $bank_account->factory->factory_name }}</td>
                            <td class="text-left">{{ \Carbon\Carbon::create($bank_account->date)->toFormattedDateString() }}</td>
                            <td>
                                @permission('permission_of_bank_accounts_edit')
                                <a class="btn btn-xs btn-success"
                                   href="{{ url('basic-finance/bank-accounts/create?id=' . $bank_account->id) }}">
                                    <i class="fa fa-edit"></i>
                                </a>
                                @endpermission
                            </td>
                        </tr>
                    @empty
                        <tr class="tr-height">
                            <td colspan="10" class="text-center text-danger">No Account Found</td>
                        </tr>
                    @endforelse
                    </tbody>
                    <tfoot>
                    @if($bank_accounts->total() > 15)
                        <tr>
                            <td colspan="10" align="center">
                                {{ $bank_accounts->appends(request()->except('page'))->links() }}
                            </td>
                        </tr>
                    @endif
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection
