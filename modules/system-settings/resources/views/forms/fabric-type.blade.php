@extends('skeleton::layout')
@section("title","Fabric Type")
@section('content')
    <!-- ############ PAGE START-->
    <div class="padding">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="box" >
                    <div class="box-header">
                        <h2>{{ $fabric_type ? 'Update Fabric Type' : 'New Fabric Type' }}</h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body form-colors">
                        <div class="flash-message">
                            @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                                @if(Session::has('alert-' . $msg))
                                    <div class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</div>
                                @endif
                            @endforeach
                        </div>

                        {!! Form::model($fabric_type, ['url' => $fabric_type ? 'fabric-types/'.$fabric_type->id : 'fabric-types', 'method' => $fabric_type ? 'PUT' : 'POST']) !!}
                        <div class="form-group">
                            <label for="fabric_type_name">Fabric Type</label>
                            {!! Form::text('fabric_type_name', null, ['class' => 'form-control form-control-sm', 'id' => 'fabric_type_name', 'placeholder' => 'Write fabric type here']) !!}

                            @if($errors->has('fabric_type_name'))
                                <span class="text-danger">{{ $errors->first('fabric_type_name') }}</span>
                            @endif
                        </div>
                        <div class="form-group m-t-md">
                            <button type="submit" class="btn btn-sm btn-success">{{ $fabric_type ? 'Update' : 'Create' }}</button>
                            <a class="btn btn-sm btn-warning" href="{{ url('fabric-types') }}">Cancel</a>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ############ PAGE END-->
@endsection



