@extends('skeleton::layout')
@section("title","Fabric Store Variable Settings")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Fabric Store Variable Settings</h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        @include('partials.response-message')
                    </div>
                </div>
            </div>

            <div class="row m-t">
                <div class="col-sm-12 col-md-5">
                    <div class="box form-colors">
                        <div class="box-header">
                            {!!
                                Form::open([
                                    'url' => isset($fabricStoreVariableSetting)
                                        ? 'fabric-store-variable-settings/'.$fabricStoreVariableSetting->id
                                        : 'fabric-store-variable-settings',
                                    'method' => isset($fabricStoreVariableSetting) ? 'PUT' : 'POST'
                                ])
                            !!}
                            <div class="form-group">
                                <label for="barcode">Barcode</label>
                                {!!
                                    Form::select('barcode', [1 => 'Yes', 2 => 'No'], $fabricStoreVariableSetting->barcode ?? null,
                                        [
                                            'class' => 'form-control form-control-sm',
                                            'id' => 'barcode',
                                            'placeholder' => 'Select',
                                        ]
                                    )
                                !!}
                                @if($errors->has('barcode'))
                                    <span class="text-danger">{{ $errors->first('barcode') }}</span>
                                @endif
                            </div>

                            <div class="form-group">
                                <button type="submit" id="submit" class="btn btn-sm success">
                                    <i class="fa fa-save"></i> Save
                                </button>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
