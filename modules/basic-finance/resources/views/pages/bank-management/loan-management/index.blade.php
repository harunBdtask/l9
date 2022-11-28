@extends('basic-finance::layout')
@section('title','Loan Accounts')
@section('content')
    <div class="padding">
        <div class="box" style="min-height: 610px">
            <div class="box-header btn-info">
                <h2>
                    Loan Accounts List
                </h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-6">
                        @permission('permission_of_loan_accounts_add')
                        <a href="{{ url('/basic-finance/loan/accounts/create') }}" class="btn btn-info"><i
                                class="fa fa-plus"></i>
                            New Loan Account</a>
                        @endpermission
                    </div>
                    <div class="col-sm-4 col-sm-offset-2">
                        <form action="{{ url('/basic-finance/loan/accounts') }}" method="GET">
                            <div class="input-group">
                                <input type="text" class="form-control" name="search"
                                       value="{{ $search ?? '' }}" placeholder="Search">
                                <span class="input-group-btn">
                                            <button class="btn btn-info" type="submit">Search</button>
                                        </span>
                            </div>
                        </form>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="flash-message">
                            @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                                @if(Session::has('alert-' . $msg))
                                    <p class="alert alert-{{ $msg }} text-center">{{ Session::get('alert-' . $msg) }}</p>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="row m-t">
                    <div class="col-sm-12">
                        <table class="reportTable">
                        </table>
                    </div>
                </div>
                <div class="row m-t">
                    <div class="col-sm-12">
                        @if($loanAccounts)
                        @include('basic-finance::pages.bank-management.loan-management.list')
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
