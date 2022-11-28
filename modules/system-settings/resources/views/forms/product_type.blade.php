@extends('skeleton::layout')
@section("title","Product Types")
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="box form-colors">
                    <div class="box-header">
                        <h2>{{ $productType ? 'Update Product Type' : 'New Product Type' }}</h2>
                    </div>
                    <div class="box-body">
                        {!! Form::model($productType, ['url' => $productType ? 'product-types/'.$productType->id : 'product-types', 'method' => $productType ? 'PUT' : 'POST']) !!}
                        <div class="form-group">
                            <label for="name">Product Type</label>

                            {!! Form::text('name', null, ['class' => 'form-control form-control-sm', 'id' => 'name', 'placeholder' => 'Product Type']) !!}

                            @if($errors->has('name'))
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-save"></i> {{ $productType ? 'Update' : 'Create' }}</button>
                            <a class="btn btn-sm btn-warning" href="{{ url('product-types') }}"><i class="fa fa-remove"></i> Cancel</a>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
