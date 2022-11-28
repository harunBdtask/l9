@extends('skeleton::layout')

@section('content')
<div class="padding">
	<div class="row">
		<div class="col-md-12">
			<div class="box">
				<div class="box-header">
					<h2>Update Target</h2>
				</div>
				<div class="box-divider m-a-0"></div>
				<div class="box-body">
					<form action="{{ url('sales-target-determination/'.$salesTarget->id) }}" method="post">
						{{ method_field('PUT') }}
						{{ csrf_field() }}
						<div class="row">
							<div class="col-sm-4">
								<div class="form-group">
									<label for="buyer_id">Buyer</label>
									<select name="buyer_id" id="buyer_id" class="form-control form-control-sm" required>
										<option value="">Select Buyer</option>
										@foreach($buyers as $buyer)
										<option value="{{ $buyer->id }}" {{ $buyer->id == $salesTarget->buyer_id ? 'selected' : null }}>{{ $buyer->name }}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<label for="buying_agent_id">Buying Agent</label>
									<select name="buying_agent_id" id="buying_agent_id" class="form-control form-control-sm" required>
										<option value="">Select Buying Agent</option>
										@foreach($buyingAgents as $agent)
										<option value="{{ $agent->id }}" {{ $agent->id == $salesTarget->buying_agent_id ? 'selected' : null }}>{{ $agent->buying_agent_name }}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<label for="team_leader_id">Team Leader</label>
									<select name="team_leader_id" id="team_leader_id" class="form-control form-control-sm" required>
										<option value="">Select Team Leader</option>
										@foreach($leaders as $leader)
										<option value="{{ $leader['id'] }}" {{ $leader['id'] == $salesTarget->team_leader_id ? 'selected' : null }}>{{ $leader['name'] }}</option>
										@endforeach
									</select>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label for="month">Select Month</label>
									<select name="month" id="month" class="form-control form-control-sm" required>
										<option value="">Select Month</option>
										@foreach($months as $month)
										<option value="{{ $month }}" {{ $month == $salesTarget->month ? 'selected' : null }}>{{ $month }}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label for="year">Select Year</label>
									<select name="year" id="year" class="form-control form-control-sm" required>
										<option value="">Select Year</option>
										@for($i=1; $i < 16; $i++) <option value="{{ 2020 + $i }}" {{ (2020 + $i) == $salesTarget->year ? 'selected' : null }}>{{ 2020 + $i }}</option>
											@endfor
									</select>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<table class="table">
									<thead>
										<tr>
											<th style="text-align: left; padding-left: 3px;">Month</th>
											<th>Quantity Target</th>
											<th>Quantity Value</th>
											<th>Currency</th>
										</tr>
									</thead>
									<tbody>
										@foreach($months as $month)
										<tr>
											<td style="text-align: left;">
												<div class="form-group">
													<p>{{ $month }}</p>
													<input type="hidden" value="{{ $month }}" name="months[]">
												</div>
											</td>
											<td>
												<div class="form-group">
													<input type="number" step=".1" name="targets[]" value="{{ $salesTarget->details->where('month', $month)->first()->target }}" class="form-control form-control-sm form-control form-control-sm-sm" placeholder="Q. Target">
												</div>
											</td>
											<td>
												<div class="form-group">
													<input type="number" step=".1" name="values[]" value="{{ $salesTarget->details->where('month', $month)->first()->value }}" class="form-control form-control-sm form-control form-control-sm-sm" placeholder="Q. Value">
												</div>
											</td>
											<td>
												<div class="form-group">
													<select name="currency[]" class="form-control form-control-sm" style="height: 32px;">
														<option value="">Select Currency</option>
														@foreach($currencies as $currency)
														<option value="{{ $currency->id }}" {{ $currency->id == $salesTarget->details->where('month', $month)->first()->currency_id ? 'selected' : null }}>{{ $currency->currency_name }}</option>
														@endforeach
													</select>
												</div>
											</td>
										</tr>
										@endforeach
										<tr>
											<td colspan="4">
												<div class="form-group">
													<button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-save"></i> Update</button>
													<a class="btn btn-sm btn-danger" href="{{ url('sales-target-determination') }}"><i class="fa fa-close"></i> Cancel</a>
												</div>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
						{{-- <div class="row m-t-2">--}}
						{{-- <div class="col-sm-10 col-sm-offset-1">--}}
						{{-- <div class="row">--}}
						{{-- <div class="col-sm-3">--}}
						{{-- <p class="text-center">Month</p>--}}
						{{-- </div>--}}
						{{-- <div class="col-sm-3">--}}
						{{-- <p class="text-center">Quantity Target</p>--}}
						{{-- </div>--}}
						{{-- <div class="col-sm-3">--}}
						{{-- <p class="text-center">Quantity Value</p>--}}
						{{-- </div>--}}
						{{-- <div class="col-sm-3">--}}
						{{-- <p class="text-center">Currency</p>--}}
						{{-- </div>--}}
						{{-- </div>--}}
						{{-- </div>--}}
						{{-- <div class="col-sm-10 col-sm-offset-1">--}}
						{{-- @foreach($months as $month)--}}
						{{-- <div class="row">--}}
						{{-- <div class="col-sm-3">--}}
						{{-- <div class="form-group">--}}
						{{-- <p>{{ $month }}</p>--}}
						{{-- <input type="hidden" value="{{ $month }}" name="months[]">--}}
						{{-- </div>--}}
						{{-- </div>--}}

						{{-- <div class="col-sm-3">--}}
						{{-- <div class="form-group">--}}
						{{-- <input type="number" step=".1" name="targets[]" value="{{ $salesTarget->details->where('month', $month)->first()->target }}" class="form-control form-control-sm form-control form-control-sm-sm" placeholder="Q. Target">--}}
						{{-- </div>--}}
						{{-- </div>--}}

						{{-- <div class="col-sm-3">--}}
						{{-- <div class="form-group">--}}
						{{-- <input type="number" step=".1" name="values[]" value="{{ $salesTarget->details->where('month', $month)->first()->value }}" class="form-control form-control-sm form-control form-control-sm-sm" placeholder="Q. Value">--}}
						{{-- </div>--}}
						{{-- </div>--}}

						{{-- <div class="col-sm-3">--}}
						{{-- <div class="form-group">--}}
						{{-- <select name="currency[]" class="form-control form-control-sm">--}}
						{{-- <option value="">Select Currency</option>--}}
						{{-- @foreach($currencies as $currency)--}}
						{{-- <option value="{{ $currency->id }}" {{ $currency->id == $salesTarget->details->where('month', $month)->first()->currency_id ? 'selected' : null }}>{{ $currency->currency_name }}</option>--}}
						{{-- @endforeach--}}
						{{-- </select>--}}
						{{-- </div>--}}
						{{-- </div>--}}
						{{-- </div>--}}
						{{-- @endforeach--}}
						{{-- <div class="row">--}}
						{{-- <div class="col">--}}
						{{-- <button type="submit" class="btn btn-success">Update</button>--}}
						{{-- <a class="btn btn-danger" href="{{ url('sales-target-determination') }}">Cancel</a>--}}
						{{-- </div>--}}
						{{-- </div>--}}
						{{-- </div>--}}
						{{-- </div>--}}
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection