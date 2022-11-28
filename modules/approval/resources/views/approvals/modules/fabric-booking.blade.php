@extends('skeleton::layout')
@section('title','Approval | Fabric Booking')
@section('content')

    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>
                    Fabric Booking Approval
                </h2>
            </div>

            <div class="box-body">
                <div class="box-body" id="element">

                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('/js/fabric-booking-permission.js') }}"></script>
@endsection
