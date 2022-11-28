@extends('skeleton::layout')
@section('title', 'Operator Skill')
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="box">
                    <div class="box-header">
                        <h2>{{ $operator_skills ? 'Update Skill' : 'Add New Skill' }}</h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body form-colors">
                        {!! Form::model($operator_skills, ['url' => $operator_skills ? 'operator-skill/'.$operator_skills->id : 'operator-skill', 'method' => $operator_skills ? 'PUT' : 'POST']) !!}
                        <div class="form-group">
                            <label for="name">Name</label>
                            {!! Form::text('name', null, ['class' => 'form-control form-control-sm', 'id' => 'name', 'placeholder' => 'Write skill name here']) !!}

                            @if($errors->has('name'))
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                        <div class="form-group m-t-md">
                            <button type="submit"
                                    class="btn btn-success">{{ $operator_skills ? 'Update' : 'Create' }}</button>
                            <a class="btn btn-warning" href="{{ url('operator-skill') }}">Cancel</a>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
