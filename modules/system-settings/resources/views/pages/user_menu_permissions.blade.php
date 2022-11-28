@extends('skeleton::layout')

@section('title', 'Assigned User Menu List')
@section('styles')
  <style>
    .form-custom-control {
      border-color: rgba(120, 130, 140, 0.2);
      border-radius: 0;
    }
  </style>
@endsection
@section('content')
	<div class="padding">
		<div class="box" >
			<div class="box-header">
				<h2>Assigned User Menu List</h2>
			</div>
			<div class="box-body">
				<div class="flash-message">
					@foreach (['danger', 'warning', 'success', 'info'] as $msg)
						@if(Session::has('alert-' . $msg))
							<p class="text-center alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</p>
						@endif
					@endforeach
				</div>
        {!! Form::open(['url' => url('assign-permissions/'.$user_id), 'method' => 'get']) !!}
				<table class="reportTable">
					<thead>
            <tr>
              <th rowspan="2">Sl</th>
              <th rowspan="2">User Name</th>
              <td>
                <div>
                  {!! Form::text('module_name', request('module_name') ?? null, ['class' => 'form-custom-control', 'placeholder' => 'Module Name']) !!}
                </div>
              </td>
              <td colspan="2">
                <div>
                  {!! Form::text('menu_name', request('menu_name') ?? null, ['class' => 'form-custom-control', 'placeholder' => 'Sub Module/ Menu Name']) !!}
                </div>
              </td>
              <td>
                <button type="submit" class="btn btn-xs btn-primary">
                  <i class="fa fa-search"></i>
                </button>
              </td>
            </tr>
            <tr>
              <th>Module</th>
              <th>Submodule</th>
              <th>Menu Name</th>
              <th width="15%">Actions</th>
            </tr>
					</thead>
					<tbody>
					@if($user_permissions && !$user_permissions->isEmpty())
						@foreach($user_permissions as $menu)
							<tr>
								<td>{{ $loop->iteration }}</td>
								<td>{{ $menu->user->first_name . " " . $menu->user->last_name  }}</td>
								<td>{{ $menu->module->module_name ?? '' }}</td>
								<td>{{ $menu->menu->sub_module->menu_name ?? '' }}</td>
								<td>{{ $menu->menu->menu_name ?? '' }}</td>
								<td>
									@if(Session::has('permission_of_assign-permissions_edit') || getRole() == 'super-admin'|| getRole() == 'admin')
										<a href="{{ url('/assign-permissions/'.$menu->id.'/edit') }}" class="btn btn-xs btn-success"><i
													class="fa fa-edit"></i></a>
									@endif
									@if(Session::has('permission_of_assign-permissions_delete') || getRole() == 'super-admin'|| getRole() == 'admin')
										<button type="button" class="btn btn-xs btn-danger show-modal" data-toggle="modal"
										        data-target="#confirmationModal" ui-toggle-class="flip-x" ui-target="#animate"
										        data-url="{{ url('assign-permissions/'.$menu->id) }}">
											<i class="fa fa-times"></i>
										</button>
									@endif
								</td>
							</tr>
						@endforeach
					@else
						<tr>
							<td colspan="6" align="center">No Permissions Menu </td>
						</tr>
					@endif
					</tbody>
					<tfoot>
					@if($user_permissions && $user_permissions->total() > 15)
						<tr>
							<td colspan="6" align="center">{{ $user_permissions->appends(request()->except('page'))->links() }}</td>
						</tr>
					@endif
					</tfoot>
				</table>
        {!! Form::close() !!}
			</div>
		</div>
	</div>
@endsection
