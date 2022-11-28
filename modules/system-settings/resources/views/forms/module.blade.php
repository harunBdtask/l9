@extends('skeleton::layout')
@section("title","Module")
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h2>{{ $module ? 'Update Module' : 'New Module' }}</h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body">
                        {!! Form::model($module, ['url' => $module ? 'modules-data/'.$module->id : 'modules-data', 'method' => $module ? 'PUT' : 'POST']) !!}
                        <div class="form-group">
                            <label for="module_name">Module Name</label>
                            {!! Form::text('module_name', null, ['class' => 'form-control form-control-sm', 'id' => 'module_name', 'placeholder' => 'Write module\'s name here']) !!}

                            @if($errors->has('module_name'))
                                <span class="text-danger">{{ $errors->first('module_name') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-sm white"><i
                                    class="fa fa-save"></i> {{ $module ? 'Update' : 'Create' }}</button>
                            <a class="btn btn-sm btn-dark" href="{{ url('modules-data') }}">
                                <i class="fa fa-remove"></i>
                                Cancel</a>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
