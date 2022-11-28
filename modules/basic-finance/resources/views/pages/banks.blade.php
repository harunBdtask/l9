@extends('basic-finance::layout')
@section('title', 'Banks')
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
                <h2>Banks</h2>
            </div>
            <div class="box-body b-t">
                <div style="margin-bottom: 15px">
                    @permission('permission_of_banks_add')
                    <a class="btn btn-sm white" href="{{ url('basic-finance/banks/create') }}">
                        <i class="glyphicon glyphicon-plus"></i> New Bank
                    </a>
                    @endpermission
                    <div class="pull-right" style="width: 40%">
                        <form action="{{ url('basic-finance/banks') }}" method="GET">
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm" name="search"
                                       value="{{ request('search') }}">
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
                        <th class="text-left">Bank Name</th>
                        <th class="text-left">Bank Short Name</th>
                        <th class="text-left">Branch Name</th>
                        <th class="text-left">Swift Code</th>
                        <th class="text-left">Contract Person</th>
                        <th class="text-left">Contract Number</th>
                        <th class="text-left">Email Address</th>
                        <th style="width: 5%;">Actions</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse($banks as $bank)
                        @php
                            $contractPersons = collect($bank->bankContractDetails)->pluck('name')->join(', ');
                            $contractNumbers = collect($bank->bankContractDetails)->pluck('contract_number')->join(', ');
                            $contractEmails = collect($bank->bankContractDetails)->pluck('email')->join(', ');
                        @endphp
                        <tr class="tr-height">
                            <td class="text-left">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</td>
                            <td class="text-left">{{ $bank->account->name }}</td>
                            <td class="text-left">{{ $bank->short_name ?? '--' }}</td>
                            <td class="text-left">{{ $bank->branch_name ?? '--' }}</td>
                            <td class="text-left">{{ $bank->swift_code ?? '--' }}</td>
                            <td class="text-left">{{ $contractPersons ?? '--' }}</td>
                            <td class="text-left">{{ $contractNumbers ?? '--' }}</td>
                            <td class="text-left">{{ $contractEmails ?? '--' }}</td>
{{--                            @if ($bank->currency_type_id === 1)--}}
{{--                                <td class="text-left">Home</td>--}}
{{--                            @elseif($bank->currency_type_id === 2)--}}
{{--                                <td class="text-left">Foren</td>--}}
{{--                            @else--}}
{{--                                <td class="text-left">--</td>--}}
{{--                            @endif--}}
                            <td>
                                @permission('permission_of_banks_edit')
                                <a class="btn btn-xs btn-success"
                                   href="{{ url('basic-finance/banks/create?id=' . $bank->id) }}">
                                    <i class="fa fa-edit"></i>
                                </a>
                                @endpermission
                            </td>
{{--                            <td>--}}
{{--                            @permission('permission_of_banks_delete')--}}
{{--                                {{ Form::open(['url' => "basic-finance/banks/$bank->id", 'method'=>'DELETE'])  }}--}}
{{--                                <button onclick="return confirm('Are you sure?')" class="btn btn-xs btn-danger">--}}
{{--                                    <i class="fa fa-trash"></i>--}}
{{--                                </button>--}}
{{--                                {{ Form::close()  }}--}}
{{--                            @endpermission --}}
{{--                            </td>--}}
                        </tr>
                    @empty
                        <tr class="tr-height">
                            <td colspan="6" class="text-center text-danger">No Account Found</td>
                        </tr>
                    @endforelse
                    </tbody>

                    <tfoot>
                    @if($banks->total() > 15)
                        <tr>
                            <td colspan="10" align="center">
                                {{ $banks->appends(request()->except('page'))->links() }}
                            </td>
                        </tr>
                    @endif
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection
