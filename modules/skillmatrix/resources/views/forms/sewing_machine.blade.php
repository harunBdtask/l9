@extends('skillmatrix::layout')

@section('title', $sewingMachine ? 'Update Sewing Machine' : 'New Sewing Machine')
@section('content')
<div class="padding">
  <div class="row">
    <div class="col-md-6 col-md-offset-3">
      <div class="box">
        <div class="box-header">
          <h2>{{ $sewingMachine ? 'Update Sewing Machine' : 'New Sewing Machine' }}</h2>
        </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          {!! Form::model($sewingMachine, ['url' => $sewingMachine ? 'sewing-machines/'.$sewingMachine->id : 'sewing-machines', 'method' => $sewingMachine ? 'PUT' : 'POST', 'onsubmit' => '$(this).find(\':button[type=submit]\').prop(\'disabled\', true);']) !!}
          <div class="form-group row">
            <label for="name" class="col-sm-2 form-control-label">Name <span class="text-danger">*</span></label>
            <div class="col-sm-10">
              {!! Form::text('name', null, ['class' => 'form-control', 'id' => 'name', 'required', 'placeholder' => 'Write sewing machine\'s name here']) !!}

              @if($errors->has('name'))
              <span class="text-danger">{{ $errors->first('name') }}</span>
              @endif
            </div>
          </div>
          <div class="form-group row m-t-md">
            <div class="text-center">
              <button type="submit" class="{{ $sewingMachine ? 'btn btn-sm btn-primary' : 'btn btn-sm btn-success' }}"><i class="fa fa-save"></i>
                &nbsp; {{ $sewingMachine ? 'Update' : 'Create' }}</button>
              <a class="btn btn-sm btn-danger" href="{{ url('sewing-machines') }}"> <i class="fa fa-times"></i> &nbsp; Cancel</a>
            </div>
          </div>
          {!! Form::close() !!}
        </div>
      </div>
    </div>
  </div>
</div>
@endsection