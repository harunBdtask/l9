@extends('skeleton::layout')
@section("title","Composition Types")
@section('content')
	<div class="padding">
		@if(Session::has('permission_of_composition_type_view') || getRole() == 'super-admin' || getRole() == 'admin')
			<div class="box" >
				<div class="box-header">
					<h2>Composition Types</h2>
				</div>
				<div class="box-body b-t">
					<div class="col-md-6">
						<div style="margin-bottom: 20px;">
							@if(Session::has('permission_of_composition_type_add') || getRole() == 'super-admin' || getRole() == 'admin')
								<a href="{{ url('composition-types/create') }}" class="btn btn-sm white m-b btn-sm">
									<i class="glyphicon glyphicon-plus"></i> New Composition Type
								</a>
							@endif
						</div>
					</div>
					<div class="col-md-6">
						<div class="pull-right">
							{!! Form::open(['url' => 'composition-types', 'method' => 'GET']) !!}
							<div class="pull-left" style="margin-right: 10px;">
								{!! Form::text('q', request('q') ?? null, ['class' => 'form-control form-control-sm']) !!}
							</div>
							<div class="pull-right">
								<input type="submit" class="btn btn-sm white" value="Search">
							</div>
							{!! Form::close() !!}
						</div>
					</div>

					<div class="col-md-12 flash-message">
						@foreach (['danger', 'warning', 'success', 'info'] as $msg)
							@if(Session::has('alert-' . $msg))
								<div class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</div>
							@endif
						@endforeach
					</div>
					<div class="table-responsive" style="margin-top: 20px;">
						<table class="reportTable">
							<thead>
							<tr>
								<th>SL</th>
								<th>Composition Type</th>
								<th>Action</th>
							</tr>
							</thead>
							<tbody>
							@if(!$composition_types->getCollection()->isEmpty())
								@foreach($composition_types->getCollection() as $composition_type)
									<tr>
										<td>{{ $loop->iteration }}</td>
										<td>{{ $composition_type->name }}</td>
										<td>
											@if(Session::has('permission_of_composition_type_edit') || getRole() == 'super-admin' || getRole() == 'admin')
												<a href="{{ url('composition-types/'.$composition_type->id.'/edit')}}"
												   class="btn btn-xs btn-success"><i class="fa fa-edit"></i></a>
											@endif
											@if(Session::has('permission_of_composition_type_delete') || getRole() == 'super-admin' || getRole() == 'admin')
												<button type="button" class="btn btn-xs btn-danger show-modal"
												        data-toggle="modal" data-target="#confirmationModal"
												        ui-toggle-class="flip-x" ui-target="#animate"
												        data-url="{{ url('composition-types/'.$composition_type->id) }}">
													<i class="fa fa-times"></i>
												</button>
											@endif
										</td>
									</tr>
								@endforeach
							@else
								<tr>
									<td colspan="3" align="center">No Data</td>
								</tr>
							@endif
							</tbody>
							<tfoot>
							@if($composition_types->total() > 15)
								<tr>
									<td colspan="3"
									    align="center">{{ $composition_types->appends(request()->except('page'))->links() }}</td>
								</tr>
							@endif
							</tfoot>
						</table>
					</div>
				</div>
			</div>
		@endif
	</div>
@endsection

