@extends('skeleton::layout')
@section('title','Fabric Issue')
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>
                    Fabric Issue List
                </h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-8">
                        <a href="{{ url('/inventory/fabric-issues/create') }}" class="btn btn-sm btn-info m-b"><i
                                class="fa fa-plus"></i> New Fabric Issue</a>
                    </div>
                    <form class="col-sm-4" action="{{ url('inventory/fabric-issues') }}" method="GET">
                        <div class="input-group">
                            <input type="text" class="form-control form-control-sm" id="search"
                                   name="search"
                                   value="{{ request('search') }}" placeholder="Search">
                            <span class="input-group-btn">
                                <button class="btn btn-sm btn-info m-b" type="submit">Search</button>
                            </span>
                        </div>
                    </form>
                </div>
                @include('partials.response-message')
                <div class="row m-t">
                    <div class="col-sm-12">
                        <table class="reportTable">
                            <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Issue No</th>
                                <th>Issue Date</th>
                                <th>Challan No</th>
                                <th>Buyer</th>
                                <th>Service Company/Party</th>
                                <th>Service Source</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($issues as $issue)
                                <tr>
                                    <th>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</th>
                                    <td>{{ $issue->issue_no }}</td>
                                    <td>{{ $issue->issue_date }}</td>
                                    <td>{{ $issue->challan_no }}</td>
                                    <td>{{ $issue->buyer->name }}</td>
                                    <td>{{ $issue->serviceCompany->name }}</td>
                                    <td>{{ $issue->service_source }}</td>
                                    <td style="padding: 2px">
                                        @if ($issue->status == 0)
                                            <button style="margin-left: 2px;" type="button"
                                                    class="btn btn-xs btn-success show-modal" title="Approve"
                                                    data-toggle="modal" data-target="#approvalModal"
                                                    ui-toggle-class="flip-x"
                                                    ui-target="#animate"
                                                    data-url="{{ url('/inventory-api/v1/fabric-issues/'.$issue->id.'/approve') }}">
                                                <i class="fa fa-check"></i>
                                            </button>
                                            <a href="{{ url('/inventory/fabric-issues/'.$issue->id.'/edit') }}"
                                               class="btn btn-xs btn-warning">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <button style="margin-left: 2px;" type="button"
                                                    class="btn btn-xs btn-danger show-modal" title="Delete Budget"
                                                    data-toggle="modal" data-target="#confirmationModal"
                                                    ui-toggle-class="flip-x"
                                                    ui-target="#animate"
                                                    data-url="{{ url('/inventory-api/v1/fabric-issues/'.$issue->id. '/delete') }}">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        @endif
                                        <a target="_blank" class="btn btn-xs btn-info"
                                           href="{{ url('/inventory/fabric-issues/'. $issue->id.'/view') }}">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <th colspan="10">No Data Found</th>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row m-t">
                    <div class="col-sm-12">
                        @if(count($issues))
                            {{ $issues->render() }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
