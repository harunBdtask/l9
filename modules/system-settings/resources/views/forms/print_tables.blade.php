@extends('skeleton::layout')
@section("title","Table")
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="box">
                    <div class="box-header">
                        <h2>{{ $table ? 'Update Table' : 'Create Table' }}</h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body form-colors">
                        {!! Form::model($table, ['url' => $table ? 'print-factory-tables/'.$table->id : 'print-factory-tables', 'method' => $table ? 'PUT' : 'POST']) !!}
                        <div class="form-group">
                            <label for="menu_name">Table Name</label>
                            {!! Form::text('name', null, ['class' => 'form-control form-control-sm', 'id' => 'name', 'placeholder' => 'Table Name']) !!}

                            @if($errors->has('name'))
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            @endif
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-save"></i> {{ $table ? 'Update' : 'Create' }}</button>
                            <a class="btn btn-sm btn-warning" href="{{ url('print-factory-tables') }}"><i class="fa fa-remove"></i> Cancel</a>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
