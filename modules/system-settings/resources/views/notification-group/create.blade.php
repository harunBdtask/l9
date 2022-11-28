@extends('skeleton::layout')
@section("title", "Notification Group")
@section('styles')
    <style>
        .custom-control-label {
            padding: 0.165rem 0;
        }

        .custom-form-section {
            border-radius: 6px;
        }

        .custom-field {
            width: 90%;
            border: 1px solid #cecece;
        }
    </style>
@endsection
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Notification Group</h2>
            </div>
            <div class="box-body b-t">
                <div class="row">
                    <div class="flash-message">
                        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                            @if(Session::has('alert-' . $msg))
                                <div
                                    class="alert alert-{{ $msg }} text-center">{{ Session::get('alert-' . $msg) }}</div>
                            @endif
                        @endforeach
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 custom-form-section">
                        <div class="box">
                            <div class="box-header">
                                {!! Form::open(['url' => '/notification-group', 'method' => 'POST', 'id' => 'notification-group-form']) !!}
                                <div class="form-group">
                                    <label for="name" class="custom-control-label">Group Name</label>
                                    {!! Form::text('name', request('name'), [
                                        'class' => 'form-control form-control-sm',
                                        'id' =>'name',
                                        'placeholder'=>'Group name'
                                        ]) !!}
                                    @if($errors->has('name'))
                                        <span class="text-danger">{{ $errors->first('name') }}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="users" class="custom-control-label">Users</label>
                                    {!! Form::select('users[]', $users ?? [], null, [
                                        'class' => 'form-control select2-input form-control-sm',
                                        'id' => 'users',
                                        'multiple']) !!}
                                    @if($errors->has('users'))
                                        <span class="text-danger">{{ $errors->first('users') }}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-sm btn-success" id="submit">
                                        <i class="fa fa-save"></i>
                                        Submit
                                    </button>
                                    <a href="{{ url('/notification-group') }}" class="btn btn-sm btn-warning"><i
                                            class="fa fa-refresh"></i>
                                        Reset</a>
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        {!! Form::open(['url' => '/notification-group', 'method' =>'GET']) !!}
                        <table class="reportTable">
                            <thead>
                            <tr>
                                <th></th>
                                <td>
                                    {!! Form::text('search_group', request()->search_group ?? null, [
                                        'class' => 'custom-field text-center',
                                        'placeholder' => 'Search here'
                                        ]) !!}
                                </td>
                                <th></th>
                                <th>
                                    <button type="submit" class="btn btn-xs btn-info"><i class="fa fa-search"></i>
                                    </button>
                                </th>
                            </tr>
                            <tr>
                                <th>SL</th>
                                <th>Groups</th>
                                <th>Users</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(!$groups->getCollection()->isEmpty())
                                @foreach($groups->getCollection() as $group)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $group->name }}</td>
                                        <td>{{ $group->user_notifications }}</td>
                                        <td>
                                            <button type="button" onclick="editGroup({{ $group->id }})"
                                                    class="edit-btn btn btn-xs btn-success">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-xs btn-danger show-modal"
                                                    data-toggle="modal"
                                                    data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                    ui-target="#animate"
                                                    data-url="{{ url('notification-group/'.$group->id) }}">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4" class="text-center">No Data</td>
                                </tr>
                            @endif
                            </tbody>
                            <tfoot>
                            @if($groups->total() > 15)
                                <tr>
                                    <td colspan="4"
                                        class="text-center">{{ $groups->appends(request()->except('page'))->links() }}
                                    </td>
                                </tr>
                            @endif
                            </tfoot>
                        </table>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        function editGroup(id) {
            $.ajax({
                url: `/notification-group/${id}`,
                type: "get",
                dataType: "JSON",
                success(response) {
                    const notificationGroup = response;
                    $("#name").val(notificationGroup.name);
                    $("#users").val(notificationGroup.users).select2();
                    $("#notification-group-form").attr('action', `/notification-group/${notificationGroup.id}`);
                    $("#submit").html('<i class="fa fa-check"></i> Update');
                }
            });
        }
    </script>
@endsection
