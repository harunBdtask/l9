@extends('finance::layout')
@section('title', 'Customer Bill Receives')
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
                <h2>Customer Bill Receives</h2>
            </div>
            <div class="box-body b-t">
                <div style="margin-bottom: 15px">
                    <a class="btn btn-sm white" href="{{ url('finance/customer-bill-payment/create') }}">
                        <i class="glyphicon glyphicon-plus"></i> Create
                    </a>
                    <div class="pull-right" style="width: 30%">
                        <form action="{{ url('finance/customer-bill-payment') }}" method="GET">
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm" name="search"
                                       value="{{ request('search') }}">
                                <span class="input-group-btn">
                                    <button class="btn btn-sm white m-b" type="submit">Search</button>
                                </span>
                            </div>
                        </form>
                    </div>
                </div>
                @include('partials.response-message')
                <table class="reportTable">
                    <thead class="thead-light">
                    <tr>
                        <th>Sl No.</th>
                        <th>Group</th>
                        <th>Company</th>
                        <th>Project</th>
                        <th>Currency</th>
                        <th>Customer</th>
                        <th>Action</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse($customerBillPayments as $item)
                        <tr class="tr-height">
                            <td>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ $item->group->company_name ?? '' }}</td>
                            <td>{{ $item->company->factory_name ?? '' }}</td>
                            <td>{{ $item->project->project ?? '' }}</td>
                            <td>{{ $item->currency_name }}</td>
                            <td>{{ $item->customer->name ?? '' }}</td>
                            <td>
                                <a class="btn btn-xs btn-success"
                                   href="{{ url('finance/customer-bill-payment/'.$item->id.'/edit') }}">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <button style="margin-left: 2px;" type="button"
                                        class="btn btn-xs btn-danger show-modal" title="Delete Budget"
                                        data-toggle="modal" data-target="#confirmationModal"
                                        ui-toggle-class="flip-x"
                                        ui-target="#animate"
                                        data-url="{{ url('/finance/customer-bill-payment/'.$item->id) }}">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr class="tr-height">
                            <td colspan="7" class="text-center text-danger">No Item Found</td>
                        </tr>
                    @endforelse
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="7" align="center">
                            {{ $customerBillPayments->appends(request()->except('page'))->links() }}
                        </td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection
