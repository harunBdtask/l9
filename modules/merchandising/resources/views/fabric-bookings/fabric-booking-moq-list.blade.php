@extends('skeleton::layout')
@section('title','Fabric Booking')

@section('styles')
    {{-- <style>
        .table-header {
            background: #93dcf9;
        }
    </style> --}}
@endsection

@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>
                    Fabric Booking (MOQ Qty) List
                </h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-6">

                    </div>
                    <div class="col-sm-4 col-sm-offset-2">
                        <form action="{{ url('/fabric-bookings-moq-qty') }}" method="GET">
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm" name="search"
                                       value="{{ $search ?? '' }}" placeholder="Search">
                                <span class="input-group-btn">
                                            <button class="btn btn-sm btn-info m-b" type="submit">Search</button>
                                        </span>
                            </div>
                        </form>
                    </div>
                </div>
                @include('partials.response-message')
                <div class="row m-t">
                    <div class="col-sm-12 parentTableFixed" style="overflow-x: scroll;">
                        <table class="reportTable fixTable">
                            <thead>
                            <tr class="table-header" style="background-color: rgb(148, 218, 251);">
                                <th>Sl</th>
                                <th>Company Name</th>
                                <th>Buyer Name</th>
                                <th>Booking No</th>
                                <th>{{ localizedFor('Style') }} </th>
                                <th>Budget Unique Id</th>
                                <th>{{ localizedFor('PO') }} </th>
                                <th>Fabric Source</th>
                                <th>Booking Date</th>
                                <th>Delivery Date</th>
                                <th>Level</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($fabricBookings as $fabricBooking)
                                <tr style="{{ $fabricBooking->detailsBreakdown()->count() === 0 ? 'background: #c7c7c7' : '' }}">
                                    <td>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</td>
                                    <td>{{ $fabricBooking->factory->factory_name }}</td>
                                    <td class="text-left">{{ $fabricBooking->buyer->name }}</td>
                                    <td>{{ $fabricBooking->unique_id }}</td>
                                    <td>{{ $fabricBooking->style_name }}</td>
                                    <td>{{ $fabricBooking->budget_job_no }}</td>
                                    <td>{{ $fabricBooking->po_no }}</td>
                                    <td>{{ $fabricBooking->fabric_source_name }}</td>
                                    <td>{{ $fabricBooking->booking_date }}</td>
                                    <td>{{ $fabricBooking->delivery_date }}</td>
                                    <td>{{ $fabricBooking->level_name }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <th colspan="11">No Data Found</th>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row m-t">
                    <div class="col-sm-12">
                        {{ $fabricBookings->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
