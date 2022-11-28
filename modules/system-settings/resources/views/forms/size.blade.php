@extends('skeleton::layout')
@section("title","Size")
@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="box" >
          <div class="box-header">
            <h2>{{ $size ? 'Update Size' : 'New Size' }}</h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body">
            {!! Form::model($size, ['url' => $size ? 'sizes/'.$size->id : 'sizes', 'method' => $size ? 'PUT' : 'POST']) !!}
              <div class="form-group">
                <label for="name" class="col-sm-2 form-control form-control-sm-label">Name</label>
                <div class="col-sm-10">
                   {!! Form::text('name', null, ['class' => 'form-control form-control-sm', 'id' => 'name', 'placeholder' => 'Write size\'s name here']) !!}

                   @if($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                   @endif
                </div>
              </div>
              <div class="form-group">
                <div class="text-right">
                  <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> {{ $size ? 'Update' : 'Create' }}</button>
                  <a class="btn btn-danger" href="{{ url('sizes') }}"><i class="fa fa-remove"></i> Cancel</a>
                </div>
              </div>
            {!! Form::close() !!}
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
