@extends('skeleton::layout')

@section('content')
    <!-- ############ PAGE START-->
    <div class="padding">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="box">
                    <div class="box-header">
                        <h2>{{$yarn_types ? 'UPDATE YARN TYPE' : 'ADD YARN TYPE'}}</h2>
                        <div class="flash-message">
                            @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                                @if(Session::has('alert-' . $msg))
                                    <div class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="box-body b-t form-colors">
                        {!! Form::model($yarn_types, ['url' => $yarn_types ? 'yarn-types/'.$yarn_types->id : 'yarn-types', 'method' => $yarn_types ? 'PUT' : 'POST']) !!}
                        <div class="form-group">
                            <label for="yarn_type">Yarn Type</label>
                            {!! Form::text('yarn_type', null, ['class' => 'form-control form-control-sm', 'id' => 'yarn_type', 'placeholder' => 'Write Yarn Type here']) !!}

                            @if($errors->has('yarn_type'))
                                <span class="text-danger">{{ $errors->first('yarn_type') }}</span>
                            @endif
                        </div>
                        <div class="form-group m-t-md">
                            <button type="submit" class="btn btn-success">{{ $yarn_types ? 'Update' : 'Create' }}</button>
                            <a class="btn btn-warning" href="{{ url('yarn-types') }}">Cancel</a>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ############ PAGE END-->
@endsection



