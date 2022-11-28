@extends('printembrdroplets::layout')
@section('title', 'Gatepass Challan')
@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
            <h2>Gatepass Challan Create</h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body">

            @include('partials.response-message')
            <div class="row">
              <form method="POST" action="{{ url('/send-to-print-post')}}" accept-charset="UTF-8" onsubmit="submit.disabled = true; return true;">
                @csrf
                <input type="hidden" name="challan_no" value="{{ $challan_no }}">
                <div class="row form-group">
                  <div class="col-sm-6 col-sm-offset-3">
                    {!! Form::select('operation_name', OPERATION, null, ['class' => 'form-control form-control-sm c-select', 'id' => 'operation_name', 'placeholder' => 'Select a operation']) !!}
  
                    @if($errors->has('operation_name'))
                      <span class="text-danger">{{ $errors->first('operation_name') }}</span>
                    @endif
                  </div>
                </div>
  
                <div class="row form-group">
                  <div class="col-sm-6 col-sm-offset-3">
                    {!! Form::select('factory_id', $factories, null, ['class' => 'form-control form-control-sm c-select select2-input', 'id' => 'factory_id', 'placeholder' => 'Select a factory']) !!}
  
                    @if($errors->has('factory_id'))
                      <span class="text-danger">{{ $errors->first('factory_id') }}</span>
                    @endif
                  </div>
                </div>
  
                <div class="row form-group">
                  <div class="col-sm-6 col-sm-offset-3">
                    {!! Form::select('part_id', $parts, null, ['class' => 'form-control form-control-sm c-select select2-input', 'id' => 'part_id', 'placeholder' => 'Select a part']) !!}
  
                    @if($errors->has('part_id'))
                      <span class="text-danger">{{ $errors->first('part_id') }}</span>
                     @endif
                  </div>
                </div>
  
                <div class="row form-group">
                  <div class="col-sm-6 col-sm-offset-3">
                    {!! Form::selectRange('bag', 1, 20,null, ['class' => 'form-control form-control-sm c-select select2-input', 'id' => 'part_id', 'placeholder' => 'Select a bag(s)']) !!}
  
                    @if($errors->has('bag'))
                      <span class="text-danger">{{ $errors->first('bag') }}</span>
                     @endif
                  </div>
                </div>
  
                <div class="row form-group m-t-md">
                  <div class="text-center">
                    <button name="submit" type="submit" class="btn btn-success">Continue</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('head-script')
  <script type="text/javascript">
      window.history.forward();
      function noBack(){ window.history.forward(); }
  </script>
@endsection
