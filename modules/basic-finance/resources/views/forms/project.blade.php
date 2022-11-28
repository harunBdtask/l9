@extends('finance::layout')

@section('title', ($project ? 'Update Project' : 'New Project'))
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="box">
                    <div class="box-header">
                        <h2>{{ $project ? 'Update Project' : 'New Project' }}</h2>
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
                                {!! Form::model($project, ['url' => $project ? 'basic-finance/projects/'.$project->id : 'basic-finance/projects', 'method' => $project ? 'PUT' : 'POST', 'files' => true]) !!}
                                <div class="form-group">
                                    <label for="name">Factory *</label>
                                    {!! Form::select('factory_id', $factories, null, ['class' => 'form-control form-control-sm select2-input', 'id' => 'factory_id']) !!}

                                    @if($errors->has('factory_id'))
                                        <span class="text-danger">{{ $errors->first('factory_id') }}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="name">Users *</label>
                                    {!! Form::select('user_ids[]', $users, null, ['class' => 'form-control form-control-sm select2-input', 'id' => 'user_ids', 'multiple']) !!}

                                    @if($errors->has('user_ids'))
                                        <span class="text-danger">{{ $errors->first('user_ids') }}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="project">Project *</label>
                                    {!! Form::text('project', null, ['class' => 'form-control form-control-sm', 'id' => 'project', 'placeholder' => 'Write project here']) !!}

                                    @if($errors->has('project'))
                                        <span class="text-danger">{{ $errors->first('project') }}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="project_head_name">Name of Project Head</label>
                                    {!! Form::text('project_head_name', null, ['class' => 'form-control form-control-sm', 'id' => 'project_head_name', 'placeholder' => 'Write name of project head here']) !!}

                                    @if($errors->has('project_head_name'))
                                        <span class="text-danger">{{ $errors->first('project_head_name') }}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="phone_no">Phone No</label>
                                    {!! Form::number('phone_no', null, ['class' => 'form-control form-control-sm', 'id' => 'phone_no', 'placeholder' => 'Write Phone no here']) !!}

                                    @if($errors->has('phone_no'))
                                        <span class="text-danger">{{ $errors->first('phone_no') }}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    {!! Form::email('email', null, ['class' => 'form-control form-control-sm', 'id' => 'email', 'placeholder' => 'Write Email here']) !!}

                                    @if($errors->has('email'))
                                        <span class="text-danger">{{ $errors->first('email') }}</span>
                                    @endif
                                </div>
                                <div class="form-group m-t-md">
                                    <button type="submit" class="btn btn-success">{{ $project ? 'Update' : 'Create' }}</button>
                                    <a class="btn btn-danger" href="{{ url('basic-finance/projects') }}">Cancel</a>
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
