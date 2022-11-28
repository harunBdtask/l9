@extends('skeleton::layout')
@section('title', 'Member List')
@section('content')
    <div class="padding">
        <div class="box" >
            <div class="box-header">
                <h2>Member List</h2>
            </div>
            <div class="box-body b-t">
                @if(Session::has('permission_of_teams_add') || getRole() == 'super-admin' || getRole() == 'admin')
                    <a class="btn btn-sm white m-b" href="{{ url('teams/create') }}">
                        <i class="glyphicon glyphicon-plus"></i> New Member
                    </a>
                @endif
                <div class="flash-message" style="margin-top:20px;">
                    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                        @if(Session::has('alert-' . $msg))
                            <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</p>
                        @endif
                    @endforeach
                </div>
            </div>
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="reportTable">
                        <thead>
                        <tr>
                            <th width="10%">SL</th>
                            <th width="10%">Member Name</th>
                            <th width="10%">Designation</th>
                            <th width="20%">Email</th>
                            <th width="20%">Team Name</th>
                            <th width="20%">Status</th>
                            <th width="30%">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(!$members->getCollection()->isEmpty())
                            @foreach($members->getCollection() as $member)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $member->member_name }}</td>
                                    <td>{{ $member->team_lead_id }}</td>
                                    <td>{{$member->designation_id}}</td>
                                    <td>Due</td>
                                    @if( getRole() == 'super-admin')
                                        <td>{{ $member->factory->factory_name }}</td>
                                    @endif
                                    <td>{{ $member->status == 1? "Active" : ($member->status == 2 ? "In Active": "Cancelled") }}</td>

                                    <td>
                                        @if(Session::has('permission_of_teams_edit') || getRole() == 'super-admin' || getRole() == 'admin')
                                            <a class="btn btn-xs btn-success" href="{{ url('members/'.$member->id.'/edit') }}"><i class="fa fa-edit"></i></a>
                                        @endif
                                        @if(Session::has('permission_of_teams_delete') || getRole() == 'super-admin' || getRole() == 'admin')
                                            <button type="button" class="btn btn-xs btn-danger show-modal" data-toggle="modal" data-target="#confirmationModal" ui-toggle-class="flip-x" ui-target="#animate" data-url="{{ url('teams/'.$team->id) }}">
                                                <i class="fa fa-trash-o"></i>
                                            </button>
                                        @endif
                                        {{--                                            <i class="fa fa-users" aria-hidden="true"></i>--}}
                                        <button type="button" class="btn btn-xs btn-danger show-modal" data-toggle="modal" data-target="#confirmationModal" ui-toggle-class="flip-x" ui-target="#animate" data-url="{{ url('teams/'.$team->id) }}">
                                            <i class="fa fa-users" aria-hidden="true"></i>
                                        </button>

                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4" align="center">No Data
                                <td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                    <div class="text-center">
                        {{ $members->appends(request()->except('page'))->links() }}
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
@endsection
