@extends('finance::layout')

@section('title', ($department ? 'Update Department' : 'New Department'))
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="box">
                    <div class="box-header">
                        <h2>{{ $department ? 'Update Department' : 'New Department' }}</h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body">
                        <div class="flash-message">
                            @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                                @if(Session::has('alert-' . $msg))
                                    <div class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</div>
                                @endif
                            @endforeach
                        </div>
                        <div class="row">
                            <div class="col-md-12">

                                {!! Form::model($department, ['url' => $department ? 'basic-finance/departments/'.$department->id : 'basic-finance/departments', 'method' => $department ? 'PUT' : 'POST', 'files' => true]) !!}
                                <div class="form-group">
                                    <label for="department">Department *</label>
                                    {!! Form::text('department', null, ['class' => 'form-control form-control-sm', 'id' => 'department', 'placeholder' => 'Write department name here']) !!}

                                    @if($errors->has('department'))
                                        <span class="text-danger">{{ $errors->first('department') }}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="dept_details">Department Details</label>
                                    {!! Form::text('dept_details', null, ['class' => 'form-control form-control-sm', 'id' => 'dept_details', 'placeholder' => 'Write department details here']) !!}

                                    @if($errors->has('dept_details'))
                                        <span class="text-danger">{{ $errors->first('dept_details') }}</span>
                                    @endif
                                </div>
                                @if(!empty($variable) && $variable->departmental_approval)
                                <div class="form-group">
                                    <label for="is_accounting">Is Accounting Department?</label>

                                    {!! Form::select('is_accounting', [0=>'No', 1=> 'Yes'], null, ['class' => 'form-control form-control-sm select2-input', 'id' => 'is_accounting']) !!}

                                    @if($errors->has('is_accounting'))
                                        <span class="text-danger">{{ $errors->first('is_accounting') }}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="notify_to">Notify To</label>
                                    {!! Form::select('notify_to', $users??[], null, ['class' => 'form-control form-control-sm select2-input', 'id' => 'notify_to']) !!}

                                    @if($errors->has('notify_to'))
                                        <span class="text-danger">{{ $errors->first('notify_to') }}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="alternative_notify_to">Alternative Notify To</label>
                                    {!! Form::select('alternative_notify_to', $users??[], null, ['class' => 'form-control form-control-sm select2-input', 'id' => 'alternative_notify_to']) !!}

                                    @if($errors->has('alternative_notify_to'))
                                        <span class="text-danger">{{ $errors->first('alternative_notify_to') }}</span>
                                    @endif
                                </div>
                                @endif
                                <div class="form-group m-t-md">
                                    <button type="submit" class="btn btn-success">{{ $department ? 'Update' : 'Create' }}</button>
                                    <a class="btn btn-danger" href="{{ url('basic-finance/departments') }}">Cancel</a>
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
