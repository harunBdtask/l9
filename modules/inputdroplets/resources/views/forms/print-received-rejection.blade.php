@extends('inputdroplets::layout')
@section('title', 'Print Rejection')
@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
            <h2>Print Rejection || {{ date("jS F, Y") }}</h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body">

             @include('partials.response-message')
             <form method="POST" action="{{ url('/print-rejection-post')}}" accept-charset="UTF-8">
               @csrf
               <input type="hidden" name="id" value="{{ $cutting_inventory->id }}">
               <input type="hidden" name="challan_no" value="{{ $cutting_inventory->challan_no }}">

              <div class="form-group">
                <div class="col-sm-8 col-sm-offset-2">
                    <label>Print Rejection</label>
                    <input type="number" class="form-control form-control-sm has-value" id="print_rejection" placeholder="Please enter print rejection. eg: only for numeric value " autofocus="" name="print_rejection" required="required">
                    @if(isset($cutting_inventory))
                      <span> Challan no: {{ $cutting_inventory->challan_no }}</span>
                    @endif
                    @if($errors->has('print_rejection'))
                    <span class="text-danger">{{ $errors->first('print_rejection') }}</span>
                   @endif
                </div>
              </div>
              <div class="form-group m-t-md">
                <div class="col-sm-offset-5 col-sm-7">
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
