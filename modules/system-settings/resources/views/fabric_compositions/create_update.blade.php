@extends('skeleton::layout')
@section("title","Fabric Composition")
@push('style')
    <style>

    </style>
@endpush
@section('content')
    <div class="padding">
        @if(Session::has('permission_of_item_add') || Session::get('user_role') == 'super-admin'|| Session::get('user_role') == 'admin')
            <div class="col-md-8 col-md-offset-2">
                <div class="box" >
                    <div class="box-header">
                        <h2>{{ $fabric_composition ? 'Update Fabric Composition' : 'New Fabric Composition' }}</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="box-body b-t">
                        <div class="flash-message">
                            @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                                @if(Session::has('alert-' . $msg))
                                    <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</p>
                                @endif
                            @endforeach
                        </div>
                        {!! Form::model($fabric_composition, ['url' => $fabric_composition ? 'fabric-compositions/'.$fabric_composition->id.'/update' : 'fabric-compositions/store', 'method' => $fabric_composition ? 'PUT' : 'POST']) !!}
                        <div class="form-group">
                            <label for="item_name" class="col-sm-4 form-control form-control-sm-label">Fabric Composition</label>
                            <div class="col-sm-8">
                                {!! Form::text('yarn_composition', null, ['class' => 'form-control form-control-sm', 'id' => 'yarn_composition', 'placeholder' => 'Fabric Composition']) !!}
                                @if($errors->has('yarn_composition'))
                                    <span class="text-danger">{{ $errors->first('yarn_composition') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-sm white"><i class="fa fa-save"></i> {{ $fabric_composition ? 'Update' : 'Create' }}</button>
                            <a class="btn btn-sm btn-dark" href="{{ url('fabric-compositions') }}"><i class="fa fa-remove"></i> Cancel</a>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
