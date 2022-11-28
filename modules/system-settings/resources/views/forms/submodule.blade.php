@extends('skeleton::layout')
@section("title","Sub Module")
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h2>{{ $submodule ? 'Update Sub Module' : 'New Sub Module' }}</h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body">
                        {!! Form::model($submodule, ['url' => $submodule ? 'submodules/'.$submodule->id : 'submodules', 'method' => $submodule ? 'PUT' : 'POST']) !!}

                        <div class="form-group">
                            <label for="country">Module</label>
                            {!! Form::select('module_id', $modules, null, ['class' => 'form-control form-control-sm', 'id' => 'modules', 'placeholder' => 'Select a module']) !!}

                            @if($errors->has('module_id'))
                                <span class="text-danger">{{ $errors->first('module_id') }}</span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="submodule_name">Submodule Name</label>
                            {!! Form::text('submodule_name', null, ['class' => 'form-control form-control-sm', 'id' => 'submodule_name', 'placeholder' => 'Write submodule\'s name here']) !!}

                            @if($errors->has('submodule_name'))
                                <span class="text-danger">{{ $errors->first('submodule_name') }}</span>
                            @endif
                        </div>

                        <div class="form-group m-t-md">
                            <button type="submit" class="btn btn-sm white">{{ $submodule ? 'Update' : 'Create' }}</button>
                            <a class="btn btn-sm btn-dark" href="{{ url('submodules') }}">Cancel</a>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
