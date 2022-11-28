@extends('skeleton::layout')
@section('title', 'User')
@section('content')
    <div class="padding">
        <div class="box" >
            <div class="box-header">
                <h2>User List</h2>
            </div>
            <div class="box-body b-t">
                @if(getRole() == 'super-admin' || getRole() == 'admin')
                    <a class="btn btn-sm white m-b" href="{{ url('users/create') }}">
                        <i class="glyphicon glyphicon-plus"></i> New User
                    </a>
                @endif
                <div class="pull-right">
                    <form action="{{ url('/search-users') }}" method="GET">
                        <div class="pull-left" style="margin-right: 10px;">
                            <input type="text" class="form-control form-control-sm" name="q" value="{{ $q ?? '' }}">
                        </div>
                        <div class="pull-right">
                            <input type="submit" class="btn btn-sm white" value="Search">
                        </div>
                    </form>
                </div>
                @include('partials.response-message')

            </div>
            <table class="reportTable">
                <thead>
                <tr>
                    <th width="5%">SL</th>
                    <th width="8%">F. Name</th>
                    <th width="8%">L. Name</th>
                    <th width="8%">Email</th>
                    <th width="8%">Phone No.</th>
                    <th width="8%">Designation</th>
                    <th width="10%">Addresss</th>
                    <th width="8%">Department</th>
                    <th width="8%">Company</th>
                    <th width="8%">Role</th>
                    <th width="15%">Action</th>
                </tr>
                </thead>
                <tbody>
                @if(!$users->getCollection()->isEmpty())
                    @foreach($users->getCollection() as $user)
                        @if(getRole() != 'super-admin')
                            @if($user->role_slug == 'super-admin')
                                @continue
                            @endif
                        @endif
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $user->first_name }}</td>
                            <td>{{ $user->last_name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone_no }}</td>
                            <td>{{ $user->designation }}</td>
                            <td>{{ $user->address }}</td>
                            <td>{{ $user->department_name }}</td>
                            <td>{{ $user->factory_name }}</td>
                            <td>{{ $user->role_name }}</td>
                            <td>
                                @if(getRole() == 'super-admin' || getRole() == 'admin')
                                    <a class="btn btn-sm white" href="{{ url('users/'.$user->id.'/edit') }}"><i class="fa fa-edit"></i></a>
                                @endif
                                @if(getRole() == 'super-admin' || getRole() == 'admin')
                                    <button type="button" class="btn btn-sm white show-modal" data-toggle="modal" data-target="#confirmationModal" ui-toggle-class="flip-x" ui-target="#animate" data-url="{{ url('users/'.$user->id) }}">
                                        <i class="fa fa-times"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="11" align="center">No Users
                        <td>
                    </tr>
                @endif
                </tbody>
                <tfoot>
                @if($users->total() > 15)
                    <tr>
                        <td colspan="11" align="center">{{ $users->appends(request()->except('page'))->links() }}</td>
                    </tr>
                @endif
                </tfoot>
            </table>
        </div>
    </div>
@endsection
