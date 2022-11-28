@extends('skeleton::layout')
@section('title', 'Sewing Floor')
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="box">
                    <div class="box-header">
                        <h2>{{ $floor ? 'Update Floor' : 'New Floor' }}</h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body form-colors">
                        {!! Form::model($floor, ['url' => $floor ? 'floors/'.$floor->id : 'floors', 'method' => $floor ? 'PUT' : 'POST']) !!}

                        <div class="form-group">
                            <label for="code">Floor No</label>
                            {!! Form::text('floor_no', null, ['class' => 'form-control form-control-sm', 'id' => 'code', 'placeholder' => 'Give floor no']) !!}

                            @if($errors->has('floor_no'))
                                <span class="text-danger">{{ $errors->first('floor_no') }}</span>
                            @endif
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-sm btn-success"><i
                                    class="fa fa-save"></i> {{ $floor ? 'Update' : 'Create' }}</button>
                            <button type="button" class="btn btn-sm btn-warning"><a href="{{ url('floors') }}"><i
                                        class="fa fa-remove"></i>
                                    Cancel</a></button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
