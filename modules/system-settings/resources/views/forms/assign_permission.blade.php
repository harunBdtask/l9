@extends('skeleton::layout')
@section('title', 'Assign Permission')
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
    width: 100%;
  }

  .select2-container--default .select2-selection--single .select2-selection__arrow {
    top: 8px;
  }

  .error+.select2-container .select2-selection--single {
    border: 1px solid red;
  }

  .select2-container--default .select2-selection--multiple {
    min-height: 40px !important;
    border-radius: 0px;
    width: 100%;
  }

  #loader,
  #modal-loader {
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
    <div class="col-md-12">
      <div class="box assign_permission_box">
        <div class="box-header">
          <h2>New Assign Permission</h2>
        </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body form-colors">
          <div class="col-md-12 flash-message pb-2">
            @foreach (['danger', 'warning', 'success', 'info'] as $msg)
            @if(Session::has('alert-' . $msg))
            <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</p>
            @endif
            @endforeach
          </div>
          {!! Form::open(['url' => '/assign-module-wise-permission', 'method' => 'POST', 'id' =>
          'user-assign-module-wise-permission-form']) !!}

          <div class="form-group">
            <label for="factory_id">Select Company</label>
            {!! Form::select('factory_id', $factories, null, ['class' => 'form-control form-control-sm',
            'id'=>'assign_permission_factory_id', 'placeholder' => 'Select a Company']) !!}
            <span class="text-danger factory_id"></span>
            @if($errors->has('factory_id'))
            <span class="text-danger">{{ $errors->first('factory_id') }}</span>
            @endif
          </div>

          <div class="form-group">
            <label for="user_id">Select User</label>
            {!! Form::select('user_id[]', [], null, ['class' => 'form-control form-control-sm', 'multiple' => true]) !!}
            <span class="text-danger user_id"></span>
            @if($errors->has('user_id'))
            <span class="text-danger">{{ $errors->first('user_id') }}</span>
            @endif
          </div>

          <div class="form-group">
            <label for="module_ids">Select Modules</label>
            {!! Form::select('module_ids[]', $modules, null, ['class' => 'form-control form-control-sm', 'multiple' =>
            true]) !!}
            <span class="text-danger module_ids"></span>
          </div>
          <div class="col-md-12 menu-permission-section">

          </div>
          <div class="col-md-12 permission_input_validation_dom">
          </div>
          <div class="form-group m-t-md">
            <button type="submit" class="btn btn-sm btn-success submit-btn">Assign Permissions</button>
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
				$('.submit-btn').hide();
				$('.permission_input_validation_dom').hide();

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

				$(document).on('change', 'select[name="module_ids[]"]', function (e) {
					e.preventDefault();
					var module_ids = $(this).val();
					var loader = $('#loader');
					var menuPermissionDom = $('.menu-permission-section');
					menuPermissionDom.html('');
					$('.submit-btn').hide();
					if (module_ids) {
						loader.show();
						$.ajax({
							type: 'GET',
							url: '/get-menu-with-permission-form/' + module_ids
						}).done(function (response) {
							loader.hide();
							if (response.status === 'success') {
								menuPermissionDom.html(response.html);
								$('.submit-btn').show();
							}
						}).fail(function (response) {
							loader.hide();
							console.log(response.responseJSON);
						});
					}
				});

        $(document).on('click', '.permission_names', function (e) {
					e.preventDefault();
          var permission_identifier = $(this).attr('id');
					$('tbody tr').each(function () {
            let className = '.permission_check.'+permission_identifier;
						var permission_checkbox_dom = $(this).find(className);
						permissionCheckboxCheckUncheckHandler(permission_checkbox_dom);
					})
        })

				$(document).on('click', '.check-all-module-permission-btn', function (e) {
					e.preventDefault();
					$('tbody tr').each(function () {
						var permission_checkbox_dom = $(this).find('.permission_check');
						permissionCheckboxCheckUncheckHandler(permission_checkbox_dom);
					})
				});

				$(document).on('click', '.check-all-permission-btn', function () {
					var permission_checkbox_dom = $(this).parents('tr').find('.permission_check');
					permissionCheckboxCheckUncheckHandler(permission_checkbox_dom);
				});

				function permissionCheckboxCheckUncheckHandler(permission_checkbox_dom) {
					$.each(permission_checkbox_dom, function (key, val) {
						if (!val.checked) {
							val.checked = true;
						} else {
							val.checked = false;
						}
					});
				}

				$(document).on('change keyup', 'select,input', function (e) {
					e.preventDefault();
					var nameAttrExist = $(this).attr('name');

					if (typeof nameAttrExist !== typeof undefined && nameAttrExist !== false) {
						var nameAttr = nameAttrExist.replace(/[^\w\s]/gi, '');
						$(this).parent()[0].querySelector('span.' + nameAttr).innerHTML = '';
					}
				});

				$(document).on('submit', '#user-assign-module-wise-permission-form', function (e) {
					e.preventDefault();
					var flashMessageDom = $('.flash-message');
					var loader = $('#loader');
					var permission_input_validation_message_dom = $('.permission_input_validation_dom');
					permission_input_validation_message_dom.hide();
					permission_input_validation_message_dom.html('');
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
							setTimeout(loadAssignPermissionCreatePage(), 3000);
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
							if(errorDomElement === '.permission_id') {
								permission_input_validation_message = "<p class='alert alert-danger text-center'>"+ errorMessage +"</p>";
								permission_input_validation_message_dom.html(permission_input_validation_message);
								permission_input_validation_message_dom.fadeIn().delay(2000).fadeOut(2000);
							} else {
								$(errorDomElement).html(errorMessage);
							}
						});
					});
				});

				function loadAssignPermissionCreatePage() {
					window.location.href = '/assign-permissions/create';
				}
			});
</script>
@endsection