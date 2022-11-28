@extends('commercial::layout')
@section('title','Sales Contract Amendment')

@push('style')
    <style>
        .form-control form-control-sm {
            border: 1px solid #909ac8 !important;
            border-radius: 10px 0 0 0;
        }

        input, select {
            min-height: 30px !important;
        }

        .form-control form-control-sm:focus {
            border: 2px solid #909ac8 !important;
        }

        .req {
            font-size: 1rem;
        }

        .mainForm td, .mainForm th {
            border: none !important;
            padding: .3rem !important;
        }

        li.parsley-required {
            color: red;
            list-style: none;
            text-align: left;
        }

        input.parsley-error,
        select.parsley-error,
        textarea.parsley-error {
            border-color: #843534;
            box-shadow: none;
        }


        input.parsley-error:focus,
        select.parsley-error:focus,
        textarea.parsley-error:focus {
            border-color: #843534;
            box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075), 0 0 6px #ce8483
        }

        .remove-po {
            border: none;
            display: block;
            width: 100%;
            background-color: #843534;
            color: whitesmoke;
        }

        .close-po {
            border: none;
            display: block;
            width: 100%;
            background-color: #6cc788;
            color: whitesmoke;
        }

        .error + .select2-container .select2-selection--single {
            border: 1px solid red;
        }
    </style>
@endpush

@section('content')
    <div class="padding">


        <div class="box" >
            <div class="box-header text-center" >
                <h2 style="font-weight: 400; ">Sales Contract Amendment</h2>
            </div>

            <div class="box-body">

                @include('commercial::partials.flash')

                {!! Form::open(['url' => 'commercial/sales-contract-amendments/create-form', 'method' => 'post']) !!}

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="file_no">File No</label>
                            <select class="form-control form-control-sm select2-input" name="file_no"
                                    style="height: 20px"
                                    id="file_no">
                                <option value="" disabled>Select Internal FIle No</option>
                                @foreach($fileNo as $key => $file)
                                    <option
                                        value="{{ $file->text }}" {{$key == 0 ? 'selected' : ''}}>{{ $file->text }}</option>
                                @endforeach
                            </select>
{{--                            {!!  Form::text('file_no', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'sales_contract_no']) !!}--}}
                            @if($errors->has('file_no'))
                                <span class="text-danger">{{ $errors->first('file_no') }}</span>
                            @endif
                        </div>

                        <button class="btn btn-sm btn-primary">Submit</button>
                    </div>
                </div>

                {!! Form::close() !!}

            </div>
        </div>
    </div>
@endsection
