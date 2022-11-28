@extends('finishingdroplets::layout')
@section('styles')
<style type="text/css">
  @media screen and (-webkit-min-device-pixel-ratio: 0) {
    input[type=date].form-control form-control-sm {
      height: 20px !important;
    }
  }
</style>
@endsection
@section('title', 'Edit Iron, Poly & Packing')
@section('content')
<div class="padding">
  <div class="row">
    <div class="col-md-8 col-md-offset-2">
      <div class="box">
        <div class="box-header">
          <h2>Edit Iron, Poly & Packing</h2>
        </div>
        <div class="box-divider m-a-0"></div>
        @include('partials.response-message')
        <div class="box-body">

          {!! Form::model($poly, ['url' => 'iron-poly-packings/'.$poly->id, 'method' => 'PUT']) !!}
          <div class="form-group row">
            <label for="buyer_id" class="col-sm-2 form-control-sm-label">Prod. Date</label>
            <div class="col-sm-10">
              {!! Form::date('production_date', null, ['class' => 'form-control form-control-sm', 'disabled' => true])
              !!}
              @if($errors->has('production_date'))
              <span class="text-danger">{{ $errors->first('production_date') }}</span>
              @endif
            </div>
          </div>
          <div class="form-group row">
            <label for="buyer_id" class="col-sm-2 form-control-sm-label">Buyer</label>
            <div class="col-sm-10">
              {!! Form::select('buyer_id', $buyers ?? [], $poly->buyer_id ?? null, ['class' => 'form-control
              form-control-sm', 'id' => 'buyer_id', 'placeholder' => 'Select a Buyer', 'disabled' => true]) !!}
              @if($errors->has('buyer_id'))
              <span class="text-danger">{{ $errors->first('buyer_id') }}</span>
              @endif
            </div>
          </div>
          <div class="form-group row">
            <label for="order_id" class="col-sm-2 form-control-sm-label">Style/Order</label>
            <div class="col-sm-10">
              {!! Form::select('order', $orders ?? [], $poly->order_id ?? null, ['class' => 'form-control
              form-control-sm', 'id' => 'order_id', 'placeholder' => 'Select a Order', 'disabled' => true]) !!}
              @if($errors->has('order_id'))
              <span class="text-danger">{{ $errors->first('order_id') }}</span>
              @endif
            </div>
          </div>
          <div class="form-group row">
            <label for="color_id" class="col-sm-2 form-control-sm-label">Color</label>
            <div class="col-sm-10">
              {!! Form::select('color', $colors ?? [], $poly->color_id ?? null, ['class' => 'form-control
              form-control-sm', 'id' => 'color_id', 'placeholder' => 'Select a Color', 'disabled' => true]) !!}
              @if($errors->has('color_id'))
              <span class="text-danger">{{ $errors->first('color_id') }}</span>
              @endif
            </div>
          </div>
          <div class="form-group row">
            <label for="finishing_floor_id" class="col-sm-2 form-control-sm-label">Finishing Floor</label>
            <div class="col-sm-10">
              {!! Form::select('finishing_floor_id', ($poly && $poly->id) ? [$poly->finishing_floor_id => $poly->finishingFloor->name] : [], $poly->finishing_floor_id ??
              null, ['class' => 'form-control form-control-sm', 'id' => 'finishing_floor_id', 'placeholder' =>
              'Finishing FLoor']) !!}
              @if($errors->has('finishing_floor_id'))
              <span class="text-danger">{{ $errors->first('finishing_floor_id') }}</span>
              @endif
            </div>
          </div>

          <div class="form-group row">
            <label for="iron_qty" class="col-sm-2 form-control-sm-label">Iron Qty</label>
            <div class="col-sm-10">
              {!! Form::number('iron_qty', null, ['class' => 'form-control form-control-sm', 'id' => 'iron_qty',
              'placeholder' => 'Iton Qty', 'required' => true]) !!}
              @if($errors->has('iron_qty'))
              <span class="text-danger">{{ $errors->first('iron_qty') }}</span>
              @endif
            </div>
          </div>
          <div class="form-group row">
            <label for="iron_rejection_qty" class="col-sm-2 form-control-sm-label">Iron
              Rej. Qty</label>
            <div class="col-sm-10">
              {!! Form::number('iron_rejection_qty', null, ['class' => 'form-control form-control-sm', 'id' =>
              'iron_rejection_qty', 'placeholder' => 'Iton Rejection Qty', 'required' => true]) !!}
              @if($errors->has('iron_rejection_qty'))
              <span class="text-danger">{{ $errors->first('iron_rejection_qty') }}</span>
              @endif
            </div>
          </div>
          <div class="form-group row">
            <label for="poly_qty" class="col-sm-2 form-control-sm-label">Poly Qty</label>
            <div class="col-sm-10">
              {!! Form::number('poly_qty', null, ['class' => 'form-control form-control-sm', 'id' => 'poly_qty',
              'placeholder' => 'Poly Qty', 'required' => true]) !!}
              @if($errors->has('poly_qty'))
              <span class="text-danger">{{ $errors->first('poly_qty') }}</span>
              @endif
            </div>
          </div>
          <div class="form-group row">
            <label for="poly_rejection_qty" class="col-sm-2 form-control-sm-label">Poly
              Rej.Qty</label>
            <div class="col-sm-10">
              {!! Form::number('poly_rejection_qty', null, ['class' => 'form-control form-control-sm', 'id' =>
              'poly_rejection_qty', 'placeholder' => 'Poly Rejection Qty', 'required' => true]) !!}
              @if($errors->has('poly_rejection_qty'))
              <span class="text-danger">{{ $errors->first('poly_rejection_qty') }}</span>
              @endif
            </div>
          </div>
          <div class="form-group row">
            <label for="packing_qty" class="col-sm-2 form-control-sm-label">Packing
              Qty</label>
            <div class="col-sm-10">
              {!! Form::number('packing_qty', null, ['class' => 'form-control form-control-sm', 'id' => 'packing_qty',
              'placeholder' => 'Packing Qty', 'required' => true]) !!}
              @if($errors->has('packing_qty'))
              <span class="text-danger">{{ $errors->first('packing_qty') }}</span>
              @endif
            </div>
          </div>
          <div class="form-group row">
            <label for="packing_rejection_qty" class="col-sm-2 form-control-sm-label">Packing
              Rej. Qty</label>
            <div class="col-sm-10">
              {!! Form::number('packing_rejection_qty', null, ['class' => 'form-control form-control-sm', 'id' =>
              'packing_rejection_qty', 'placeholder' => 'Packing Rejection Qty', 'required' => true]) !!}
              @if($errors->has('packing_rejection_qty'))
              <span class="text-danger">{{ $errors->first('packing_rejection_qty') }}</span>
              @endif
            </div>
          </div>
          <div class="form-group row">
            <label for="reason" class="col-sm-2 form-control-sm-label">Reasons</label>
            <div class="col-sm-10">
              {!! Form::textarea('reason', null, ['class' => 'form-control form-control-sm', 'id' => 'short_reject_qty',
              'placeholder' => 'Please give a reason for short/reject qty!', 'rows' => 1]) !!}
              @if($errors->has('reason'))
              <span class="text-danger">{{ $errors->first('reason') }}</span>
              @endif
            </div>
          </div>
          <div class="form-group row">
            <label for="remarks" class="col-sm-2 form-control-sm-label">Remarks</label>
            <div class="col-sm-10">
              {!! Form::textarea('remarks', null, ['class' => 'form-control form-control-sm', 'id' => 'remarks',
              'placeholder' => 'Please give a remarks!', 'rows' => 1]) !!}
              @if($errors->has('remarks'))
              <span class="text-danger">{{ $errors->first('remarks') }}</span>
              @endif
            </div>
          </div>
          <div class="form-group row m-t-md">
            <div class="col-sm-offset-2 col-sm-10">
              <button type="submit" class="btn btn-success">Update</button>
              <button type="button" class="btn btn-danger">
                <a href="{{ url('/iron-poly-packings') }}">Cancel</a>
              </button>
            </div>
          </div>
          {!! Form::close() !!}

        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('scripts')
  <script src="{{ asset('protracker/custom.js') }}"></script>
  <script>
    $(function () {
        const finishingFloorSelectDom = $('[name="finishing_floor_id"]');

        finishingFloorSelectDom.select2({
            ajax: {
                url: function (params) {
                    return `/fetch-finishing-floors`
                },
                data: function (params) {
                    return {
                        search: params.term,
                    }
                },
                processResults: function (data, params) {
                    return {
                        results: data,
                        pagination: {
                            more: false
                        }
                    }
                },
                cache: true,
                delay: 250
            },
            placeholder: 'Finishing Floor',
            allowClear: true
        });
    });
  </script>
@endsection