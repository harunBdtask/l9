@extends('skeleton::layout')
@section('title','Approval | Cutting Qty')
@section('content')

    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>
                    Cutting Qty Approval
                </h2>
            </div>

            <div class="box-body">
                <div class="box-body" id="element">

                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('/js/cutting-qty-approval.js') }}"></script>
@endsection
