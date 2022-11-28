@extends('skeleton::layout')
@section("title", "Dyes Chemical Store Variable Settings")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Dyes Chemical Store Variable Settings</h2>
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
                                    'url' => isset($dyesChemicalStoreVariableSetting)
                                        ? 'dyes-chemical-store-variable-settings/'.$dyesChemicalStoreVariableSetting->id
                                        : 'dyes-chemical-store-variable-settings',
                                    'method' => isset($dyesChemicalStoreVariableSetting) ? 'PUT' : 'POST'
                                ])
                            !!}
                            <div class="form-group">
                                <label for="approval_maintain">Approval Maintain</label>
                                {!!
                                    Form::select('approval_maintain', [1 => 'Yes', 2 => 'No'], $dyesChemicalStoreVariableSetting->approval_maintain ?? null,
                                        [
                                            'class' => 'form-control form-control-sm',
                                            'id' => 'barcode',
                                            'placeholder' => 'Select',
                                        ]
                                    )
                                !!}
                                @if($errors->has('approval_maintain'))
                                    <span class="text-danger">{{ $errors->first('approval_maintain') }}</span>
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
