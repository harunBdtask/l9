@extends('skeleton::layout')
@section("title","Designation")
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="box">
                    <div class="box-header">
                        <h2>{{ $designation ? 'Update Designation' : 'New Designation' }}</h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body form-colors">
                        {!! Form::model($designation, ['url' => $designation ? 'designations/'.$designation->id : 'designations', 'method' => $designation ? 'PUT' : 'POST']) !!}
                        <div class="form-group">
                            <label for="designation">Designation</label>
                            {!! Form::text('designation', null, ['class' => 'form-control form-control-sm', 'id' => 'designation', 'placeholder' => 'Write Designation here']) !!}

                            @if($errors->has('designation'))
                                <span class="text-danger">{{ $errors->first('designation') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-success">{{ $designation ? 'Update' : 'Create' }}</button>
                            <a class="btn btn-warning" href="{{ url('designations') }}">Cancel</a>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
