@extends('skeleton::layout')
@section('title', 'Sewing Line')
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="box">
                    <div class="box-header">
                        <h2>{{ $line ? 'Update Line' : 'New Line' }}</h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body form-colors">
                        {!! Form::model($line, ['url' => $line ? 'lines/'.$line->id : 'lines', 'method' => $line ? 'PUT' : 'POST']) !!}
                        <div class="form-group">
                            <label for="floor">Floor</label>
                            {!! Form::select('floor_id', $floors, null, ['class' => 'form-control form-control-sm select2-input', 'id' => 'floor', 'placeholder' => 'Select a floor']) !!}

                            @if($errors->has('floor_id'))
                                <span class="text-danger">{{ $errors->first('floor_id') }}</span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="line_no">Line No</label>
                            {!! Form::text('line_no', null, ['class' => 'form-control form-control-sm', 'id' => 'line_no', 'placeholder' => 'Give line no']) !!}

                            @if($errors->has('line_no'))
                                <span class="text-danger">{{ $errors->first('line_no') }}</span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="sort">Line Sequence</label>
                            {!! Form::text('sort', null, ['class' => 'form-control form-control-sm', 'id' => 'sort', 'placeholder' => 'Sequence No']) !!}

                            @if($errors->has('sort'))
                                <span class="text-danger">{{ $errors->first('sort') }}</span>
                            @endif
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-sm btn-success"><i
                                    class="fa fa-save"></i> {{ $line ? 'Update' : 'Create' }}</button>
                            <button type="button" class="btn btn-sm btn-warning"><a href="{{ url('lines') }}"><i
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
