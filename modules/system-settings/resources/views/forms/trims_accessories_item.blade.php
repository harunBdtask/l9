@extends('skeleton::layout')
@section("title","Trims Accessories Item")
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="box" >
                    <div class="box-header">
                        <h2>{{ $trimsAccessoriesItem ? 'Update Trims Accessories Item' : 'New Trims Accessories Item' }}</h2>
                    </div>
                    <div class="box-body">
                        {!! Form::model($trimsAccessoriesItem, ['url' => $trimsAccessoriesItem ? 'trims-accessories-item/'.$trimsAccessoriesItem->id : 'trims-accessories-item', 'method' => $trimsAccessoriesItem ? 'PUT' : 'POST']) !!}
                        <div class="form-group">
                            <label for="name">Trims Accessories Item</label>

                            {!! Form::text('name', null, ['class' => 'form-control form-control-sm', 'id' => 'name', 'placeholder' => 'Trims Accessories Item']) !!}

                            @if($errors->has('name'))
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-sm white"><i class="fa fa-save"></i> {{ $trimsAccessoriesItem ? 'Update' : 'Create' }}</button>
                            <a class="btn btn-sm btn-dark" href="{{ url('trims-accessories-item') }}"><i class="fa fa-remove"></i> Cancel</a>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
