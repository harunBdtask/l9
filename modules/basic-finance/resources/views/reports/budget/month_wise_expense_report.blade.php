@extends('basic-finance::layout')
@section('title','Basic Finance')

@section('styles')
    <style type="text/css">
        .addon-btn-primary {
            padding: 0;
            margin: 0;
            background: #0275d8;
        }

        .addon-btn-primary:hover {
            background: #025aa5;
        }

        select.c-select {
            min-height: 2.375rem;
        }

        input[type=date].form-control, input[type=time].form-control, input[type=datetime-local].form-control, input[type=month].form-control {
            line-height: 1rem;
        }

        .reportTable th.text-left, .reportTable td.text-left {
            text-align: left;
            padding-left: 5px;
        }

        .reportTable th.text-right, .reportTable td.text-right {
            text-align: right;
            padding-right: 5px;
        }

        .reportTable th.text-center, .reportTable td.text-center {
            text-align: center;
        }
    </style>
@endsection

@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Budget Expense Report</h2>
            </div>
            <div class="box-body b-t">
                <div class="row">
                    {!! Form::open(['url'=>'basic-finance/budget-month-wise-report', 'method'=>'GET', 'id' => 'form']) !!}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>From Month</label>
                            {!! Form::month('from_month', request('from_month'), ['class'=>'form-control', 'id' => 'from_month']) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>To Month</label>
                            {!! Form::month('to_month', request('to_month'), ['class'=>'form-control', 'id' => 'to_month']) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Account</label>
                            {!! Form::select('account_id', $accounts, request('account_id'), ['class' => 'form-control', 'id' => 'account_id', 'placeholder' => 'Select']) !!}
                        </div>
                    </div>
                    <div class="col-md-1" style="margin-top: 25px;">
                        <button type="submit" class="form-control btn btn-xs btn-primary" id="submit"
                                style="width: 57px;">
                            Search
                        </button>
                    </div>
                    <div class="col-md-1" style="margin-top: 25px;">
                        <button type="button" class="form-control btn btn-xs btn-danger" id="pdf" style="width: 57px;">
                            Pdf
                        </button>
                    </div>
                    <div class="col-md-1" style="margin-top: 25px;">
                        <button type="button" class="form-control btn btn-xs btn-success" id="excel" style="width: 57px;">
                            Excel
                        </button>
                    </div>
                    {!! Form::close() !!}
                </div>

                <div class="row">
                    @if(Session::has('success'))
                        <div class="col-md-6 col-md-offset-3 alert alert-success alert-dismissible text-center">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <small>{{ Session::get('success') }}</small>
                        </div>
                    @elseif(Session::has('failure'))
                        <div class="col-md-6 col-md-offset-3 alert alert-danger alert-dismissible text-center">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <small>{{ Session::get('failure') }}</small>
                        </div>
                    @endif
                </div>

                <div class="row">
                    <div class="col-md-12" id="reportTable">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div style="text-align: center;">
        <img class="loader" src="{{asset('loader.gif')}}" style="height: 30px;display: none;" alt="loader">
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            $(document).on('submit', '#form', function (event) {
                event.preventDefault();
                let formData = $(this).serializeArray();
                $.ajax({
                    url: "/basic-finance/budget-month-wise-expense-report",
                    type: "post",
                    dataType: "html",
                    data: formData,
                    beforeSend() {
                        $('html,body').css('cursor', 'wait');
                        $("html").css({'background-color': 'black', 'opacity': '0.5'});
                        $(".loader").show();
                    },
                    complete() {
                        $('html,body').css('cursor', 'default');
                        $("html").css({'background-color': '', 'opacity': ''});
                        $(".loader").hide();
                    },
                    success: function (data) {
                        $("#reportTable").html(data);
                    }
                })
            });

            $(document).on('click', '#pdf', function () {
                let fromMonth = $('#from_month').val();
                let toMonth = $('#to_month').val();
                let accountId = $('#account_id').val();

                let link = `{{ url('/basic-finance/budget-month-wise-expense-report-pdf') }}?from_month=${fromMonth}&to_month=${toMonth}&account_id=${accountId}`;
                window.open(link, '_blank');
            });

            $(document).on('click', '#excel', function () {
                let fromMonth = $('#from_month').val();
                let toMonth = $('#to_month').val();
                let accountId = $('#account_id').val();

                let link = `{{ url('/basic-finance/budget-month-wise-expense-report-excel') }}?from_month=${fromMonth}&to_month=${toMonth}&account_id=${accountId}`;
                window.open(link, '_blank');
            });
        })
    </script>
@endsection
