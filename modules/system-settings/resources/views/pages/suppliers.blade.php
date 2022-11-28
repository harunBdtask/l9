@extends('skeleton::layout')
@section("title","Suppliers")
@section('content')
	<div class="padding">
		<div class="box" >
			<div class="box-header">
				<h2>Supplier List</h2>
			</div>
			<div class="box-body b-t">
				@if(Session::has('permission_of_suppliers_add') || getRole() == 'super-admin' || getRole() == 'admin')
					<a class="btn btn-sm white m-b" href="{{ url('suppliers/create') }}">
						<i class="glyphicon glyphicon-plus"></i> New Supplier
					</a>
				@endif
				<div class="pull-right">
					<form action="{{ url('/suppliers/search') }}" method="GET">
						<div class="pull-left" style="margin-right: 1px;">
							<input type="text" class="form-control form-control-sm" name="q" value="{{ $q ?? '' }}" placeholder="Search..">
						</div>
						<div class="pull-right">
							<input type="submit" class="btn btn-sm white" value="Search">
						</div>
					</form>
				</div>
			</div>
			@include('partials.response-message')
			@foreach (['danger', 'warning', 'success', 'info'] as $msg)
				@if(Session::has('alert-' . $msg))
					<p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</p>
				@endif
			@endforeach
			<div class="col-md-12">
				<table class="reportTable">
					<thead>
					<tr>
						<th>SL</th>
						<th>Supplier's Name</th>
						<th>Short Name</th>
						<th>Party Type</th>
						<th>Contact Person</th>
						<th>Designation</th>
						<th>Credit Limit(Days)</th>
						<th>Credit Limit (Amount)</th>
						<th>Currency</th>
						<th>Status</th>
						<th>Actions</th>
					</tr>
					</thead>
					<tbody>
					@forelse($suppliers as $supplier)
						<tr>
							<td>{{ $loop->iteration }}</td>
							<td>{{ $supplier->name }}</td>
							<td>{{ $supplier->short_name ?? 'N/A' }}</td>
							<td>{{ $supplier->party_type ?? 'N/A' }}</td>
							<td>{{ $supplier->contact_person ?? 'N/A' }}</td>
							<td>{{ $supplier->designation ?? 'N/A' }}</td>
							<td>{{ $supplier->day_credit_limit ?? 'N/A' }}</td>
							<td>{{ $supplier->amount_credit_limit ?? 'N/A' }}</td>
							<td>{{ $supplier->currency->currency_name ?? 'N/A' }}</td>
							<td>{{ $supplier->status ?? 'N/A' }}</td>
							<td>
								@if(Session::has('permission_of_suppliers_edit') || getRole() == 'super-admin' || getRole() == 'admin')
									<a class="btn btn-sm white" href="{{ url('suppliers/'.$supplier->id.'/edit') }}"><i
												class="fa fa-edit"></i></a>
								@endif
								@if(Session::has('permission_of_suppliers_delete') || getRole() == 'super-admin' || getRole() == 'admin')
									<button type="button" class="btn btn-sm white show-modal" data-toggle="modal"
									        data-target="#confirmationModal" ui-toggle-class="flip-x" ui-target="#animate"
									        data-url="{{ url('suppliers/'.$supplier->id) }}">
										<i class="fa fa-times"></i>
									</button>
								@endif
							</td>
						</tr>
					@empty

					@endforelse
					</tbody>
				</table>
				<div class="text-center">
					{{ $suppliers->appends(request()->except('page'))->links() }}
				</div>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
@endsection
