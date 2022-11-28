@extends('skeleton::layout')
@section('title', 'Mail Employee List')
@push('style')
    <style>
        .table-div{
            margin-top: 30px;
        }
    </style>

@endpush
@section('content')
    <div class="padding">
        @if(Session::get('user_role') == 'super-admin')
            <div class="box" >
                <div class="box-header">
                    <h2>Mail Employee List</h2>
                </div>
                <div class="row padding">
                    <div class="col-sm-12 col-md-3">
                        <div class="box form-colors">
                            <div class="box-header">
                                @if(Session::get('user_role') == 'super-admin')

                                    {{ Form::open(array('url' => 'mail-employee-list/store', 'method' => 'POST', 'id'=>'form')) }}
                                    <div class="form-group">
                                        <label for="mail_employee_lists" >Email address</label>
                                        {!! Form::text('email', null, ['class' => 'form-control form-control-sm', 'id' => 'mail_employee_lists_email', 'placeholder' => 'Email address']) !!}
                                        @if($errors->has('email'))
                                            <span class="text-danger">{{ $errors->first('email') }}</span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label for="mail_employee_lists" >Status</label>
                                        {!! Form::select('status', ['1' => 'Active', '0' => 'Inactive'], $mail_employee_lists->status ?? 1, ['class' => 'form-control form-control-sm', 'id'=>'status']); !!}
                                        @if($errors->has('status'))
                                            <span class="text-danger">{{ $errors->first('status') }}</span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" id="submit" class="btn btn-sm btn-success"><i class="fa fa-save"></i> Create </button>
                                        <a class="btn btn-sm btn-warning" onclick="cancel()" href="javascript:void(0)"><i class="fa fa-remove"></i> Cancel</a>
                                    </div>
                                    {{ Form::close() }}
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-9">
                        <div class="flash-message print-delete">
                            @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                                @if(Session::has('alert-' . $msg))
                                    <p class="alert alert-{{ $msg }} text-center">{{ Session::get('alert-' . $msg) }}</p>
                                @endif
                            @endforeach
                        </div>
                        <div class="table-responsive table-div">
                            <table class="reportTable" style="text-align: left">
                                <thead>
                                <tr>
                                    <th>Email Address</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($mail_employee_lists as $mail_employee_list)
                                    <tr>
                                        <td>{{$mail_employee_list->email}}</td>
                                        <td>{{$mail_employee_list->status == 1 ? 'Active' : 'Inactive'}}</td>
                                        <td>
                                            @if(Session::get('user_role') == 'super-admin')
{{--                                                <a class="btn btn-xs btn-success" href="{{url('mail-employee-list/edit?id='.$mail_employee_list->id)}}"><i class="fa fa-edit"></i> </a>--}}
                                                <a href="javascript:void(0)" data-id="{{ $mail_employee_list->id }}" class="btn btn-sm btn-success edit"><i class="fa fa-edit"></i></a>
                                            @endif
                                            @if(Session::get('user_role') == 'super-admin')
                                                    <button type="button" class="btn btn-sm btn-danger show-modal" data-toggle="modal" data-target="#confirmationModal" ui-toggle-class="flip-x" ui-target="#animate" data-url="{{url('mail-employee-list/delete/'.$mail_employee_list->id)}}">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="text-center">{{$mail_employee_lists->appends($_GET)->links() }}</div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="box-body">
{{--                    <div class="col-md-6">--}}
{{--                        @if( Session::get('user_role') == 'super-admin')--}}
{{--                            <a href="{{url('mail-employee-list/create')}}" class="btn btn-sm white m-b add-new-btn btn-sm print-delete">--}}
{{--                                <i class="glyphicon glyphicon-plus"></i> Mail Employees--}}
{{--                            </a>--}}
{{--                        @endif--}}
{{--                    </div>--}}

{{--                    <div class="flash-message print-delete">--}}
{{--                        @foreach (['danger', 'warning', 'success', 'info'] as $msg)--}}
{{--                            @if(Session::has('alert-' . $msg))--}}
{{--                                <p class="alert alert-{{ $msg }} text-center">{{ Session::get('alert-' . $msg) }}</p>--}}
{{--                            @endif--}}
{{--                        @endforeach--}}
{{--                    </div>--}}
{{--                    <div class="table-responsive">--}}
{{--                        <table class="reportTable" style="text-align: left">--}}
{{--                            <thead>--}}
{{--                            <tr>--}}
{{--                                <th>Email Address</th>--}}
{{--                                <th>Status</th>--}}
{{--                                <th>Action</th>--}}
{{--                            </tr>--}}
{{--                            </thead>--}}
{{--                            <tbody>--}}
{{--                            @foreach($mail_employee_lists as $mail_employee_list)--}}
{{--                                <tr>--}}
{{--                                    <td>{{$mail_employee_list->email}}</td>--}}
{{--                                    <td>{{$mail_employee_list->status == 1 ? 'Active' : 'Inactive'}}</td>--}}
{{--                                    <td>--}}
{{--                                        @if(Session::get('user_role') == 'super-admin')--}}
{{--                                            <a class="btn btn-xs btn-success" href="{{url('mail-employee-list/edit?id='.$mail_employee_list->id)}}"><i class="fa fa-edit"></i> </a>--}}
{{--                                        @endif--}}
{{--                                        @if(Session::get('user_role') == 'super-admin')--}}
{{--                                            <a class="btn btn-xs btn-danger" href="{{url('mail-employee-list/delete?id='.$mail_employee_list->id)}}"><i class="fa fa-trash-o"></i> </a>--}}
{{--                                        @endif--}}
{{--                                    </td>--}}
{{--                                </tr>--}}
{{--                            @endforeach--}}
{{--                            </tbody>--}}
{{--                        </table>--}}
{{--                        <div class="text-center">{{$mail_employee_lists->appends($_GET)->links() }}</div>--}}
{{--                    </div>--}}
                </div>
                <div class="clearfix"></div>
            </div>
        @endif
    </div>


@endsection

@push('script-head')
    <script>
        $(function () {
            $('body').on('click', '#print', function () {
                $('.print-delete').hide();
                $('#tableOrder').removeClass('table-responsive');
                window.print();
                $('.print-delete').show();
            });
        });
    </script>

    <script>
        $(document).on('click', '.edit', function () {
            let id = $(this).data('id');
            $.ajax({
                method: 'get',
                url: '{{ url('mail-employee-list') }}/' + id + '/edit',
                success: function (result) {
                    $('#form').attr('action', `mail-employee-list/${result.id}/update`).append(`<input type="hidden" id="_method" name="_method" value="PUT"/>`);
                    $('#mail_employee_lists_email').val(result.email);
                    $('#status').val(result.status);
                    $('#submit').html(`<i class="fa fa-save"></i> Update`);
                },
                error: function (xhr) {
                    console.log(xhr)
                }
            })
        })

        function cancel(){
            $('#mail_employee_lists_email').val('');
            $('#status').val('');
            $('#form').attr('action', 'mail-employee-list/store').append(`<input type="hidden" id="_method" name="_method" value="POST"/>`);
            $('#submit').html(`<i class="fa fa-save"></i> Create`);
            $('.text-danger').hide();
        }
    </script>

@endpush

