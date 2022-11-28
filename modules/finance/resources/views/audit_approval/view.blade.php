@extends('skeleton::layout')
@section('title', 'Fund Requisition')
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Audit Approval view
                    <a href="/finance/fund-requisition/audit-approval/print/{{collect($approval)->first()->requisition_id}}?date={{collect($approval)->first()->audit_date}}"
                       class="btn btn-xs pull-right"><i
                            class="fa fa-print"></i></a>
                </h2>
            </div>
            <div class="box-body b-t">
                @include('finance::audit_approval.view_body')
                <br>
                <div class="text-center">
                    <a href="/finance/fund-requisition/audit-approval" class="btn btn-sm btn-danger">Back</a>
                </div>
            </div>
        </div>
    </div>
@endsection
