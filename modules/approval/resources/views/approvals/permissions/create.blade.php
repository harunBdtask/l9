@extends('skeleton::layout')
@section('title','Approval - Permission')
@section('content')
    <div class="padding">
        <div class="box" >
            <div class="box-header">
                <h2>
                    Permission Create
                </h2>
            </div>

            <div class="box-body">
                <div class="box-body" id="element">

                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('/js/approval_permission.js') }}"></script>
@endsection
