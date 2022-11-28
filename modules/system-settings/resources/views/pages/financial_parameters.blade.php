@extends('skeleton::layout')
@section("title","Financial Parameters")
@section('content')
	<div class="padding">
		<div class="box" >
			<div class="box-header">
				<h2>Financial Parameter List</h2>
			</div>
			<div class="box-body b-t">
				@if(Session::has('permission_of_financial_parameter_setup_add') || getRole() == 'super-admin' || getRole() == 'admin')
					<a class="btn btn-sm white m-b" href="{{ url('financial-parameter-setups/create') }}">
						<i class="glyphicon glyphicon-plus"></i> New Financial Parameter
					</a>
				@endif
				<div class="pull-right">
					<form action="{{ url('/search-financial-parameter-setups') }}" method="GET">
						<div class="pull-left" style="margin-right: 1px;">
							<input type="text" class="form-control form-control-sm" name="search" value="{{ $search ?? '' }}" placeholder="Search">
						</div>
						<div class="pull-right">
							<input type="submit" class="btn btn-sm white" value="Search">
						</div>
					</form>
				</div>
			</div>
			@include('partials.response-message')
			<div class="col-md-12">
				<table class="reportTable">
					<thead>
					<tr>
						<th>SL</th>
						<th>Company</th>
						<th>Date Range</th>
						<th>Working Hour</th>
						<th>Cost Per Minute</th>
						<th>Actual CM</th>
						<th>Max Profit</th>
						<th>Status</th>
						<th>Actions</th>
					</tr>
					</thead>
					<tbody>
					@forelse($parameters as $parameter)
						<tr>
							<td>{{ $loop->iteration }}</td>
							<td>{{ $parameter->factory->factory_name  ?? ' '}}</td>
							<td>{{ date_format(date_create($parameter->date_from), 'd-M-y') }}
								to {{ date_format(date_create($parameter->date_to), 'd-M-y') }}</td>
							<td>{{ $parameter->working_hour }}</td>
							<td>{{ $parameter->cost_per_minute }}</td>
							<td>{{ $parameter->actual_cm }}</td>
							<td>{{ $parameter->max_profit }}</td>
							<td>{{ $parameter->status }}</td>
							<td>
								@if(Session::has('permission_of_financial_parameter_setup_edit') || getRole() == 'super-admin' || getRole() == 'admin')
									<a class="btn btn-sm btn-warning"
									   href="{{ url('financial-parameter-setups/'.$parameter->id.'/edit') }}"><i
												class="fa fa-edit"></i></a>
								@endif
								@if(Session::has('permission_of_financial_parameter_setup_delete') || getRole() == 'super-admin' || getRole() == 'admin')
									<button type="button" class="btn btn-sm btn-danger show-modal" data-toggle="modal"
									        data-target="#confirmationModal" ui-toggle-class="flip-x" ui-target="#animate"
									        data-url="{{ url('financial-parameter-setups/'.$parameter->id) }}">
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
					{{ $parameters->appends(request()->except('page'))->links() }}
				</div>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
@endsection
