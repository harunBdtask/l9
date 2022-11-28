@extends('dyes-store::layout')
@section('title','Dyes Chemical Receive Return')
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>DYES CHEMICALS RECEIVING RETURN</h2>
            </div>
            <div class="box-body table-responsive b-t">
                <div class="row m-b-2">
                    <div class="box-body">
                        <a class="btn btn-sm btn-info" href="{{ url('/dyes-store/dyes-chemical-receive-return/create') }}">
                            <i class="glyphicon glyphicon-plus"></i> Receive Return
                        </a>
                    </div>
                </div>
                <div class="flash-message">
                    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                        @if (Session::has('alert-' . $msg))
                            <div class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</div>
                        @endif
                    @endforeach
                </div>


                <div class="row">
                    <div class="col-md-12">
                        <table class="reportTable">
                            <thead>
                            <tr style="background: ghostwhite;">
                                <th style="text-align:left; padding-left: 1em;">Sl</th>
                                <th style="text-align:left; padding-left: 1em;">Return Date</th>
                                <th style="text-align:left; padding-left: 1em;">System Generate Id</th>
                                <th style="text-align:left; padding-left: 1em;">Supplier Name</th>
                                <th style="text-align:left; padding-left: 1em;">Reference / Challan No</th>
                                <th style="width: 170px;">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($receiveReturn as $receive)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $receive->return_date }}</td>
                                    <td>{{ $receive->system_generate_id }}</td>
                                    <td>{{ $receive->supplier->name }}</td>
                                    <td>{{ $receive->challan_no }}</td>
                                    <td>
                                        @if($receive->readonly)
                                            <a class="btn btn-xs text-info" data-toggle="tooltip" data-placement="top"
                                               href="{{ url('dyes-store/dyes-chemical-receive-return/create?id=' . $receive->id) }}"
                                               title="Edit">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <a class="btn btn-xs text-success"
                                               data-toggle="tooltip"
                                               data-placement="top"
                                               href="{{ url('dyes-store/dyes-chemical-receive-return/' . $receive->id . '/stock-transaction?type=receive_return') }}"
                                               onclick="return confirm('Are You Sure?');"
                                               title="Make Transaction">
                                                <i class="fa fa-check-square-o"></i>
                                            </a>
                                            <button style="margin-left: 2px;" type="button"
                                                    class="btn btn-xs btn-danger show-modal"
                                                    title="Delete Order"
                                                    data-toggle="modal"
                                                    data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                    ui-target="#animate"
                                                    data-url="{{ url('dyes-store/dyes-chemical-receive-return/'.$receive->id) }}">
                                                <em class="fa fa-trash"></em>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <td colspan="9">Not Found!</td>
                            @endforelse
                            </tbody>
                        </table>

                        @if ($receiveReturn->total() > 15)
                            <div
                                class="text-center print-delete">{{ $receiveReturn->appends(request()->except('page'))->links() }}
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
