@extends('skeleton::layout')
@section('title','Fabric Receive')
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>
                    Fabric Receive List
                </h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <form method="GET" action="/inventory/fabric-receives">
                    <div class="col-sm-8">
                        <a href="{{ url('/inventory/fabric-receives/create') }}" class="btn btn-sm btn-info m-b"><i
                                class="fa fa-plus"></i> New
                            Fabric Receive</a>
                    </div>
                    <div class="col-sm-2">
                        <input type="text" class="form-control" name="search" placeholder="Write Something"
                        value="{{ request('search') }}"/>
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
                                <th>Buyer</th>
                                <th>Style</th>
                                <th>Receive Basis</th>
                                <th>Booking No</th>
                                <th>Receive No</th>
                                <th>Challan No.</th>
                                <th>Receive Date</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($receive as $data)
                                @php
                                    $buyers = collect($data->details)->pluck('buyer.name')->unique()->join(', ');
                                    $styles = collect($data->details)->pluck('style_name')->unique()->join(', ');
                                @endphp
                                <tr>
                                    <td>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</td>
                                    <td>{{ $buyers }}</td>
                                    <td>{{ $styles }}</td>
                                    <td>{{ $data->receive_basis }}</td>
                                    <td>{{ $data->booking->unique_id }}</td>
                                    <td>{{ $data->receive_no }}</td>
                                    <td>{{ $data->receive_challan  }}</td>
                                    <td>{{ $data->receive_date }}</td>

                                    <td style="padding: 2px">
                                        @if ($data->status == 0)
                                            <button style="margin-left: 2px;" type="button"
                                                    class="btn btn-xs btn-success show-modal" title="Approve"
                                                    data-toggle="modal" data-target="#approvalModal"
                                                    ui-toggle-class="flip-x"
                                                    ui-target="#animate"
                                                    data-url="{{ url('/inventory-api/v1/fabric-receives/'.$data->id.'/approve') }}">
                                                <i class="fa fa-check"></i>
                                            </button>

                                            <a href="{{ url('/inventory/fabric-receives/'.$data->id.'/edit') }}"
                                               class="btn btn-xs btn-warning">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        @endif

                                        @if (isset($variableSettings) && $variableSettings->barcode == 1 && $data->status != 0)
                                            <a target="_blank" class="btn btn-xs btn-info"
                                               href="{{ url('/inventory/fabric-receives/'. $data->id.'/barcodes') }}">
                                                <i class="fa fa-barcode"></i>
                                            </a>
                                        @endif

                                        <a target="_blank" class="btn btn-xs btn-info"
                                           href="{{ url('/inventory/fabric-receives/'. $data->id.'/view') }}">
                                            <i class="fa fa-eye"></i>
                                        </a>


                                        @if ($data->status == 0)
                                            <button style="margin-left: 2px;" type="button"
                                                class="btn btn-xs btn-danger show-modal" title="Delete Finish Fabric"
                                                data-toggle="modal" data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                ui-target="#animate"
                                                data-url="{{ url('/inventory/fabric-receives/'.$data->id. '/delete') }}">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        @endif

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
                    <div class="col-sm-12 text-center">
                        {{ $receive->appends(request()->except('page'))->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
