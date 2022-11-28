@extends('skeleton::layout')
@section('title','Roll Wise Fabric Delivery')
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>
                    Roll Wise Fabric Delivery List
                </h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-6">
                        <a href="{{ url('/knitting/roll-wise-fabric-delivery/create') }}" class="btn btn-sm btn-info m-b">
                            <i class="fa fa-plus"></i> New Roll Wise Fabric Delivery
                        </a>
                    </div>
                </div>
                @include('inventory::partials.flash')

                @include('skeleton::partials.dashboard',['dashboardOverview'=>$dashboardOverview])

                @include('skeleton::partials.table-export')

                    <div class="col-sm-12" style="padding-top:20px">
                        <div class="table-responsive">
                            <table class="reportTable-zero-padding">
                                <thead>
                                <tr style="background: #0ab4e6;">
                                    <th>SL</th>
                                    <th>Challan No</th>
                                    <th>Company Name</th>
                                    <th>Book. Company</th>
                                    <th>Booking Type</th>
                                    <th>Buyer Name</th>
                                    <th>Delivery Date</th>
                                    <th>Delivery Qty</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(isset($challan_list))
                                    @if(!$challan_list->getCollection()->isEmpty())
                                        @foreach($challan_list->getCollection() as $challan)
                                            @php
                                                $firstChallanDetail = $challan->challanDetails->first();
                                                $book_company = $firstChallanDetail ? ($firstChallanDetail->knittingProgram->knittingParty ? ($firstChallanDetail->knittingProgram->knittingParty->factory_name ?? $firstChallanDetail->knittingProgram->knittingParty->name): '') : '';
                                                $buyer = $challan->challanDetails->unique('planningInfo.buyer_name')->values()->implode('planningInfo.buyer_name', ', ');
                                                $bookingType = $firstChallanDetail ? ($firstChallanDetail->planningInfo->booking_type) : null;
                                            @endphp
                                            <tr class="tooltip-data row-options-parent">
                                                <td>{{ str_pad($loop->iteration + $challan_list->firstItem() - 1, 2, '0', STR_PAD_LEFT) }}</td>
                                                <td>{{ $challan->challan_no }}
                                                    <br>
                                                    <div class="row-options" style="display:none ">

                                                        @permission('permission_of_roll_wise_fabric_delivery_edit')
                                                            <a href="{{ url('/knitting/roll-wise-fabric-delivery/'.$challan->challan_no.'/edit') }}"
                                                               title="Edit"
                                                                class="text-warning edit"><i
                                                                    class="fa fa-edit"></i></a>
                                                            @endpermission

                                                            @permission('permission_of_roll_wise_fabric_delivery_delete')
                                                            <a href="{{ url('/knitting/roll-wise-fabric-delivery/'.$challan->id.'/delete') }}" class="text-danger show-modal"
                                                                    data-toggle="modal" data-target="#confirmationModal"
                                                                    ui-toggle-class="flip-x" ui-target="#animate"
                                                                    title="Delete"
                                                                    data-url="{{ url('/knitting/roll-wise-fabric-delivery/'.$challan->id.'/delete') }}">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                            @endpermission
                                                </td>
                                                <td>{{ $challan->factory->factory_name }}</td>
                                                <td>{{ $book_company }}</td>
                                                <td style="text-transform: capitalize">{{ $bookingType }}</td>
                                                <td>{{ $buyer }}</td>
                                                <td>{{ $challan->challan_date }}</td>
                                                <td>{{ $challan->delivery_qty }}</td>

                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <th class="text-center" colspan="8">No Data Found</th>
                                        </tr>
                                    @endif
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row m-t">
                    <div class="col-sm-12">
                        @if(isset($challan_list) && $challan_list->total() > 15)
                            {{ $challan_list->appends(request()->except('page'))->links() }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
