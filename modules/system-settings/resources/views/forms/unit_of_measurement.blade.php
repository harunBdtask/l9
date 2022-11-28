@extends('skeleton::layout')
@section("title","UOM")
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="box" >
                    <div class="box-header">
                        <h2>{{ $uoms ? 'Update UOM' : 'New UOM' }}</h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body">
                        {!! Form::model($uoms, ['url' => $uoms ? 'unit-of-measurement/'.$uoms->id : 'unit-of-measurement', 'method' => $uoms ? 'PUT' : 'POST']) !!}
                        <div class="form-group">
                            <label for="unit_of_measurements" class="col-sm-2 form-control form-control-sm-label">UOM</label>
                            <div class="col-sm-10">
                                {!! Form::text('unit_of_measurements', null, ['class' => 'form-control form-control-sm', 'id' => 'unit_of_measurements', 'placeholder' => 'Write unit of measurement here']) !!}

                                @if($errors->has('unit_of_measurements'))
                                    <span class="text-danger">{{ $errors->first('unit_of_measurements') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="status" class="col-sm-2 form-control form-control-sm-label">Status</label>
                            <div class="col-sm-10">
                                {!! Form::select('status', $status, null, ['class' => 'form-control form-control-sm c-select', 'id' => 'status','placeholder'=>'Select status','required'=>'required']) !!}

                                @if($errors->has('status'))
                                    <span class="text-danger">{{ $errors->first('status') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="text-right">
                                <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-save"></i> {{ $uoms ? 'Update' : 'Create' }}</button>
                                <button type="button" class="btn btn-sm btn-danger"><a href="{{ url('unit-of-measurement') }}"><i class="fa fa-remove"></i> Cancel</a></button>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
