@extends('printembrdroplets::layout')
@section('title', 'Print Factory QC Rejection')
@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
            <h2>Print Factory QC Rejection</h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body">
            @include('partials.response-message')
            {!! Form::open(['url' => 'print-factory-qc-rejection-post', 'method' => 'post', 'onsubmit' => 'submit.disabled = true; return true;']) !!}
            {!! Form::hidden('bundle_id', $bundle->id) !!}
            {!! Form::hidden('type', $type) !!}
            <div class="form-group">
              <div class="col-sm-6 col-sm-offset-3">
                <label>Print Delivery Rejection</label>
                @php
                  $inputAttr =  [
                      'class' => 'form-control form-control-sm',
                      'id' => 'name',
                      'min' => 1,
                      'placeholder' => 'Please enter rejection. eg: only for numeric value like 1, 2, 3', 'required',
                      'autofocus'
                  ]
                @endphp
                {!! Form::number('rejection_qty', null, $inputAttr) !!}
                @if($errors->has('rejection_qty'))
                  <span class="text-danger">{{ $errors->first('rejection_qty') }}</span>
                @endif
              </div>
            </div>
            <div class="form-group m-t-md">
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
@endsection
