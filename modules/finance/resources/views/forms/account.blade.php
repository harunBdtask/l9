@extends('finance::layout')

@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="box">
                    <div class="box-header">
                        <h2>{{ isset($account) ? 'Update Account' : 'Create New Account' }}</h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-lg-12">
                                {!! Form::model($account ?? null, ['url' => isset($account) ? 'finance/accounts/'.$account->id : 'finance/accounts', 'method' => isset($account) ? 'PUT' : 'POST']) !!}
                                <div class="form-group">
                                    <label for="name">Name *</label>
                                    {!! Form::text('name', null, ['class' => 'form-control form-control-sm', 'id' => 'name', 'placeholder' => 'Account name']) !!}

                                    @if($errors->has('name'))
                                        <span class="text-danger">{{ $errors->first('name') }}</span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label for="code">AC Code *</label>
                                    {!! Form::text('code', null, ['class' => 'form-control form-control-sm', 'id' => 'code', 'placeholder' => 'Account code']) !!}

                                    @if($errors->has('code'))
                                        <span class="text-danger">{{ $errors->first('code') }}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="parent_ac">Parent
                                        Account</label>
                                    {!! Form::select('parent_ac', $accounts, null, ['class' => 'form-control form-control-sm c-select select2-input', 'id' => 'parent_ac', 'placeholder' => 'Select parent account if any']) !!}

                                    @if($errors->has('parent_ac'))
                                        <span class="text-danger">{{ $errors->first('parent_ac') }}</span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label for="type">AC Type *</label>
                                    {!! Form::select('type_id', $ac_types, null, ['class' => 'form-control form-control-sm c-select select2-input', 'id' => 'type', 'placeholder' => 'Select account type']) !!}

                                    @if($errors->has('type_id'))
                                        <span class="text-danger">{{ $errors->first('type_id') }}</span>
                                    @endif
                                    @if($errors->has('parent_ac'))
                                        <span class="text-danger">{{ $errors->first('parent_ac') }}</span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    {!! Form::text('particulars', null, ['class' => 'form-control form-control-sm', 'id' => 'particulars', 'placeholder' => 'Brief description']) !!}

                                    @if($errors->has('particulars'))
                                        <span class="text-danger">{{ $errors->first('particulars') }}</span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <button type="submit"
                                            class="btn btn-sm white">{{ isset($account) ? 'Update' : 'Create' }}</button>
                                    <button type="button" class="btn btn-sm btn-dark"><a
                                            href="{{ url('finance/accounts') }}">Cancel</a></button>
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
