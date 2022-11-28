@extends('skeleton::layout')
@section('title','Trims Issue')
@section('content')
    <div class="padding">
        <div class="box" >
            <div class="box-header">
                <h2>
                    Trims Issue List
                </h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-6">
                        <a href="{{ url('/inventory/trims-issue') }}" class="btn btn-sm white m-b"><i
                                class="fa fa-plus"></i> New Trims Issue</a>
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
                                <th>Issue ID</th>
                                <th>Year</th>
                                <th>Issue Basis</th>
                                <th>Challan No</th>
                                <th>Store</th>
                                <th>Location</th>
                                <th>Issue date</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($trimsIssue as $data)
                                <tr>
                                    <th>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</th>
                                    <td>{{$data->uniq_id}}</td>
                                    <td>--</td>
                                    <td>{{ $data->issue_basis}}</td>
                                    <td>{{ $data->issue_challan_no}}</td>
                                    <td>{{ $data->store->name}}</td>
                                    <td>{{ $data->location}}</td>
                                    <td>{{ $data->issue_date}}</td>
                                    <td style="padding: 2px">
                                        <a href="{{ url('/inventory/trims-issue/'.$data->id.'/edit') }}"
                                           class="btn btn-xs btn-warning">
                                            <i class="fa fa-edit"></i>
                                        </a>

                                        <a target="_blank" class="btn btn-xs btn-info"
                                           href="{{ url('/inventory/trims-issue/'. $data->id . '/view') }}">
                                            <i class="fa fa-eye"></i>
                                        </a>

                                        <button style="margin-left: 2px;" type="button"
                                                class="btn btn-xs btn-danger show-modal"
                                                title="Delete Budget"
                                                data-toggle="modal"
                                                data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                ui-target="#animate"
                                                data-url="{{ url('/inventory-api/v1/trims-issues/'.$data->id. '/delete') }}">
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
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
