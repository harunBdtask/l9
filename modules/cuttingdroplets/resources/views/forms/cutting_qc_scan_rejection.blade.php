@extends('cuttingdroplets::layout')
@section('title', 'Cutting QC Rejection Scan')
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header text-center">
                        <h2>Cutting Rejection || {{ date("D\ - F d- Y") }}</h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body">
                        <form action="{{ url('cutting-qc-scan-post') }}" method="post">
                            @csrf
                            <input type="hidden" name="id" value="{{ ($bundle->id ?: null) }}">
                            <input type="hidden" name="cutting_qc_challan_no" value="{{ $qc_challan }}">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="replace">Replace</label>
                                        {!! Form::number('replace', null, ['class' => 'form-control form-control-sm', 'id' => 'replace', 'placeholder' => 'Enter replace here']) !!}

                                        @if($errors->has('replace'))
                                            <span class="text-danger">{{ $errors->first('replace') }}</span>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label for="fabric_holes_small">Fabric Holes small</label>
                                        {!! Form::number('fabric_holes_small', null, ['class' => 'form-control form-control-sm', 'id' => 'fabric_holes_small', 'placeholder' => 'Enter fabric holes small here']) !!}
                                    </div>

                                    <div class="form-group">
                                        <label for="fabric_holes_large">Fabric Holes large</label>
                                        {!! Form::number('fabric_holes_large', null, ['class' => 'form-control form-control-sm', 'id' => 'fabric_holes_large', 'placeholder' => 'Enter fabric holes large here']) !!}
                                    </div>

                                    <div class="form-group">
                                        <label for="end_out">End Out</label>
                                        <div class="col-sm-9">
                                            {!! Form::number('end_out', null, ['class' => 'form-control form-control-sm', 'id' => 'end_out', 'placeholder' => 'Enter end out here']) !!}
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="dirty_spot">Dirty Spot</label>
                                        {!! Form::number('dirty_spot', null, ['class' => 'form-control form-control-sm', 'id' => 'dirty_spot', 'placeholder' => 'Enter dirty spot here']) !!}

                                    </div>

                                    <div class="form-group">
                                        <label for="oil_spot">Oil Spot</label>
                                        {!! Form::number('oil_spot', null, ['class' => 'form-control form-control-sm', 'id' => 'oil_spot', 'placeholder' => 'Enter oil spot here']) !!}

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="colour_spot">Colour Spot</label>
                                        {!! Form::number('colour_spot', null, ['class' => 'form-control form-control-sm', 'id' => 'colour_spot', 'placeholder' => 'Enter colour spot here']) !!}

                                    </div>

                                    <div class="form-group">
                                        <label for="lycra_missing">Lycra Missing</label>
                                        {!! Form::number('lycra_missing', null, ['class' => 'form-control form-control-sm', 'id' => 'lycra_missing', 'placeholder' => 'Enter lycra missing here']) !!}

                                    </div>

                                    <div class="form-group">
                                        <label for="missing_yarn">Missing Yarn</label>
                                        {!! Form::number('missing_yarn', null, ['class' => 'form-control form-control-sm', 'id' => 'missing_yarn', 'placeholder' => 'Enter missing yarn here']) !!}

                                    </div>

                                    <div class="form-group">
                                        <label for="yarn_contamination">Yarn Contamination</label>
                                        {!! Form::number('yarn_contamination', null, ['class' => 'form-control form-control-sm', 'id' => 'yarn_contamination', 'placeholder' => 'Enter yarn contamination here']) !!}

                                    </div>

                                    <div class="form-group">
                                        <label for="crease_mark">Crease Mark</label>
                                        {!! Form::number('crease_mark', null, ['class' => 'form-control form-control-sm', 'id' => 'crease_mark', 'placeholder' => 'Enter crease mark here']) !!}

                                    </div>

                                    <div class="form-group">
                                        <label for="others">Others</label>
                                        {!! Form::number('others', null, ['class' => 'form-control form-control-sm', 'id' => 'others', 'placeholder' => 'Enter others here']) !!}

                                    </div>
                                </div>
                            </div>


                            {{--
                                <div class="form-group">
                                  <label for="total_rejection">Total Rejection</label>
                                  <div class="col-sm-9">
                                     {!! Form::number('total_rejection', null, ['class' => 'form-control form-control-sm', 'id' => 'total_rejection', 'placeholder' => 'Enter total rejection here']) !!}
                                  </div>
                                </div>
                            --}}

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group m-t-md">
                                        <div class="col-sm-offset-5 col-sm-7">
                                            <button type="submit" class="btn  btn-sm white">Submit</button>
                                            <button type="button" class="btn btn-sm btn-dark"><a href="{{ url('/') }}">Cancel</a>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        {!! Form::close() !!}

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
