@extends('basic-finance::layout')
@section('title', 'Balance-Sheet')
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

        input[type=date].form-control, input[type=time].form-control, input[type=datetime-local].form-control, input[type=month].form-control {
            line-height: 1rem;
        }

        .select2-selection {
            min-height: 2.375rem;
        }

        .select2-selection__rendered, .select2-selection__arraow {
            margin: 4px;
        }

        .invalid, invalid + .select2 .select2-selection {
            border-color: red !important;
        }
        tbody tr:hover {
            background-color: lightcyan;
        }
        td {
            padding-right: 8px;
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

        .custom-padding {
            padding: 0px 40px 0px 40px;
        }
    </style>
@endsection

@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>BALANCE SHEET</h2>
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
                                        <input type="date" name="start_date" value="{{ empty(request('start_date')) ? Carbon\carbon::today()->startOfMonth() : request('start_date') }}" class="form-control select2-input" id="start_date">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>To Date</label>
                                        <input type="date" name="end_date" value="{{ empty(request('end_date')) ? Carbon\carbon::today()->endOfMonth() : request('end_date') }}" class="form-control select2-input" id="end_date">
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
                            <a class="btn" href="/basic-finance/balance-sheet?{{ Request::getQueryString().'&type=pdf' }}"><i class="fa fa-file-pdf-o"></i></a>
                        </div>
                    </div>
                    <center>
                        <table style="border: 1px solid black;width: 20%;">
                            <thead>
                            <tr>
                                <td class="text-center">
                                    <span style="font-size: 12pt; font-weight: bold;">Balance Book</span>
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
                                @includeIf('basic-finance::tables.balance_sheet_v2_table')
                            </div>
                        </div>
                    </div>

                </div>
                {{--                @endif--}}
            </div>
        </div>
    </div>
@endsection

@section('scripts')
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

        $('.pdf').click(function (e) {
            e.preventDefault();
            let url = window.location.toString();
            if (url.includes('?')) {
                url += '&type=pdf';
            } else {
                url += '?type=pdf';
            }
            window.open(url, '_blank');
        });

        $('.excel').click(function (e) {
            e.preventDefault();
            let url = window.location.toString();
            if (url.includes('?')) {
                url += '&type=excel';
            } else {
                url += '?type=excel';
            }
            window.open(url, '_blank');
        });
    </script>
@endsection
