@extends('skeleton::layout')
@section('title','Approval - Permission')
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>
                    Permission List
                </h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-6">
                        <a href="{{ url('/approvals/create') }}" class="btn btn-sm btn-info m-b"><i class="fa fa-plus"></i>
                            New
                            Permission</a>
                    </div>
                    <div class="col-sm-4 col-sm-offset-2">
                        <form action="{{ url('/approvals/user-approval-permission/search') }}" method="GET">
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm" name="search"
                                       value="{{ $search ?? '' }}" placeholder="Search">
                                <span class="input-group-btn">
                                            <button class="btn btn-sm btn-info m-b" type="submit">Search</button>
                                        </span>
                            </div>
                        </form>
                    </div>
                </div>
                @include('partials.response-message')
                <div class="row m-t">
                    <div class="col-sm-12" style="overflow-x: scroll;">
                        <table class="reportTable">
                            <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Company Name</th>
                                <th>User Name</th>
                                <th>Page Name</th>
                                <th>Buyers</th>
                                <th>Alternative User</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($approvals as $approval)
                                <tr>
                                    <td>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</td>
                                    <td>{{ $approval->factory->factory_name }}</td>
                                    <td>{{ $approval->user->screen_name }}</td>
                                    <td>{{ $approval->page_name }}</td>
                                    <td>
                                        {{ $approval->buyer_names == 'All Buyers' ? 'All Buyers' : collect($approval->buyer_names)->pluck('name')->implode(', ') }}
                                    </td>
                                    <td>{{ $approval->alternativeUser->screen_name }}</td>
                                    <td style="padding: 2px">
                                        <div style="display:flex">
                                            <a style="margin-right: 3px;" href="{{ url('/approvals/create?approval_id=') . $approval->id }}"
                                            class="btn btn-xs btn-warning">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-xs btn-danger show-modal"
                                                    data-toggle="modal"
                                                    data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                    ui-target="#animate"
                                                    data-url="{{ url('approvals/'.$approval->id) }}">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <th colspan="10">No Data Found</th>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row m-t">
                    <div class="col-sm-12">
                        {{ $approvals->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
