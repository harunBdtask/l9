@extends('sewingdroplets::layout')
@section('title', 'Sewing Rejection')
@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
            <h2>Sewing Rejection</h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body">
            @include('partials.response-message')
            <form method="POST" action="{{ url('/sewing-rejection-post')}}" accept-charset="UTF-8" onsubmit="submit.disabled = true; return true;">
              @csrf
              <input type="hidden" name="id" value="{{ $sewing_output->bundle_card_id }}">

              <div class="row form-group">
                <div class="col-sm-6 col-sm-offset-3">
                    <label>Sewing Rejection</label>
                    <input type="number" class="form-control form-control-sm has-value" id="sewing_rejection" placeholder="Please enter sewing rejection. eg: only for numeric value 1,2.." autofocus="" name="sewing_rejection" required="required">
                    @if($errors->has('sewing_rejection'))
                    <span class="text-danger">{{ $errors->first('sewing_rejection') }}</span>
                   @endif
                </div>
              </div>
              <div class="row form-group m-t-md">
                <div class="col-sm-offset-3 col-sm-6 text-center">
                    <button name="submit" class="btn btn-success">Submit</button>
                </div>
              </div>
            </form>

          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
