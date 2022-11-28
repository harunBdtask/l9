@extends('warehouse-management::layout')
@section('styles')
    <style>
        .select2-container .select2-selection--single {
            height: 40px;
            border-radius: 0px;
            line-height: 50px;
            border: 1px solid #e7e7e7;
        }

        .reportTable .select2-container .select2-selection--single {
            border: 1px solid #e7e7e7;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 40px;
            width: 150px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 8px;
        }

        .select2-container--default .select2-selection--multiple {
            min-height: 40px !important;
            border-radius: 0px;
            width: 100%;
        }
    </style>
@endsection
@section('title', $warehouse_rack ? 'Update Warehouse Rack' : 'New Warehouse Rack')
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="box">
                    <div class="box-header">
                        <h2>{{ $warehouse_rack ? 'Update Warehouse Rack' : 'New Warehouse Rack' }}</h2>
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
                        {!! Form::model($warehouse_rack, ['url' => $warehouse_rack ? '/warehouse-racks/'.$warehouse_rack->id : '/warehouse-racks', 'method' => $warehouse_rack ? 'PUT' : 'POST']) !!}
                        <div class="form-group row">
                            <label for="name" class="col-sm-3 form-control-label">Rack Name/No <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                {!! Form::text('name', null, ['class' => 'form-control', 'id' => 'name', 'placeholder' => 'Write rack\'s name or no here']) !!}

                                @if($errors->has('name'))
                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="warehouse_floor_id" class="col-sm-3 form-control-label">Floor Name/No <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                {!! Form::select('warehouse_floor_id', $warehouse_floors, null, ['class' => 'form-control select2-input', 'id' => 'warehouse_floor_id', 'placeholder' => 'Select floor']) !!}

                                @if($errors->has('warehouse_floor_id'))
                                    <span class="text-danger">{{ $errors->first('warehouse_floor_id') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="capacity" class="col-sm-3 form-control-label">Capacity <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                {!! Form::number('capacity', null, ['class' => 'form-control', 'id' => 'capacity', 'placeholder' => 'Write rack\'s capacity here']) !!}

                                @if($errors->has('capacity'))
                                    <span class="text-danger">{{ $errors->first('capacity') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row m-t-md">
                            <div class="text-center">
                                <button type="submit" class="{{ $warehouse_rack ? 'btn btn-primary':'btn btn-success'}}">{{ $warehouse_rack ? 'Update' : 'Create' }}</button>
                                <a class="btn btn-danger" href="{{ url('/warehouse-racks') }}">Cancel</a>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection