@extends('skeleton::layout')
@section("title","Sales Target Determination")

@section('styles')
<style>
	/* {{-- <style>
        .table-header {
            background: #93dcf9;
        }
</style> --}} */
</style>
@endsection

@section('content')
<div class="padding">
	<div class="box">
		<div class="box-header">
			<h2>Sales Target List</h2>
		</div>
		<div class="box-body b-t">
			<a class="btn btn-sm btn-info m-b" href="{{ url('sales-target-determination/create') }}">
				<i class="glyphicon glyphicon-plus"></i> New Sales Target
			</a>
			<div class="pull-right">
				{{-- search area--}}
			</div>
		</div>
		<!-- @include('partials.response-message')
		@foreach (['danger', 'warning', 'success', 'info'] as $msg)
		@if(Session::has('alert-' . $msg))
		<p class="alert alert-{{ $msg }} text-center">{{ Session::get('alert-' . $msg) }}</p>
		@endif
		@endforeach -->
		<div class="col-md-12">
			<table class="reportTable">
				<thead>
					<tr class="table-header">
						<th>
							@php $sort = request('sort') == 'asc' ? 'desc' : 'asc';@endphp
							<a class="btn btn-sm btn-light" href="{{  url('sales-target-determination?sort=' . $sort)}}">
								<i class="fa {{ $sort == 'asc' ? 'fa-angle-down' : 'fa-angle-up' }}">SL</i>
							</a>
						</th>
						<th>Buyer's Name</th>
						<th>Buying Agent</th>
						<th>Team Leader</th>
						<th>Month</th>
						<th>Year</th>
						<th>Action</th>
						<!-- <th style="width: 85px">Action</th> -->
					</tr>
				</thead>
				<tbody>
					@forelse($targets as $target)
					<tr>
						<td>{{ $loop->iteration }}</td>
						<td>{{ $target->buyer->name }}</td>
						<td>{{ $target->buyingAgent->buying_agent_name }}</td>
						<td>{{ $target->teamLeader->first_name }} {{ $target->teamLeader->last_name }}</td>
						<td>{{ $target->month }}</td>
						<td>{{ $target->year }}</td>
						<td class="action_field">
							<a class="btn btn-sm btn-success" href="{{ url('sales-target-determination/'. $target->id .'/edit') }}">
								<i class="fa fa-edit"></i>
							</a>
							<a class="btn btn-sm btn-default" href="{{ url('sales-target-determination/'. $target->id) }}" target="_blank">
								<i class="fa fa-eye"></i>
							</a>
							<a type="button" href="#" class="show-modal btn btn-sm btn-danger" data-toggle="modal" data-target="#confirmationModal" ui-toggle-class="flip-x" ui-target="#animate" data-url="{{ url('sales-target-determination/'.$target->id) }}">
								<i class="fa fa-trash"></i>
							</a>
						</td>
					</tr>
					@empty
					<tr>
						<td colspan="7">No Data Found</td>
					</tr>
					@endforelse
				</tbody>
			</table>
			<div class="text-center">
				{{ $targets->appends(request()->except('page'))->links() }}
			</div>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
@endsection


@push("script-head")
<script>
	$(document).ready(function() {


	});
</script>
@endpush