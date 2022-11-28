@extends('skeleton::layout')
@section('title', 'Edit Assigned Permission')
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
          <h2>Update Assign Permission</h2>
        </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <div class="col-md-12 flash-message pb-2">
          </div>
          {!! Form::model($assign_permission, ['url' => 'assign-permissions/'.$assign_permission->id, 'method' => 'PUT', 'id' => 'user-assign-permission-edit-form']) !!}
          @php
          $assigned_permissions = isset($assign_permission->permissions) ? explode(",", $assign_permission->permissions) : [];
          @endphp
          <div class="col-md-12 table-responsive">
            <table class="reportTable">
              <thead>
                <tr class="tr-height">
                  <th>User</th>
                  <th>Email</th>
                  <th>Role</th>
                </tr>
              </thead>
              <tbody>
                <tr class="tr-height">
                  <td>
                    {{ $assign_permission->user->screen_name }}
                    {!! Form::hidden('factory_id', $assign_permission->user->factory_id) !!}
                    {!! Form::hidden('user_id', $assign_permission->user_id) !!}
                  </td>
                  <td>
                    {{ $assign_permission->user->email }}
                  </td>
                  <td>
                    {{ $assign_permission->user->role->name }}
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="col-md-12">
            <table class="reportTable">
              <thead>
                <tr>
                  <th rowspan="2">Module</th>
                  <th rowspan="2">Submodule</th>
                  <th rowspan="2">Menu</th>
                  <th colspan="{{ $permissions ? $permissions->count() : 1 }}">Permissions</th>
                  <th rowspan="2">Action</th>
                </tr>
                <tr>
                  @if ($permissions && $permissions->count())
                  @foreach($permissions as $permission)
                  <th>{{ strtoupper($permission->permission_name) }}</th>
                  @endforeach
                  @else
                  <th> No Permission Found</th>
                  @endif
                </tr>
              </thead>
              <tbody id="permission-assign-tbody">
                <tr class="tr-height">
                  <td>
                      {!! Form::hidden('module_id', $assign_permission->module_id) !!}
                      {{ $assign_permission->module->module_name }}
                  </td>
                  <td>{{ $assign_permission->menu->sub_module->menu_name ?? 'N/A' }}</td>
                  <td>
                    {{ $assign_permission->menu->menu_name }}
                    {!! Form::hidden('menu_id', $assign_permission->menu_id) !!}
                    <span class="text-danger menu_id"></span>
                  </td>
                  @if ($permissions && $permissions->count())
                  @foreach($permissions as $permission)
                  @php
                  $permission_id_name_attr = 'permission_id['. $assign_permission->menu_id.'][]';
                  @endphp
                  <td>
                    <label class="md-check">
                      {!! Form::checkbox($permission_id_name_attr, $permission->id, (is_array($assigned_permissions) &&
                      in_array($permission->id, $assigned_permissions)) ? true : null, ['class' => 'permission_check'])
                      !!}
                      <i class="teal-200"></i>
                    </label>
                  </td>
                  @endforeach
                  @else
                  <td> No Permission Found</td>
                  @endif
                  <td>
                    <button type="button" class="btn btn-xs btn-warning-outline check-all-permission-btn">Check/
                      Uncheck Permission
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="col-md-12 permission_input_validation_dom">
          </div>

          <div class="form-group">
            <div class="text-right">
              <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> {{ $assign_permission ? 'Update'
                : 'Create' }}</button>
              <button type="button" class="btn btn-danger"><a href="{{ url('assign-permissions') }}"><i
                    class="fa fa-remove"></i> Cancel</a></button>
            </div>
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
            $('.permission_input_validation_dom').hide();

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

            $(document).on('submit', '#user-assign-permission-edit-form', function (e) {
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
                        setTimeout(loadAssignPermissionViewPage(), 3000);
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

            function loadAssignPermissionViewPage() {
                let user_id = $('input[name="user_id"]').val();
                window.location.href = '/assign-permissions/' + user_id;
            }
        });
</script>
@endsection