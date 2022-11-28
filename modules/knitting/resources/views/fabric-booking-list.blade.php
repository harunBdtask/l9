@extends('skeleton::layout')
@section('title','Fabric Booking List')

@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>
                    Fabric Booking List
                </h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-4"></div>
                    <div class="col-sm-8">
                        <form action="{{ url('/knitting/fabric-booking-list') }}" class="form-inline pull-right" method="GET">
                            <div class="form-group">
                                <div class="input-group">
                                    <select name="type" class="form-control form-control">
                                        <option value="fabric" @if(request('type') == 'fabric') selected @endif>
                                            Fabric Booking
                                        </option>
                                        <option value="sample" @if(request('type') == 'sample') selected @endif>
                                            Sample Fabric
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="text"
                                        class="form-control form-control-sm"
                                        name="q"
                                        value="{{ request('q')?request('q'): '' }}" placeholder="Search">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <button class="btn btn-sm btn-info" type="submit">Search</button>
                                    <a href="{{ url('/knitting/fabric-booking-list') }}" class="btn btn-sm btn-danger">Clear</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                @include('partials.response-message')
                @include('skeleton::partials.dashboard',$dashboardOverview)
                @include('skeleton::partials.table-export')

                <div class="col-sm-12" style="padding-top:20px">
                    <table class="reportTable">
                        <thead>
                        <tr class="table-header">
                            <th>SL</th>
                            <th>Type</th>
                            <th>Booking No</th>
                            <th>Buyer</th>
                            <th>Style</th>
                            <th>Booking Date</th>
                            <th>Delivery Date</th>
                            <th>Created By</th>
                            <th>Supplier</th>
                        </tr>
                        </thead>

                        <tbody>
                        @forelse($data as $key => $fabricBooking)
                            <tr class="tooltip-data row-options-parent"
                                @if($fabricBooking['fabric_sales_order_count'] > 0)
                                    style="background: #94dafb54"
                                @endif
                                >
                                <td>{{ str_pad($loop->iteration + $pagination->firstItem() - 1, 2, '0', STR_PAD_LEFT) }}</th>
                                <td>{{ $fabricBooking['booking_type'] }}</td>
                                <td>
                                    {{ $fabricBooking['booking_no'] }}
                                    @if(date('d-M-Y') == $fabricBooking['created_at'])
                                        <strong class="label bg-danger">New</strong>
                                    @endif
                                    <br>
                                    <div class="row-options" style="display:none">
                                        @permission('permission_of_fabric_booking_list_view')
                                        @if($fabricBooking['booking_type'] == 'Sample')
                                            <a href="{{ url('sample-management/order-requisition/fabric-booking-view/' . $fabricBooking['id']) }}"
                                               class="text-info"
                                               title="View Details"
                                               target="_blank">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        @elseif($fabricBooking['booking_type'] == 'Main')
                                            <a href="{{ url('fabric-bookings/' . $fabricBooking['id'].'/view') }}"
                                               class="text-info"
                                               title="View Details"
                                               target="_blank">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        @else
                                            <a href="{{ url('short-fabric-bookings/' . $fabricBooking['id'].'/summary-view?type=short') }}"
                                               class="btn btn-xs btn-info"
                                               title="View Details"
                                               target="_blank">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        @endif
                                        @endpermission
                                    </div>
                                </td>
                                <td>{{ $fabricBooking['buyer_value'] }}</td>
                                <td>{{ $fabricBooking['style_name'] }}</td>
                                <td>{{ $fabricBooking['booking_date'] }}</td>
                                <td>{{ $fabricBooking['delivery_date'] }}</td>
                                <td>{{ $fabricBooking['created_by'] }}</td>
                                <td>{{ $fabricBooking['supplier_value'] }}</td>
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
                    {{ $pagination->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
