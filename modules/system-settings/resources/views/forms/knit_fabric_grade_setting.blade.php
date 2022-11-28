@extends('skeleton::layout')
@section('title', 'Knit Fabric Grade')
@section('content')
    <!-- ############ PAGE START-->
    <div class="padding">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="box">
                    <div class="box-header">
                        <h2>{{ $knit_fabric_grade ? 'Update Knit Fabric Grade' : 'New Knit Fabric Grade' }}</h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body b-t">
                        <div class="flash-message">
                            @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                                @if(Session::has('alert-' . $msg))
                                    <div class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</div>
                                @endif
                            @endforeach
                        </div>
                        {!! Form::model($knit_fabric_grade, ['url' => $knit_fabric_grade ? 'knit_fabric_grade_settings/'.$knit_fabric_grade->id : 'knit_fabric_grade_settings', 'method' => $knit_fabric_grade ? 'PUT' : 'POST']) !!}
                        <div class="form-group">
                            <label for="from">From</label>
                            {!! Form::text('from', null, ['class' => 'form-control form-control-sm', 'id' => 'from', 'placeholder' => 'From']) !!}

                            @if($errors->has('from'))
                                <span class="text-danger">{{ $errors->first('from') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="to">To</label>
                            {!! Form::text('to', null, ['class' => 'form-control form-control-sm', 'id' => 'to', 'placeholder' => 'To']) !!}

                            @if($errors->has('to'))
                                <span class="text-danger">{{ $errors->first('to') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="to">Grade</label>
                            {!! Form::text('grade', null, ['class' => 'form-control form-control-sm', 'id' => 'grade', 'placeholder' => 'Grade']) !!}

                            @if($errors->has('grade'))
                                <span class="text-danger">{{ $errors->first('grade') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="to">Status</label>
                            {!! Form::select('status', [1 => 'Active', 2 => 'Inactive'], 1, ['class' => 'form-control form-control-sm', 'id' => 'status']) !!}

                            @if($errors->has('status'))
                                <span class="text-danger">{{ $errors->first('status') }}</span>
                            @endif
                        </div>
                        <div class="form-group m-t-md">
                            <button type="submit" class="btn btn-sm white"> <i class="fa fa-save"></i> &nbsp; {{ $knit_fabric_grade ? 'Update' : 'Create' }}</button>
                            <a class="btn btn-sm btn-dark" href="{{ url('knit_fabric_grade_settings') }}"> <i class="fa fa-times"></i> &nbsp; Cancel</a>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ############ PAGE END-->
@endsection



