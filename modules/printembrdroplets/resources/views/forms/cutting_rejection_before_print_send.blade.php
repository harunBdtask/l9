@extends('printembrdroplets::layout')
@section('title', 'Cutting Rejection')
@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
            <h2>Cutting Rejection || {{ date("jS F, Y") }}</h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body">
            @include('partials.response-message')
            <form method="POST" action="{{ url('/cutting-rejection-post')}}" accept-charset="UTF-8" onsubmit="submit.disabled = true; return true;">
              @csrf
              <input type="hidden" name="id" value="{{ $bundle->id }}">

              <div class="form-group">
                <div class="col-sm-6 col-sm-offset-3">
                    <label>Cutting Rejection</label>
                    <input type="number" class="form-control form-control-sm has-value" id="fabric_rejection" placeholder="Please enter print rejection. eg: only for numeric value " autofocus="" name="cutting_rejection" required="required">

                    @if($errors->has('cutting_rejection'))
                    <span class="text-danger">{{ $errors->first('cutting_rejection') }}</span>
                   @endif
                </div>
              </div>

              <div class="form-group m-t-md text-center">
                <div class="col-sm-offset-4 col-sm-4">
                    <button class="btn btn-success" name="submit">Submit</button>
                </div>
              </div>
            </form>

          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
