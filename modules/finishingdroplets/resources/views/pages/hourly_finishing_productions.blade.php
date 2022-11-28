@extends('iedroplets::layout')
@section('title', 'Hour Wise Finishing target')
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header text-center">
                        <h2>Hour Wise Finishing Production</h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body" id="hour-wise-finishing-production-form">

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('/js/hour-wise-finishing-production.js') }}"></script>
@endsection
