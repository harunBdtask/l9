@extends('basic-finance::layout')
@section('title', 'Provisional Ledger Details')
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

        .select2-selection__rendered, .select2-selection__arrow {
            margin: 4px;
        }

        .invalid, .invalid + .select2 .select2-selection {
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

        @media (min-width: 768px) {
            .modal-dialog {
                width: 600px;
                margin: 30px auto;
            }
            .modal-xl {
                width: 90%;
            max-width:1200px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>LEDGER DETAILS</h2>
            </div>
            <div id="reportForm" class="box-body b-t">
                {!! Form::open(['url'=>'basic-finance/provisional-ledger', 'method'=>'get']) !!}
                @php
                    $accounts=collect($accounts)->prepend('Select Account','');
                    $departments=collect($departments)->prepend('All Department',0);
                    $cost_centers=collect($cost_centers)->prepend('All Cost Centre',0);
                @endphp
                <div class="form-group row custom-padding">
                    <div class="col-sm-4">
                        <label class="col-sm-4 form-control-label"><b>From Date</b></label>
                        <div class="col-sm-8">
                            {!! Form::date("start_date", request('start_date') ?? \Carbon\Carbon::now()->firstOfMonth(),['class'=>"form-control form-control-sm"]) !!}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <label class="col-sm-4 form-control-label"><b>To Date</b></label>
                        <div class="col-sm-8">
                            {!! Form::date("end_date", request('end_date') ?? now(),['class'=>"form-control form-control-sm"]) !!}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="col-sm-4 form-control-label"><b>Account Name</b></label>
                        <div class="col-sm-8">
                            {!! Form::select("account_id", $accounts ?? [], request('account_id') ?? $account->id ?? null,["class"=>"form-control c-select select2-input", "id"=>"account_id", 'required']) !!}
                        </div>
                    </div>
                </div>
                <div class="form-group row custom-padding">
                    <div class="col-md-4">
                        <label class="col-sm-4 form-control-label"><b>Company Name</b></label>
                        <div class="col-sm-8">
                            <select name="factory_id" class="form-control select2-input" id="factory_id">
                                @foreach($companies as $key=>$factory)
                                    <option
                                        value="{{ $factory->id }}" {{ $factory->id == $factoryId ? 'selected' : null }}>{{ $factory->factory_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="col-sm-4 form-control-label"><b>Project Name</b></label>
                        <div class="col-sm-8">
                            <select name="project_id" class="form-control select2-input" id="project_id">
                                <option value="">Select Project</option>
                                @foreach($projects as $project)
                                    <option
                                        value="{{ $project->id }}" {{ $project->id == $projectId ? 'selected' : null }}>{{ $project->project }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="col-sm-4 form-control-label"><b>Unit Name</b></label>
                        <div class="col-sm-8">
                            <select name="unit_id" class="form-control select2-input" id="unit_id">
                                <option value="">Select Unit</option>
                                @foreach($units as $unit)
                                    <option
                                        value="{{ $unit->id }}" {{ $unit->id == $unitId ? 'selected' : null }}>{{ $unit->unit }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group row custom-padding">
                    <div class="col-md-4">
                        <label class="col-sm-4 form-control-label"><b>Department</b></label>
                        <div class="col-sm-8">
                            {!! Form::select("department_id", $departments,request('department_id') ?? null,["class"=>"form-control c-select select2-input", "id"=>"department_id"]) !!}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="col-sm-4 form-control-label"><b>Cost Centre</b></label>
                        <div class="col-sm-8">
                            {!! Form::select("cost_centre", $cost_centers,request('cost_centre') ?? null,["class"=>"form-control c-select select2-input", "id"=>"cost_centre"]) !!}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="col-sm-4 form-control-label"><b>Currency Type</b></label>
                        <div class="col-sm-8">
                            {!! Form::select("currency_type_id", [1 => 'Home', 2 => 'FC', 3 => 'Both'], request('currency_type_id') ?? 3,["class"=>"form-control c-select select2-input", "id"=>"currency_type_id"]) !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6"></div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label></label>
                            <button class="btn btn-info">
                                <i class="fa fa-search">Search</i>
                            </button>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
                <div class="row">
                    <form>
                        <div class="header-section" style="padding-bottom: 0px;">
                            <div class="pull-right">
                                <table>
                                    <tr>
                                        <td><a style="margin-right: 1px;" type="button"
                                               class="form-control btn pdf pull-right"><i class="fa fa-file-pdf-o"></i></a>
                                        </td>
                                        <td>
                                            <a style="margin-right: 8px;" type="button"
                                               class="form-control btn excel pull-right"><i
                                                    class="fa fa-file-excel-o"></i></a>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="row">
                    <div class="col-lg-5"></div>
                    <div>
                        <table style="border: 1px solid black;width: 20%;">
                            <thead>
                            <tr>
                                <td class="text-center">
                                    <span style="font-size: 14pt; font-weight: bold;">Provisional Ledger Details</span>
                                    <br>
                                </td>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                @includeIf('basic-finance::tables.provisional_ledger_table')
            </div>
            <div style="text-align: center;">
                <img class="loader" src="{{asset('loader.gif')}}" style="display: none;" alt="loader">
            </div>
        </div>
    </div>

<!-- modal button -->
<div class="modal fade" id="voucher-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="voucherModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form>
                @csrf
                <div class="modal-body" id="voucherDetails">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script type="text/javascript">
        const voucherDetails = $('#voucherDetails');

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

        //voucherInfo
        $('body').on('click', '.voucherInfo', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            var account = $(this).data('account');
            $('#voucherModalLabel').text("Voucher Details");
            voucherDetails.html('');
            $('#voucher-modal').modal('show');
            $.ajax({
                url: "/basic-finance/ledger-voucher-details",
                type: "get",
                dataType: "html",
                data: {
                    'voucher_no': id,
                    'account': account,
                },
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
                success(data) {
                    voucherDetails.html(data);
                },
                error(errors) {
                    alert("Something Went Wrong");
                }
            })
        });
    </script>
@endsection
