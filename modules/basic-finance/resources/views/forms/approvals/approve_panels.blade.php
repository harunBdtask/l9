@extends('basic-finance::layout')
@section('title','Voucher Approvals')

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
    </style>
@endsection

@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Voucher Approval Panels</h2>
            </div>
            <div class="box-body b-t">
                <div class="row">
                    <form id="form" action="{{ url('basic-finance/vouchers-approve-panels') }}" method="GET">
                        <div class="col-md-12">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Company</label>
                                    {!! Form::select('factory_id', $factories, request('factory_id') ?? null, [
                                        'class' => 'form-control select2-input', 'id' => 'factory_id', 'placeholder' => 'Select'
                                    ]) !!}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Voucher Type</label>
                                    {!! Form::select('type_id', $voucherTypes, request('type_id') ?? null, [
                                        'class' => 'form-control select2-input', 'placeholder' => 'Select'
                                    ]) !!}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Voucher No</label>
                                    {!! Form::text('voucher_no', request('voucher_no') ?? null, [
                                        'class' => 'form-control', 'placeholder' => 'write voucher no'
                                    ]) !!}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Date Type</label>
                                    {!! Form::select('date_type', [], request('date_type') ?? null, [
                                        'class' => 'form-control select2-input', 'placeholder' => 'Select'
                                    ]) !!}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>From Date</label>
                                    {!! Form::date('from_date', request('from_date') ?? $fromDate?? null, [
                                        'class' => 'form-control select2-input', 'placeholder' => 'Select'
                                    ]) !!}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>To Date</label>
                                    {!! Form::date('to_date', request('to_date') ?? $toDate?? null, [
                                        'class' => 'form-control select2-input', 'placeholder' => 'Select'
                                    ]) !!}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Project Name</label>
                                    {!! Form::select('project_id', [], request('project_id') ?? null, [
                                        'class' => 'form-control select2-input', 'id' => 'project_id', 'placeholder' => 'Select'
                                    ]) !!}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Unit Name</label>
                                    {!! Form::select('unit_id', [], request('unit_id') ?? null, [
                                        'class' => 'form-control select2-input', 'id' => 'unit_id', 'placeholder' => 'Select'
                                    ]) !!}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="col-md-1" style="float: right;">
                                <div class="form-group">
                                    <button type="submit" class="form-control form-control-sm btn-success" id="search">
                                        Search
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <br>
                <br>

                <div class="form-group row">
                    <div class="col-lg-12">
                        @includeIf('basic-finance::forms.approvals.unapprove_vouchers')
                        <br>
                        <br>
                        @includeIf('basic-finance::forms.approvals.approve_vouchers')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function () {

            $(document).on('change', '#factory_id', function () {
                let factoryId = $(this).val();
                getProjects(factoryId);
            });

            $(document).on('change', '#project_id', function () {
                let factoryId = $('#factory_id').val();
                let projectId = $(this).val();
                getUnits(factoryId, projectId);
            });

            $(document).on('click', '#post', function () {
                let id = $(this).attr('data');
                let url = $('#voucher-post').find('form').attr('action');
                url += `/basic-finance/vouchers/${id}/approval`;
                $('#voucher-post').find('form').attr('action', url);
            });

            $(document).on('click', '#cancel', function () {
                let id = $(this).attr('data');
                let url = $('#voucher-cancel').find('form').attr('action');
                url += `/basic-finance/vouchers/${id}/approval`;
                $('#voucher-cancel').find('form').attr('action', url);
            });

            $(document).on('click', '#posts', function (event) {
                $(this).hide();
                event.preventDefault();

                let vouchers = [];
                $.each($("input[name='voucher_id']:checked").not("[disabled]"), function () {
                    vouchers.push($(this).val());
                });
                axios.post(`/basic-finance/multiple-vouchers-posting`, vouchers).then((response) => {
                    if (response.status === 201) {
                        location.reload();
                    }
                }).catch((error) => {
                    console.log(error);
                })
            });

        });

        function getProjects(factoryId) {
            axios.get(`/basic-finance/api/v1/fetch-company-wise-projects/${factoryId}`).then((response) => {
                let projects = response.data;
                let options = [];
                $(`#project_id`).find('option').not(':first').remove();
                projects.forEach((project) => {
                    options.push([
                        `<option value="${project.id}" data-id="${project.id}" data-name="${project.text}">${project.text}</option>`
                    ].join(''));
                });
                $('#project_id').append(options);
            }).catch((error) => console.log(error))
        }

        function getUnits(factoryId, projectId) {
            axios.get(`/basic-finance/api/v1/fetch-project-wise-units/${factoryId}/${projectId}`).then((response) => {
                let units = response.data;
                let options = [];
                $(`#unit_id`).find('option').not(':first').remove();
                units.forEach((unit) => {
                    options.push([
                        `<option value="${unit.id}" data-id="${unit.id}" data-name="${unit.text}">${unit.text}</option>`
                    ].join(''));
                });
                $('#unit_id').append(options);
            }).catch((error) => console.log(error))
        }

        $(document).on('change', '#select_all', function () {
            if(this.checked){
                $('.voucher_id').prop('checked', true);
            }else{
                $('.voucher_id').prop('checked', false);
            }
        });
    </script>
@endsection
