@extends('skeleton::layout')
@section("title","Mail Configuration")
@section('content')
    <style>
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        /* Hide default HTML checkbox */
        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        /* The slider */
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked + .slider {
            background-color: #2ecc71;
        }

        input:focus + .slider {
            box-shadow: 0 0 1px #2ecc71;
        }

        input:checked + .slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }
    </style>

    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Mail Configuration</h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="flash-message">
                            @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                                @if(Session::has('alert-' . $msg))
                                    <p class="alert alert-{{ $msg }} text-center">{{ Session::get('alert-' . $msg) }}</p>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="row m-t">
                    <div class="col-sm-12">
                        <form
                            action="{{ url('/mail-configuration')}}"
                            method="post" id="form">
                            @csrf
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="driver">Driver<span class="text-danger">*</span></label>
                                    <input type="text" id="driver" name="driver"
                                           class="form-control form-control-sm"
                                           value="{{ $mailConfig->driver ?? '' }}"
                                           placeholder="Ex: smtp">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="host">Host<span class="text-danger">*</span></label>
                                    <input type="text" id="host" name="host"
                                           class="form-control form-control-sm"
                                           value="{{ $mailConfig->host ?? '' }}"
                                           placeholder="Ex: smtp.gmail.com">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="port">Port<span class="text-danger">*</span></label>
                                    <input type="text" id="port" name="port"
                                           class="form-control form-control-sm"
                                           value="{{ $mailConfig->port ?? '' }}"
                                           placeholder="Ex: 587">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="username">Username<span class="text-danger">*</span></label>
                                    <input type="text" id="username" name="username"
                                           class="form-control form-control-sm"
                                           value="{{ $mailConfig->username ?? '' }}"
                                           placeholder="Ex: hero@mail.com">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="password">Password<span class="text-danger">*</span></label>
                                    <input type="password" id="password" name="password"
                                           class="form-control form-control-sm"
                                           placeholder="Write mail password">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="encryption">Encryption<span class="text-danger">*</span></label>
                                    <input type="text" id="encryption" name="encryption"
                                           class="form-control form-control-sm"
                                           value="{{ $mailConfig->encryption ?? '' }}"
                                           placeholder="Ex: tls">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="from_address">From Address<span class="text-danger">*</span></label>
                                    <input type="text" id="from_address" name="from_address"
                                           class="form-control form-control-sm"
                                           value="{{ $mailConfig->from_address ?? '' }}"
                                           placeholder="Ex: hero@mail.com">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="from_name">From Name<span class="text-danger">*</span></label>
                                    <input type="text" id="from_name" name="from_name"
                                           class="form-control form-control-sm"
                                           value="{{ $mailConfig->from_name ?? '' }}"
                                           placeholder="Ex: hero">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="sending_time">Sending Time</label>
                                    <input type="time" id="sending_time" name="sending_time"
                                           style="line-height:1rem"
                                           class="form-control form-control-sm"
                                           value="{{ $mailConfig->sending_time ?? '' }}"
                                           placeholder="Ex: 19:05">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <label for="is_enabled" style="cursor: pointer">Disable/Enable <span
                                        class="text-danger">*</span></label><br/>
                                <label class="switch">
                                    <input type="checkbox"
                                           name="is_enabled"
                                           id="is_enabled" {{ isset($mailConfig) && $mailConfig->is_enabled ? 'checked' : '' }}>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="from_name">Logo Url</label>
                                    <input type="text" id="from_name" name="logo_url"
                                           class="form-control form-control-sm"
                                           value="{{ $mailConfig->logo_url ?? '' }}"
                                           placeholder="Ex: https://something.com/image.jpeg">
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <div class="form-group">
                                    <button style="margin-top: 26%;" type="submit" id="submit"
                                            class="btn btn-sm btn-success">
                                        <em class="fa fa-save"></em> Update
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
