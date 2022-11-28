@extends('skeleton::layout')
@section('title', 'Fund Requisition')
@section('content')
    <div class="padding">
        <div class="box">
            <form action="/finance/fund-requisition/{{$requisition->id}}/approve" id="approve-form" method="post">
                @csrf
            </form>
            <div class="box-header">
                <h2>Fund Requisition view
                    @if(!$requisition->is_approved)
                        <button class="btn btn-xs btn-success pull-right" onclick="submitApproveForm()">Approve
                        </button>
                    @endif
                    <a href="/finance/fund-requisition/{{$requisition->id}}/print" class="btn btn-xs pull-right"><i
                            class="fa fa-print"></i></a>
                </h2>
            </div>
            <div class="box-body b-t">
                @include('finance::fund_requisition.view_body')
                <br>
                <div class="text-center">
                    <a href="/finance/fund-requisition" class="btn btn-sm btn-danger">Back</a>
                </div>
            </div>
        </div>
    </div>
    <script>
        function submitApproveForm() {
            $("#approve-form").submit();
        }
    </script>
@endsection
