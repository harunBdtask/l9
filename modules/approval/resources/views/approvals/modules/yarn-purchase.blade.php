@extends('skeleton::layout')
@section('title','Approval | Yarn Purchase')
@section('content')

    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>
                    Yarn Purchase Approval
                </h2>
            </div>

            <div class="box-body">
                <div class="box-body" id="element">

                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('/js/yarn-purchase-permission.js') }}"></script>
@endsection
