@extends('dyes-store::layout')

@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="box">
                    <div class="box-header">
                        <h2>{{ $store ? 'Update Store' : 'New Store' }}</h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body">
                        <div class="flash-message">
                            @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                                @if(Session::has('alert-' . $msg))
                                    <div class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</div>
                                @endif
                            @endforeach
                        </div>
                        <div class="row">
                            <div class="col-md-12">

                                {!! Form::model($store, ['url' => $store ? '/dyes-store/stores/'.$store->id : '/dyes-store/stores', 'method' => $store ? 'PUT' : 'POST']) !!}
                                <div class="form-group row">
                                    <label for="name" class="col-sm-2 form-control-label">Name</label>
                                    <div class="col-sm-10">
                                        {!! Form::text('name', null, ['class' => 'form-control', 'id' => 'name', 'placeholder' => 'Write Store\'s name here']) !!}

                                        @if($errors->has('name'))
                                            <span class="text-danger">{{ $errors->first('name') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="name" class="col-sm-2 form-control-label">Code</label>
                                    <div class="col-sm-10">
                                        {!! Form::text('code', null, ['class' => 'form-control', 'id' => 'name', 'placeholder' => 'Write Store\'s Code here']) !!}

                                        @if($errors->has('code'))
                                            <span class="text-danger">{{ $errors->first('code') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="name" class="col-sm-2 form-control-label">Sym</label>
                                    <div class="col-sm-10">
                                        {!! Form::text('sym', null, ['class' => 'form-control', 'id' => 'sym', 'placeholder' => 'Write Store\'s Sym here']) !!}

                                        @if($errors->has('sym'))
                                            <span class="text-danger">{{ $errors->first('sym') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="name" class="col-sm-2 form-control-label">Description</label>
                                    <div class="col-sm-10">
                                        {!! Form::textarea('description', null, ['class' => 'form-control', 'rows'=>2,'id' => 'name', 'placeholder' => 'Description']) !!}
                                    </div>
                                </div>
                                <div class="form-group row m-t-md">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <button type="submit"
                                                class="btn btn-info">{{ $store ? 'Update' : 'Create' }}</button>
                                        <a class="btn btn-danger" href="{{ url('/dyes-store/stores') }}">Cancel</a>
                                    </div>
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
