@extends('skeleton::layout')
@section("title","Buyer")
@section('content')
	<div class="padding">
		<div class="box" >
			<div class="box-header">
				<h2>Buyer List</h2>
			</div>
			<div class="box-body b-t">
				@if(Session::has('permission_of_buyers_add') || getRole() == 'super-admin' || getRole() == 'admin')
					<a class="btn btn-sm white m-b" href="{{ url('buyers/create') }}">
						<i class="glyphicon glyphicon-plus"></i> New Buyer
					</a>
				@endif
				<div class="pull-right">
					<form action="{{ url('/search-buyer') }}" method="GET">
						<div class="pull-left" style="margin-right: 1px;">
							<input type="text" class="form-control form-control-sm" name="q" value="{{ $q ?? '' }}"
							       placeholder="Search..">
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
						<th>Buyer's Name</th>
						<th>Party Type</th>
						<th>PDF Conversion Key</th>
						<th>Supplier Name</th>
						<th>Party Name</th>
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
					@forelse($buyers as $buyer)
						<tr>
							<td>{{ $loop->iteration }}</td>
							<td>{{ $buyer->name }}</td>
							<td>{{ $buyer->party_type }}</td>
							<td>{{ $buyer->pdf_conversion_key }}</td>
							<td>{{ $buyer->supplier->name ?? 'N/A' }}</td>
							<td>{{ $buyer->party->party_name ?? 'N/A' }}</td>
							<td>{{ $buyer->contact_person ?? 'N/A' }}</td>
							<td>{{ $buyer->designation ?? 'N/A' }}</td>
							<td>{{ $buyer->day_credit_limit ?? 'N/A' }}</td>
							<td>{{ $buyer->amount_credit_limit ?? 'N/A' }}</td>
							<td>{{ $buyer->currency->currency_name ?? 'N/A' }}</td>
							<td>{{ $buyer->status ?? 'N/A' }}</td>
							<td>
								@if(Session::has('permission_of_buyers_edit') || getRole() == 'super-admin' || getRole() == 'admin')
									<a class="btn btn-sm white" href="{{ url('buyers/'.$buyer->id.'/edit') }}"><i
												class="fa fa-edit"></i></a>
								@endif
							</td>
						</tr>
					@empty

					@endforelse
					</tbody>
				</table>
				<div class="text-center">
					{{ $buyers->appends(request()->except('page'))->links() }}
				</div>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
@endsection
