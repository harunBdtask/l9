@extends('skeleton::layout')
@section("title","Product Types")
@section('content')
	<div class="padding">
		<div class="box" >
			<div class="box-header">
				<h2>Product Type List</h2>
			</div>
			<div class="box-body">

				<div class="box-body">
					<div class="row">
						<div class="col-md-6">
							@if(Session::has('permission_of_product_type_add') || getRole() == 'super-admin' || getRole() == 'admin')
								<a class="btn btn-sm white m-b" href="{{ url('product-types/create') }}">
									<i class="glyphicon glyphicon-plus"></i> New Product Type
								</a>
							@endif
						</div>
						<div class="col-md-6">
							<form action="{{ url('product-types/search') }}" method="GET">
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
										<th>Product Type Name</th>
										<th>Created By</th>
										<th>Actions</th>
									</tr>
									</thead>
									<tbody>
									@forelse($productTypes as $key=>$productType)
										<tr>
											<td>{{ $loop->iteration }}</td>
											<td>{{ $productType->name }}</td>
											<td>{{ $productType->user->screen_name }}</td>
											<td>
												@if(Session::has('permission_of_product_type_edit') || getRole() == 'super-admin' || getRole() == 'admin')
													<a class="btn btn-xs btn-success"
													   href="{{ url('product-types/'.$productType->id.'/edit') }}"><i
																class="fa fa-edit"></i></a>
												@endif
												@if(Session::has('permission_of_product_type_delete') || getRole() == 'super-admin' || getRole() == 'admin')
													<button type="button" class="btn btn-xs danger show-modal"
													        data-toggle="modal" data-target="#confirmationModal"
													        ui-toggle-class="flip-x" ui-target="#animate"
													        data-url="{{ url('product-types/'.$productType->id) }}">
														<i class="fa fa-trash"></i>
													</button>
												@endif
											</td>
										</tr>
									@empty
										<tr>
											<td colspan="4" align="center">No Data Found</td>
										</tr>
									@endforelse
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12 text-center">
							{{ $productTypes->appends(request()->except('page'))->links() }}
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
