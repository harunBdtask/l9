@extends('skeleton::layout')
@section('title', 'Shift')
@section('content')
    <!-- ############ PAGE START-->
    <div class="padding">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="box">
                    <div class="box-header">
                        <h2>{{ $shifts ? 'Update Shift' : 'New Shift' }}</h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body b-t form-colors">
                        <div class="flash-message">
                            @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                                @if(Session::has('alert-' . $msg))
                                    <div class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</div>
                                @endif
                            @endforeach
                        </div>
                        {!! Form::model($shifts, ['url' => $shifts ? 'shifts/'.$shifts->id : 'shifts', 'method' => $shifts ? 'PUT' : 'POST']) !!}
                        <div class="form-group">
                            <label for="shift_name">Shift Name</label>
                            {!! Form::text('shift_name', null, ['class' => 'form-control form-control-sm', 'id' => 'shift_name', 'placeholder' => 'Write shift name here']) !!}

                            @if($errors->has('shift_name'))
                                <span class="text-danger">{{ $errors->first('shift_name') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="start_time">Start Time</label>
                            {!! Form::time('start_time', null, ['class' => 'form-control form-control-sm', 'id' => 'start_time']) !!}

                            @if($errors->has('start_time'))
                                <span class="text-danger">{{ $errors->first('start_time') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="end_time">End Time</label>
                            {!! Form::time('end_time', null, ['class' => 'form-control form-control-sm', 'id' => 'end_time']) !!}

                            @if($errors->has('end_time'))
                                <span class="text-danger">{{ $errors->first('end_time') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="extra_time">Extra Time <strong>(In minutes)</strong></label>
                            {!! Form::number('extra_time', null, ['class' => 'form-control form-control-sm', 'id' => 'extra_time']) !!}

                            @if($errors->has('extra_time'))
                                <span class="text-danger">{{ $errors->first('extra_time') }}</span>
                            @endif
                        </div>
                        <div class="form-group m-t-md">
                            <button type="submit" class="btn btn-sm btn-success"> <i class="fa fa-save"></i> &nbsp; {{ $shifts ? 'Update' : 'Create' }}</button>
                            <a class="btn btn-sm btn-warning" href="{{ url('shifts') }}"> <i class="fa fa-times"></i> &nbsp; Cancel</a>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ############ PAGE END-->
@endsection



