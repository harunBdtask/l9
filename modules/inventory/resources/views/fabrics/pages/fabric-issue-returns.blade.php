@extends('skeleton::layout')
@section('title','Fabric Issue Return')
@section('content')
    <div class="padding">
        <div class="box" >
            <div class="box-header">
                <h2>
                    Fabric Fabric Issue Return List
                </h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-6">
                        <a href="{{ url('/inventory/fabric-issue-returns/create') }}" class="btn btn-sm btn-info m-b"><i
                                class="fa fa-plus"></i> New Fabric Issue Return</a>
                    </div>
                <!-- <div class="col-sm-4 col-sm-offset-2">
                        <form action="{{ url('/commercial/btb-margin-lc/') }}" method="GET">
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm" name="search"
                                       value="{{ $search ?? '' }}" placeholder="Search">
                                <span class="input-group-btn">
                                            <button class="btn btn-sm white m-b" type="submit">Search</button>
                                        </span>
                            </div>
                        </form>
                    </div> -->
                </div>
                @include('partials.response-message')
                <div class="row m-t">
                    <div class="col-sm-12">
                        <table class="reportTable">
                            <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Issue Return No</th>
                                <th>Return Date</th>
                                <th>Challan No</th>
                                <th>Factory</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($issueReturns as $issueReturn)
                                <tr>
                                    <th>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</th>
                                    <td>{{ $issueReturn->issue_return_no }}</td>
                                    <td>{{ $issueReturn->return_date }}</td>
                                    <td>{{ $issueReturn->challan_no }}</td>
                                    <td>{{ $issueReturn->factory->factory_name }}</td>
                                    <td style="padding: 2px">
                                        <a href="{{ url('/inventory/fabric-issue-returns/'.$issueReturn->id.'/edit') }}"
                                           class="btn btn-xs btn-warning">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <button style="margin-left: 2px;" type="button"
                                                class="btn btn-xs btn-danger show-modal" title="Delete Budget"
                                                data-toggle="modal" data-target="#confirmationModal"
                                                ui-toggle-class="flip-x"
                                                ui-target="#animate"
                                                data-url="{{ url('/inventory-api/v1/fabric-issue-returns/'.$issueReturn->id. '/delete') }}">
                                            <i class="fa fa-trash"></i>
                                        </button>
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
                        @if(count($issueReturns))
                            {{ $issueReturns->render() }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
