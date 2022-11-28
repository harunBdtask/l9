@extends('skeleton::layout')
@section("title","Sample Booking | Confirm Order")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Sample Booking Before Order & R&D</h2>
            </div>
            <div class="box-body b-t">
                <a class="btn btn-sm btn-info m-b" href="{{ url('sample-booking-for-before-order/create') }}">
                    <i class="glyphicon glyphicon-plus"></i> New Booking
                </a>
                <div class="pull-right">
                </div>
            </div>
            @include('partials.response-message')
            @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                @if(Session::has('alert-' . $msg))
                    <p class="alert alert-{{ $msg }} text-center">{{ Session::get('alert-' . $msg) }}</p>
                @endif
            @endforeach
            <br>
            @include('skeleton::partials.dashboard', ['dashboardOverview'=>$dashboardOverview])

            @include('skeleton::partials.table-export')
            <div class="col-md-12" style="overflow-x: scroll;">
                <table class="reportTable">
                    <thead>
                    <tr>
                        <th>SL</th>
                        <th>Company</th>
                        <th>Buyer</th>
                        <th>Fabric Nature</th>
                        <th>Fabric Source</th>
                        <th>Booking No</th>
                        <th>Booking Date</th>
                        <th>Supplier</th>
                        <th>Pay Mode</th>
                        <th>Style</th>
                        <th>Approved</th>
                        <th>Is Short</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($bookings as $booking)
                        <tr class="tooltip-data row-options-parent">
                            <td data-col="1">{{ ++$loop->index }}</td>
                            <td data-col="2">{{ $booking->factory->group_name }}</td>
                            <td data-col="3">{{ $booking->buyer->name }}
                                <br>
                                <div class="row-options" style="display:none ">
                                    <a class="text-primary" href="{{ url("sample-booking-for-before-order/$booking->id/edit") }}" title="Edit">
                                        <i class="fa fa-edit" style="color:#0275d8"></i>
                                    </a>
                                </div>
                            
                            </td>
                            <td data-col="4">{{ $booking->fabricNature->name }}</td>
                            <td data-col="5">{{ $booking->fabric_source_value }}</td>
                            <td data-col="6">{{ $booking->booking_no }}</td>
                            <td data-col="7">{{ $booking->booking_date }}</td>
                            <td data-col="8">{{ $booking->supplier->name }}</td>
                            <td data-col="9">{{ $booking->pay_mode_value }}</td>
                            <td data-col="10">{{ $booking->style_name }}</td>
                            <td data-col="11">
                                @if($booking->ready_to_approve == 1)
                                    Yes
                                @elseif($booking->ready_to_approve == 2)
                                    No
                                @endif
                            </td>
                            <td data-col="12">
                                @if($booking->is_short == 1)
                                    Yes
                                @elseif($booking->is_short == 2)
                                    No
                                @endif
                            </td>
                          
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
@endsection
