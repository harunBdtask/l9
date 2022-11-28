@extends('inputdroplets::layout')
@section('title', 'Cutting Rejection')
@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
            <h2>Cutting Rejection</h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body">
            @include('partials.response-message')
            <div class="row">
              {!! Form::open(['url' => 'cutting-rejection-post', 'method' => 'post', 'onsubmit' => 'submit.disabled = true; return true;']) !!}
                {!! Form::hidden('bundle_id', $bundle->id) !!}
                {!! Form::hidden('type', $type) !!}
                <div class="row form-group">
                  <div class="col-sm-6 col-sm-offset-3">
                    <label>Cutting Rejection</label>
                    {!! Form::number('cutting_rejection', null, ['class' => 'form-control form-control-sm', 'id' => 'name', 'placeholder' => 'Please enter rejection. eg: only for numeric value like 1, 2, 3', 'required', 'autofocus' => true]) !!}
                    @if($errors->has('cutting_rejection'))
                      <span class="text-danger">{{ $errors->first('cutting_rejection') }}</span>
                    @endif
                  </div>
                </div>
                <div class="row form-group m-t-md">
                  <div class="col-sm-offset-4 col-sm-4 text-center">
                      <button name="submit" class="btn btn-success">Submit</button>
                  </div>
                </div>
              {!! Form::close() !!}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
