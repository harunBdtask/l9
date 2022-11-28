@extends('finance::layout')
@section('title', 'Supplier Bill Entry')
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
                <h2>Supplier Bill Entry</h2>
            </div>
            <div class="box-body b-t">
                <div style="margin-bottom: 15px">
                    <a class="btn btn-sm white" href="{{ url('finance/supplier-bill-entry/create') }}">
                        <i class="glyphicon glyphicon-plus"></i> Create
                    </a>
                    <div class="pull-right" style="width: 40%">
                        <form action="{{ url('finance/supplier-bill-entry') }}" method="GET">
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
                    <div class="col-md-12 alert alert-success alert-dismissible text-center">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <small>{{ Session::get('success') }}</small>
                    </div>
                @elseif(Session::has('failure'))
                    <div class="col-md-12 alert alert-danger alert-dismissible text-center">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <small>{{ Session::get('failure') }}</small>
                    </div>
                @endif

                <table class="reportTable">
                    <thead class="thead-light">
                    <tr>
                        <th>Sl No.</th>
                        <th>Group</th>
                        <th>Company</th>
                        <th>Receive Date</th>
                        <th>Bill Number</th>
                        <th>Party Payable</th>
                        <th>Actions</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse($items as $item)
                        <tr class="tr-height">
                            <td>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ $item->group->company_name }}</td>
                            <td>{{ $item->company->factory_name }}</td>
                            <td>{{ $item->bill_receive_date }}</td>
                            <td>{{ $item->bill_number }}</td>
                            <td>{{ $item->party_payable }}</td>
                            <td>
                                <a class="btn btn-xs btn-info"
                                   target="_blank"
                                   href="{{ url('finance/supplier-bill-entry/'.$item->id.'/view') }}">
                                    <i class="fa fa-eye"></i>
                                </a>

                                <a class="btn btn-xs btn-success"
                                   href="{{ url('finance/supplier-bill-entry/'.$item->id.'/edit') }}">
                                    <i class="fa fa-edit"></i>
                                </a>

                                <button type="button" class="btn btn-xs btn-danger show-modal" data-toggle="modal"
                                        data-target="#confirmationModal" ui-toggle-class="flip-x"
                                        ui-target="#animate"
                                        data-url="{{ url('/finance/supplier-bill-entry/'.$item->id) }}">
                                    <i class="fa fa-times"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr class="tr-height">
                            <td colspan="6" class="text-center text-danger">No Item Found</td>
                        </tr>
                    @endforelse
                    </tbody>
                    <tfoot>
                    @if($items->total() > 15)
                        <tr>
                            <td colspan="6" align="center">
                                {{ $items->appends(request()->except('page'))->links() }}
                            </td>
                        </tr>
                    @endif
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection
