@extends('warehouse-management::layout')
@section('title', $warehouse_floor ? 'Update Warehouse Floor' : 'New Warehouse Floor')
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="box">
                    <div class="box-header">
                        <h2>{{ $warehouse_floor ? 'Update Warehouse Floor' : 'New Warehouse Floor' }}</h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body">
                        <div class="flash-message" style="margin-bottom: 20px;">
                            @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                                @if(Session::has('alert-' . $msg))
                                    <p class="text-center alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</p>
                                @endif
                            @endforeach
                        </div>
                        {!! Form::model($warehouse_floor, ['url' => $warehouse_floor ? '/warehouse-floors/'.$warehouse_floor->id : '/warehouse-floors', 'method' => $warehouse_floor ? 'PUT' : 'POST']) !!}
                        <div class="form-group row">
                            <label for="name" class="col-sm-3 form-control-label">Floor Name/No <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                {!! Form::text('name', null, ['class' => 'form-control', 'id' => 'name', 'placeholder' => 'Write floor\'s name or no here']) !!}

                                @if($errors->has('name'))
                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row m-t-md">
                            <div class="text-center">
                                <button type="submit" class="{{ $warehouse_floor ? 'btn btn-primary' : 'btn btn-success'}}">{{ $warehouse_floor ? 'Update' : 'Create' }}</button>
                                <a class="btn btn-danger" href="{{ url('/warehouse-floors') }}">Cancel</a>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection