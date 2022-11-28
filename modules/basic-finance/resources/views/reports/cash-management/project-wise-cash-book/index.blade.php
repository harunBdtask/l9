@extends('skeleton::layout')
@section('title','Cash Book')
@section('content')
    <style type="text/css">
        .v-align-top td, .v-algin-top th {
            vertical-align: top;
        }

        * {
            box-sizing: border-box;
            -moz-box-sizing: border-box;
        }

        .page {
            width: 210mm;
            min-height: 297mm;
            margin: 10mm auto;
            border-radius: 5px;
            background: white;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        .header-section {
            padding: 10px;
        }

        .body-section {
            padding: 10px;
            padding-top: 0px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding-left: 5px;
            padding-right: 5px;
            padding-top: 3px;
            padding-bottom: 3px;
        }

        table.borderless {
            border: none;
        }

        .borderless td, .borderless th {
            border: none;
        }

        @page {
            size: landscape;
            /*margin: 5mm;*/
            /*margin-left: 15mm;*/
            /*margin-right: 15mm;*/
        }

        @media print {
            html, body {
                width: 210mm;
                /*height: 293mm;*/
            }

            .page {
                margin: 0;
                border: initial;
                border-radius: initial;
                width: initial;
                min-height: initial;
                box-shadow: initial;
                background: initial;
                page-break-after: always;
            }
        }
    </style>
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Cash Book</h2>
            </div>
            <div class="box-body table-responsive b-t">
                <div class="row">
                    <form id="form-submit">
                        <div class="col-sm-8">
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Company</label>
                                        {!! Form::select('factory_id', $factories ?? [], null, [
                                            'class' => 'form-control select2-input',
                                            'id' => 'factory_id',
                                            'placeholder' => 'Select a Factory'
                                        ]) !!}
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Project</label>
                                        {!! Form::select('project_id[]', [], null, [
                                            'class' => 'form-control select2-input',
                                            'multiple' => "multiple",
                                            'id' => 'project_id',
                                        ]) !!}
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Cash Book</label>
                                        {!! Form::select('account_id[]', [], null, [
                                            'class' => 'form-control select2-input',
                                            'id' => 'account_id',
                                            'multiple' => "multiple",
                                        ]) !!}
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>From Date</label>
                                        {!! Form::date('from_date', $fromDate ?? null, [
                                            'class' => 'form-control form-control-sm',
                                            'id' => 'from_date',
                                        ]) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>To Date</label>
                                        {!! Form::date('to_date', $toDate ?? null, [
                                            'class' => 'form-control form-control-sm',
                                            'id' => 'to_date',
                                        ]) !!}
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label></label>
                                        <button style="margin-top: 30px;" class="btn btn-info btn-sm"
                                                id="search-report-data">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="">
                    <div class="header-section" style="padding-bottom: 0;">
                        <div class="pull-right" style="margin-bottom: -5%;">
                            <a class="btn" id="pdf"><i class="fa fa-file-pdf-o"></i></a>
                            <a class="btn" id="excel"><i class="fa fa-file-excel-o"></i></a>
                        </div>
                    </div>
                    <center>
                        <table style="border: 1px solid black;width: 30%;">
                            <thead>
                            <tr>
                                <td class="text-center">
                                    <span style="font-size: 12pt; font-weight: bold;">Projects wish Cash Books Summary Reports</span>
                                    <br>
                                </td>
                            </tr>
                            </thead>
                        </table>
                        {{--                        <table class="m-t-sm" style="border: 1px solid black;width: 20%;">--}}
                        {{--                            <thead>--}}
                        {{--                            <tr>--}}
                        {{--                                <td class="text-center">--}}
                        {{--                                    <span style="font-size: 12pt; font-weight: bold;">{{ \Carbon\Carbon::make($fromDate)->toFormattedDateString() }} to--}}
                        {{--                                            {{ \Carbon\Carbon::make($toDate)->toFormattedDateString() }}</span>--}}
                        {{--                                    <br>--}}
                        {{--                                </td>--}}
                        {{--                            </tr>--}}
                        {{--                            </thead>--}}
                        {{--                        </table>--}}
                    </center>
                    <br>
                    <div class="body-section" style="margin-top: 0;">
                        <div class="row">
                            <div class="col-lg-12" id="report-table">
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection
@push("script-head")
    <script type="text/javascript">
        function fetchFactoryWiseProjects(factoryId) {
            $.ajax({
                url: `/basic-finance/api/v1/fetch-company-wise-projects/${factoryId}`,
                type: 'GET',
                success: function (response) {
                    let projectSection = $('#project_id');
                    let projects = response;
                    let options = [];
                    projectSection.find('option').not(':first').remove();
                    projects.forEach((project) => {
                        options.push([
                            `<option value="${project.id}" data-id="${project.id}" data-name="${project.text}">${project.text}</option>`
                        ].join(''));
                    });
                    projectSection.append(options);
                }
            })
        }

        function fetchCashInHandAccounts(factoryId) {
            $.ajax({
                url: `/basic-finance/api/v1/get-cash-in-hand-accounts?factory_id=${factoryId}`,
                type: 'GET',
                success: function (response) {
                    let accountSection = $('#account_id');
                    let accounts = response.data;
                    let options = [];
                    accountSection.find('option').not(':first').remove();
                    accounts.forEach((account) => {
                        options.push([
                            `<option value="${account.id}" data-id="${account.id}" data-name="${account.text}">${account.text}</option>`
                        ].join(''));
                    });
                    accountSection.append(options);
                }
            })
        }

        $(document).ready(function () {

            $(document).on('change', '#factory_id', function () {
                const factoryId = $(this).val();

                if (factoryId) {
                    fetchFactoryWiseProjects(factoryId);
                    fetchCashInHandAccounts(factoryId);
                }
            });

            $(document).on('submit', '#form-submit', function (e) {
                e.preventDefault();

                let queryString = $(this).serialize();
                $.ajax({
                    url: `/basic-finance/cash-management/get-report-data?${queryString}`,
                    type: 'GET',
                    success: function (response) {
                        let reportTable = $('#report-table');
                        let pdfQueryString = `/basic-finance/cash-management/get-pdf?${queryString}`
                        let excelQueryString = `/basic-finance/cash-management/get-excel?${queryString}`
                        reportTable.empty();
                        reportTable.append(response);
                        $('#pdf').attr('href', pdfQueryString);
                        $('#excel').attr('href', excelQueryString);
                    }
                })
            });

        });
    </script>
@endpush
