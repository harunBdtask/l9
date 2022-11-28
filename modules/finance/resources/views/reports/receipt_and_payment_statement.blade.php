@extends('finance::layout')

@section('styles')
<style type="text/css">
    .addon-btn-primary {
        padding: 0;
        margin: 0px;
        background: #0275d8;
    }
    .addon-btn-primary:hover {
        background: #025aa5;
    }
    select.c-select {
        min-height: 2.375rem;
    }
    input[type=date].form-control form-control-sm, input[type=time].form-control form-control-sm, input[type=datetime-local].form-control form-control-sm, input[type=month].form-control form-control-sm {
        line-height: 1rem;
    }
</style>
@endsection

@section('content')
<div class="padding">
    <div class="box">
        <div class="box-header">
            <h2>Receipt and Payment Statement</h2>
        </div>
        <div class="box-body b-t">
            <h4>Content goes here</h4>
        </div>
    </div>
</div>
@endsection
