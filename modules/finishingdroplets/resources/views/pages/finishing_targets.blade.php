@extends('iedroplets::layout')
@section('title', 'Date wise Finishing target')
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header text-center">
                        <h2>Date Wise Finishing Target</h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body" id="finishing-targets-form">

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('/js/finishing-targets.js') }}"></script>
@endsection
