@extends('skeleton::layout')
@section('title', 'Yarn Brand')
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="box">
                    <div class="box-header">
                        <h2>{{ $brands ? 'Update Yarn Brand' : 'New Yarn Brand' }}</h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body form-colors">
                        {!! Form::model($brands, ['url' => $brands ? 'brands/'.$brands->id : 'brands', 'method' => $brands ? 'PUT' : 'POST']) !!}
                        <div class="form-group">
                            <label for="brand_name">Brand Name</label>
                            {!! Form::text('brand_name', null, ['class' => 'form-control form-control-sm', 'id' => 'brand_name', 'placeholder' => 'Write brand\'s name here']) !!}

                            @if($errors->has('brand_name'))
                                <span class="text-danger">{{ $errors->first('brand_name') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="brand_type">Brand Type</label>
                            {!! Form::select('brand_type', [1 => 'Yarn',2 => 'Dye'], null, ['class' => 'form-control form-control-sm c-select', 'id' => 'brand_type', 'placeholder' => 'Select a brand type']) !!}

                            @if($errors->has('brand_type'))
                                <span class="text-danger">{{ $errors->first('brand_type') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-sm btn-success">{{ $brands ? 'Update' : 'Create' }}</button>
                            <a class="btn btn btn-warning" href="{{ url('brands') }}">Cancel</a>
                        </div>
                        {!! Form::close() !!}

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
