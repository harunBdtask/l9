@extends('subcontract::layout')
@section("title","Sub Grey Fabric Transfer")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Sub Grey Fabric Transfer</h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        @include('partials.response-message')
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 m-b">
                        <a href="{{ url('subcontract/material-fabric-transfer/create') }}"
                           class="btn btn-sm btn-info m-b">
                            <em class="fa fa-plus"></em>&nbsp;Create
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="reportTable">
                            <thead>
                            <tr>
                                <th>SL</th>
                                <th>Transfer Challan No</th>
                                <th>Unique Id</th>
                                <th>Order No</th>
                                <th>Operation</th>
                                <th>Body Part</th>
                                <th>Fabric Composition</th>
                                <th>Fabric Type</th>
                                <th>Fin Dia</th>
                                <th>GSM</th>
                                <th>Total Roll</th>
                                <th>Transfer Qty</th>
                                <th>Ready To Approve</th>
                                <th>Remarks</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $index = 1;
                            @endphp
                            @forelse($fabricTransfer as $transfer)
                                @foreach($transfer->details as $detail)
                                    <tr>
                                        @php
                                            $rowCount = $transfer->details->count();
                                        @endphp
                                        @if($loop->first)
                                            <td rowspan="{{ $rowCount }}">{{ $index++ }}</td>
                                            <td rowspan="{{ $rowCount }}">{{ $transfer->challan_no }}</td>
                                            <td rowspan="{{ $rowCount }}">{{ $transfer->transfer_uid }}</td>
                                        @endif
                                        <td>{{ $detail->toOrderDetail->order_no ?? '' }}</td>
                                        <td>{{ $detail->toOrderDetail->subTextileOperation->name ?? '' }}</td>
                                        <td>{{ $detail->toOrderDetail->bodyPart->name ?? '' }}</td>
                                        <td>{{ $detail->toOrderDetail->fabric_composition_value ?? '' }}</td>
                                        <td>{{ $detail->toOrderDetail->fabricType->construction_name ?? '' }}</td>
                                        <td>{{ $detail->toOrderDetail->finish_dia ?? '' }}</td>
                                        <td>{{ $detail->toOrderDetail->gsm ?? '' }}</td>
                                        <td>{{ $detail->detailMSI->to_total_roll ?? '' }}</td>
                                        <td>{{ $detail->detailMSI->transfer_qty }}</td>
                                        <td>
                                            @if($transfer->ready_to_approve == 1)
                                                <span>Yes</span>
                                            @else
                                                <span>No</span>
                                            @endif
                                        </td>
                                        <td>{{ $transfer->remarks }}</td>
                                        @if($loop->first)
                                            <td rowspan="{{ $rowCount }}">
                                                <a class="btn btn-xs btn-info" type="button"
                                                   href="{{ url('subcontract/material-fabric-transfer/create?id='. $transfer->id) }}">
                                                    <em class="fa fa-pencil"></em>
                                                </a>
                                                <a class="btn btn-success btn-xs" type="button"
                                                   href="{{ url('subcontract/material-fabric-transfer/view/'.$transfer->id) }}">
                                                    <em class="fa fa-eye"></em>
                                                </a>
                                                <button style="margin-left: 2px;" type="button"
                                                        class="btn btn-xs btn-danger show-modal"
                                                        title="Delete Order"
                                                        data-toggle="modal"
                                                        data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                        ui-target="#animate"
                                                        data-url="{{ url('subcontract/material-fabric-transfer/'. $transfer->id) }}">
                                                    <em class="fa fa-trash"></em>
                                                </button>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            @empty
                                <tr>
                                    <td colspan="11" align="center">No Data</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                        <div class="text-center">
                            {{ $fabricTransfer->appends(request()->except('page'))->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->


    <style>
        /*.custom-field {*/
        /*    */
        /*    */
        /*}*/
    </style>
@endsection

