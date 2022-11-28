@extends('skeleton::layout')
@section('title', 'Permission')
@push('style')
    <style>


    </style>
@endpush

@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Permission List</h2>
            </div>
            <div class="row padding">
                <div class="col-sm-12 col-md-3">
                    @if(Session::has('permission_of_permissions_add') || getRole() == 'super-admin')
                        <div class="box">
                            <div class="box-body form-colors">
                                {!! Form::model($permission, ['url' => $permission ? 'permissions/'.$permission->id : 'permissions', 'method' => 'POST', 'id'=>'form']) !!}
                                <div class="form-group">
                                    <label for="permission_name"><b>Name</b></label>
                                    {!! Form::text('permission_name', null, ['class' => 'form-control form-control-sm', 'id' => 'permission_name', 'placeholder' => 'Write permission\'s name here']) !!}

                                    @if($errors->has('permission_name'))
                                        <span class="text-danger">{{ $errors->first('permission_name') }}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <button type="submit" id="submit" class="btn btn-sm btn-success"><i
                                            class="fa fa-save"></i> Create
                                    </button>
                                    <button type="button" onclick="cancel()" class="btn btn-sm btn-warning"><a
                                            href="javascript:void(0)"><i class="fa fa-remove"></i> Cancel</a></button>
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    @endif
                </div>
                <div class="col-sm-12 col-md-9">
                    <div class="col-md-6"></div>
                    <div class="col-md-6">
                        {!! Form::open(['url' => 'permissions', 'method' => 'GET']) !!}
                        <div class="input-group">
                            <input type="text" class="form-control form-control-sm" name="q"
                                   value="{{ request('q') ?? '' }}" placeholder="Search">
                            <span class="input-group-btn">
                            <button class="btn btn-sm btn-info m-b" type="submit">Search</button>
                        </span>
                        </div>
                        {!! Form::close() !!}
                    </div>
                    <div class="flash-message">
                        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                            @if(Session::has('alert-' . $msg))
                                <p class="alert alert-{{ $msg }} text-center">{{ Session::get('alert-' . $msg) }}</p>
                            @endif
                        @endforeach
                    </div>
                    <table class="reportTable">
                        <thead>
                        <tr>
                            <th width="20%">SL</th>
                            <th width="40%">Permission Name</th>
                            <th width="40%">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(!$permissions->getCollection()->isEmpty())
                            @foreach($permissions->getCollection() as $permission)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $permission->permission_name }}</td>
                                    <td>
                                        @if(Session::has('permission_of_permissions_edit') || getRole() == 'super-admin')
                                            <a href="javascript:void(0)" data-id="{{ $permission->id }}"
                                               class="btn btn-sm btn-success edit"><i class="fa fa-edit"></i></a>
                                        @endif
                                        @if(Session::has('permission_of_permissions_delete') || getRole() == 'super-admin')
                                            <button type="button" class="btn btn-sm btn-danger show-modal"
                                                    data-toggle="modal"
                                                    data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                    ui-target="#animate"
                                                    data-url="{{ url('permissions/'.$permission->id) }}">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="3" align="center">No Permissions
                                <td>
                            </tr>
                        @endif
                        </tbody>
                        <tfoot>
                        @if($permissions->total() > 15)
                            <tr>
                                <td colspan="3"
                                    align="center">{{ $permissions->appends(request()->except('page'))->links() }}</td>
                            </tr>
                        @endif
                        </tfoot>
                    </table>
                </div>
            </div>
            {{--            <div class="box-body b-t">--}}
            {{--                @if(Session::has('permission_of_permissions_add') || getRole() == 'super-admin')--}}
            {{--                    <a class="btn btn-sm btn-info m-b" href="{{ url('permissions/create') }}">--}}
            {{--                        <i class="glyphicon glyphicon-plus"></i> New Permission--}}
            {{--                    </a>--}}
            {{--                @endif--}}
            {{--            </div>--}}
            {{--            <div class="flash-message">--}}
            {{--                @foreach (['danger', 'warning', 'success', 'info'] as $msg)--}}
            {{--                    @if(Session::has('alert-' . $msg))--}}
            {{--                        <p class="alert alert-{{ $msg }} text-center">{{ Session::get('alert-' . $msg) }}</p>--}}
            {{--                    @endif--}}
            {{--                @endforeach--}}
            {{--            </div>--}}
            {{--            <table class="reportTable">--}}
            {{--                <thead>--}}
            {{--                <tr>--}}
            {{--                    <th width="20%">SL</th>--}}
            {{--                    <th width="40%">Permission Name</th>--}}
            {{--                    <th width="40%">Actions</th>--}}
            {{--                </tr>--}}
            {{--                </thead>--}}
            {{--                <tbody>--}}
            {{--                @if(!$permissions->getCollection()->isEmpty())--}}
            {{--                    @foreach($permissions->getCollection() as $permission)--}}
            {{--                        <tr>--}}
            {{--                            <td>{{ $loop->iteration }}</td>--}}
            {{--                            <td>{{ $permission->permission_name }}</td>--}}
            {{--                            <td>--}}
            {{--                                @if(Session::has('permission_of_permissions_edit') || getRole() == 'super-admin')--}}
            {{--                                    <a class="btn btn-sm white"--}}
            {{--                                       href="{{ url('permissions/'.$permission->id.'/edit') }}"><i--}}
            {{--                                            class="fa fa-edit"></i></a>--}}
            {{--                                @endif--}}
            {{--                                @if(Session::has('permission_of_permissions_delete') || getRole() == 'super-admin')--}}
            {{--                                    <button type="button" class="btn btn-sm white show-modal" data-toggle="modal"--}}
            {{--                                            data-target="#confirmationModal" ui-toggle-class="flip-x"--}}
            {{--                                            ui-target="#animate" data-url="{{ url('permissions/'.$permission->id) }}">--}}
            {{--                                        <i class="fa fa-times"></i>--}}
            {{--                                    </button>--}}
            {{--                                @endif--}}
            {{--                            </td>--}}
            {{--                        </tr>--}}
            {{--                    @endforeach--}}
            {{--                @else--}}
            {{--                    <tr>--}}
            {{--                        <td colspan="3" align="center">No Approvals--}}
            {{--                        <td>--}}
            {{--                    </tr>--}}
            {{--                @endif--}}
            {{--                </tbody>--}}
            {{--                <tfoot>--}}
            {{--                @if($permissions->total() > 15)--}}
            {{--                    <tr>--}}
            {{--                        <td colspan="3"--}}
            {{--                            align="center">{{ $permissions->appends(request()->except('page'))->links() }}</td>--}}
            {{--                    </tr>--}}
            {{--                @endif--}}
            {{--                </tfoot>--}}
            {{--            </table>--}}
        </div>
    </div>
@endsection

@push('script-head')

    <script>
        $(document).on('click', '.edit', function () {
            let id = $(this).data('id');
            $.ajax({
                method: 'get',
                url: '{{ url('permissions') }}/' + id + '/edit',
                success: function (result) {
                    $('#form').attr('action', `permissions/${result.id}`).append(`<input type="hidden" id="_method" name="_method" value="PUT"/>`);
                    $('#permission_name').val(result.permission_name);
                    $('#submit').html(`<i class="fa fa-save"></i> Update`);
                },
                error: function (xhr) {
                    console.log(xhr)
                }
            })
        })

        function cancel() {
            $('#permission_name').val('');
            $('#form').attr('action', '/permissions').append(`<input type="hidden" id="_method" name="_method" value="POST"/>`);
            $('#submit').html(`<i class="fa fa-save"></i> Create`);
            $('.text-danger').hide();
        }
    </script>
@endpush
