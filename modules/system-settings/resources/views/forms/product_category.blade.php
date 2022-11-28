@extends('skeleton::layout')
@section("title","Product Category")
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="box" >
                    <div class="box-header">
                        <h2>{{ $product_category ? 'Update Product Category' : 'New Product Category' }}</h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body form-colors">
                        {!! Form::model($product_category, ['url' => $product_category ? 'product-category/'.$product_category->id : 'product-category', 'method' => $product_category ? 'PUT' : 'POST']) !!}

                        <div class="form-group">
                            <label for="category_name"> Name</label>
                            {!! Form::text('category_name', null, ['class' => 'form-control form-control-sm', 'id' => 'category_name', 'placeholder' => 'Write  name here', 'required'=>'required']) !!}

                            @if($errors->has('category_name'))
                                <span class="text-danger">{{ $errors->first('category_name') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="associate_with">Associate With</label>
                            {!! Form::select('associate_with[]', $factories, $associateWith ?? [], ['class' => 'form-control form-control-sm select2-input c-select form-control form-control-sm-sm', 'id' => 'associate_with', 'multiple' => 'multiple']) !!}
                            @if($errors->has('associate_with'))
                                <span class="text-danger">{{ $errors->first('associate_with') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-save"></i> {{ $product_category ? 'Update' : 'Create' }}</button>
                            <button type="button" class="btn btn-sm btn-warning"><i class="fa fa-remove"></i> <a href="{{ url('product-category') }}">Cancel</a></button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
