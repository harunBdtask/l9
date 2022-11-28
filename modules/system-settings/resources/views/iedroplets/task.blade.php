@extends('skeleton::layout')

@section('title', 'Sewing Line Tasks')
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="box" >
                    <div class="box-header">
                        <h2>{{ $task ? 'Update Task' : 'New Task' }}</h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body form-colors">
                        {!! Form::model($task, ['url' => $task ? 'tasks/'.$task->id : 'tasks', 'method' => $task ? 'PUT' : 'POST']) !!}

                        <div class="form-group">
                            <label for="name">Task Name</label>
                            {!! Form::text('name', null, ['class' => 'form-control form-control-sm', 'id' => 'name', 'placeholder' => 'Write task\'s name here']) !!}

                            @if($errors->has('name'))
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                        <div class="form-group m-t-md">
                            <button type="submit" class="btn btn-success">{{ $task ? 'Update' : 'Create' }}</button>
                            <a class="btn btn-warning" href="{{ url('tasks') }}">Cancel</a>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
