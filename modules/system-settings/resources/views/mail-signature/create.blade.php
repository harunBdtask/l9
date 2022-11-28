@extends('skeleton::layout')
@section("title","Mail Signature")
@section('styles')
    <style>
        .custom-control-label {
            padding: 0.165rem 0;
        }

        .custom-form-section {
            border-radius: 6px;
        }

        .custom-field {
            width: 90%;
            border: 1px solid #cecece;
        }
    </style>
@endsection
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Mail Signature</h2>
            </div>
            <div class="box-body b-t">
                <div class="row">
                    <div class="flash-message">
                        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                            @if(Session::has('alert-' . $msg))
                                <div
                                    class="alert alert-{{ $msg }} text-center">{{ Session::get('alert-' . $msg) }}</div>
                            @endif
                        @endforeach
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8 col-md-offset-2 custom-form-section">
                        {!! Form::open(['url' => '/mail-signature', 'method' => 'POST', 'id' => 'mail-signature-form']) !!}
                        <div class="form-group">
                            <label for="name" class="custom-control-label">Signature</label>
                            <textarea rows="3" cols="25" name="signature" id="mailSignature"
                                class="form-control form-control-sm" placeholder="signature" required>
                                {{ $signature->signature ?? '' }}
                            </textarea>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-sm btn-success" id="submit">
                                <i class="fa fa-save"></i>
                                Submit
                            </button>
                            <a href="{{ url('/mail-signature') }}" class="btn btn-sm btn-warning"><i
                                    class="fa fa-refresh"></i>
                                Reset</a>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        CKEDITOR.replace('mailSignature');
        CKEDITOR.config.height = '10em';
        CKEDITOR.config.enterMode = CKEDITOR.ENTER_BR;
    </script>
@endsection
