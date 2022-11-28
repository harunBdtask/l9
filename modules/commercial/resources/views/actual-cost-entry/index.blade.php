@extends('skeleton::layout')
@section('title','Actual Cost Entry List')
@section('content')
    <div class="padding">
        <div class="box" >
            <div class="box-header">
                <h2>
                    Actual Cost Entry List
                </h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-6">
                        <a href="{{ url('commercial/actual-cost-entry/create') }}" class="btn btn-sm btn-info m-b"><i
                                class="fa fa-plus"></i> New Actual Cost</a>
                    </div>
                    <div class="col-sm-4 col-sm-offset-2">
{{--                        <form action="{{ url('commercial/export-invoice/') }}" method="GET">--}}
{{--                            <div class="input-group">--}}
{{--                                <input type="text" class="form-control form-control-sm" name="search"--}}
{{--                                       value="{{ $search ?? '' }}" placeholder="Search">--}}
{{--                                <span class="input-group-btn">--}}
{{--                                            <button class="btn btn-sm btn-info m-b" type="submit">Search</button>--}}
{{--                                        </span>--}}
{{--                            </div>--}}
{{--                        </form>--}}
                    </div>
                </div>
                @include('partials.response-message')
                <div class="row m-t">
                    <div class="col-sm-12">
                        <table class="reportTable">
                            <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Company</th>
                                <th>Cost Head</th>
                                <th>Incurred Date From</th>
                                <th>Incurred Date To</th>
                                <th>Applying Period	From</th>
                                <th>Applying Period	To</th>
                                <th>Amount</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($actualCosts as $key => $cost)
                                <tr>
                                    <th>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</th>
                                    <td>{{ $cost->company->factory_name }}</td>
                                    <td>{{ collect($costHead)->firstWhere('id',$cost->cost_head_id)['text']}}</td>
                                    <td>{{ $cost->incurred_date_from}}</td>
                                    <td>{{ $cost->incurred_date_to}}</td>
                                    <td>{{ $cost->applying_period_from}}</td>
                                    <td>{{ $cost->applying_period_to}}</td>
                                    <td>{{ $cost->amount}}</td>

                                    <td style="padding: 2px">
                                        <a href="{{ url('/commercial/actual-cost-entry/create?actual_cost_id=') . $cost->id }}"
                                           class="btn btn-xs btn-warning">
                                            <i class="fa fa-edit"></i>
                                        </a>

{{--                                        <a target="_blank" class="btn btn-xs btn-info"--}}
{{--                                           href="{{ url('/commercial/actual-cost-entry/'. $cost->id . '/view') }}">--}}
{{--                                            <i class="fa fa-eye"></i>--}}
{{--                                        </a>--}}

                                        <button style="margin-left: 2px;" type="button"
                                                class="btn btn-xs btn-danger show-modal"
                                                title="Delete Budget"
                                                data-toggle="modal"
                                                data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                ui-target="#animate"
                                                data-url="{{ url('/commercial/actual-cost-entry/'.$cost->id) }}">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <th colspan="9">No Data Found</th>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row m-t">
                    <div class="col-sm-12">
                        {{ $actualCosts->appends(request()->query())->links()  }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
