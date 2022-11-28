@extends('skeleton::layout')
@section("title","Color Ranges")
@section('content')
	<div class="padding">
		@if(Session::has('permission_of_color_range_view') || getRole() == 'super-admin' || getRole() == 'admin')
			<div class="box" >
				<div class="box-header">
					<h2>Color Ranges</h2>
				</div>
				<div class="box-body b-t">
					<div class="col-md-6">
						<div style="margin-bottom: 20px;">
							@if(Session::has('permission_of_color_range_add') || getRole() == 'super-admin' || getRole() == 'admin')
								<a href="{{ url('color-ranges/create') }}" class="btn btn-sm white m-b btn-sm">
									<i class="glyphicon glyphicon-plus"></i> New Color Range
								</a>
							@endif
						</div>
					</div>
					<div class="col-md-6">
						<div class="pull-right">
							{!! Form::open(['url' => 'color-ranges', 'method' => 'GET']) !!}
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
								<th>Color Range</th>
								<th>Action</th>
							</tr>
							</thead>
							<tbody>
							@if(!$color_ranges->getCollection()->isEmpty())
								@foreach($color_ranges->getCollection() as $color_range)
									<tr>
										<td>{{ $loop->iteration }}</td>
										<td>{{ $color_range->name }}</td>
										<td>
											@if(Session::has('permission_of_color_range_edit') || getRole() == 'super-admin' || getRole() == 'admin')
												<a href="{{ url('color-ranges/'.$color_range->id.'/edit')}}"
												   class="btn btn-xs btn-success"><i class="fa fa-edit"></i></a>
											@endif
											@if(Session::has('permission_of_color_range_delete') || getRole() == 'super-admin' || getRole() == 'admin')
												<button type="button" class="btn btn-xs btn-danger show-modal"
												        data-toggle="modal" data-target="#confirmationModal"
												        ui-toggle-class="flip-x" ui-target="#animate"
												        data-url="{{ url('color-ranges/'.$color_range->id) }}">
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
							@if($color_ranges->total() > 15)
								<tr>
									<td colspan="3"
									    align="center">{{ $color_ranges->appends(request()->except('page'))->links() }}</td>
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

