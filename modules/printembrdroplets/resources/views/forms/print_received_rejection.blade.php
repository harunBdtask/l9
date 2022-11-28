@extends('printembrdroplets::layout')
@section('title', $printOrEmbroidery.' Rejection')
@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
            <h2>{{ $printOrEmbroidery }} Rejection</h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body">
            @include('partials.response-message')
            {!! Form::open(['url' => 'print-rejection-post', 'method' => 'post', 'onsubmit' => 'submit.disabled = true; return true;']) !!}
              {!! Form::hidden('id', $cuttingInventory->id) !!}
              {!! Form::hidden('type', $type) !!}

              <div class="row form-group">
                <div class="col-sm-6 col-sm-offset-3">
                    <label>{{ $printOrEmbroidery }} Rejection</label>
                    {!! Form::number('print_rejection', null, ['class' => 'form-control form-control-sm', 'id' => 'name', 'placeholder' => 'Please enter' .strtolower($printOrEmbroidery) .'rejection. eg: only for numeric value 1, 2, 3', 'required']) !!}
                    @if($errors->has('print_rejection'))
                    <span class="text-danger">{{ $errors->first('print_rejection') }}</span>
                   @endif
                </div>
              </div>
              <div class="row form-group m-t-md">
                <div class="text-center">
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
