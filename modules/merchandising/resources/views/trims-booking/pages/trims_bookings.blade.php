@extends('skeleton::layout')

@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Trims Requisition List</h2>
            </div>

            <div class="box-body b-t">
                @if($po_id)
                <a class="btn btn-sm white m-b" href="{{ url('purchase-order/trims-requisition?purchase_order_id='.$po_id) }}">
                    <i class="glyphicon glyphicon-plus"></i>New Tirms Requisition
                </a>
                @endif
                <div class="pull-right">
                    <form action="{{ url('purchase-order/search-trims-requisition') }}" method="get">
                        <input type="hidden" name="po_id" value="{{ $po_id ?? '' }}">
                        <div class="pull-left" style="margin-right: 10px;">
                            <input type="text" class="form-control form-control-sm" name="q" value="{{ $q ?? '' }}" placeholder="Enter Requisition No.">
                        </div>
                        <div class="pull-right">
                            <input type="submit" class="btn btn-sm white" value="Search">
                        </div>
                    </form>
                </div>
            </div>
            <br/>
            <hr>
            @include('partials.response-message')
            <table class="reportTable">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>PO No.</th>
                        <th>Requisition No.</th>
                        <th>
                            <table style="border: none !important;">
                                <tr>
                                    <th width="8%">Item Group</th>
                                    <th width="8%">Item</th>
                                    <th width="8%">Amount</th>
                                    <th width="10%">Description</th>
                                    <th width="8%">Brand</th>
                                    <th width="8%">Nominated Supply</th>
                                    <th width="8%">Apvl. Rqst.</th>
                                </tr>
                            </table>
                        </th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @if($trims_bookings && !$trims_bookings->getCollection()->isEmpty())
                    @foreach($trims_bookings->getCollection()->groupBy('requisition_no') as $tbookingData)
                        @php
                           $trimsReq =  $tbookingData->first();
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $trimsReq->po->order_no }}</td>
                            <td>{{ $trimsReq->requisition_no }}</td>
                            <td>
                                <table class="reportTable">
                                    @foreach($tbookingData as $tbooking)
                                        <tr style="height: 25px">
                                            <td width="8%">{{ $tbooking->itemsGroup->item_group_name ?? '' }}</td>
                                            <td width="8%">{{ $tbooking->item->item_name ?? '' }}</td>
                                            <td width="8%">{{ $tbooking->amount }} {{ $tbooking->item->uom_data->unit_of_measurements ?? '' }}</td>
                                            <td width="10%">{{ $tbooking->item->item_desc ?? '' }}</td>
                                            <td width="8%">{{ $tbooking->brand->brand_name ?? '' }}</td>
                                            <td width="8%">{{ $tbooking->nominatedSupplier->supplier_name ?? '' }}</td>
                                            <td width="8%">{{ IS_APPROVED[$tbooking->is_approved] }}</td>
                                        </tr>
                                    @endforeach
                                </table>
                            </td>
                            <td>
                                @if(Session::has('permission_of_trims_booking_edit') || getRole() == 'super-admin')
                                    <a class="btn btn-sm white" href="{{ url('trims-booking/'.$trimsReq->requisition_no.'/edit') }}"><i class="fa fa-edit"></i></a>
                                @endif
                                @if(Session::has('permission_of_trims_booking_delete') || getRole() == 'super-admin')
                                    <button type="button" class="btn btn-sm white show-modal" data-toggle="modal" data-target="#confirmationModal" ui-toggle-class="flip-x" ui-target="#animate" data-url="{{ url('trims-booking/'.$trimsReq->requisition_no) }}">
                                        <i class="fa fa-times"></i>
                                    </button>
                                @endif
                                {{--
                                @if(Session::has('permission_of_trims_booking') || getRole() == 'super-admin')
                                    <a class="btn btn-sm white" href="{{ url('trims-booking/'.$trimsReq->requisition_no.'/report') }}"><i class="fa fa-print"></i></a>
                                @endif
                                --}}
                            </td>

                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="9" align="center" class="text-danger">No Data
                        <td>
                    </tr>
                @endif
                </tbody>
                <tfoot>
                @if($trims_bookings && $trims_bookings->total() > 15)
                    <tr>
                        <td colspan="9" align="center">{{ $trims_bookings->appends(request()->except('page'))->links() }}</td>
                    </tr>
                @endif
                </tfoot>
            </table>
        </div>
    </div>
@endsection
