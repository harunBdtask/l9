@extends('skeleton::layout')
@section("title","Garments Item Group")
@section('content')
	<div class="padding">
		<div class="box" >
			<div class="box-header">
				<h2>Garments Item Group List</h2>
			</div>
			<div class="box-body">

				<div class="box-body">
					<div class="row">
						<div class="col-md-6">
							@if(Session::has('permission_of_garments_item_group_add') || getRole() == 'super-admin' || getRole() == 'admin')
								<a class="btn btn-sm white m-b" href="{{ url('garments-item-group/create') }}">
									<i class="glyphicon glyphicon-plus"></i> New Garments Item Group
								</a>
							@endif
						</div>
						<div class="col-md-6">
							<form action="{{ url('garments-item-group/search') }}" method="GET">
								<div class="input-group">
									<input type="text" class="form-control form-control-sm" name="search"
									       value="{{ $search ?? '' }}" placeholder="Search">
									<span class="input-group-btn">
                                            <button class="btn btn-sm white m-b" type="submit">Search</button>
                                        </span>
								</div>
							</form>
						</div>
					</div>
					<div class="row m-t">
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
					<div class="row">
						<div class="col-sm-12">
							<div class="table-responsive">
								<table class="reportTable">
									<thead>
									<tr>
										<th>SL</th>
										<th>Garments Item Group Name</th>
										<th>Actions</th>
									</tr>
									</thead>
									<tbody>
									@forelse($garmentsItemGroups as $key=>$garmentsItemGroup)
										<tr>
											<td>{{ $loop->iteration }}</td>
											<td>{{ $garmentsItemGroup->name }}</td>
											<td>
												@if(Session::has('permission_of_garments_item_group_edit') || getRole() == 'super-admin' || getRole() == 'admin')
													<a class="btn btn-xs btn-success"
													   href="{{ url('garments-item-group/'.$garmentsItemGroup->id.'/edit') }}"><i
																class="fa fa-edit"></i></a>
												@endif
												@if(Session::has('permission_of_garments_item_group_delete') || getRole() == 'super-admin' || getRole() == 'admin')
													<button type="button" class="btn btn-xs danger show-modal"
													        data-toggle="modal" data-target="#confirmationModal"
													        ui-toggle-class="flip-x" ui-target="#animate"
													        data-url="{{ url('garments-item-group/'.$garmentsItemGroup->id) }}">
														<i class="fa fa-trash"></i>
													</button>
												@endif
											</td>
										</tr>
									@empty
										<tr>
											<td colspan="9" align="center">No Data Found</td>
										</tr>
									@endforelse
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12 text-center">
							{{ $garmentsItemGroups->links() }}
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
