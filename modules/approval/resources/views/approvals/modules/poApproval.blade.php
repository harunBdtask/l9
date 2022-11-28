@extends('skeleton::layout')
@section('title','Approval | PO')

@section('style')
    <style>
        /*table, td, th {*/
        /*    border: 1px solid black;*/
        /*}*/

        /*table {*/
        /*    border-collapse: collapse;*/
        /*    width: 100%;*/
        /*}*/

        /*td {*/
        /*    text-align: center;*/
        /*}*/
    </style>
@endsection
@section('content')

    <div class="padding">
        <div class="box" >
            <div class="box-header">
                <h2>
                    PO Approval
                </h2>
            </div>

            <div class="box-body">
                <div class="box-body" id="element">

                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('/js/po_approval_for_budget.js') }}"></script>
@endsection
