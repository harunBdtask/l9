@extends('basic-finance::layout')
@section('title','Chart Of Accounts V2')
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
                <div class="row">
                    <div class="col-sm-3"><h2>Chart Of Accounts</h2></div>
                    <div class="col-sm-7"></div>
                    <div class="col-sm-2">
                        <div style="margin-left: -14%">
                            <a class="btn btn-sm btn-warning" href="{{ url('basic-finance/accounts') }}">Chart Of Account Tree View</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-body b-t">
                <div style="margin-bottom: 15px">
                    <button class="btn btn-sm white" data-toggle="modal" data-target="#exampleModalCenter">
                        <i class="glyphicon glyphicon-plus"></i> New Account
                    </button>
{{--                    <a class="btn btn-sm white" href="{{ url('basic-finance/accounts/create') }}">--}}
{{--                        <i class="glyphicon glyphicon-plus"></i> New Account--}}
{{--                    </a>--}}
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="reportTable">
                            <thead class="thead-light">
                            <tr>
                                <th class='text-center'>Ac Code</th>
                                <th class='text-center'>Ac Type</th>
                                <th class='text-center'>Parent Account</th>
                                <th class='text-center'>Group Account</th>
                                <th class='text-center'>Control Ledger</th>
                                <th class='text-center'>Ledger Name</th>
                            </tr>
                            </thead>
                            @if(isset($data))
                                <tbody>
                                @foreach($data as $accounts)
                                    @foreach($accounts as $account)
                                        @php
                                            $part_0 = substr($account['code'],1,1);
                                            $part_1 = substr($account['code'],2,2);
                                            $part_2 = substr($account['code'],4,3);
                                            $part_3 = substr($account['code'],7,3);
                                            $part_4 = substr($account['code'],10,3);
                                        @endphp
                                        @if($part_4 > 0)
                                            <tr>
                                                <td>{{ $account['parent'][0]['code'] }}</td>
                                                <td>{{ $account['type'] }}</td>
                                                <td>{{ $account['parent'][0]['parent'][0]['parent'][0]['parent'][0]['name'] }}</td>
                                                <td>{{ $account['parent'][0]['parent'][0]['parent'][0]['name'] }}</td>
                                                <td>{{ $account['parent'][0]['parent'][0]['name'] }}</td>
                                                <td>{{ $account['parent'][0]['name'] }}</td>
                                                {{--                                                <td>{{ $account['name'] }}</td>--}}
                                            </tr>
                                        @else
                                            @if($part_3 > 0)
                                                <tr>
                                                    <td>{{ $account['code'] }}</td>
                                                    <td>{{ $account['type'] }}</td>
                                                    <td>{{ $account['parent'][0]['parent'][0]['parent'][0]['name'] }}</td>
                                                    <td>{{ $account['parent'][0]['parent'][0]['name'] }}</td>
                                                    <td>{{ $account['parent'][0]['name'] }}</td>
                                                    <td>{{ $account['name'] }}</td>
                                                    {{--                                                    <td>{{ '' }}</td>--}}
                                                </tr>
                                            @elseif($part_2 > 0)
                                                <tr>
                                                    <td>{{ $account['code'] }}</td>
                                                    <td>{{ $account['type'] }}</td>
                                                    <td>{{ $account['parent'][0]['parent'][0]['name'] }}</td>
                                                    <td>{{ $account['parent'][0]['name'] }}</td>
                                                    <td>{{ $account['name'] }}</td>
                                                    <td>{{ '' }}</td>
                                                    {{--                                                    <td>{{ '' }}</td>--}}
                                                </tr>
                                            @elseif($part_1 > 0)
                                                <tr>
                                                    <td>{{ $account['code'] }}</td>
                                                    <td>{{ $account['type'] }}</td>
                                                    <td>{{ $account['parent'][0]['name'] }}</td>
                                                    <td>{{ $account['name'] }}</td>
                                                    <td>{{ '' }}</td>
                                                    <td>{{ '' }}</td>
                                                    {{--                                                    <td>{{ '' }}</td>--}}
                                                </tr>
                                            @endif
                                        @endif
                                    @endforeach
                                @endforeach
                                </tbody>
                            @else
                                <tbody>
                                <tr>
                                    <td colspan="6" class="text-center text-danger">No Account Data Available</td>
                                </tr>
                                </tbody>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
             aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h5 class="modal-title" id="exampleModalLongTitle">Add Account</h5>
                    </div>
                    <div class="modal-body" style="max-height : 350px; overflow-x: scroll">

                        {!! Form::open([
                            "method" => 'POST',
                            "url" => url('basic-finance/accounts'),
                            "id" => 'account-form'
                        ]) !!}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label for="type" class="col-sm-3 form-control-label">AC Type *</label>
                                    <div class="col-sm-9">
                                        {!! Form::select('type_id', $types ?? [], null, [
                                            'class' => 'form-control select2-input',
                                            'id' => 'type_id',
                                            'placeholder' => 'Select a type',
                                        ]) !!}
                                        <span class="text-danger" id="type_id_error"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label for="type" class="col-sm-3 form-control-label">Parent Accounts *</label>
                                    <div class="col-sm-9">
                                        {!! Form::select('parent_ac', [], null, [
                                            'class' => 'form-control select2-input',
                                            'id' => 'parent_ac',
                                            'placeholder' => 'Select a parent_ac',
                                        ]) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label for="type" class="col-sm-3 form-control-label">Name *</label>
                                    <div class="col-sm-9">
                                        {!! Form::text('name', null, [
                                            'class' => 'form-control',
                                            'id' => 'name',
                                            'placeholder' => 'Write account name...',
                                        ]) !!}
                                        <span class="text-danger" id="name_error"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label for="type" class="col-sm-3 form-control-label">Code *</label>
                                    <div class="col-sm-9">
                                        {!! Form::text('code', null, [
                                            'class' => 'form-control',
                                            'id' => 'code',
                                            'placeholder' => 'Write account code...',
                                            'readonly' => 'readonly'
                                        ]) !!}
                                        <span class="text-danger" id="code_error"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label for="code" class="col-sm-3 form-control-label">Is Active? *</label>
                                    <div class="col-sm-9">
                                        {!! Form::checkbox('is_active', 1, false, [
                                            'id' => 'is_active',
                                        ]) !!}
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-sm btn-success">Submit</button>
                            <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Close</button>
                        </div>
                        {!! Form::close(); !!}

                    </div>
                </div>
            </div>

        </div>
        @endsection

        @push("script-head")
            <script>
                $(document).ready(function () {

                    $('select[name="type_id"]').change(function () {
                        let typeId = $(this).val();
                        let parentAc = $(`#parent_ac`).val();
                        axios.get(`/basic-finance/api/v1/get-type-wise-parent-account/${typeId}`).then((response) => {
                            let accounts = response.data.data;
                            let options = [];
                            // $('#parent_ac').val(parentAc ?? '').change();
                            $('#parent_ac').find('option').not(':first').remove();
                            accounts.forEach((account) => {
                                options.push([
                                    `<option value="${account.id}" data-id="${account.id}" data-name="${account.text}">${account.text}</option>`
                                ].join(''));
                            });
                            $('#parent_ac').append(options);
                            // $('#parent_ac').select2('val', 0);
                        }).catch((error) => console.log(error))
                    });

                    $('select[name="parent_ac"]').change(function () {
                        let parentAc = $(this).val();
                        let typeId = $('#type_id').val();

                        axios.get(`/basic-finance/api/v1/accounts/fetch-account-code/${typeId}?parent_account_id=${parentAc}`)
                            .then((response) => {
                                $('#code').val(response.data);
                            })
                            .catch((error) => {
                                console.log(error);
                            })
                    });

                    $('#account-form').submit(function (event) {
                        event.preventDefault();

                        const url = $(this).attr('action');
                        const data = {
                            type_id: $('#type_id').val(),
                            parent_ac: $('#parent_ac').val(),
                            name: $('#name').val(),
                            code: $('#code').val(),
                            is_active: $('#is_active').is(":checked") ? $('#is_active').val() : null,
                        };

                        axios.post(url, data).then((response) => {
                            console.log(response);
                            location.reload();
                        }).catch((error) => {
                            console.log(error);

                            if (error.response.status === 422) {
                                let errors = error.response.data.errors;
                                console.log(Object.keys(errors));
                                Object.keys(errors).forEach((element) => {
                                    $(`#${element}_error`).text(errors[element][0]);
                                })
                            }
                        })
                    });

                });
            </script>
    @endpush
