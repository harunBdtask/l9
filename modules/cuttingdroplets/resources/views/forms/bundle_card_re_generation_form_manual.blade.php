@extends('cuttingdroplets::layout')
@section('title', 'Bundle Card Form')
@section('content')
<div class="padding">
  <div class="row">
    <div class="col-md-6 col-md-offset-3">
      <div class="box">
        <div class="box-header">
          <h2>{{ 'Bundle Card Regeneration' }}</h2>
        </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          {!! Form::open(['url' => 'bundle-card-generation-manual/'.request()->route('id').'/re-generate', 'method' =>
          'POST', 'id' => 'bundleCardReGenerationForm']) !!}
          <div class="row">
            <div class="col-sm-12">
              <div class="form-group">
                <label for="part">Part</label>
                {!! Form::select('part_id', $parts, null, ['class' => 'form-control form-control-sm c-select
                select2-input', 'id' =>
                'part', 'placeholder' => 'Select a part']) !!}
  
                @if($errors->has('part_id'))
                <span class="text-danger">{{ $errors->first('part_id') }}</span>
                @endif
              </div>
            </div>
            <div class="col-sm-12">
              <div class="form-group">
                <label for="type">Type</label>
                {!! Form::select('type_id', $types, null, ['class' => 'form-control form-control-sm c-select
                select2-input', 'id' =>
                'type', 'placeholder' => 'Select a type']) !!}
  
                @if($errors->has('type_id'))
                <span class="text-danger">{{ $errors->first('type_id') }}</span>
                @endif
              </div>
            </div>
          </div>

          <div class="row form-group m-t-md">
            <div class="col-sm-12 text-center">
              <button type="submit" class="btn white">Generate</button>
              <button type="button" class="btn white"><a
                  href="{{ url('bundle-card-generation-manual') }}">Cancel</a></button>
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
<script src="{{ asset('protracker/bundlecard.js') }}"></script>
@endsection