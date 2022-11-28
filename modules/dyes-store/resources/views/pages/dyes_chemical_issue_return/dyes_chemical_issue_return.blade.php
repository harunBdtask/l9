@extends('dyes-store::layout')
@section('title','Dyes Chemical Issue Return')
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>DYES CHEMICALS ISSUE RETURN</h2>
            </div>
            <div class="box-body table-responsive b-t">
                <div class="row m-b-2">
                    <div class="box-body">
                        <a class="btn btn-sm btn-info" href="{{ url('/dyes-store/dyes-chemical-issue-return/create') }}">
                            <i class="glyphicon glyphicon-plus"></i> Issue Return
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
                                <th style="width: 170px;">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($issueReturn as $issue)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $issue->return_date }}</td>
                                    <td>{{ $issue->system_generate_id }}</td>
                                    <td>
                                        @if($issue->readonly)
                                        <a class="btn btn-xs text-info" data-toggle="tooltip" data-placement="top"
                                           href="{{ url('dyes-store/dyes-chemical-issue-return/create?id=' . $issue->id) }}"
                                           title="Edit">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a class="btn btn-xs text-success"
                                           data-toggle="tooltip"
                                           data-placement="top"
                                           href="{{ url('dyes-store/dyes-chemical-issue-return/' . $issue->id . '/stock-transaction?type=issue_return') }}"
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
                                                data-url="{{ url('dyes-store/dyes-chemical-issue-return/'.$issue->id) }}">
                                            <em class="fa fa-trash"></em>
                                        </button>
                                    </td>
                                    @endif
                                </tr>
                            @empty
                                <td colspan="9">Not Found!</td>
                            @endforelse
                            </tbody>
                        </table>

                        @if ($issueReturn->total() > 15)
                            <div
                                class="text-center print-delete">{{ $issueReturn->appends(request()->except('page'))->links() }}</div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
