@extends('skeleton::layout')
@section('title', 'Cutting Quantity Request')
@section('content')

    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>
                    Cutting Quantity Request
                </h2>
            </div>

            <div class="box-body">
                <div class="box-body" id="element">

                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('/js/protracker/cuttingdroplets/cutting-quantity-request.js') }}"></script>
@endsection
