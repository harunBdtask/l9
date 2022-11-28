@extends('skeleton::layout')
@section('title','Sample Trims Booking')

@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>
                    Sample Trims Booking List
                </h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-6">
                        <a href="{{ url('/sample-trims-booking/create') }}" class="btn btn-sm btn-info m-b"><i
                                class="fa fa-plus"></i>
                            New Sample Trims Booking</a>
                    </div>
                </div>
                <br>
                @include('skeleton::partials.dashboard', ['dashboardOverview'=>$dashboardOverview])

                @include('skeleton::partials.row-number',['allExcel'=>'true','noExport'=>'true'])
                <div class="row m-t">
                    <div class="col-sm-12 " style="overflow-x: scroll;">
                        <table class="reportTable ">
                            <thead>
                            <tr class="table-header">
                                <th>Sl</th>
                                <th>Booking No</th>
                                <th>Company Name</th>
                                <th>Buyer Name</th>
                                <th>Location</th>
                                <th>Supplier</th>
                                <th>Booking Date</th>
                                <th>Delivery Date</th>
                                <th>Material Source</th>
                                <th>Pay Mode</th>
                                <th>Source</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($bookings as $booking)
                                <tr class="tooltip-data row-options-parent">
                                    <td>{{ str_pad($loop->iteration + $bookings->firstItem() - 1, 2, '0', STR_PAD_LEFT) }}</td>
                                    <td>{{ $booking->booking_no }}
                                        <br>
                                        <div class="row-options" style="display:none ">
                                  
                                            <a class="text-warning"
                                            href="{{ url('sample-trims-booking/'.$booking->id.'/edit') }}">
                                                <i class="fa fa-edit" style="color:#f0ad4e"></i>
                                            </a>
                                            <span>|</span>
                                            <a class="text-success"
                                            href="{{ url('sample-trims-booking/'.$booking->id.'/view') }}">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </div>
                                   
                                    </td>
                                    <td>{{ $booking->factory->factory_name ?? '' }}</td>
                                    <td class="text-left">{{ $booking->buyer->name ?? '' }}</td>
                                    <td>{{ $booking->location }}</td>
                                    <td class="text-left">{{ $booking->supplier->name ?? '' }}</td>
                                    <td>{{ $booking->booking_date }}</td>
                                    <td>{{ $booking->delivery_date }}</td>
                                    <td>{{ $booking->material_source_value }}</td>
                                    <td>{{ $booking->pay_mode_value }}</td>
                                    <td>{{ $booking->source_value }}</td>
                                   
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
                        {{ $bookings->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')

    <script>
        $("#selectOption").change(function(){
        var selectBox = document.getElementById("selectOption");
        var selectedValue = (selectBox.value);
        if (selectedValue == -1){
            if(window.location.href.indexOf("search") != -1){
                selectedValue = {{$searchedOrders}};
        }
            else{
                selectedValue = {{$dashboardOverview["Total Bookings"]}};
            }
        }
        let url = new URL(window.location.href);
        url.searchParams.set('paginateNumber',parseInt(selectedValue));
        window.location.replace(url);
        });
    </script>

@endsection