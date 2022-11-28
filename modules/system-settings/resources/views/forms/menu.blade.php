@extends('skeleton::layout')

@section('title', 'Menu')
@section('content')
	<div class="padding">
		<div class="row">
			<div class="col-md-12">
				<div class="box" >
					<div class="box-header">
						<h2>{{ $menu ? 'Update Menu' : 'New Menu' }}</h2>
					</div>
					<div class="box-divider m-a-0"></div>
					<div class="box-body" style="background-color: #4dc6f3;">
						{!! Form::model($menu, ['url' => $menu ? 'menus/'.$menu->id : 'menus', 'method' => $menu ? 'PUT' : 'POST']) !!}

                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="menu_name">Menu Name</label>
                                    {!! Form::text('menu_name', null, ['class' => 'form-control form-control-sm', 'id' => 'menu_name', 'placeholder' => 'Write menu\'s name here']) !!}

                                    @if($errors->has('menu_name'))
                                        <span class="text-danger">{{ $errors->first('menu_name') }}</span>
                                    @endif
                                </div>

                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="menu_url">Menu Url</label>
                                    {!! Form::text('menu_url', null, ['class' => 'form-control form-control-sm', 'id' => 'menu_url', 'placeholder' => 'Write menu\'s url here']) !!}

                                    @if($errors->has('menu_url'))
                                        <span class="text-danger">{{ $errors->first('menu_url') }}</span>
                                    @endif
                                </div>

                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="module">Module</label>
                                    {!! Form::select('module_id', $modules, null, ['class' => 'administration-module form-control form-control-sm select2-input', 'id' => 'module', 'placeholder' => 'Select a module']) !!}

                                    @if($errors->has('module_id'))
                                        <span class="text-danger">{{ $errors->first('module_id') }}</span>
                                    @endif
                                </div>

                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="submodule_id">Sub Module</label>
                                    {!! Form::select('submodule_id', $menu? $submodules :[], null, ['class' => 'administration-submodule form-control form-control-sm select2-input', 'id' => 'submodule_id', 'placeholder' => 'Select a submodule']) !!}

                                </div>

                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="left_menu">Left Menu</label>
                                    {!! Form::select('left_menu', [1 => 'Yes', 2 => 'No'], null, ['class' => 'form-control form-control-sm c-select', 'id' => 'left_menu', 'placeholder' => 'Select a type']) !!}
                                    @if($errors->has('left_menu'))
                                        <span class="text-danger">{{ $errors->first('left_menu') }}</span>
                                    @endif
                                </div>

                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="sort">Module Wise Sort</label>
                                    {!! Form::number('sort', null, ['class' => 'form-control form-control-sm', 'id' => 'sort', 'placeholder' => 'Write sort number here']) !!}
                                    @if($errors->has('sort'))
                                        <span class="text-danger">{{ $errors->first('sort') }}</span>
                                    @endif
                                </div>

                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="display_as">Display Name</label>
                                    {!! Form::text('display_as', null, ['class' => 'form-control form-control-sm', 'id' => 'display_as', 'placeholder' => 'Menu Display Name']) !!}

                                    @if($errors->has('display_as'))
                                        <span class="text-danger">{{ $errors->first('display_as') }}</span>
                                    @endif
                                </div>

                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-sm btn-success"><i
                                            class="fa fa-save"></i> {{ $menu ? 'Update' : 'Create' }}</button>
                                    <a class="btn btn-sm btn-warning" href="{{ url('menus') }}"><i class="fa fa-remove"></i> Cancel</a>
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
@section('scripts')
	<script>
		// size wise report
		$(document).on('change', '.administration-module', function () {
			var module_id = $(this).val();
			var subModuleDom = $('.administration-submodule');
			subModuleDom.empty();
			subModuleDom.val('').select2();
			if (module_id) {
				$.ajax({
					type: 'GET',
					url: '/get-submodules/' + module_id,
					success: function (response) {
						var OrderDropdown = '<option value="">Select a Submodule</option>';
						if (Object.keys(response).length > 0) {
							$.each(response, function (index, val) {
								OrderDropdown += '<option value="' + index + '">' + val + '</option>';
							});
						}
						subModuleDom.append(OrderDropdown);
						subModuleDom.val('').select2();
					}
				});
			}
		});
	</script>
@endsection
