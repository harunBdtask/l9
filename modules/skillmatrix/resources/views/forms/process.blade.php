@extends('skillmatrix::layout')

@section('title', $process ? 'Update Sewing Process' : 'New Sewing Process')

@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-8 col-md-offset-2">
        <div class="box">
          <div class="box-header">
            <h2>{{ $process ? 'Update Sewing Process' : 'New Sewing Process' }}</h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body">
            {!! Form::model($process, ['url' => $process ? 'sewing-processes/'.$process->id : 'sewing-processes', 'method' => $process ? 'PUT' : 'POST', 'onsubmit' => '$(this).find(\':button[type=submit]\').prop(\'disabled\', true);']) !!}
              <div class="form-group row">
                <label for="name" class="col-sm-3 form-control-label">Name <span class="text-danger">*</span></label>
                <div class="col-sm-9">
                   {!! Form::text('name', null, ['class' => 'form-control', 'required', 'id' => 'name', 'placeholder' => 'Write process\'s name here']) !!}

                   @if($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                   @endif
                </div>
              </div>
              <div class="form-group row">
                <label for="standard_capacity" class="col-sm-3 form-control-label">Standard Capacity <span class="text-danger">*</span></label>
                <div class="col-sm-9">
                   {!! Form::text('standard_capacity', null, ['class' => 'form-control', 'required', 'id' => 'standard_capacity', 'placeholder' => 'Write process\'s standard_capacity here']) !!}

                   @if($errors->has('standard_capacity'))
                    <span class="text-danger">{{ $errors->first('standard_capacity') }}</span>
                   @endif
                </div>
              </div>
              <div class="form-group row m-t-md">
                <div class="text-center">
                  <button type="submit" class="{{ $process ? 'btn btn-sm btn-primary' : 'btn btn-sm btn-success' }}"><i class="fa fa-save"></i>
                    &nbsp; {{ $process ? 'Update' : 'Create' }}</button>
                  <a class="btn btn-sm btn-danger" href="{{ url('sewing-processes') }}"> <i class="fa fa-times"></i> &nbsp; Cancel</a>
                </div>
              </div>
            {!! Form::close() !!}
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection