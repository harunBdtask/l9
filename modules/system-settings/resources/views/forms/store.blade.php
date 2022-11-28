@extends('skeleton::layout')
@section("title","Stores")
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="box" >
                    <div class="box-header">
                        <h2>{{ $stores ? 'Update Store' : 'New Store' }}</h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body">
                        {!! Form::model($stores, ['url' => $stores ? 'store/'.$stores->id : 'store', 'method' => $stores ? 'PUT' : 'POST']) !!}
                        <div class="form-group">
                            <label for="store_name" class="col-sm-2 form-control form-control-sm-label">Store Name</label>
                            <div class="col-sm-10">
                                {!! Form::text('store_name', null, ['class' => 'form-control form-control-sm', 'id' => 'store_name', 'placeholder' => 'Write store name here', 'required'=>'required']) !!}

                                @if($errors->has('store_name'))
                                    <span class="text-danger">{{ $errors->first('store_name') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="text-right">
                                <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> {{ $stores ? 'Update' : 'Create' }}</button>
                                <button type="button" class="btn btn-danger"><a href="{{ url('stores') }}"><i class="fa fa-remove"></i> Cancel</a></button>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
