@extends('finance::layout')
@section('title', 'Create Budget')
@section('styles')
    <style type="text/css">
        .borderless td, .borderless th {
            border: none;
        }

        #tabular-form thead {
            background-color: #00b0ff;
        }

        #tabular-form table, #tabular-form thead, #tabular-form tbody, #tabular-form th, #tabular-form td {
            padding: 3px !important;
            vertical-align: middle !important;
            font-size: 12px;
            text-align: center;
            border-color: black;
        }

    </style>
@endsection

@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Create <Budget></Budget></h2>
            </div>
            <div class="box-body">
                <form>
                    <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group row">
                            <label class="col-sm-4 form-control-label"><b>Budget ID :</b></label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm" name="budget_id">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 form-control-label"><b>Select Month :</b></label>
                            <div class="col-sm-8">
                                <input type="month" class="form-control form-control-sm" name="month">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 form-control-label"><b>Budget Date :</b></label>
                            <div class="col-sm-8">
                                <input type="date" class="form-control form-control-sm" name="budget_date">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6"></div>
                </div>

                    <div class="form-group row">
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table class="table reportTable" id="tabular-form">
                                    <thead class="thead-light" style="background-color: deepskyblue;">
                                        <tr>
                                            <th>Head Of Accounts</th>
                                            <th>Previous Month TK</th>
                                            <th>Budget Amount</th>
                                            <th>B.Remarks</th>
                                            <th class="text-center" >ACTION</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>
                                            <select class="form-control c-select select2-input" name="account" id="account">
                                                <option value="0">Select</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" name="previous_month_amount" id="previous_month_amount" class="form-control" autocomplete="off">
                                        </td>
                                        <td>
                                            <input type="text" name="budget_amount" id="budget_amount" class="form-control" autocomplete="off">
                                        </td>
                                        <td>
                                            <input type="text" name="remarks" id="remarks" class="form-control" autocomplete="off">
                                        </td>
                                        <td>
                                            <a class="btn btn-primary btn-icon btn-sm add-to-cart">
                                                <i class="fa fa-plus"></i>
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
