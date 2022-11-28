@extends('skeleton::layout')
@section("title","Sample Booking | Confirm Order")
@section('content')
<div class="padding">
    <div class="box">
        <div class="box-header">
            <h2>Sample Booking Confirm Order</h2>
        </div>
        <div class="box-body b-t">
            <a class="btn btn-sm btn-info m-b" href="{{ url('sample-booking-for-confirm-order/create') }}">
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
        @include('skeleton::partials.dashboard', ['dashboardOverview'=>$dashboardOverview])

        @include('skeleton::partials.row-number',['allExcel'=>'true','noExport'=>'true'])
        <div class="col-md-12 parentTableFixed" style="overflow-x: scroll;">
            <table class="reportTable fixTable">
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
                        <th>{{ localizedFor('Style') }}</th>
                        <th>Approved</th>
                        <th>Is Short</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bookings as $booking)
                    <tr class="tooltip-data row-options-parent">
                        <td data-col="1">{{ ++$loop->index }}</td>
                        <td data-col="2">{{ $booking->factory->group_name }}</td>
                        <td data-col="3">{{ $booking->buyer->name }}</td>
                        <td data-col="4">{{ $booking->fabricNature->name }}</td>
                        <td data-col="5">{{ $booking->fabric_source_value }}</td>
                        <td data-col="6">{{ $booking->booking_no }}
                            <br>
                            <div class="row-options" style="display:none ">
                                <a class="text-success" href="{{ url("sample-booking-for-confirm-order/$booking->id/view") }}" title="View">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <span>|</span>
                                <a class="text-warning" href="{{ url("sample-booking-for-confirm-order/$booking->id/view?Rnd=yes") }}" title="View">
                                    <i class="fa fa-eye" style="color:#f0ad4e;"></i>
                                </a>
                                <span>|</span>
                                <a class="text-primary" href="{{ url("sample-booking-for-confirm-order/$booking->id/edit") }}" title="Edit">
                                    <i class="fa fa-edit" style="color:#0275d8"></i>
                                </a>
                            </div>

                        </td>
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
@section('scripts')

<script>
    $("#selectOption").change(function() {
        var selectBox = document.getElementById("selectOption");
        var selectedValue = (selectBox.value);
        if (selectedValue == -1) {
            if (window.location.href.indexOf("search") != -1) {
                selectedValue = {{ $searchedOrders }};
            } else {
                selectedValue = {{ $dashboardOverview["Total Confirmed Booking"] }};
            }
        }
        let url = new URL(window.location.href);
        url.searchParams.set('paginateNumber', parseInt(selectedValue));
        window.location.replace(url);
    });
</script>
@endsection
