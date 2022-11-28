@extends('skeleton::layout')
@section("title","Product Departments")
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="box">
                    <div class="box-header">
                        <h2>{{ $product_departments ? 'Update Product Department' : 'New Product Department' }}</h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body form-colors">
                        {!! Form::model($product_departments, ['url' => $product_departments ? 'product-department/'.$product_departments->id : 'product-department', 'method' => $product_departments ? 'PUT' : 'POST']) !!}
                        <div class="form-group">
                            <label for="product_department">Product Department</label>

                            {!! Form::text('product_department', null, ['class' => 'form-control form-control-sm', 'id' => 'product_department', 'placeholder' => 'Write product department here','required'=>'required']) !!}

                            @if($errors->has('product_department'))
                                <span class="text-danger">{{ $errors->first('product_department') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>

                            {!! Form::select('status', $status, null, ['class' => 'form-control form-control-sm c-select', 'id' => 'status','placeholder'=>'Select status','required'=>'required']) !!}

                            @if($errors->has('status'))
                                <span class="text-danger">{{ $errors->first('status') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-sm btn-success"><i
                                    class="fa fa-save"></i> {{ $product_departments ? 'Update' : 'Create' }}
                            </button>
                            <button type="button" class="btn btn-sm btn-warning"><a
                                    href="{{ url('product-department') }}"><i class="fa fa-remove"></i> Cancel</a>
                            </button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
