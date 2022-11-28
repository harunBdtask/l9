@extends('skeleton::layout')
@section('title', 'Cutting Floor')

@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h2>{{ $cutting_floor ? 'Update Cutting Floor' : 'New Cutting Floor' }}</h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body form-colors">
                        {!! Form::model($cutting_floor, ['url' => $cutting_floor ? 'cutting-floors/'.$cutting_floor->id : 'cutting-floors', 'method' => $cutting_floor ? 'PUT' : 'POST']) !!}

                        <div class="form-group">
                            <label for="code">Cutting Floor No</label>
                            {!! Form::text('floor_no', null, ['class' => 'form-control form-control-sm', 'id' => 'code', 'placeholder' => 'Give floor no']) !!}

                            @if($errors->has('floor_no'))
                                <span class="text-danger">{{ $errors->first('floor_no') }}</span>
                            @endif
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-sm btn-success"><i
                                    class="fa fa-save"></i> {{ $cutting_floor ? 'Update' : 'Create' }}</button>
                            <button type="button" class="btn btn-sm btn-warning"><a href="{{ url('cutting-floors') }}"><i
                                        class="fa fa-remove"></i> Cancel</a></button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
