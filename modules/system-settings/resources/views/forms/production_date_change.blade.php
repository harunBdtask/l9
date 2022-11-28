@extends('skeleton::layout')
@section('title', 'Production Date Change')
@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="box" >
          <div class="box-header">
            <h2>Production Date Change || {{ date("jS F, Y") }}</h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body form-colors">

              @include('partials.response-message')

              <form action="{{ url('/production-date-change-post') }}" method="post">
                @csrf
                <div class="form-group">
                  <div class="row m-b">
                    <div class="col-sm-3">
                        <label>From Date</label>
                        <input type="date" name="from_date" class="form-control form-control-sm" required="required" value="{{ $from_date ?? null }}">

                        @if($errors->has('from_date'))
                          <span class="text-danger">{{ $errors->first('from_date') }}</span>
                        @endif
                    </div>
                    <div class="col-sm-3">
                        <label>To Date</label>
                        <input type="date" name="to_date" class="form-control form-control-sm" required="required" value="{{ $to_date ?? null }}">

                        @if($errors->has('to_date'))
                          <span class="text-danger">{{ $errors->first('to_date') }}</span>
                        @endif
                    </div>
                    <div class="col-sm-2">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn btn-sm btn-success form-control form-control-sm">Submit</button>
                    </div>
                  </div>
                </div>
              </form>

          </div>
        </div>
      </div>
    </div>
  </div>

  <style type="text/css">
      @media screen and (-webkit-min-device-pixel-ratio: 0){

      input[type=date].form-control form-control-sm{
        line-height: 1;
      }
      }
  </style>
@endsection
