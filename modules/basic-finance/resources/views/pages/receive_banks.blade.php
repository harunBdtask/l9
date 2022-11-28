@extends('basic-finance::layout')
@section('title', 'Receive Banks')
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
                <h2>Receive Banks</h2>
            </div>
            <div class="box-body b-t">
                <div style="margin-bottom: 15px">
                    @permission('permission_of_receive_bank_list_add')
                    <a class="btn btn-sm white" href="{{ url('basic-finance/receive-banks/create') }}">
                        <i class="glyphicon glyphicon-plus"></i> New Receive Bank
                    </a>
                    @endpermission
                    <div class="pull-right" style="width: 40%">
                        <form action="{{ url('basic-finance/receive-banks') }}" method="GET">
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
                        <th style="width: 5%;">Actions</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse($banks as $bank)
                        <tr class="tr-height">
                            <td class="text-left">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</td>
                            <td class="text-left">{{ $bank->name }}</td>
                            <td class="text-left">{{ $bank->short_name ?? '--' }}</td>
                            <td>
                                @permission('permission_of_receive_bank_list_edit')
                                <a class="btn btn-xs btn-success"
                                   href="{{ url('basic-finance/receive-banks/'.$bank->id.'/edit') }}">
                                    <i class="fa fa-edit"></i>
                                </a>
                                @endpermission
                            </td>
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
