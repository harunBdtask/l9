@extends('skeleton::layout')
@section('title', 'New Assign Full Permission')
@section('styles')
    <style>

        .select2-container .select2-selection--single {
            height: 40px;
            border-radius: 0px;
            line-height: 50px;
            border: 1px solid #e7e7e7;
        }

        .select2-container .select2-selection--single {
            background-color: #fff !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 40px;
            width: 150px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 8px;
        }

        .error + .select2-container .select2-selection--single {
            border: 1px solid red;
        }

        .select2-container--default .select2-selection--multiple {
            min-height: 40px !important;
            border-radius: 0px;
            width: 100%;
        }

        #loader, #modal-loader {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(226, 226, 226, 0.75) no-repeat center center;
            width: 100%;
            z-index: 1000;
        }

        .spin-loader {
            position: relative;
            top: 46%;
            left: 5%;
        }
    </style>
@endsection
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-7 col-md-offset-2">
                <div class="box assign_permission_box">
                    <div class="box-header">
                        <h2>{{ $assign_permission ? 'Update Assign Full Permission' : 'New Assign Full Permission' }}</h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body" style="background-color: #4dc6f3;">
                        <div class="col-md-12 flash-message pb-2">
                            @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                                @if(Session::has('alert-' . $msg))
                                    <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</p>
                                @endif
                            @endforeach
                        </div>
                        {!! Form::open(['url' => 'assign-module-wise-full-permission', 'method' => 'POST', 'id' => 'user-assign-full-permission-form']) !!}

                        <div class="form-group">
                            <label for="factory_id">Select Company</label>
                            {!! Form::select('factory_id', $factories, null, ['class' => 'form-control form-control-sm', 'id'=>'assign_permission_factory_id', 'placeholder' => 'Select a Company']) !!}
                            <span class="text-danger factory_id"></span>
                            @if($errors->has('factory_id'))
                                <span class="text-danger">{{ $errors->first('factory_id') }}</span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="user_id">Select a User</label>
                            {!! Form::select('user_id[]', [], null, ['class' => 'form-control form-control-sm', 'multiple' => true]) !!}
                            <span class="text-danger user_id"></span>
                            @if($errors->has('user_id'))
                                <span class="text-danger">{{ $errors->first('user_id') }}</span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="module_id">Select Module</label>
                            {!! Form::select('module_id[]', $modules, null, ['class' => 'form-control form-control-sm', 'multiple' => true]) !!}
                            <span class="text-danger module_id"></span>
                            @if($errors->has('module_id'))
                                <span class="text-danger">{{ $errors->first('module_id') }}</span>
                            @endif
                        </div>

                        <div class="form-group">
                            <button type="submit"
                                    class="btn btn-sm btn-success"><i class="fa fa-save"></i> Assign Full Permission
                            </button>
                            <button type="button" class="btn btn-sm btn-danger remove_all_permission"><i
                                    class="fa fa-remove"></i> Remove All Permission
                            </button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
        <div id="loader">
            <div class="text-center spin-loader"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i></div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function () {
            function setSelect2() {
                $(document).find('select').select2();
            }

            setSelect2();

            $(document).on('change', 'select[name="factory_id"]', function () {
                var factory_id = $(this).val();
                var domElements = $('select[name="user_id[]"]');
                domElements[0].innerHTML = '';
                if (factory_id) {
                    $.ajax({
                        type: 'GET',
                        url: '/get-users/' + factory_id,
                        success: function (response) {
                            $.each(domElements, function (index, domElement) {
                                domElement.innerHTML = "";
                                var dropdown = '';
                                if (Object.keys(response).length > 0) {
                                    $.each(response, function (index, val) {
                                        dropdown += '<option value="' + val.id + '">' + val.email + '</option>';
                                    });
                                }
                                domElement.innerHTML = dropdown;
                                domElement.value = '';
                                setSelect2();
                            });
                        }
                    });
                }
            });

            $(document).on('change keyup', 'select,input', function (e) {
                e.preventDefault();
                var nameAttr = $(this).attr('name').replace(/[^\w\s]/gi, '');
                $(this).parent()[0].querySelector('span.' + nameAttr).innerHTML = '';
            });

            $(document).on('submit', '#user-assign-full-permission-form', function (e) {
                e.preventDefault();
                var flashMessageDom = $('.flash-message');
                var loader = $('#loader');
                var form = $(this);
                $('.text-danger').html('');
                loader.show();

                $.ajax({
                    type: form.attr('method'),
                    url: form.attr('action'),
                    data: form.serialize()
                }).done(function (response) {
                    loader.hide();
                    if (response.status == 'success') {
                        flashMessageDom.html(response.message);
                        flashMessageDom.fadeIn().delay(2000).fadeOut(2000);
                        $("html, body").animate({scrollTop: 0}, "slow");
                        //setTimeout(loadAssignFullPermissionPage(), 3000);
                    }

                    if (response.status == 'danger') {
                        flashMessageDom.html(response.message);
                        flashMessageDom.fadeIn().delay(2000).fadeOut(2000);
                    }
                }).fail(function (response) {
                    loader.hide();
                    $.each(response.responseJSON.errors, function (errorIndex, errorValue) {
                        let errorDomElement, error_index, errorMessage;
                        errorDomElement = '' + errorIndex;
                        errorDomIndexArray = errorDomElement.split(".");
                        errorDomElement = '.' + errorDomIndexArray[0];
                        error_index = errorDomIndexArray[1];
                        errorMessage = errorValue[0];
                        $(errorDomElement).html(errorMessage);
                    });
                });
            });

            function loadAssignFullPermissionPage() {
                window.location.href = '/assign-module-wise-full-permission';
            }

            $(document).on('click', 'button.remove_all_permission', function (e) {
                e.preventDefault();
                var confirmMessage = confirm("Are you sure to remove all permissions of the users from the modules?");
                var factory_id = $('#assign_permission_factory_id').val();
                var user_id = $('select[name="user_id[]"]').val();
                var module_id = $('select[name="module_id[]"]').val();
                var flashMessageDom = $('.flash-message');
                var loader = $('#loader');
                if (confirmMessage) {
                    $('.text-danger').html('');
                    var data = {
                        factory_id: factory_id,
                        user_id: user_id,
                        module_id: module_id,
                    };
                    loader.show();
                    $.ajax({
                        type: "POST",
                        url: '/assign-module-wise-full-permission/remove',
                        data: data
                    }).done(function (response) {
                        loader.hide();
                        if (response.status == 'success') {
                            flashMessageDom.html(response.message);
                            flashMessageDom.fadeIn().delay(2000).fadeOut(2000);
                            $("html, body").animate({scrollTop: 0}, "slow");
                            //setTimeout(loadAssignFullPermissionPage(), 3000);
                        }

                        if (response.status == 'danger') {
                            flashMessageDom.html(response.message);
                            flashMessageDom.fadeIn().delay(2000).fadeOut(2000);
                        }
                    }).fail(function (response) {
                        loader.hide();
                        $.each(response.responseJSON.errors, function (errorIndex, errorValue) {
                            let errorDomElement, error_index, errorMessage;
                            errorDomElement = '' + errorIndex;
                            errorDomIndexArray = errorDomElement.split(".");
                            errorDomElement = '.' + errorDomIndexArray[0];
                            error_index = errorDomIndexArray[1];
                            errorMessage = errorValue[0];
                            $(errorDomElement).html(errorMessage);
                        });
                    });
                }
            });
        });
    </script>
@endsection
