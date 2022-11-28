@extends('printembrdroplets::layout')
@section('title', 'Delivered Factory')
@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
              <h2>Delivered Factory</h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body">
            @include('partials.response-message')
            <form method="POST" action="{{ url('/create-print-factory-delivery-challan-post')}}" accept-charset="UTF-8" onsubmit="submit.disabled = true; return true;">
                @csrf
                <input type="hidden" name="challan_no" value="{{ $challan_no }}">
                <div class="form-group">
                  <div class="col-sm-4 col-sm-offset-4">
                    {!! Form::select('delivery_factory_id', $delivery_factories, null, ['class' => 'form-control form-control-sm c-select select2-input', 'id' => 'part_id', 'placeholder' => 'Select a factory']) !!}

                    @if($errors->has('delivery_factory_id'))
                      <span class="text-danger">{{ $errors->first('delivery_factory_id') }}</span>
                     @endif
                  </div>
                </div>

                <div class="form-group">
                  <div class="col-sm-4 col-sm-offset-4">
                    {!! Form::textarea('remarks', null, ['class' => 'form-control form-control-sm', 'rows' => '2', 'placeholder' => 'Delivery notes']) !!}

                    @if($errors->has('remarks'))
                      <span class="text-danger">{{ $errors->first('remarks') }}</span>
                     @endif
                  </div>
                </div>
                <div class="form-group m-t-md">
                  <div class="text-center">
                      <button name="submit" type="submit" class="btn btn-sm btn-success">Submit</button>
                  </div>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('head-script')
    <script>
       window.history.forward();

       function noBack() {
          window.history.forward();
       }
    </script>
@endsection
