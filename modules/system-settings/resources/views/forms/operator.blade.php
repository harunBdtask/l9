@extends('skeleton::layout')
@section("title","Operator")
@section('content')
    <!-- ############ PAGE START-->
    <div class="padding">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="box">
                    <div class="box-header">
                        <h2>{{ $operator ? 'Update Operator' : 'New Operator' }}</h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body form-colors">
                        <div class="flash-message">
                            @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                                @if(Session::has('alert-' . $msg))
                                    <div class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</div>
                                @endif
                            @endforeach
                        </div>
                        {!! Form::model($operator, ['url' => $operator ? 'operators/'.$operator->id : 'operators', 'method' => $operator ? 'PUT' : 'POST']) !!}
                        <div class="form-group">
                            <label for="operator_name">Operator Name</label>
                            {!! Form::text('operator_name', null, ['class' => 'form-control form-control-sm', 'id' => 'operator_name', 'placeholder' => 'Write operator\'s name here']) !!}

                            @if($errors->has('operator_name'))
                                <span class="text-danger">{{ $errors->first('operator_name') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="operator_code">Operator Code</label>
                            {!! Form::text('operator_code', null, ['class' => 'form-control form-control-sm', 'id' => 'operator_code', 'placeholder' => 'Give an unique code for the operator']) !!}

                            @if($errors->has('operator_code'))
                                <span class="text-danger">{{ $errors->first('operator_code') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="operator_type">Operator Type</label>
                            {!! Form::select('operator_type', [1 => 'Knit Card Operator', 2 => 'Knit QC Operator'], null, ['class' => 'form-control form-control-sm c-select', 'id' => 'operator_type', 'placeholder' => 'Select operator type']) !!}

                            @if($errors->has('operator_type'))
                                <span class="text-danger">{{ $errors->first('operator_type') }}</span>
                            @endif
                        </div>
                        <div class="form-group m-t-md">
                            <button type="submit" class="btn btn-sm btn-success">{{ $operator ? 'Update' : 'Create' }}</button>
                            <a class="btn btn-sm btn-warning" href="{{ url('operators') }}">Cancel</a>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ############ PAGE END-->
@endsection



