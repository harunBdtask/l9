@extends('skeleton::layout')

@section('content')
<div class="padding">
	<div class="row">
		<div class="col-md-12">
			<div class="box">
				<div class="box-header">
					<div class="row">
						<div class="col-md-6">Sales Target Details</div>
					</div>
				</div>
				<br>
				<div class="box-body">
					<!-- <div class="row">
						<div class="col">
							<table class="table">
								<thead>
									<tr>
										<th colspan="5" class="text-center">Sales Target</th>
									</tr>
									<tr>
										<th>Buyer's Name</th>
										<th>Buying Agent</th>
										<th>Team Leader</th>
										<th>Month</th>
										<th>Year</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>{{ $salesTarget->buyer->name }}</td>
										<td>{{ $salesTarget->buyingAgent->buying_agent_name }}</td>
										<td>{{ $salesTarget->teamLeader->first_name }} {{ $salesTarget->teamLeader->last_name }}</td>
										<td>{{ $salesTarget->month }}</td>
										<td>{{ $salesTarget->year }}</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div> -->
					<div class="container text-center">
						<p class="font-weight-bold">Sales Target</p>
						<div class="row">
							<div class="col-md-6">
								<span class="font-weight-bold">Buyer's Name: </span> {{ $salesTarget->buyer->name }} <br>
								<span class="font-weight-bold">Buying Agent: </span> {{ $salesTarget->buyingAgent->buying_agent_name }}
							</div>
							<div class="col-md-6">
								<span class="font-weight-bold">Team Leader: </span> {{ $salesTarget->teamLeader->first_name }} {{ $salesTarget->teamLeader->last_name }} <br>
								<span class="font-weight-bold">Date: </span> {{ $salesTarget->month }} - {{ $salesTarget->year }}
							</div>
						</div>
					</div>
					<br>
					<br>
					<div class="container">
						<div class="row">
							<div class="col">
								<table class="table" style="border: 1px solid #9c9c9c;">
									<thead>
										<tr>
											<th colspan="5" class="text-center" style="font-size: 14px; border: 1px solid #9c9c9c;">Sales Target Details</th>
										</tr>
										<tr>
											<th style="border: 1px solid #9c9c9c;">Sl</th>
											<th style="border: 1px solid #9c9c9c;">Month</th>
											<th style="border: 1px solid #9c9c9c;">Quantity Target</th>
											<th style="border: 1px solid #9c9c9c;">Quantity Value</th>
											<th style="border: 1px solid #9c9c9c;">Currency</th>
										</tr>
									</thead>
									<tbody>
										@foreach($salesTarget->details as $details)
										<tr>
											<td style="border: 1px solid #9c9c9c;">{{ $loop->iteration }}</td>
											<td style="border: 1px solid #9c9c9c;">{{ $details->month }}</td>
											<td style="border: 1px solid #9c9c9c;">{{ $details->target }}</td>
											<td style="border: 1px solid #9c9c9c;">{{ $details->value }}</td>
											<td style="border: 1px solid #9c9c9c;">{{ $details->currency->currency_name }}</td>
										</tr>
										@endforeach
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<br>

					<!-- <div class="row">
						<div class="col text-right m-r-2">
							<a class="btn btn-danger" href="{{ url('sales-target-determination') }}"><i class="fa fa-remove"></i>
								Cancel</a>
						</div>
					</div> -->
				</div>
			</div>
		</div>
	</div>
</div>
@endsection