@extends('skeleton::layout')
@section('title', 'Machine Types')
@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="box " >
          <div class="box-header">
            <h2>{{ $machine_type ? 'Update Machine Type' : 'New Machine Type' }}</h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body form-colors">
            {!! Form::model($machine_type, ['url' => $machine_type ? 'machine-types/'.$machine_type->id : 'machine-types', 'method' => $machine_type ? 'PUT' : 'POST']) !!}

              <div class="form-group">
                <label for="name">Name</label>
                  {!! Form::text('name', null, ['class' => 'form-control form-control-sm', 'id' => 'name', 'placeholder' => 'Write task\'s name here']) !!}

                  @if($errors->has('name'))
                      <span class="text-danger">{{ $errors->first('name') }}</span>
                  @endif
              </div>
              <div class="form-group m-t-md">
                  <button type="submit" class="btn btn-success">{{ $machine_type ? 'Update' : 'Create' }}</button>
                  <a class="btn btn-warning" href="{{ url('machine-types') }}">Cancel</a>
              </div>
            {!! Form::close() !!}
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
