@extends('skeleton::layout')
@section('title', 'New Assigned List')
@push('style')
    <style>
        .flex {
          display: flex;
        }

        .justify-content-betwen {
          justify-content: space-between;
        }

        .align-items-center {
          align-items: center;
        }
    </style>
@endpush
@php
$query_string = '';
$column_names = [
  'user_name' => 'User Name',
  'email' => 'Email',
];
$column_name = '';
if(old('query_string')){
  $query_string = old('query_string');
  $column_name = old('column_name');
}
if( request()->get('query_string')){
  $query_string =  request()->get('query_string');
  $column_name = request()->get('column_name');
}
@endphp
@section('content')
    <div class="padding">
        <div class="box" >
            <div class="box-header">
                <h2>User Assigned List</h2>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-6">
                        @permission('permission_of_assign_permissions_add')
                            <a class="btn btn-sm white m-b" href="{{ url('/assign-permissions/create') }}">
                                <i class="glyphicon glyphicon-plus"></i> New Assign Permission
                            </a>
                        @endpermission
                    </div>
                    <div class="col-md-6 ">
                      {!! Form::open(['url' => url('assign-permissions/search'), 'method' => 'GET']) !!}
                          <div class="flex justify-content-betwen">
                              <div>
                                {!! Form::select('column_name', $column_names, $column_name, ['class' => 'form-control c-select', 'placeholder' => 'Search By', 'style' => 'width: 190px;']) !!}
                              </div>
                              <div class="input-group">
                                <input name="query_string" type="text" class="form-control form-control-sm" style="width:230px"
                                  value="{{$query_string}}">
                                <button type="submit" class="btn btn-sm white m-b button-class" style="border-radius: 0px">
                                  Search
                                </button>
                              </div>
                            </div>
                        {!! Form::close() !!}
                    </div>
                </div>
                <div class="row m-t-2">
                    <div class="col">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>SL</th>
                                <th>User Name</th>
                                <th>E-mail</th>
                                <th>Department</th>
                                <th width="15%">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($users && !$users->isEmpty())
                                @foreach($users as $user)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $user->first_name ." ". $user->last_name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->departmnt->department_name ?? '' }}</td>
                                        <td>
                                            @if(Session::has('permission_of_assign_permission_edit') || getRole() == 'super-admin' || getRole() == 'admin')
                                                <a class="btn btn-sm btn-primary-outline" href="{{ url('assign-permissions/'.$user->id) }}">View
                                                    Assigned Menus</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4" align="center">No Users
                                    <td>
                                </tr>
                            @endif
                            </tbody>
                            <tfoot>
                            @if($users && $users->total() > 15)
                                <tr>
                                    <td colspan="4" align="center">{{ $users->appends(request()->except('page'))->links() }}</td>
                                </tr>
                            @endif
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
