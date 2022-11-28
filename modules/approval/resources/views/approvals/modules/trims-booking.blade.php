@extends('skeleton::layout')
@section('title','Approval | Trims Booking')
@section('content')

    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>
                    Trims Booking Approval
                </h2>
            </div>

            <div class="box-body">
                <div class="box-body" id="element">

                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('/js/trims-booking-permission.js') }}"></script>
@endsection
