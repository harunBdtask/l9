@extends('basic-finance::layout')

@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="box">
                    @if(Session::has('success'))
                        <div class="col-md-6 col-md-offset-3 alert alert-success alert-dismissible text-center">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <small>{{ Session::get('success') }}</small>
                        </div>
                    @elseif(Session::has('error'))
                        <div class="col-md-6 col-md-offset-3 alert alert-danger alert-dismissible text-center">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <small>{{ Session::get('error') }}</small>
                        </div>
                    @endif
                    <div class="box-header">
                        <h2>{{ isset($bank) ? 'Update Receive Bank' : 'Create New Receive Bank' }}</h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-lg-12">
                                {!! Form::model($bank ?? null, ['url' => isset($bank) ? 'basic-finance/receive-banks/'.$bank->id : 'basic-finance/receive-banks', 'method' => isset($bank) ? 'PUT' : 'POST']) !!}
                                <div class="form-group">
                                    <label for="name">Name *</label>
                                    {!! Form::text('name', $bank->account->name ?? null, [
                                        'class' => 'form-control form-control-sm', 'id' => 'name', 'placeholder' => 'Bank name'
                                    ]) !!}

                                    @if($errors->has('name'))
                                        <span class="text-danger">{{ $errors->first('name') }}</span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label for="name">Short Name *</label>
                                    {!! Form::text('short_name', null, ['class' => 'form-control form-control-sm', 'id' => 'short_name', 'placeholder' => 'Bank short name']) !!}

                                    @if($errors->has('short_name'))
                                        <span class="text-danger">{{ $errors->first('short_name') }}</span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label for="code">Description</label>
                                    {!! Form::textarea('description', null, ['class' => 'form-control form-control-sm', 'id' => 'description', 'placeholder' => 'Bank description']) !!}
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-sm btn-success">
                                        {{ isset($bank) ? 'Update' : 'Create' }}
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger">
                                        <a href="{{ url('basic-finance/receive-banks') }}">Cancel</a>
                                    </button>
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
