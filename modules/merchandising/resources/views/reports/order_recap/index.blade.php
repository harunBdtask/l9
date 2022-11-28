@extends('skeleton::layout')
@section("title") Order Recap Report @endsection
@push('style')
    <style>
        .grand-row {
            font-size: 16px !important;
            font-weight: bold;
        }
    </style>
@endpush
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header btn-info">
                <h2>Order Recap Report</h2>
                <div class="clearfix"></div>
            </div>

            <div class="box-body">
                {!! Form::open(['url' => 'order-recap-report', 'method' => 'get', 'id' => 'searchForm']) !!}
                <div class="row m-b">
                    <div class="col-sm-3">
                        <label>Buyer</label>
                        {!! Form::select('buyer_id', $buyers, request('buyer_id'), ['class' => 'buyer-select form-control select2-input', 'placeholder' => 'Select a Buyer']) !!}
                    </div>
                    <div class="col-sm-3">
                        <label>Month</label>
                        {!! Form::selectMonth('month',request()->month ?? date('n'),['class'=>'form-control']) !!}
                    </div>
                    <div class="col-sm-3">
                        <label>Year</label>
                        {!! Form::selectRange('year', date('Y',strtotime('5 years ago')), date('Y',strtotime('+5 years')),request()->year ?? date('Y'),['class'=>'form-control']) !!}
                    </div>
                    <div class="col-sm-3" style="margin-top:22px;">
                        <button type="submit" class="btn btn-info">Search</button>
                        <div class="pull-right" style="margin-top: 40px">
                            <button class="btn btn-xs btn-primary" id="print" type="button">
                                <i class="fa fa-file-pdf-o"></i>
                            </button>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}

                <div class="table-responsive report">
                    <div class="row">
                        <div class="col-md-12">
                            @include('merchandising::reports.order_recap.table')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).on('click', '#print', function () {
            $("#searchForm").attr('action', 'order-recap-report/pdf').submit();
        })
    </script>
@endsection
