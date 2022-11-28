@extends('washingdroplets::layout')
@section('title', 'Washing Send To Factory')
@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
            <h2>Washing challan Send To Factory || {{ date("jS F, Y") }}</h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body">
            @include('partials.response-message')
            {!! Form::open(['url' => 'sent-washing-factory-post', 'method' => 'post']) !!}
              {!! Form::hidden('sewing_to_washing_status', $sewing_to_washing_status ?? '') !!}
              {!! Form::hidden('washing_challan_no', $washing_challan_no ?? '') !!}

              <div class="form-group">
                <div class="col-sm-6 col-sm-offset-3">
                  {!! Form::select('print_wash_factory_id', $factories, null, ['class' => 'form-control form-control-sm c-select', 'id' => 'print_wash_factory_id', 'placeholder' => 'Select a wash factory']) !!}

                  @if($errors->has('print_wash_factory_id'))
                    <span class="text-danger">{{ $errors->first('print_wash_factory_id') }}</span>
                  @endif
                  @if($errors->has('washing_challan_no'))
                    <span class="text-danger">{{ $errors->first('washing_challan_no') }}</span>
                  @endif
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-6 col-sm-offset-3">
                  {!! Form::selectRange('bag', 1, 20,null, ['class' => 'form-control form-control-sm c-select', 'id' => 'part_id', 'placeholder' => 'Select bag(s)']) !!}

                  @if($errors->has('bag'))
                    <span class="text-danger">{{ $errors->first('bag') }}</span>
                   @endif
                </div>
              </div>
              <div class="form-group m-t-md">
                <div class="col-sm-offset-5 col-sm-7">
                  <button type="submit" class="btn btn-success">Continue</button>
                </div>
              </div>
            {!! Form::close() !!}
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
