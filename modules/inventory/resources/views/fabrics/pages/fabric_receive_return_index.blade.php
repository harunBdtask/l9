@extends('skeleton::layout')
@section('title','Fabric Receive Return')
@section('content')
<div class="padding">
    <div class="box" >
        <div class="box-header">
            <h2>
                Fabric Receive Return List
            </h2>
        </div>

        <div class="box-body">
            <div class="row">
                <form method="GET" action="/inventory/fabric-receive-returns">
                <div class="col-sm-8">
                    <a href="{{ url('/inventory/fabric-receive-returns/create') }}" class="btn btn-sm btn-info m-b"><i
                            class="fa fa-plus"></i> New
                        Fabric Receive Return</a>
                </div>
                <div class="col-sm-2">
                    <input type="text" class="form-control" name="search" placeholder="Write Something"/>
                </div>
                <div class="col-sm-2">
                    <div class="btn-group">
                        <button id="filter-button" class="btn btn-sm btn-outline text-dark b-info">Filter</button>
                    </div>
                </div>
                </form>
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
                                <th>Receive Return No</th>
                                <th>Receive Return Date</th>
                                <th>Return To</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($return as $data)
                            <tr>
                                <th>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</th>
                                <td>{{$data->receive_return_no}}</td>
                                <td>{{$data->return_date}}</td>
                                <td>{{$data->store->name}}</td>


                                <td style="padding: 2px">
                                    <a href="{{ url('/inventory/fabric-receive-returns/'.$data->id.'/edit') }}"
                                        class="btn btn-xs btn-warning">
                                        <i class="fa fa-edit"></i>
                                    </a>

                                    <!-- <a target="_blank" class="btn btn-xs btn-info"
                                           href="{{ url('/inventory/trims-issue/'. $data->id . '/view') }}">
                                            <i class="fa fa-eye"></i>
                                        </a> -->

                                    <!-- <button style="margin-left: 2px;" type="button"
                                        class="btn btn-xs btn-danger show-modal" title="Delete Budget"
                                        data-toggle="modal" data-target="#confirmationModal" ui-toggle-class="flip-x"
                                        ui-target="#animate"
                                        data-url="{{ url('/inventory-api/v1/trims-issues/'.$data->id. '/delete') }}">
                                        <i class="fa fa-trash"></i>
                                    </button> -->
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
