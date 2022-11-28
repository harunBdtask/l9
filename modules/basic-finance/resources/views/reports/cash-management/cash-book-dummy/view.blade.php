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
                    <form action="">
                        <div class="col-sm-8">
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Company</label>
                                        <select name="factory_id" class="form-control select2-input" id="factory_id">
                                            @foreach($factories as $key=>$factory)
                                                <option
                                                    value="{{ $factory->id }}" {{ $factory->id == $factoryId ? 'selected' : null }}>{{ $factory->factory_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Project</label>
                                        <select name="project_id" class="form-control select2-input" id="project_id">
                                            <option value="">Select Project</option>
                                            @foreach($projects as $project)
                                                <option
                                                    value="{{ $project->id }}" {{ $project->id == $projectId ? 'selected' : null }}>{{ $project->project }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Unit</label>
                                        <select name="unit_id" class="form-control select2-input" id="unit_id">
                                            <option value="">Select Unit</option>
                                            @foreach($units as $unit)
                                                <option
                                                    value="{{ $unit->id }}" {{ $unit->id == $unitId ? 'selected' : null }}>{{ $unit->unit }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>From Date</label>
                                        <input type="date" name="from_date" value="{{request('from_date') ?? null}}" class="form-control select2-input" id="from_date">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>To Date</label>
                                        <input type="date" name="to_date" value="{{request('to_date') ?? null}}" class="form-control select2-input" id="to_date">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label></label>
                                        <button style="margin-top: 30px;" class="btn btn-info">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                {{--                @if($currentRatio)--}}
                <div class="">
                    <div class="header-section" style="padding-bottom: 0px;">
                        <div class="pull-right" style="margin-bottom: -5%;">
                            <a class="btn"
{{--                               href="/basic-finance/cash-management/cash-book/pdf?{{ Request::getQueryString() }}"--}}
                            ><i
                                    class="fa fa-file-pdf-o"></i></a>
{{--                            <a class="btn"--}}
{{--                               href="/basic-finance/cash-management/cash-book/excel?{{ Request::getQueryString() }}"><i--}}
{{--                                    class="fa fa-file-excel-o"></i></a>--}}
                        </div>
                    </div>
                    <center>
                        <table style="border: 1px solid black;width: 20%;">
                            <thead>
                            <tr>
                                <td class="text-center">
                                    <span style="font-size: 12pt; font-weight: bold;">Cash Book</span>
                                    <br>
                                </td>
                            </tr>
                            </thead>
                        </table>
                    </center>
                    <br>
                    <div class="body-section" style="margin-top: 0;">
                        <div class="row">
                            <div class="col-lg-12">
                                @includeIf('basic-finance::reports.cash-management.cash-book-dummy.table')
                            </div>
                        </div>
                    </div>

                </div>
                {{--                @endif--}}
            </div>
        </div>
    </div>

@endsection
@push("script-head")
    <script type="text/javascript">
        $(function () {
            let companyId = $('#factory_id').val();
            fetchProjects(companyId);
            $('#factory_id').on('change', function () {
                let companyId = $('#factory_id').val();
                fetchProjects(companyId);
            })

            function fetchProjects(companyId) {
                axios.get(`/basic-finance/api/v1/fetch-company-wise-projects/${companyId}`).then((response) => {
                    let projects = response.data;
                    $(`#project_id`).find('option').not(':first').remove();
                    let options = [];
                    projects.forEach((project) => {
                        options.push([
                            `<option value="${project.id}" data-id="${project.id}" data-name="${project.text}">${project.text}</option>`
                        ].join(''));
                    });
                    $('#project_id').append(options);
                    $('#project_id').select2('val', {{$projectId ?? null}} );
                });
            }

            $('#project_id').on('change', function () {
                let companyId = $('#factory_id').val();
                let projectId = $(this).val();
                fetchUnits(companyId, projectId);
            })

            function fetchUnits(companyId, projectId) {
                if (companyId && projectId) {
                    let unitId = $(`#unit_id`).val();
                    axios.get(`/basic-finance/api/v1/fetch-project-wise-units/${companyId}/${projectId}`).then((response) => {
                        let units = response.data;
                        let options = [];
                        $(`#unit_id`).val(unitId ?? '').change();
                        $(`#unit_id`).find('option').not(':first').remove();
                        units.forEach((unit) => {
                            options.push([
                                `<option value="${unit.id}" data-id="${unit.id}" data-name="${unit.text}">${unit.text}</option>`
                            ].join(''));
                        });
                        $('#unit_id').append(options);
                        $('#unit_id').select2('val', {{$unitId ?? null}});
                    })
                }
            }
        });

    </script>
@endpush
