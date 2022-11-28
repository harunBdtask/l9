@extends('finance::layout')
@section('title', 'Create New Account')
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="box">
                    <div class="box-header">
                        <h2>{{ isset($account) ? 'Update Account' : 'Create New Account' }}</h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body">
                        {{ Form::open(['url' => '/finance/api/v1/save-ledger-account', 'method' => 'POST', 'id' => 'account_form']) }}

                        <input type="hidden" name="account_type" class="account_type" value="4">
                        <div class="form-group row account_type">
                            <label class="col-sm-3" for="type_id" class="col-sm-3">Type Of A/C<span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-7">
                                {!! Form::select('type_id', $ac_types, null, ['class' => 'form-control form-control-sm c-select select2-input', 'id' => 'type', 'required'=>true, 'placeholder' => 'Select account type']) !!}
                                <span class="text-danger">{{ $errors->first('type_id') }}</span>
                            </div>
                        </div>

                        <div class="form-group row parent_account">
                            <label class="col-sm-3" for="parent_ac">Parent Name<span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-7">
                                {!! Form::select('parent_account_id', [], null, ['class' => 'form-control form-control-sm parent-account', 'id' => 'parent_ac', 'required'=>true, 'placeholder' => 'Select parent account if any']) !!}
                                <span class="text-danger">{{ $errors->first('parent_account_id') }}</span>
                            </div>
                            <div class="col-sm-2">
                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                        style="float : right"
                                        data-target="#parentAccountModal"
                                        onclick="accountType()">
                                    <em class="fa fa-plus"></em>
                                </button>
                            </div>
                        </div>

                        <div class="form-group row group_account">
                            <label class="col-sm-3" for="parent_ac">Group Name<span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-7">
                                {!! Form::select('group_account_id', [], null, ['class' => 'form-control form-control-sm group_account', 'id' => 'group_ac', 'required'=>true, 'placeholder' => 'Select']) !!}
                                <span class="text-danger">{{ $errors->first('group_account_id') }}</span>
                            </div>
                            <div class="col-sm-2">
                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                        style="float : right"
                                        onclick="groupAccount()"
                                        data-target="#groupAccountModal">
                                    <em class="fa fa-plus"></em>
                                </button>
                            </div>
                        </div>

                        <div class="form-group row control_account">
                            <label class="col-sm-3" for="parent_ac">Control Ledger<span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-7">
                                {!! Form::select('control_account_id', [], null, ['class' => 'form-control form-control-sm control_account', 'id' => 'control_ac', 'required'=>true, 'placeholder' => 'Select']) !!}
                                <span class="text-danger">{{ $errors->first('control_account_id') }}</span>
                            </div>
                            <div class="col-sm-2">
                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                        style="float : right"
                                        onclick="controlAccount()"
                                        data-target="#controlAccountModal">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </div>

                        {{--                        <div class="form-group row">--}}
                        {{--                            <label class="col-sm-3" for="parent_ac">Create Sub Ledger</label>--}}
                        {{--                            <div class="col-sm-9">--}}
                        {{--                                <input type="checkbox" name="create_sub_ledger" class="create_sub_ledger" checked>--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}

                        <div class="form-group row">
                            <label class="col-sm-3" for="parent_ac">Ledger</label>
                            <div class="ledger_option">
                                <div class="col-sm-7">
                                    {!! Form::text('name', null, ['class' => 'form-control form-control-sm', 'id' => 'ledger', 'placeholder' => 'Select']) !!}
                                    {{--                                    {!! Form::select('ledger_account_id', [], null, ['class' => 'form-control form-control-sm', 'id' => 'ledger_ac', 'placeholder' => 'Select']) !!}--}}
                                    <span class="text-danger">{{ $errors->first('ledger_account_id') }}</span>
                                </div>
                                {{--                                <div class="col-sm-2">--}}
                                {{--                                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"--}}
                                {{--                                            style="float : right"--}}
                                {{--                                            onclick="ledgerAccount()"--}}
                                {{--                                            data-target="#ledgerModal">--}}
                                {{--                                        <i class="fa fa-plus"></i>--}}
                                {{--                                    </button>--}}
                                {{--                                </div>--}}
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3" for="parent_ac">Ac
                                Code</label>
                            <div class="col-sm-7">
                                {!! Form::text('code', null, ['class' => 'form-control form-control-sm', 'placeholder' => 'Ac Code', 'readonly' => true]) !!}
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3" for="parent_ac">Status</label>
                            <div class="col-sm-7">
                                {!! Form::select('status', $status ?? [], null, ['class' => 'form-control form-control-sm c-select select2-input', 'readonly' => true]) !!}
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3" for="parent_ac">Transactional</label>
                            <div class="col-sm-7">
                                {!! Form::select('is_transactional', [1 => 'Yes', 0 => 'No'], null, ['class' => 'form-control form-control-sm c-select select2-input', 'readonly' => true]) !!}
                            </div>
                        </div>

                        {{--                        <div class="form-group row">--}}
                        {{--                            <label class="col-sm-3" for="parent_ac">Sub Ledger</label>--}}
                        {{--                            <div class="col-sm-7">--}}
                        {{--                                {!! Form::text('name', '', ['class' => 'form-control form-control-sm', 'id' => 'sub_ledger', 'placeholder' => 'Sub Ledger']) !!}--}}
                        {{--                                <span class="text-danger" id="error_sub_ledger"></span>--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}

                        <div class="form-group m-t-md row">
                            <div class="col-sm-7 col-sm-offset-3">
                                <button type="submit" class="btn btn-sm success" id="button_one">
                                    <em class="fa fa-save"></em> {{ isset($account) ? 'Update' : 'Create New' }}
                                </button>
                                <button type="submit" class="btn btn-sm danger" id="button_two">
                                    <em class="fa fa-save"></em> {{ isset($account) ? 'Update' : 'Create & close' }}
                                </button>
                                <button type="button" class="btn btn-sm btn-dark" id="button_three">
                                    <a href="{{ url('finance/accounts') }}">
                                        <em class="fa fa-arrow-circle-left"></em>
                                        Back
                                    </a>
                                </button>
                            </div>

                        </div>

                        {!! Form::close() !!}
                    </div>

                    <!-- Modal -->
                    <div class="modal fade" id="parentAccountModal" tabindex="-1" role="dialog"
                         aria-labelledby="parentAccountModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="parentAccountModalLabel">Parent Account</h5>
                                    <button type="button" class="close" data-dismiss="modal"
                                            aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="{{ url('finance/api/v1/save-parent-account') }}" method="post"
                                      id="parent-account-form">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Account Type</label>
                                            <div class="col-sm-9">
                                                <input type="text" readonly
                                                       class="form-control form-control-sm form-control form-control-sm-plaintext account-type-text"
                                                       value="">
                                                <input type="hidden" name="type_id" class="type_id">
                                                <input type="hidden" name="account_type" value="1">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-3" for="parent_ac">Ac
                                                Code</label>
                                            <div class="col-sm-9">
                                                <div class="ledger_option">
                                                    {!! Form::text('code', null, ['class' => 'form-control form-control-sm', 'placeholder' => 'Ac Code', 'id' => 'parent_code', 'readonly' => true]) !!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3" for="parent_ac">Name</label>
                                            <div class="col-sm-9">
                                                <div class="ledger_option">
                                                    {!! Form::text('name', null, ['class' => 'form-control form-control-sm', 'id' => 'parent_name', 'placeholder' => 'Name']) !!}
                                                    <span class="text-danger" id="error_parent_name"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-sm white"><i
                                                class="fa fa-save"></i> Save
                                        </button>
                                        <button type="button" class="btn btn-sm btn-dark"
                                                data-dismiss="modal">Close
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="groupAccountModal" tabindex="-1" role="dialog"
                         aria-labelledby="groupAccountModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="groupAccountModalLabel">Group Account</h5>
                                    <button type="button" class="close" data-dismiss="modal"
                                            aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="{{ url('finance/api/v1/save-group-account') }}" method="post"
                                      id="group-account-form">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Account Type</label>
                                            <div class="col-sm-9">
                                                <input type="text" readonly
                                                       class="form-control form-control-sm form-control form-control-sm-plaintext account-type-text"
                                                       value="">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Parent Account</label>
                                            <div class="col-sm-9">
                                                <input type="text" readonly
                                                       class="form-control form-control-sm form-control form-control-sm-plaintext parent-account-text"
                                                       value="">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3" for="parent_ac">Ac
                                                Code</label>
                                            <div class="col-sm-9">
                                                <div class="ledger_option">
                                                    {!! Form::text('code', null, ['class' => 'form-control form-control-sm', 'placeholder' => 'Ac Code', 'id' => 'group_code', 'readonly' => true]) !!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3" for="parent_ac"
                                            >Name</label>
                                            <div class="col-sm-9">
                                                <div class="ledger_option">
                                                    {!! Form::text('name', null, ['class' => 'form-control form-control-sm', 'id' => 'group_name', 'placeholder' => 'Name']) !!}
                                                    <span class="text-danger" id="error_group_name"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-sm white"><i
                                                class="fa fa-save"></i> Save
                                        </button>
                                        <button type="button" class="btn btn-sm btn-dark"
                                                data-dismiss="modal">Close
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="controlAccountModal" tabindex="-1" role="dialog"
                         aria-labelledby="controlAccountLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="controlAccountLabel">Control Account</h5>
                                    <button type="button" class="close" data-dismiss="modal"
                                            aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="{{ url('finance/api/v1/save-control-account') }}" method="post"
                                      id="control-account-form">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Account Type</label>
                                            <div class="col-sm-9">
                                                <input type="text" readonly
                                                       class="form-control form-control-sm form-control form-control-sm-plaintext account-type-text"
                                                       value="">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Parent Account</label>
                                            <div class="col-sm-9">
                                                <input type="text" readonly
                                                       class="form-control form-control-sm form-control form-control-sm-plaintext parent-account-text"
                                                       value="">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Group Account</label>
                                            <div class="col-sm-9">
                                                <input type="text" readonly
                                                       class="form-control form-control-sm form-control form-control-sm-plaintext group-account-text"
                                                       value="">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3" for="parent_ac">Ac
                                                Code</label>
                                            <div class="col-sm-9">
                                                <div class="ledger_option">
                                                    {!! Form::text('code', null, ['class' => 'form-control form-control-sm', 'placeholder' => 'Ac Code', 'id' => 'control_code', 'readonly' => true]) !!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3" for="parent_ac"
                                            >Name</label>
                                            <div class="col-sm-9">
                                                <div class="ledger_option">
                                                    {!! Form::text('name', null, ['class' => 'form-control form-control-sm', 'id' => 'control_name', 'placeholder' => 'Name']) !!}
                                                    <span class="text-danger" id="error_control_name"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-3" for="parent_ac">Transactional</label>
                                            <div class="col-sm-9">
                                                <div class="ledger_option">
                                                    {!! Form::select('is_transactional', [1 => 'Yes', 0 => 'No'], null, ['class' => 'form-control form-control-sm c-select select2-input', 'readonly' => true]) !!}
                                                    <span class="text-danger" id="error_control_name"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-sm white"><i
                                                class="fa fa-save"></i> Save
                                        </button>
                                        <button type="button" class="btn btn-sm btn-dark"
                                                data-dismiss="modal">Close
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="ledgerModal" tabindex="-1" role="dialog"
                         aria-labelledby="ledgerLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="ledgerLabel">Ledger</h5>
                                    <button type="button" class="close" data-dismiss="modal"
                                            aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="{{ url('finance/api/v1/save-ledger-account') }}" method="post"
                                      id="ledger-account-form">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Account Type</label>
                                            <div class="col-sm-9">
                                                <input type="text" readonly
                                                       class="form-control form-control-sm form-control form-control-sm-plaintext account-type-text"
                                                       value="">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Parent Account</label>
                                            <div class="col-sm-9">
                                                <input type="text" readonly
                                                       class="form-control form-control-sm form-control form-control-sm-plaintext parent-account-text"
                                                       value="">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Group Account</label>
                                            <div class="col-sm-9">
                                                <input type="text" readonly
                                                       class="form-control form-control-sm form-control form-control-sm-plaintext group-account-text"
                                                       value="">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Control Account</label>
                                            <div class="col-sm-9">
                                                <input type="text" readonly
                                                       class="form-control form-control-sm form-control form-control-sm-plaintext control-account-text"
                                                       value="">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3" for="parent_ac">Ac
                                                Code</label>
                                            <div class="col-sm-9">
                                                {!! Form::text('code', null, ['class' => 'form-control form-control-sm', 'id' => 'ledger_code', 'placeholder' => 'Ac Code', 'readonly' => true]) !!}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3" for="parent_ac"
                                            >Name</label>
                                            <div class="col-sm-9">
                                                {!! Form::text('name', null, ['class' => 'form-control form-control-sm', 'id' => 'ledger_name', 'placeholder' => 'Name']) !!}
                                                <span class="text-danger" id="error_ledger_name"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-sm white"><i
                                                class="fa fa-save"></i> Save
                                        </button>
                                        <button type="button" class="btn btn-sm btn-dark"
                                                data-dismiss="modal">Close
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        let submit = false;
        let submitAndClose = false;

        $(document).on('click', '.create_sub_ledger', function (e) {
            var account_form = $('#account_form')
            var ledger_option = $('.ledger_option')
            ledger_option.empty()
            if (e.target.checked) {
                ledger_option.append(`
                    <div class="col-sm-7">
                        {!! Form::select('ledger_account_id', [], null, ['class' => 'form-control form-control-sm', 'id' => 'ledger_ac', 'placeholder' => 'Select']) !!}
                </div>
                <div class="col-sm-2">
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                            style="float : right"
                            onclick="ledgerAccount()"
                            data-target="#ledgerModal">
                        <i class="fa fa-plus"></i>
                    </button>
                </div>
`)

                $('#control_ac').val($('#control_ac').val())
                $('#control_ac').trigger('change')
                $('#sub_ledger').prop('disabled', false)
                $('.account_type').val(5)
                account_form.attr('action', '/finance/api/v1/save-sub-ledger-account')
            } else {
                ledger_option.append(`
                    <div class="col-sm-7">
                        {!! Form::text('name', null, ['class' => 'form-control form-control-sm', 'id' => 'ledger', 'placeholder' => 'Select']) !!}
                </div>
`)
                $('#sub_ledger').prop('disabled', true);
                $('.account_type').val(4)
                account_form.attr('action', '/finance/api/v1/save-ledger-account')
            }
        })

        function accountType() {
            let account_type = $('.account_type').find(':selected')[0];
            $('.account-type-text').val(account_type.innerText);
            $('.type_id').val(account_type.index);
        }

        function groupAccount() {
            $('.account-type-text').val($('.account_type').find(':selected')[0].innerText)
            $('.parent-account-text').val($('.parent_account').find(':selected')[0].innerText)
        }

        function controlAccount() {
            $('.account-type-text').val($('.account_type').find(':selected')[0].innerText)
            $('.parent-account-text').val($('.parent_account').find(':selected')[0].innerText)
            $('.group-account-text').val($('.group_account').find(':selected')[0].innerText)
        }

        function ledgerAccount() {
            $('.account-type-text').val($('.account_type').find(':selected')[0].innerText)
            $('.parent-account-text').val($('.parent_account').find(':selected')[0].innerText)
            $('.group-account-text').val($('.group_account').find(':selected')[0].innerText)
            $('.control-account-text').val($('.control_account').find(':selected')[0].innerText)
        }

        $(document).on('click', '#button_one', function () {
            submit = true;
        })

        $(document).on('click', '#button_two', function () {
            submitAndClose = true;
        })

        $(document).on('submit', '#account_form', function (e) {
            e.preventDefault();
            let form = $(this);
            let url = form.attr('action');

            $.ajax({
                type: 'POST',
                url: url,
                data: `${form.serialize()}&`
            }).done(function (response) {

                if (submit) {
                    location.href = '/finance/accounts/create';
                } else if (submitAndClose) {
                    location.href = '/finance/accounts';
                }

            }).fail(function (response) {
                let text = response.responseText;
                let error = JSON.parse(text).errors;
                $('#error_sub_ledger').text(error.name);
            });
        })

        $(document).on('keyup', '#parent_name', function (e) {
            e.preventDefault();

            $.ajax({
                type: 'GET',
                url: `/finance/api/v1/get-parent-account-code?type_id=${$('.type_id').val()}`,
            }).done(function (response) {
                $('#parent_code').val(response);
            }).fail(function (response) {
                console.log(response);
            });
        })

        $(document).on('submit', '#parent-account-form', function (e) {
            e.preventDefault();
            var form = $(this);
            var url = form.attr('action');

            $.ajax({
                type: 'POST',
                url: url,
                data: form.serialize()
            }).done(function (response) {
                $('#parentAccountModal').modal('hide')
                let select2_option_value = new Option(response.name, response.id, true, true)
                $(select2_option_value).html(response.name)
                $("#parent_ac").append(select2_option_value).trigger('change')
            }).fail(function (response) {
                let text = response.responseText;
                let error = JSON.parse(text).errors;
                $('#error_parent_name').text(error.name);
            });
        })

        $(document).on('keyup', '#group_name', function (e) {
            e.preventDefault();
            let parentId = $('.parent_account').find(':selected')[0].attributes[0].value;

            $.ajax({
                type: 'GET',
                url: `/finance/api/v1/get-group-account-code?type_id=${$('#type').val()}&parent_account_id=${parentId}`,
            }).done(function (response) {
                $('#group_code').val(response);
            }).fail(function (response) {
                console.log(response)
            });
        })

        $(document).on('submit', '#group-account-form', function (e) {
            e.preventDefault();
            let form = $(this);
            let url = form.attr('action')

            let formData = form.serialize() +
                '&parent_account_id=' + $('#parent_ac').val() +
                '&type_id=' + $('#type').val() + '&account_type=2'

            $.ajax({
                type: 'POST',
                url: url,
                data: formData
            }).done(function (response) {
                $('#groupAccountModal').modal('hide')
                let select2_option_value = new Option(response.name, response.id, true, true)
                $(select2_option_value).html(response.name)
                $("#group_ac").append(select2_option_value).trigger('change')
            }).fail(function (response) {
                let text = response.responseText;
                let error = JSON.parse(text).errors;
                $('#error_group_name').text(error.name);
            });
        })

        $(document).on('keyup', '#control_name', function (e) {
            e.preventDefault();

            $.ajax({
                type: 'GET',
                url: `/finance/api/v1/get-control-account-code?type_id=${$('#type').val()}&parent_account_id=${$('#parent_ac').val()}$group_account_id=${$('#group_ac').val()}&account_type=3`,
            }).done(function (response) {
                $('#control_code').val(response);
            }).fail(function (response) {
                console.log(response)
            });
        })

        $(document).on('submit', '#control-account-form', function (e) {
            e.preventDefault();
            let form = $(this);
            let url = form.attr('action')

            let formData = form.serialize() +
                '&parent_account_id=' + $('#parent_ac').val() +
                '&group_account_id=' + $('#group_ac').val() +
                '&type_id=' + $('#type').val() +
                '&account_type=3'

            $.ajax({
                type: 'POST',
                url: url,
                data: formData
            }).done(function (response) {
                $('#controlAccountModal').modal('hide')
                let select2_option_value = new Option(response.name, response.id, true, true)
                $(select2_option_value).html(response.name)
                $("#control_ac").append(select2_option_value).trigger('change')
            }).fail(function (response) {
                let text = response.responseText;
                let error = JSON.parse(text).errors;
                $('#error_control_name').text(error.name);
            });
        })

        $(document).on('keyup', '#ledger_name', function (e) {
            e.preventDefault();

            $.ajax({
                type: 'GET',
                url: `/finance/api/v1/get-ledger-account-code?type_id=${$('#type').val()}&parent_account_id=${$('#parent_ac').val()}$group_account_id=${$('#group_ac').val()}&control_account_id=${$('#control_ac').val()}&account_type=4`,
            }).done(function (response) {
                $('#ledger_code').val(response);
            }).fail(function (response) {
                console.log(response)
            });
        })

        $(document).on('submit', '#ledger-account-form', function (e) {
            e.preventDefault();
            let form = $(this);
            let url = form.attr('action')

            let formData = form.serialize() +
                '&parent_account_id=' + $('#parent_ac').val() +
                '&group_account_id=' + $('#group_ac').val() +
                '&control_account_id=' + $('#control_ac').val() +
                '&type_id=' + $('#type').val() +
                '&account_type=4'

            $.ajax({
                type: 'POST',
                url: url,
                data: formData
            }).done(function (response) {
                $('#ledgerModal').modal('hide')
                let select2_option_value = new Option(response.name, response.id, true, true)
                $(select2_option_value).html(response.name)
                $("#ledger_ac").append(select2_option_value).trigger('change')
            }).fail(function (response) {
                let text = response.responseText;
                let error = JSON.parse(text).errors;
                $('#error_ledger_name').text(error.name);
            });
        })

        $(document).on('change', '#type', function () {
            $.ajax({
                type: 'GET',
                url: `/finance/api/v1/get-parent-account?type_id=${$(this).val()}`,
            }).done(function (response) {
                setNextAccount(response, 'parent_ac')
            })
        })

        $(document).on('change', '#parent_ac', function () {
            $.ajax({
                type: 'GET',
                url: `/finance/api/v1/get-group-account?parent_account_id=${$(this).val()}`,
            }).done(function (response) {
                setNextAccount(response, 'group_ac')
            })
        })

        $(document).on('change', '#group_ac', function () {
            $.ajax({
                type: 'GET',
                url: `/finance/api/v1/get-control-account?parent_account_id=${$('#parent_ac').val()}&group_account_id=${$(this).val()}`,
            }).done(function (response) {
                setNextAccount(response, 'control_ac')
            })
        })

        $(document).on('change', '#control_ac', function () {
            $.ajax({
                type: 'GET',
                url: `/finance/api/v1/get-ledger-account?parent_account_id=${$('#parent_ac').val()}&group_account_id=${$('#group_ac').val()}&control_account_id=${$(this).val()}`,
            }).done(function (response) {
                setNextAccount(response, 'ledger_ac')
            })
        })

        function setNextAccount(response, id) {
            var selectElement = $("#" + id)
            selectElement.empty()
            selectElement.append(`<option value="">Select</option>`)
            $.each(response, function (index, value) {
                selectElement.append(`<option value="${value.id}">${value.name}</option>`)
            })
            selectElement.select2()
        }

    </script>
@endsection
