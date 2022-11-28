@extends('skeleton::layout')
@section('title', 'Fund Requisition')
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Fund Requisition Report
                    <a href="/finance/fund-requisition/report/print/{{$requisition['id']}}"
                       class="btn btn-xs pull-right"><i
                            class="fa fa-print"></i></a>
                </h2>
            </div>
            <div class="box-body b-t">
                @include('finance::fund_requisition.report.view_body')
                <br>
                <div class="text-center">
                    <a href="/finance/fund-requisition/reports" class="btn btn-sm btn-danger">Back</a>
                </div>
            </div>
        </div>
    </div>
@endsection
