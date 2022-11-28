@extends('basic-finance::layout')
@section('title', 'Receive Cheques')
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
                <h2>Receive Cheques List</h2>
            </div>
            <div class="box-body b-t">
                <div style="margin-bottom: 50px;">
                    <div class="pull-right" style="width: 30%;">
                        <form action="{{ url('basic-finance/receive-cheques') }}" method="GET">
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
                        <th class="text-left">Sl No.</th>
                        <th class="text-left">Voucher No</th>
                        <th class="text-left">Bank Name</th>
                        <th class="text-left">Cheque No</th>
                        <th class="text-left">Cheque Due Date</th>
{{--                        <th style="width: 5%;">Actions</th>--}}
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($cheques as $cheque)
                        <tr class="tr-height">
                            <td class="text-left">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</td>
                            <td class="text-left">{{ $cheque->voucher_no }}</td>
                            <td class="text-left">{{ $cheque->receiveBank->name }}</td>
                            <td class="text-left">{{ $cheque->cheque_no }}</td>
                            <td class="text-left">{{ $cheque->cheque_due_date }}</td>
{{--                            <td>--}}
{{--                                <button type="button" class="btn btn-xs btn-danger show-modal" data-toggle="modal"--}}
{{--                                        data-target="#confirmationModal" ui-toggle-class="flip-x" ui-target="#animate"--}}
{{--                                        data-url="{{ url('basic-finance/receive-cheques/'.$cheque->id) }}">--}}
{{--                                    <i class="fa fa-times"></i>--}}
{{--                                </button>--}}
{{--                            </td>--}}
                        </tr>
                    @empty
                        <tr class="tr-height">
                            <td colspan="6" class="text-center text-danger">No Account Found</td>
                        </tr>
                    @endforelse
                    </tbody>

                    <tfoot>
                    @if($cheques->total() > 15)
                        <tr>
                            <td colspan="10" align="center">
                                {{ $cheques->appends(request()->except('page'))->links() }}
                            </td>
                        </tr>
                    @endif
                    </tfoot>
                </table>

            </div>
        </div>
    </div>
@endsection
