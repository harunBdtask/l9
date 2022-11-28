@extends('skeleton::layout')
@section('title', 'Roles')
@push('style')
    <style>

    </style>
@endpush

@section('content')
    <div class="padding">
        <div class="box" >
            <div class="box-header">
                <h2>Role List</h2>
            </div>

            <div class="row padding">
                <div class="col-sm-12 col-md-3">
                    @if(Session::has('permission_of_roles_add') || getRole() == 'super-admin')
                        <div class="box" >
                            <div class="box-body form-colors">
                            {!! Form::model($role, ['url' => $role ? 'roles/'.$role->id : 'roles', 'method' => 'POST', 'id'=>'form']) !!}
                                <div class="form-group">
                                    <label for="name" ><b>Name</b></label>
                                    {!! Form::text('name', null, ['class' => 'form-control form-control-sm', 'id' => 'name', 'placeholder' => 'Write roles\'s name here']) !!}

                                    @if($errors->has('name'))
                                        <span class="text-danger">{{ $errors->first('name') }}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <button type="submit" id="submit" class="btn btn-sm btn-success"><i class="fa fa-save"></i> Create </button>
                                    <button type="button" class="btn btn-sm btn-warning" onclick="cancel()"><i class="fa fa-remove"></i> Cancel</button>
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    @endif
                </div>
                <div class="col-sm-12 col-md-9">
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
                            <th>SL</th>
                            <th>Role Name</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(!$roles->getCollection()->isEmpty())
                            @foreach($roles->getCollection() as $role)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $role->name }}</td>
                                    <td>
                                        @if(Session::has('permission_of_roles_edit') || getRole() == 'super-admin')
{{--                                            <a class="btn btn-sm white" href="{{ url('roles/'.$role->id.'/edit') }}"><i--}}
{{--                                                    class="fa fa-edit"></i></a>--}}
                                            <a href="javascript:void(0)" data-id="{{ $role->id }}" class="btn btn-sm btn-success edit"><i class="fa fa-edit"></i></a>
                                        @endif
                                        @if(Session::has('permission_of_roles_delete') || getRole() == 'super-admin')
                                            <button type="button" class="btn btn-sm btn-danger show-modal" data-toggle="modal"
                                                    data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                    ui-target="#animate" data-url="{{ url('roles/'.$role->id) }}">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="3" align="center">No Roles
                                <td>
                            </tr>
                        @endif
                        </tbody>
                        <tfoot>
                        @if($roles->total() > 15)
                            <tr>
                                <td colspan="3" align="center">{{ $roles->appends(request()->except('page'))->links() }}</td>
                            </tr>
                        @endif
                        </tfoot>
                    </table>
                </div>
            </div>



{{--            -------------------- previous code ---------------}}

{{--            <div class="box-body b-t">--}}
{{--                @if(Session::has('permission_of_roles_add') || getRole() == 'super-admin')--}}
{{--                    <a class="btn btn-sm white m-b" href="{{ url('roles/create') }}">--}}
{{--                        <i class="glyphicon glyphicon-plus"></i> New Role--}}
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
{{--                    <th>SL</th>--}}
{{--                    <th>Role Name</th>--}}
{{--                    <th>Actions</th>--}}
{{--                </tr>--}}
{{--                </thead>--}}
{{--                <tbody>--}}
{{--                @if(!$roles->getCollection()->isEmpty())--}}
{{--                    @foreach($roles->getCollection() as $role)--}}
{{--                        <tr>--}}
{{--                            <td>{{ $loop->iteration }}</td>--}}
{{--                            <td>{{ $role->name }}</td>--}}
{{--                            <td>--}}
{{--                                @if(Session::has('permission_of_roles_edit') || getRole() == 'super-admin')--}}
{{--                                    <a class="btn btn-sm white" href="{{ url('roles/'.$role->id.'/edit') }}"><i--}}
{{--                                            class="fa fa-edit"></i></a>--}}
{{--                                @endif--}}
{{--                                @if(Session::has('permission_of_roles_delete') || getRole() == 'super-admin')--}}
{{--                                    <button type="button" class="btn btn-sm white show-modal" data-toggle="modal"--}}
{{--                                            data-target="#confirmationModal" ui-toggle-class="flip-x"--}}
{{--                                            ui-target="#animate" data-url="{{ url('roles/'.$role->id) }}">--}}
{{--                                        <i class="fa fa-times"></i>--}}
{{--                                    </button>--}}
{{--                                @endif--}}
{{--                            </td>--}}
{{--                        </tr>--}}
{{--                    @endforeach--}}
{{--                @else--}}
{{--                    <tr>--}}
{{--                        <td colspan="3" align="center">No Roles--}}
{{--                        <td>--}}
{{--                    </tr>--}}
{{--                @endif--}}
{{--                </tbody>--}}
{{--                <tfoot>--}}
{{--                @if($roles->total() > 15)--}}
{{--                    <tr>--}}
{{--                        <td colspan="3" align="center">{{ $roles->appends(request()->except('page'))->links() }}</td>--}}
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
                url: '{{ url('roles') }}/' + id + '/edit',
                success: function (result) {
                    $('#form').attr('action', `roles/${result.id}`).append(`<input type="hidden" id="_method" name="_method" value="PUT"/>`);
                    $('#name').val(result.name);
                    $('#submit').html(`<i class="fa fa-save"></i> Update`);
                },
                error: function (xhr) {
                    console.log(xhr)
                }
            })
        })

        function cancel(){
            $('#name').val('');
            $('#form').attr('action', '/roles').append(`<input type="hidden" id="_method" name="_method" value="POST"/>`);
            $('#submit').html(`<i class="fa fa-save"></i> Create`);
            $('.text-danger').hide();
        }
    </script>
@endpush
