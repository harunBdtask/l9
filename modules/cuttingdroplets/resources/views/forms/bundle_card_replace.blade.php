@extends('cuttingdroplets::layout')
@section('title', 'Bundle Card Replace')
@section('styles')
<style type="text/css">
  .bundle-cards table,
  .bundle-cards th,
  .bundle-cards td {
    border: 1px solid black !important;
  }

  .bundle-card-generation-details table,
  .bundle-card-generation-details th,
  .bundle-card-generation-details td {
    border: 1px solid black !important;
  }

  .bundle-cards td.third {
    display: none;
  }

  .barcode {
    padding-top: 7px !important;
  }
</style>
<link rel="stylesheet" type="text/css" href="{{ asset('css/print.css') }}">
@endsection

@section('content')
<div class="padding">
  <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header text-center noprint">
          <h2>Bundle Card Replace</h2>
        </div>
        <div class="box-divider m-a-0 noprint"></div>
        <div class="box-body">

          @include('partials.response-message')

          <form action="{{ url('/replace-bundle-card') }}" method="post" autocomplete="off">
            @csrf
            <div class="form-group">
              <input type="text" class="form-control form-control-sm" id="barcode" placeholder="Enter barcode no here"
                autofocus="" required="required" name="barcode" value="{{ request('barcode') ?? '' }}">

              @if($errors->has('barcode'))
              <span class="text-danger">{{ $errors->first('barcode') }}</span>
              @endif
            </div>

            <div class="form-group m-t-md text-center">
              <button type="submit" class="btn btn-primary">Submit</button>
              <button type="button" class="btn btn-danger"><a href="{{ url('/') }}">Cancel</a></button>
            </div>
          </form>

          @if(isset($bundle))
          @includeIf('cuttingdroplets::pages.bundle_card_replace_v2')
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@endsection