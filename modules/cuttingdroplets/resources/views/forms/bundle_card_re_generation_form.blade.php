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
          {!! Form::open(['url' => 'bundle-card-generations/'.request()->route('id').'/re-generate', 'method' => 'POST',
          'id' => 'bundleCardReGenerationForm']) !!}
          <div class="row form-group">
            <label for="part" class="col-sm-2 form-control-sm-label">Part</label>
            <div class="col-sm-10">
              {!! Form::select('part_id', $parts, null, ['class' => 'form-control form-control-sm c-select select2-input', 'id' =>
              'part', 'placeholder' => 'Select a part']) !!}

              @if($errors->has('part_id'))
              <span class="text-danger">{{ $errors->first('part_id') }}</span>
              @endif
            </div>
          </div>
          <div class="row form-group">
            <label for="type" class="col-sm-2 form-control-sm-label">Type</label>
            <div class="col-sm-10">
              {!! Form::select('type_id', $types, null, ['class' => 'form-control form-control-sm c-select select2-input', 'id' =>
              'type', 'placeholder' => 'Select a type']) !!}

              @if($errors->has('type_id'))
              <span class="text-danger">{{ $errors->first('type_id') }}</span>
              @endif
            </div>
          </div>

          <div class="row form-group m-t-md">
            <div class="col-sm-offset-2 col-sm-10">
              <button type="submit" class="btn white">Generate</button>
              <button type="button" class="btn white"><a href="{{ url('bundle-card-generations') }}">Cancel</a></button>
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
