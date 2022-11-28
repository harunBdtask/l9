@extends('washingdroplets::layout')
@section('title', 'Washing Rejection')
@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
            <h2>Washing Rejection || {{ date("D\ - F d- Y") }}</h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body">
             @include('partials.response-message')
             <form method="POST" action="{{ url('/washing-rejection-post')}}" accept-charset="UTF-8">
               @csrf
               <input type="hidden" name="id" value="{{ $washingBundle->bundle_card_id ?? '' }}">
               <input type="hidden" name="output_challan_no" value="{{ $washingBundle->washing_received_challan_no ?? '' }}">

              <div class="form-group">
                <div class="col-sm-6 col-sm-offset-3">
                    <label>Washing Rejection</label>
                    <input type="number" class="form-control form-control-sm has-value" id="washing_rejection" placeholder="Please enter sewing rejection. eg: only for numeric value 1,2.." autofocus="" name="washing_rejection" required="required">
                    @if(isset($washingBundle))
                      <span> Challan no: {{ $washingBundle->washing_received_challan_no }}</span>
                    @endif
                    @if($errors->has('washing_rejection'))
                    <span class="text-danger">{{ $errors->first('washing_rejection') }}</span>
                   @endif
                </div>
              </div>
              <div class="form-group m-t-md">
                <div class="col-sm-6 col-sm-offset-5">
                    <button class="btn btn-success">Submit</button>
                </div>
              </div>
            </form>

          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
