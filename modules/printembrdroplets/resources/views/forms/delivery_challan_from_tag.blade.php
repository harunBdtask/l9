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
            {!! Form::open(['url' => 'create-delivery-challan-from-tag-post', 'onsubmit' => 'submit.disabled = true; return true;']) !!}

              {!! Form::hidden('delivery_challan_no', $delivery_challan_no) !!}

              <div class="form-group">
                <div class="col-sm-4 col-sm-offset-4">
                  {!! Form::text('',  $delivery_challan_no, ['class' => 'form-control form-control-sm', 'rows' => '2', 'disabled']) !!}
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-4 col-sm-offset-4">
                  {!! Form::select('tags[]', $tags ?? [], $tag->id ?? null, ['class' => 'form-control form-control-sm c-select select2-input', 'multiple' => 'multiple']) !!}

                  @if($errors->has('tags'))
                    <span class="text-danger">{{ $errors->first('tags') }}</span>
                   @endif
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-4 col-sm-offset-4">
                  {!! Form::select('delivery_factory_id', $delivery_factories, null, ['class' => 'form-control form-control-sm c-select select2-input', 'placeholder' => 'Select a delivery factory']) !!}

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
            {!! Form::close() !!}
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
