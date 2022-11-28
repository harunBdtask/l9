@extends('skeleton::layout')

@push('style')
    <style>
        .select-option {
            min-height: 2.375rem !important;
        }
    </style>
@endpush
@section('content')

    <div class="padding">
        <div class="col-lg-12 col-md-12">
            <div class="box" >
                <div class="box-header">
                    <h2>Terms and Conditions</h2>
                </div>
                <div class="box-body b-t">
                    <div class="flash-message">
                        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                            @if(Session::has('alert-' . $msg))
                                <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</p>
                            @endif
                        @endforeach
                    </div>

                    <div class="row">
                        <div class="col-lg-8 col-md-8 col-lg-offset-2 col-md-offset-2">
                            {!! Form::open(['url' => 'terms', 'method' => 'post']) !!}
                                <div class="row form-group">
                                    {!! Form::text('term', null, ['class' => 'form-control form-control-sm', 'placeholder' => 'Terms and Conditions']) !!}
                                </div>
                                <div class="row form-group">
                                    {!! Form::select('type', [1 => 'General', 2 => 'Special'], null, ['class' => 'form-control form-control-sm select-option']) !!}
                                </div>
                                <div class="row form-group text-center">
                                    {!! Form::submit('Save', ['class' => 'btn btn-sm white m-b']) !!}
                                </div>
                            {!! Form::close() !!}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
