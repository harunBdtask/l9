@extends('tqm::layout')
@section('title', 'Defect List')
@section('content')
<div class="padding">
  <div class="row">
    <div class="col-md-6 col-md-offset-3">
      <div class="box form-colors">
        <div class="box-header">
          <h2>{{ $defect ? 'Update Defect' : 'New Defect' }}</h2>
        </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          {!! Form::model($defect, ['url' => $defect ? 'tqm-defects/'.$defect->id : 'tqm-defects', 'method' => $defect ? 'PUT' : 'POST', 'onsubmit' => "document.getElementById('submit').disabled=true;"]) !!}
          <div class="form-group">
            <label for="factory_id">Factory</label>
            {!! Form::select('factory_id', $factory_options ?? [], null, ['class' => 'form-control form-control-sm', 'id' => 'factory_id']) !!}

            @if($errors->has('factory_id'))
            <span class="text-danger">{{ $errors->first('factory_id') }}</span>
            @endif
          </div>
          <div class="form-group">
            <label for="section">Section</label>
            {!! Form::select('section', $sections ?? [], null, ['class' => 'form-control form-control-sm', 'id' => 'section']) !!}

            @if($errors->has('section'))
            <span class="text-danger">{{ $errors->first('section') }}</span>
            @endif
          </div>
          <div class="form-group">
            <label for="name">Name</label>
            {!! Form::text('name', null, ['class' => 'form-control form-control-sm', 'id' => 'name', 'placeholder' => 'Write part\'s name here']) !!}

            @if($errors->has('name'))
            <span class="text-danger">{{ $errors->first('name') }}</span>
            @endif
          </div>
          <div class="form-group">
            <button type="submit" id="submit" class="btn btn-sm btn-success"><i class="fa fa-save"></i> {{ $defect ? 'Update' : 'Create' }}</button>
            <a class="btn btn-sm btn-warning" href="{{ url('tqm-defects') }}"><i class="fa fa-remove"></i> Cancel</a>
          </div>
          {!! Form::close() !!}
        </div>
      </div>
    </div>
  </div>
</div>
@endsection