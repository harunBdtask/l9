<style>
    .custom-padding {
        padding: 0 80px 0 80px;
    }

    #tabular-form table,
    #tabular-form thead,
    #tabular-form tbody,
    #tabular-form th,
    #tabular-form td {
        padding: 3px !important;
        vertical-align: middle !important;
        font-size: 12px;
        text-align: center;
        border-color: black;
    }

    .hide {
        display: none;
    }

    .show {
        display: block;
    }
</style>
<div class="vourcher-area">
    {!! Form::open([
        'method' => isset($voucher) ? 'PUT' : 'POST',
        'url' => isset($voucher) ? url('finance/vouchers_all/' . $voucher->id) : url('finance/vouchers_all'),
        'id' => 'debit-voucher-form',
    ]) !!}
    <input type="hidden" value="{{ isset($voucher) ? $voucher->id : null }}" id="id">
    <div class="from-group row message"></div>
    <input type="hidden" name="type_id" id="type_id" value="{{ isset($voucher) ? $voucher->type_id : null }}">


    <div class="col-md-4">
        <div class="form-group row">
            <label class="col-sm-4 form-control-label">Voucher Type:</label>
            <div class="col-sm-8">
                {!! Form::select('voucher_type', $voucherTypeList, $typeId, [
                    'class' => 'form-control select2-input',
                    'id' => 'voucher_type',
                    'placeholder' => 'Select Voucher Type',
                ]) !!}
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 form-control-label">Group:</label>
            <div class="col-sm-8">
                {!! Form::select('group_company', $group_companies, null, [
                    'class' => 'form-control select2-input',
                    'id' => 'group_company',
                ]) !!}
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 form-control-label">Company:</label>
            <div class="col-sm-8">
                @php
                    $companyId = isset($voucher) ? $voucher->factory_id : '';
                @endphp
                <?php echo Form::select('factory_id', $companies, $companyId, ['class' => 'form-control select2-input', 'id' => 'factory_id', 'placeholder' => 'Select a Company']); ?>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 form-control-label">Project:</label>
            <div class="col-sm-8">
                @php
                    $projectId = isset($voucher) ? $voucher->project_id : '';
                @endphp
                {!! Form::select('project_id', $projects, $projectId, [
                    'class' => 'form-control select2-input',
                    'id' => 'project_id',
                    'placeholder' => 'Select a Project',
                ]) !!}
            </div>
        </div>
    </div>

    <div class="col-md-4">

        <div class="form-group row">
            <label class="col-sm-4 form-control-label">Voucher No:</label>
            <div class="col-sm-8">
                <input name="voucher_no" id="voucher_no" type="text" class="form-control" autocomplete="off"
                    value="{{ $voucher->voucher_no }}" readonly>
            </div>
        </div>
        <div class="form-group row" id="paid_mode_area">
            <label class="col-sm-4 form-control-label" id="paid_mode_title">Paid Mode</label>
            <div class="col-sm-8">
                @php
                    $paymodeId = isset($voucher) ? $voucher->paymode : 1;
                @endphp
                {!! Form::select('paymode', $payModeList, $paymodeId, [
                    'class' => 'form-control select2-input',
                    'id' => 'paymode',
                    'placeholder' => 'Select',
                ]) !!}
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 form-control-label">Ref No:</label>
            <div class="col-sm-8">
                @php
                    $referenceNo = isset($voucher) ? $voucher->reference_no : '';
                @endphp
                <input type="text" class="form-control" name="reference_no" id="reference_no"
                    value="<?php echo e($referenceNo); ?>">
            </div>
        </div>
        <div class="form-group row" id="trn_date">
            <label class="col-sm-4 form-control-label">Transaction Date:</label>
            <div class="col-sm-8">
                @php
                    $trnDate = isset($voucher) ? $voucher->trn_date->format('Y-m-d') : date('Y-m-d');
                @endphp
                {!! Form::date('trn_date', $trnDate ?? null, ['class' => 'form-control', 'id' => 'trn_date']) !!}

            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group row" id="bank_info_area" style="display: none">
            <label class="col-sm-4 form-control-label" id="bank_title">Bank Name</label>
            <div class="col-sm-8">
                <div id="bank_name_area">
                    <input type="text" class="form-control" name="bank_name" id="bank_name"
                        value="{{ isset($voucher) ? $voucher->bank_id : null }}">
                    <input type="hidden" class="form-control" name="bank_id" id="bank_id" readonly disabled
                        value="{{ isset($voucher) ? $voucher->bank_id : null }}">
                </div>
                <div id="bank_account_area" style="display:none">
                    {!! Form::select('receive_bank_id', $bankAccounts, null, ['class' => 'form-control select2-input hide', 'id' => 'receive_bank_id']) !!}
                </div>

            </div>
        </div>
        <div class="form-group row" id="cheque_info_area" style="display: none">
            <label class="col-sm-4 form-control-label" id="cheque_no_title">Cheque No:</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" name="cheque_no" id="cheque_no"
                    value="{{ isset($voucher) ? $voucher->cheque_no : null }}">
                <input type="hidden" class="form-control" name="store_cheque_name" id="store_cheque_name" readonly
                    disabled>
            </div>
        </div>
        <div class="form-group row" id="due_date_area" style="display: none">
            <label class="col-sm-4 form-control-label">Due Date:</label>
            <div class="col-sm-8">
                {!! Form::date('cheque_due_date', isset($voucher) ? $voucher->cheque_due_date : null, ['class' => 'form-control', 'id' => 'cheque_due_date']) !!}
            </div>
        </div>

    </div>
    @php
        $items = $voucher->details->items;
        if (!empty($items)) {
            $size = count($items) - 1;
        } else {
            $size = 0;
        }
    @endphp

    <input type="hidden" name="tbl_row" id="tbl_row" value="{{ $size }}">
    <div class="form-group row">
        <div class="col-lg-12">
            <div class="table-responsive">
                <table class="table reportTable" id="tabular-form">
                    <thead class="thead-light" style="background-color: deepskyblue;">
                        <tr>
                            <th rowspan="2" style="width: 8%;">A/C CODE</th>
                            <th rowspan="2" style="width: 8%;">CONTROL A/C</th>
                            <th rowspan="2" style="width: 8%;">LEDGER A/C</th>
                            <th rowspan="2" style="width: 8%;">COST CENTER</th>
                            <th rowspan="2" style="width: 5%;">CURRENCY</th>
                            <th colspan="2" style="width: 20%;">FC</th>
                            <th rowspan="2" style="width: 8%;">CON. RATE</th>
                            <th colspan="2" style="width: 20%;">BDT</th>
                            <th class="text-right" style="width: 10%;" rowspan="2">NARRATION</th>
                            <th class="text-center" width="5%" rowspan="2">ACTION</th>
                        </tr>
                        <tr>
                            <th>Debit</th>
                            <th>Credit</th>
                            <th>Debit</th>
                            <th>Credit</th>
                        </tr>
                    </thead>
                    <tbody id="voucher-items-form">

                        @for ($i = 0; $i <= 29; $i++)

                            @php
                                $accountCode = $items[$i]->account_code ?? '';
                                $controllAccount = $items[$i]->account_id ?? '';
                                $controllLedger = $items[$i]->ledger_id ?? '';
                                $controllLedgerName = $items[$i]->ledger_name ?? '';
                                $constCenter = $items[$i]->const_center ?? '';
                                $currencyId = $items[$i]->currency_id ?? '';
                                $conversionRate = $items[$i]->conversion_rate ?? '';
                                $crBd = $items[$i]->cr_bd ?? '';
                                $crFc = $items[$i]->cr_fc ?? '';
                                $drBd = $items[$i]->dr_bd ?? '';
                                $drFc = $items[$i]->dr_fc ?? '';
                                $narration = $items[$i]->narration ?? '';
                            @endphp
                            <tr class="tr_clone" id="row_{{ $i }}" data-sl="{{ $i }}"
                                style="{{ $i > $size ? 'display:none' : '' }}">
                                <td>
                                    <input type="text" name="account_code[{{ $i }}]"
                                        id="account_code_{{ $i }}" class="form-control account_code"
                                        autocomplete="off" readonly value="{{ $accountCode ? $accountCode : '' }}">
                                </td>
                                <td>
                                    <select class="form-control c-select  select2-input select2-field account"
                                        name="account[{{ $i }}]" id="account_{{ $i }}"
                                        data-sl="{{ $i }}">
                                        <option value="0">Select</option>
                                        @foreach ($accounts as $account)
                                            <option value="{{ $account->id }}" data-id="{{ $account->id }}"
                                                {{ $controllAccount ? ($controllAccount == $account->id ? 'selected' : '') : '' }}
                                                data-name="{{ $account->name }}"
                                                data-code="{{ $account->code }}">
                                                {{ $account->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select class="form-control c-select select2-input select2-field ledger_id"
                                        name="ledger_id[{{ $i }}]" id="ledger_id_{{ $i }}">
                                        <option value="0">Select</option>
                                        @if ($controllLedger)
                                            <option value="{{ $controllLedger }}"
                                                {{ $controllLedger ? 'selected' : '' }}>
                                                {{ $controllLedgerName }}
                                            </option>
                                        @endif
                                    </select>
                                </td>
                                <td>
                                    <select class="form-control c-select select2-input  select2-field const_center"
                                        name="const_center[{{ $i }}]"
                                        id="const_center_{{ $i }}">
                                        <option value="0">Select</option>
                                        @foreach ($cost_centers as $item)
                                            <option value="{{ $item->id }}" data-id="{{ $item->id }}"
                                                {{ $constCenter ? ($constCenter == $item->id ? 'selected' : '') : '' }}
                                                data-name="{{ $item->cost_center }}">
                                                {{ $item->cost_center }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select class="form-control c-select select2-input  select2-field currency_id"
                                        name="currency_id[{{ $i }}]"
                                        id="currency_id_{{ $i }}">
                                        <option value="0">Select</option>
                                        @foreach ($currencies as $key => $item)
                                            <option value="{{ $key }}" data-id="{{ $key }}"
                                                {{ $currencyId ? ($currencyId == $key ? 'selected' : '') : '' }}
                                                data-name="{{ $item }}">
                                                {{ $item }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>

                                <td>
                                    <input type="number" name="fc_debit[{{ $i }}]"
                                        id="fc_debit_{{ $i }}" class="form-control fc_debit"
                                        {{ $drFc > 0 ? 'value=' . $drFc : 'readonly' }} autocomplete="off">
                                </td>
                                <td>
                                    <input type="number" name="fc_credit[{{ $i }}]"
                                        id="fc_credit_{{ $i }}" class="form-control fc_credit"
                                        {{ $crFc > 0 ? 'value=' . $crFc : 'readonly' }} autocomplete="off">
                                </td>
                                <td>
                                    <input type="number" name="conversion_rate[{{ $i }}]"
                                        id="conversion_rate_{{ $i }}" class="form-control conversion_rate"
                                        {{ $conversionRate == 1 ? 'readonly value=' . $conversionRate : 'value=' . $conversionRate }}
                                        autocomplete="off">
                                </td>
                                <td>
                                    <input type="number" name="bdt_debit[{{ $i }}]"
                                        id="bdt_debit_{{ $i }}" class="form-control bdt_debit"
                                        {{ $drBd > 0 ? 'value=' . $drBd : 'readonly' }} autocomplete="off">
                                </td>
                                <td>
                                    <input type="number" name="bdt_credit[{{ $i }}]"
                                        id="bdt_credit_{{ $i }}" class="form-control bdt_credit"
                                        {{ $crBd > 0 ? 'value=' . $crBd : 'readonly' }} autocomplete="off">
                                </td>
                                <td>
                                    <input type="text" name="narration[{{ $i }}]"
                                        id="narration_{{ $i }}" class="form-control narration"
                                        value="{{ $narration ? $narration : '' }}" autocomplete="off">
                                </td>
                                <td>
                                    <a class="btn btn-primary btn-icon btn-sm add-to-cart"
                                        data-sl="{{ $i }}">
                                        <i class="fa fa-plus"></i>
                                    </a>
                                    <button class="btn btn-danger btn-icon btn-sm delete_row"
                                        data-sl="{{ $i }}">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endfor
                    </tbody>
                    <tfoot>
                        <tr>
                            <td class="text-right" colspan="5"><strong>TOTAL</strong></td>
                            <td class="text-right total-fc-debit" data-total-fc-debit="{{ $total_debit_fc }}">
                                {{ $total_debit_fc }}</td>
                            <td class="text-right total-fc-credit" data-total-fc-credit="{{ $total_credit_fc }}">
                                {{ $total_credit_fc }}</td>
                            <td class="text-center"></td>
                            <td class="text-right total-bdt-debit" data-total-bdt-debit="{{ $total_debit }}">
                                {{ $total_debit }}</td>
                            <td class="text-right total-bdt-credit" data-total-bdt-credit="{{ $total_credit }}">
                                {{ $total_credit }}</td>
                            <td class="text-center"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-12 text-right">
            @if (!isset($voucher))
                <span class="btn btn-success voucher-submit-copy-btn" style="display: none">Save & Copy</span>
            @endif
            <span class="btn btn-primary voucher-submit-btn">{{ isset($voucher) ? 'Update' : 'Save' }}</span>
            @if (!isset($voucher))
                <a href="{{ url('finance/vouchers/entry') }}" class="btn btn-info voucher-refresh-btn">Refresh</a>
            @endif
            <a class="btn btn-danger" href="{{ url('finance/vouchers') }}">
                Cancel
            </a>
        </div>
    </div>
    {!! Form::close() !!}

</div>
@section('scripts')
    <script type="text/javascript">

        @php
            if (isset($voucher)) {
                echo 'const Cart = ' . json_encode($voucher->details) . ';';
            }
        @endphp

        const DebitVoucherCart = {
            'trn_date': null,
            'voucher_no': '',
            'bank_id': '',
            'receive_bank_id': '',
            'cheque_no': '',
            'receive_cheque_no': '',
            'cheque_due_date': '',
            'group_company': '',
            'factory_id': '',
            'items': [],
            'total_debit_fc': 0,
            'total_debit': 0,
            'total_credit': 0,
            'total_credit_fc': 0,
            'general_particulars': null,
            'debit_account': null,
            'debit_account_name': '',
            'debit_account_code': '',
            'credit_account': null,
            'credit_account_name': '',
            'credit_account_code': '',
            'unit_id': '',
            'department_id': '',
            'currency_id': '',
            'paymode': '',
            'to': '',
            'from': '',
            'reference_no': '',
        };

        if (typeof Cart !== 'undefined') {
            DebitVoucherCart.trn_date = Cart.trn_date;
            DebitVoucherCart.voucher_no = Cart.voucher_no;
            DebitVoucherCart.paymode = Cart.paymode;
            DebitVoucherCart.bank_id = Cart.bank_id;
            DebitVoucherCart.receive_bank_id = Cart.receive_bank_id;
            DebitVoucherCart.cheque_no = Cart.cheque_no;
            DebitVoucherCart.receive_cheque_no = Cart.receive_cheque_no;
            DebitVoucherCart.cheque_due_date = Cart.cheque_due_date;
            DebitVoucherCart.group_company = Cart.group_company;
            DebitVoucherCart.factory_id = Cart.factory_id;
            DebitVoucherCart.project_id = Cart.project_id;
            DebitVoucherCart.unit_id = Cart.unit_id;
            DebitVoucherCart.currency_id = Cart.currency_id;
            DebitVoucherCart.items = Cart.items;
            DebitVoucherCart.total_debit = Cart.total_debit;
            DebitVoucherCart.total_credit = Cart.total_credit;
            DebitVoucherCart.general_particulars = Cart.general_particulars;
            DebitVoucherCart.credit_account = Cart.credit_account;
            DebitVoucherCart.credit_account_name = Cart.credit_account_name;
            DebitVoucherCart.credit_account_code = Cart.credit_account_code;
        }

        let editIndex = null;

        var DebitVoucher = {
            changeVocherType: function() {
                let voucher = this;
                $('select[name="voucher_type"]').change(function() {
                    let vourcher_type = $(this).val();
                    voucher.fetchVoucherTypeInfo(vourcher_type);
                });
            },
            whenEditIdFound: function() {
                let voucher = this;
                let id = $('#id').val();
                let companyId = $('#factory_id').val();
                let projectId = $('#project_id').val();
                let payMode = $('#paymode').val();
                if (id) {
                    voucher.fetchProjects(companyId);
                    voucher.fetchUnits(companyId, projectId);
                }
            },
            changeCompany: function() {
                let voucher = this;
                $('select[name="factory_id"]').change(function() {
                    let companyId = $(this).val();
                    voucher.fetchProjects(companyId);
                })
            },
            fetchProjects: function(companyId) {
                if (companyId) {
                    let projectId = $(`#project_id`).val();
                    axios.get(`/finance/api/v1/fetch-company-wise-projects/${companyId}`).then((response) => {
                        let projects = response.data;
                        let options = [];
                        $(`#project_id`).val(projectId ?? '').change();
                        $(`#project_id`).find('option').not(':first').remove();
                        projects.forEach((project) => {
                            options.push([
                                `<option value="${project.id}" data-id="${project.id}" data-name="${project.text}">${project.text}</option>`
                            ].join(''));
                        });
                        $('#project_id').append(options);
                        $('#project_id').select2('val', 0);
                    }).catch((error) => console.log(error))
                }
            },
            changeProject: function() {
                let voucher = this;
                $('select[name="project_id"]').change(function() {
                    let companyId = $('#factory_id').val();
                    let projectId = $(this).val();
                    voucher.fetchUnits(companyId, projectId);
                })
            },
            fetchUnits: function(companyId, projectId) {
                if (companyId && projectId) {
                    let unitId = $(`#unit_id`).val();
                    axios.get(`/finance/api/v1/fetch-project-wise-units/${companyId}/${projectId}`).then((
                        response) => {
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
                        $('#unit_id').select2('val', 0);
                    }).finally(() => {
                        if (editIndex) {
                            jQuery('#debit-voucher-form select[name="const_center"]').select2('val',
                                DebitVoucherCart.items[editIndex].const_center);
                        }
                    })
                }
            },
            factory: function() {
                $('#debit-voucher-form select[name="factory_id"]').change(function() {
                    $(this).removeClass('invalid');
                });
            },
            project: function() {
                $('#debit-voucher-form select[name="project_id"]').change(function() {
                    $(this).removeClass('invalid');
                });
            },
            unit: function() {
                $('#debit-voucher-form select[name="unit_id"]').change(function() {
                    $(this).removeClass('invalid');
                });
            },
            paymode: function() {
                $('#debit-voucher-form select[name="paymode"]').change(function() {
                    $(this).removeClass('invalid');
                });
            },
            trnDate: function() {
                $('#debit-voucher-form input[name="trn_date"]').focusin(function() {
                    $(this).removeClass('invalid');
                });
            },
            showChequeNo: function() {
                $('#debit-voucher-form input[name="show_cheque_no"]').click(function() {
                    if ($(this).is(":checked")) {
                        $('#debit-voucher-form input[name="cheque_name"]').val('');
                    } else {
                        const chequeName = $('#debit-voucher-form input[name="store_cheque_name"]').val();
                        $('#debit-voucher-form input[name="cheque_name"]').val(chequeName);
                    }
                });
            },
            account: function() {
                $('.account').change(function() {
                    $(this).removeClass('invalid');
                    var sl = $(this).attr('data-sl');
                    let account = $(this).find(":selected");
                    $('#account_code_' + sl).val(account.attr('data-code'));

                    if (account) {
                        axios.get(`/finance/api/v1/get-ledger-account-by-control-ac/${account.val()}`)
                            .then((response) => {
                                let ledgers = response.data.data;
                                console.log(ledgers);
                                if (ledgers.length > 0) {
                                    let ledgers_html = ledgers.map(function(item) {
                                        return '<option value="' + item.control_ledger_account
                                            .id + '" data-code="' + item.control_ledger_account
                                            .code + '">' + item.control_ledger_account.name +
                                            '</option>';
                                    });
                                    $('#ledger_id_' + sl).val('').trigger('change');
                                    $('#ledger_id_' + sl).html(
                                        '<option value="" data-code="" selected>Select</option>' +
                                        ledgers_html);
                                } else {
                                    $('#ledger_id_' + sl).html(
                                        '<option value="" data-code="" selected>Select</option>');
                                    $('#ledger_id_' + sl).empty();
                                }
                            })
                    }

                });
            },
            ledger_account: function() {
                $('.ledger_id').change(function() {
                    let account = $(this).find(":selected");
                    var sl = $(this).parent().parent().attr('data-sl');
                    $('#account_code_' + sl).val(account.attr('data-code'));
                });
            },
            department: function() {
                $('#debit-voucher-form select[name="department_id"]').change(function() {
                    $(this).removeClass('invalid');
                });
            },
            constCenter: function() {
                $('.const_center').change(function() {
                    $(this).removeClass('invalid');
                });
            },
            currency: function() {
                $('.currency_id').change(function() {
                    $(this).removeClass('invalid');
                    let sl = $(this).parent().parent().attr('data-sl');
                    let currency = $(this).val();
                    if (currency == 1) {
                        $('#conversion_rate_' + sl).val(1).attr('readonly', 'true');
                        $('#fc_debit_' + sl).val(null).attr('readonly', 'true');
                        $('#fc_credit_' + sl).val(null).attr('readonly', 'true');
                        $('#bdt_debit_' + sl).val(null).removeAttr('readonly');
                        $('#bdt_credit_' + sl).val(null).removeAttr('readonly');
                    } else {
                        $('#conversion_rate_' + sl).val(null).removeAttr('readonly');
                        $('#fc_debit_' + sl).val(null).removeAttr('readonly');
                        $('#fc_credit_' + sl).val(null).removeAttr('readonly');
                        $('#bdt_debit_' + sl).val(null).attr('readonly', 'true');
                        $('#bdt_credit_' + sl).val(null).attr('readonly', 'true');
                    }
                });
            },
            conversionRate: function() {
                let voucher = this;
                $('.conversion_rate').keyup(function() {
                    let conversionRate = $(this).val();
                    if (isNaN(conversionRate)) {
                        voucher.errorMessage('Conversion Rate must be a number');
                        $(this).val('');
                    }
                });
            },
            changePayMode: function() {
                let voucher = this;
                $('select[name="paymode"]').change(function() {
                    let payMode = $(this).val();
                    let voucher_type = $('#voucher_type').val();

                    if (payMode == '2') { //cheque
                        $('#cheque_no_title').text('Cheque No');
                        $('#bank_info_area').show();
                        $('#cheque_info_area').show();
                        $('#due_date_area').show();
                        if (voucher_type == 'credit') {
                            $('#bank_title').text('Bank Name');
                            $('#bank_name_area').show();
                            $('#bank_account_area').hide();
                        } else if (voucher_type == 'debit') {
                            $('#bank_title').text('A/C Number');
                            $('#bank_name_area').hide();
                            $('#bank_account_area').show();
                        } else {
                            $('#bank_info_area').show();
                        }
                    } else if (payMode == '3') { //LC
                        $('#cheque_no_title').text('LC No');
                        $('#bank_info_area').hide();
                        $('#cheque_info_area').show();
                        $('#due_date_area').show();
                    } else {
                        $('#bank_info_area').hide();
                        $('#cheque_info_area').hide();
                        $('#due_date_area').hide();
                    }
                });
            },
            fetchVoucherTypeInfo: function(voucher_type) {
                if (voucher_type == 'debit') {
                    $('#paid_mode_title').text('Paid Mode');
                    $('#paid_mode_area').show();
                    $('#type_id').val({{ \SkylarkSoft\GoRMG\Finance\Models\Voucher::DEBIT_VOUCHER }});
                } else if (voucher_type == 'credit') {
                    $('#paid_mode_title').text('Received Mode');
                    $('#paid_mode_area').show();
                    $('#type_id').val({{ \SkylarkSoft\GoRMG\Finance\Models\Voucher::CREDIT_VOUCHER }});
                } else {
                    $('#paid_mode_area').hide();
                    $('#type_id').val({{ \SkylarkSoft\GoRMG\Finance\Models\Voucher::JOURNAL_VOUCHER }});
                }
                if (voucher_type) {
                    axios.get(`/finance/api/v1/get-voucher-type-wise-info/${voucher_type}`)
                        .then((response) => {
                            let voucher_no = response.data.data;
                            $('#voucher_no').val(voucher_no);
                        })
                }

            },

            fetchPayModeWiseCreditAccount: function(payMode) {
                axios.get(`/finance/api/v1/get-pay-mode-wise-accounts/${payMode}`)
                    .then((response) => {
                        let accounts = response.data;
                        let options = [];
                        $('#credit_account').html(`<option value="0">Select</option>`);
                        $('#debit-voucher-form select[name="credit_account"]').select2('val', '0');
                        accounts.forEach((account) => {
                            options.push([
                                `<option  value="${account.id}" data-id="${account.id}" data-bank-ac="${account.id}" data-name="${account.name}" data-code="${account.code}">${account.text}</option>`
                            ].join(''));
                        });
                        $('#credit_account').append(options);
                    })
                    .finally(() => {
                        jQuery('#debit-voucher-form select[name="credit_account"]').select2('val',
                            DebitVoucherCart.credit_account);
                    })
            },
            changeCreditAccount: function() {
                let voucher = this;
                $('select[name="credit_account"]').change(function() {
                    let payMode = $('#paymode').val();
                    if (payMode == 1) {
                        let bankAccountId = $('#debit-voucher-form select#credit_account option:selected')
                            .attr('data-bank-ac');
                        voucher.fetchBankOfDebitAcc(bankAccountId, payMode);
                    }
                });
            },
            fetchBankOfDebitAcc: function(bankAccountId, payMode) {
                if (bankAccountId, payMode) {
                    axios.get(`/finance/api/v1/get-parent-bank-acc-no/${bankAccountId}`)
                        .then((response) => {
                            let bankName = response.data;
                            if (bankName) {
                                $('#debit-voucher-form input[name="bank_name"]').val(bankName.text);
                                $('#debit-voucher-form input[name="bank_id"]').val(bankName.id);
                                if ((window.location.pathname == '/finance/vouchers/create') || (payMode ==
                                        1)) {
                                    this.fetchCreditAccountWiseChequeNo(bankName.bankAccId);
                                }
                            }
                        })
                }
            },
            fetchCreditAccountWiseChequeNo: function(bankAccountId) {
                if (bankAccountId) {
                    axios.get(`/finance/api/v1/get-cheque-no/${bankAccountId}`)
                        .then((response) => {
                            let chequeNo = response.data;
                            if (chequeNo) {
                                $('#debit-voucher-form input[name="cheque_name"]').val(chequeNo.text);
                                $('#debit-voucher-form input[name="store_cheque_name"]').val(chequeNo.text);
                                $('#debit-voucher-form input[name="cheque_no"]').val(chequeNo.id);
                            } else {
                                $('#debit-voucher-form input[name="cheque_name"]').val('');
                                $('#debit-voucher-form input[name="cheque_no"]').val();
                            }
                        })
                }
            },

            debit: function() {
                let voucher = this;

                $('.fc_debit').keyup(function() {
                    let sl = $(this).parent().parent().attr('data-sl');
                    let value = $(this).val();
                    if (value != '') {
                        let conversionRate = $('#conversion_rate_' + sl).val();
                        let drFc = $(this).val();
                        if (isNaN(drFc)) {
                            voucher.errorMessage('FC Debit must be a number');
                            return;
                        }
                        let drBd = parseFloat(conversionRate) * parseFloat(drFc);
                        $('#bdt_debit_' + sl).val(drBd || '').attr('readonly', true);
                        $('#bdt_credit_' + sl).val(null).attr('readonly', true);
                        $('#fc_credit_' + sl).val(null).attr('readonly', true);
                        voucher.calculateTotal();
                    }

                });
                $('.conversion_rate').keyup(function() {
                    let sl = $(this).parent().parent().attr('data-sl');
                    let conversionRate = $(this).val();
                    let fc_debit = $('#fc_debit_' + sl).val();
                    let fc_credit = $('#fc_credit_' + sl).val();
                    if (isNaN(conversionRate)) {
                        voucher.errorMessage('Conversion rate is not a number');
                        return;
                    }
                    let bdt_debit = parseFloat(conversionRate) * parseFloat(fc_debit);
                    let bdt_credit = parseFloat(conversionRate) * parseFloat(fc_credit);
                    $('#bdt_debit_' + sl).val(bdt_debit || '');
                    $('#bdt_credit_' + sl).val(bdt_credit || '');
                    voucher.calculateTotal();
                });
                $('.bdt_debit').keyup(function() {
                    let sl = $(this).parent().parent().attr('data-sl');
                    let value = $(this).val();
                    if (value != '') {
                        $('#bdt_credit_' + sl).val(null).attr('readonly', true);
                        voucher.calculateTotal();
                    }
                });

            },
            credit: function() {
                let voucher = this;
                $('.fc_credit').keyup(function() {
                    let sl = $(this).parent().parent().attr('data-sl');
                    let value = $(this).val();
                    if (value != '') {
                        let conversionRate = $('#conversion_rate_' + sl).val();
                        let fcCredit = $(this).val();
                        if (isNaN(fcCredit)) {
                            voucher.errorMessage('FC Credit must be a number');
                            return;
                        }
                        let bdtCredit = parseFloat(conversionRate) * parseFloat(fcCredit);
                        $('#bdt_credit_' + sl).val(bdtCredit || '').attr('readonly', true);
                        $('#bdt_debit_' + sl).val(null).attr('readonly', true);
                        $('#fc_debit_' + sl).val(null).attr('readonly', true);
                        voucher.calculateTotal();
                    }
                });
                $('.bdt_credit').keyup(function() {
                    let sl = $(this).parent().parent().attr('data-sl');
                    let value = $(this).val();
                    if (value != '') {
                        $('#bdt_debit_' + sl).val(null).attr('readonly', true);
                        voucher.calculateTotal();
                    }
                });
            },
            calculateTotal: function() {
                let voucher = this;

                var totalFcDebit = 0;
                var totalFcCredit = 0;
                var totalBdtDebit = 0;
                var totalBdtCredit = 0;

                $(".fc_debit").each(function() {
                    totalFcDebit += parseFloat($(this).val()) || 0;
                });
                $(".fc_credit").each(function() {
                    totalFcCredit += parseFloat($(this).val()) || 0;
                });
                $(".bdt_debit").each(function() {
                    totalBdtDebit += parseFloat($(this).val()) || 0;
                });
                $(".bdt_credit").each(function() {
                    totalBdtCredit += parseFloat($(this).val()) || 0;
                });

                $('.total-fc-debit').html(totalFcDebit.toFixed(2));
                $('.total-fc-credit').html(totalFcCredit.toFixed(2));
                $('.total-bdt-debit').html(totalBdtDebit.toFixed(2));
                $('.total-bdt-credit').html(totalBdtCredit.toFixed(2));

                DebitVoucherCart.total_debit_fc = parseFloat(totalFcDebit.toFixed(2));
                DebitVoucherCart.total_debit = parseFloat(totalBdtDebit.toFixed(2));
                DebitVoucherCart.total_credit_fc = parseFloat(totalFcCredit.toFixed(2));
                DebitVoucherCart.total_credit = parseFloat(totalBdtCredit.toFixed(2));

                if ((totalBdtDebit.toFixed(2)) > 0 && (totalBdtDebit.toFixed(2) == totalBdtCredit.toFixed(2))) {
                    $('.voucher-submit-copy-btn').show();
                    $('.voucher-submit-btn').show();
                } else {
                    $('.voucher-submit-copy-btn').hide();
                    $('.voucher-submit-btn').hide();
                }
            },
            validateRow: function(sl, insertItem = false) {
                var voucher = this;

                let account = $('#debit-voucher-form select#account_' + sl + ' option:selected'),
                    account_code = $('#debit-voucher-form #account_code_' + sl).val(),
                    ledger_id = $('#debit-voucher-form #ledger_id_' + sl).val(),
                    ledger_name = $('#debit-voucher-form select#ledger_id_' + sl + ' option:selected'),
                    const_center = $('#debit-voucher-form select#const_center_' + sl + ' option:selected'),
                    conversion_rate = $('#debit-voucher-form #conversion_rate_' + sl).val(),
                    fc_debit = $('#debit-voucher-form #fc_debit_' + sl).val(),
                    fc_credit = $('#debit-voucher-form #fc_credit_' + sl).val(),
                    bdt_debit = $('#debit-voucher-form #bdt_debit_' + sl).val(),
                    bdt_credit = $('#debit-voucher-form #bdt_credit_' + sl).val(),
                    narration = $('#debit-voucher-form #narration_' + sl).val(),
                    currency_id = $('#currency_id_' + sl).val(),
                    currency_name = $('#debit-voucher-form select#currency_id_' + sl + ' option:selected'),
                    validItem = true;

                if (!parseInt(account.attr('data-id'))) {
                    $('#debit-voucher-form select#account_' + sl).addClass('invalid');
                    validItem = false;
                }

                if (!parseInt(const_center.attr('data-id'))) {
                    $('#debit-voucher-form select#const_center_' + sl).addClass('invalid');
                    validItem = false;
                }

                if (conversion_rate == '') {
                    $('#debit-voucher-form input#conversion_rate_' + sl).addClass('invalid');
                    validItem = false;
                }

                if (!parseInt(currency_id)) {
                    $('#debit-voucher-form select#currency_id_' + sl).addClass('invalid');
                    validItem = false;
                }

                if (currency_id == '1') { // BDT
                    if ((bdt_debit == '') && (bdt_credit == '')) {
                        $('#debit-voucher-form input#bdt_debit_' + sl).addClass('invalid');
                        $('#debit-voucher-form input#bdt_credit_' + sl).addClass('invalid');
                        validItem = false;
                    }
                } else {
                    if ((fc_debit == '') && (fc_credit == '')) {
                        $('#debit-voucher-form input#fc_debit_' + sl).addClass('invalid');
                        $('#debit-voucher-form input#fc_credit_' + sl).addClass('invalid');
                        validItem = false;
                    }
                }

                if (!validItem) {
                    return;
                }

                $('#debit-voucher-form select#account_' + sl).removeClass('invalid');
                $('#debit-voucher-form input#account_code_' + sl).removeClass('invalid');
                $('#debit-voucher-form select#const_center_' + sl).removeClass('invalid');
                $('#debit-voucher-form select#currency_id_' + sl).removeClass('invalid');
                $('#debit-voucher-form input#conversion_rate_' + sl).removeClass('invalid');
                $('#debit-voucher-form input#fc_debit_' + sl).removeClass('invalid');
                $('#debit-voucher-form input#fc_credit_' + sl).removeClass('invalid');
                $('#debit-voucher-form input#bdt_debit_' + sl).removeClass('invalid');
                $('#debit-voucher-form input#bdt_credit_' + sl).removeClass('invalid');

                if (insertItem) {

                    let data = {
                        'account_id': account.attr('data-id'),
                        'account_code': account_code,
                        'account_name': account.attr('data-name'),
                        'ledger_id': ledger_id,
                        'ledger_name': ledger_name.text(),
                        'const_center_name': const_center.attr('data-name'),
                        'const_center': const_center.attr('data-id'),
                        'currency_id': currency_id,
                        'currency_name': currency_name.attr('data-name'),
                        'conversion_rate': conversion_rate ? conversion_rate : 1,
                        'dr_fc': fc_debit ? fc_debit : 0,
                        'cr_fc': fc_credit ? fc_credit : 0,
                        'dr_bd': bdt_debit ? bdt_debit : 0,
                        'cr_bd': bdt_credit ? bdt_credit : 0,
                        'item_type': bdt_debit ? 'debit' : 'credit',
                        'debit': bdt_debit ? bdt_debit : 0,
                        'credit': bdt_credit ? bdt_credit : 0,
                        'narration': narration,
                    };

                    let editIndex = (sl in DebitVoucherCart.items) ? true : false;

                    if (!editIndex) {
                        DebitVoucherCart.items.push(data);
                    } else {
                        DebitVoucherCart.items[sl] = {
                            ...data
                        };
                        editIndex = false;
                    }
                }

                return validItem;
            },
            addToCart: function() {
                let voucher = this;
                $("#debit-voucher-form .add-to-cart").on('click', function(e) {
                    e.preventDefault();
                    let sl = $(this).parent().parent().attr('data-sl');
                    let tbl_row = $('#tbl_row').val();

                    let account = $('#debit-voucher-form select#account_' + sl + ' option:selected'),
                        account_code = $('#debit-voucher-form #account_code_' + sl).val(),
                        ledger_id = $('#debit-voucher-form #ledger_id_' + sl).val(),
                        ledger_name = $('#debit-voucher-form select#ledger_id_' + sl + ' option:selected'),
                        const_center = $('#debit-voucher-form select#const_center_' + sl +
                            ' option:selected'),
                        conversion_rate = $('#debit-voucher-form #conversion_rate_' + sl).val(),
                        fc_debit = $('#debit-voucher-form #fc_debit_' + sl).val(),
                        fc_credit = $('#debit-voucher-form #fc_credit_' + sl).val(),
                        bdt_debit = $('#debit-voucher-form #bdt_debit_' + sl).val(),
                        bdt_credit = $('#debit-voucher-form #bdt_credit_' + sl).val(),
                        narration = $('#debit-voucher-form #narration_' + sl).val(),
                        currency_id = $('#currency_id_' + sl).val(),
                        currency = $('#currency_id_' + sl).val(),
                        currency_name = $('#debit-voucher-form select#currency_id_' + sl +
                            ' option:selected').text(),
                        validItem = true;

                    validItem = voucher.validateRow(sl);

                    if (!validItem) {
                        return;
                    }


                    tbl_row++;
                    if (tbl_row > 29) {
                        voucher.errorMessage('You can not add more than 30 items!');
                        return false;
                    }
                    $('#row_' + tbl_row).show();
                    $('#tbl_row').val(tbl_row);

                    $('#const_center_' + tbl_row).val($('#const_center_' + sl).val()).trigger('change');
                    $('#currency_id_' + tbl_row).val($('#currency_id_' + sl).val()).trigger('change');
                    $('#conversion_rate_' + tbl_row).val($('#conversion_rate_' + sl).val());
                    $('#narration_' + tbl_row).val($('#narration_' + sl).val());

                });

            },
            removeFromCart: function() {
                var voucher = this;

                $('#debit-voucher-form .delete_row').on('click', function(e) {
                    e.preventDefault();
                    let sl = $(this).parent().parent().attr('data-sl');
                    if (confirm('Are You Sure?')) {
                        $('#row_' + sl).find('input').val('');
                        $('#row_' + sl).find('.select2-input').val(null).trigger('change');
                        $('#row_' + sl).hide();
                    }
                });
            },
            editFromCart: function() {
                $('#debit-voucher-form').on('click', '.edit-from-cart', function() {
                    editIndex = $(this).attr('data-index');
                    $('#debit-voucher-form select[name="account"]').select2('val', DebitVoucherCart.items[
                        editIndex].account_id);
                    $('#debit-voucher-form input[name="account_code"]').val(DebitVoucherCart.items[
                        editIndex].account_code);
                    $('#debit-voucher-form select[name="department_id"]').select2('val', DebitVoucherCart
                        .items[editIndex].department_id);
                    $('#debit-voucher-form select[name="const_center"]').select2('val', DebitVoucherCart
                        .items[editIndex].const_center);
                    $('#debit-voucher-form input[name="conversion_rate"]').val(DebitVoucherCart.items[
                        editIndex].conversion_rate);
                    $('#debit-voucher-form input[name="dr_fc"]').val(DebitVoucherCart.items[editIndex]
                        .dr_fc);
                    $('#debit-voucher-form input[name="dr_bd"]').val(DebitVoucherCart.items[editIndex]
                        .dr_bd);
                    $('#debit-voucher-form input[name="narration"]').val(DebitVoucherCart.items[editIndex]
                        .narration);
                });
            },
            renderCart: function() {
                var cartLength = DebitVoucherCart.items.length,
                    trs = [],
                    totalFcDebit = 0,
                    totalFcCredit = 0;
                totalBdtDebit = 0;
                totalBdtCredit = 0;

                $('#debit-voucher-form .voucher-items').removeClass('text-danger');

                for (let i = 0; i < cartLength; i++) {
                    trs.push([
                        '<tr>',
                        '<td>' + DebitVoucherCart.items[i].account_code + '</td>',
                        '<td>' + DebitVoucherCart.items[i].account_name + '</td>',
                        '<td>' + DebitVoucherCart.items[i].ledger_name + '</td>',
                        '<td>' + DebitVoucherCart.items[i].const_center_name + '</td>',
                        '<td>' + DebitVoucherCart.items[i].currency_name + '</td>',
                        '<td class="text-right">' + (DebitVoucherCart.items[i].fc_debit ? parseFloat(
                            DebitVoucherCart.items[i].fc_debit).toFixed(2) : '') + '</td>',
                        '<td class="text-right">' + (DebitVoucherCart.items[i].fc_credit ? parseFloat(
                            DebitVoucherCart.items[i].fc_credit).toFixed(2) : '') + '</td>',
                        '<td>' + DebitVoucherCart.items[i].conversion_rate + '</td>',
                        '<td class="text-right">' + (DebitVoucherCart.items[i].bdt_debit ? parseFloat(
                            DebitVoucherCart.items[i].bdt_debit).toFixed(2) : '') + '</td>',
                        '<td class="text-right">' + (DebitVoucherCart.items[i].bdt_credit ? parseFloat(
                            DebitVoucherCart.items[i].bdt_credit).toFixed(2) : '') + '</td>',
                        '<td>' + DebitVoucherCart.items[i].narration + '</td>',
                        '<td class="text-center">',
                        '<a class="pointer-cursor text-danger remove-from-cart" data-id="' +
                        DebitVoucherCart.items[i].account_id + '"data-index="' + i +
                        '"><i class="fa fa-remove"></i></a>',
                        '<a class="pointer-cursor text-info edit-from-cart" style="margin-left: 5px;" data-id="' +
                        DebitVoucherCart.items[i].account_id + '"data-index="' + i +
                        '"><i class="fa fa-pencil"></i></a>',
                        '</td>',
                        '</tr>',
                    ].join(''));

                    totalFcDebit += parseFloat(DebitVoucherCart.items[i].fc_debit);
                    totalFcCredit += parseFloat(DebitVoucherCart.items[i].fc_credit);
                    totalBdtDebit += parseFloat(DebitVoucherCart.items[i].bdt_debit);
                    totalBdtCredit += parseFloat(DebitVoucherCart.items[i].bdt_credit);
                }

                if (!cartLength) {
                    trs.push([
                        '<tr>',
                        '<td colspan="11" class="text-center">No Items in the Cart</td>',
                        '</tr>'
                    ].join(''));
                }

                $('#debit-voucher-form .voucher-items').html(trs.join(''));
                $('#debit-voucher-form .total-fc-debit').html(totalFcDebit.toFixed(2));
                $('#debit-voucher-form .total-fc-credit').html(totalFcCredit.toFixed(2));
                $('#debit-voucher-form .total-bdt-debit').html(totalBdtDebit.toFixed(2));
                $('#debit-voucher-form .total-bdt-credit').html(totalBdtCredit.toFixed(2));

            },
            voucherTransactions: function() {
                var voucher = this;

                let tbl_row = $('#tbl_row').val();
                for (let i = 0; i <= tbl_row; i++) {
                    validItem = voucher.validateRow(i, true);

                    if (!validItem) {
                        return;
                    }
                }
                return validItem;
            },
            updateChequeDetails: function(chequeId, to, amount, trnDate, dueDate) {
                if (chequeId && to && amount && trnDate && dueDate) {
                    axios.get(
                        `/finance/api/v1/update-cheque-details/${chequeId}/${to}/${amount}/${trnDate}/${dueDate}`
                    )
                }
            },
            submitAndCopyForm: function() {
                var voucher = this;
                $('#debit-voucher-form .voucher-submit-copy-btn').click(function(event) {

                    validItem = voucher.voucherTransactions();
                    if (!validItem) {
                        return;
                    }

                    let creditAccount = $('#debit-voucher-form select#credit_account option:selected');
                    DebitVoucherCart.type_id = $('#debit-voucher-form input#type_id').val();
                    DebitVoucherCart.trn_date = $('#debit-voucher-form input[name="trn_date"]').val();
                    DebitVoucherCart.group_company = $('#debit-voucher-form select[name="group_company"]')
                        .val();
                    DebitVoucherCart.voucher_no = $('#debit-voucher-form input[name="voucher_no"]').val();
                    DebitVoucherCart.paymode = $('#debit-voucher-form #paymode').val();
                    if (DebitVoucherCart.paymode == 1) {
                        DebitVoucherCart.bank_id = null;
                        DebitVoucherCart.cheque_no = null;
                        DebitVoucherCart.cheque_due_date = null;
                    } else {
                        DebitVoucherCart.bank_id = $('#debit-voucher-form select[name="bank_id"]').val();
                        DebitVoucherCart.cheque_no = $('#debit-voucher-form input[name="cheque_no"]').val();
                        DebitVoucherCart.cheque_due_date = $(
                            '#debit-voucher-form input[name="cheque_due_date"]').val();
                    }
                    DebitVoucherCart.receive_bank_id = null;
                    DebitVoucherCart.receive_cheque_no = null;
                    DebitVoucherCart.factory_id = $('#factory_id').val();
                    DebitVoucherCart.general_particulars = $(
                        '#debit-voucher-form input[name="general_particulars"]').val();
                    DebitVoucherCart.total_credit = DebitVoucherCart.total_debit;
                    DebitVoucherCart.credit_account = creditAccount.attr('data-id');
                    DebitVoucherCart.credit_account_name = creditAccount.attr('data-name');
                    DebitVoucherCart.credit_account_code = creditAccount.attr('data-code');
                    DebitVoucherCart.project_id = $('#debit-voucher-form #project_id').val();
                    DebitVoucherCart.currency_id = $('#debit-voucher-form #currency_id_0').val();
                    DebitVoucherCart.to = $('#to').val();
                    DebitVoucherCart.reference_no = $('#debit-voucher-form #reference_no').val();

                    if (DebitVoucherCart.paymode == 2) {
                        voucher.updateChequeDetails(DebitVoucherCart.cheque_no, DebitVoucherCart.to,
                            DebitVoucherCart.total_debit, DebitVoucherCart.trn_date, DebitVoucherCart
                            .cheque_due_date);
                    }
                    if (voucher.validate()) {
                        let method = $('#debit-voucher-form input[name="_method"]').val();
                        $.ajax({
                            url: $('#debit-voucher-form').attr('action'),
                            type: "POST",
                            data: {
                                _token: $('#debit-voucher-form input[name="_token"]').val(),
                                _method: method ? method : 'POST',
                                type_id: DebitVoucherCart.type_id,
                                trn_date: DebitVoucherCart.trn_date,
                                voucher_no: DebitVoucherCart.voucher_no,
                                credit_account: DebitVoucherCart.credit_account,
                                bank_id: DebitVoucherCart.bank_id,
                                receive_bank_id: DebitVoucherCart.receive_bank_id,
                                cheque_no: DebitVoucherCart.cheque_no,
                                receive_cheque_no: DebitVoucherCart.receive_cheque_no,
                                cheque_due_date: DebitVoucherCart.cheque_due_date,
                                group_company: DebitVoucherCart.group_company,
                                factory_id: DebitVoucherCart.factory_id,
                                amount: DebitVoucherCart.total_debit,
                                general_particulars: DebitVoucherCart.general_particulars,
                                project_id: DebitVoucherCart.project_id,
                                currency_id: DebitVoucherCart.currency_id,
                                paymode: DebitVoucherCart.paymode,
                                reference_no: DebitVoucherCart.reference_no,
                                to: DebitVoucherCart.to,
                                details: JSON.stringify(DebitVoucherCart)
                            },
                            beforeSend: function() {
                                $('#debit-voucher-form .voucher-submit-copy-btn').html(
                                    'Submitting...');
                            },
                            success: function(previewUrl, status) {
                                DebitVoucherCart.trn_date = moment(new Date()).format(
                                    'DD-MM-YYYY');
                                DebitVoucherCart.items = [];
                                DebitVoucherCart.total_debit = 0;
                                DebitVoucherCart.total_credit = 0;
                                DebitVoucherCart.voucher_amount = 0;
                                DebitVoucherCart.general_particulars = null;
                                DebitVoucherCart.credit_account = 0;
                                DebitVoucherCart.credit_account = 0;
                                let bankAccountId = $(
                                    '#debit-voucher-form input[name="bank_id"]').val();


                                $('#debit-voucher-form .voucher-submit-copy-btn').html(
                                    'Save & Copy');
                                $('#debit-voucher-form').trigger("reset");
                                $("#debit-voucher-form .select2-input").select2("val", "0");

                                voucher.showSuccessMessage();

                                voucher.refreshForm();
                            },
                            error: function(error) {
                                let errors = {
                                    ...error.responseJSON.errors
                                };
                                $.each(errors, function(key, value) {
                                    $(`#debit-voucher-form #${key}`).addClass(
                                        'invalid');
                                    $(`#debit-voucher-form #${key}`).attr('title',
                                        value[0]);
                                });
                            }
                        });
                    }
                });
            },
            submitForm: function() {
                var voucher = this;
                $('#debit-voucher-form .voucher-submit-btn').click(function(event) {

                    validItem = voucher.voucherTransactions();
                    if (!validItem) {
                        return;
                    }

                    let creditAccount = $('#debit-voucher-form select#credit_account option:selected');
                    DebitVoucherCart.type_id = $('#debit-voucher-form input#type_id').val();
                    DebitVoucherCart.trn_date = $('#debit-voucher-form input[name="trn_date"]').val();
                    DebitVoucherCart.group_company = $('#debit-voucher-form select[name="group_company"]')
                        .val();
                    DebitVoucherCart.voucher_no = $('#debit-voucher-form input[name="voucher_no"]').val();
                    DebitVoucherCart.paymode = $('#debit-voucher-form #paymode').val();
                    if (DebitVoucherCart.paymode == 1) {
                        DebitVoucherCart.bank_id = null;
                        DebitVoucherCart.cheque_no = null;
                        DebitVoucherCart.cheque_due_date = null;
                    } else {
                        DebitVoucherCart.bank_id = $('#debit-voucher-form select[name="bank_id"]').val();
                        DebitVoucherCart.cheque_no = $('#debit-voucher-form input[name="cheque_no"]').val();
                        DebitVoucherCart.cheque_due_date = $(
                            '#debit-voucher-form input[name="cheque_due_date"]').val();
                    }
                    DebitVoucherCart.receive_bank_id = null;
                    DebitVoucherCart.receive_cheque_no = null;
                    DebitVoucherCart.factory_id = $('#factory_id').val();
                    DebitVoucherCart.general_particulars = $(
                        '#debit-voucher-form input[name="general_particulars"]').val();
                    DebitVoucherCart.total_credit = DebitVoucherCart.total_debit;
                    DebitVoucherCart.credit_account = creditAccount.attr('data-id');
                    DebitVoucherCart.credit_account_name = creditAccount.attr('data-name');
                    DebitVoucherCart.credit_account_code = creditAccount.attr('data-code');
                    DebitVoucherCart.project_id = $('#debit-voucher-form #project_id').val();
                    DebitVoucherCart.currency_id = $('#debit-voucher-form #currency_id_0').val();
                    DebitVoucherCart.to = $('#to').val();
                    DebitVoucherCart.reference_no = $('#debit-voucher-form #reference_no').val();

                    if (DebitVoucherCart.paymode == 2) {
                        voucher.updateChequeDetails(DebitVoucherCart.cheque_no, DebitVoucherCart.to,
                            DebitVoucherCart.total_debit, DebitVoucherCart.trn_date, DebitVoucherCart
                            .cheque_due_date);
                    }
                    if (voucher.validate()) {
                        let method = $('#debit-voucher-form input[name="_method"]').val();
                        let url = $('#debit-voucher-form').attr('action');
                        $.ajax({
                            url: url,
                            type: "POST",
                            data: {
                                _token: $('#debit-voucher-form input[name="_token"]').val(),
                                _method: method,
                                type_id: DebitVoucherCart.type_id,
                                trn_date: DebitVoucherCart.trn_date,
                                voucher_no: DebitVoucherCart.voucher_no,
                                credit_account: DebitVoucherCart.credit_account,
                                bank_id: DebitVoucherCart.bank_id,
                                receive_bank_id: DebitVoucherCart.receive_bank_id,
                                cheque_no: DebitVoucherCart.cheque_no,
                                receive_cheque_no: DebitVoucherCart.receive_cheque_no,
                                cheque_due_date: DebitVoucherCart.cheque_due_date,
                                group_company: DebitVoucherCart.group_company,
                                factory_id: DebitVoucherCart.factory_id,
                                amount: DebitVoucherCart.total_debit,
                                general_particulars: DebitVoucherCart.general_particulars,
                                project_id: DebitVoucherCart.project_id,
                                currency_id: DebitVoucherCart.currency_id,
                                paymode: DebitVoucherCart.paymode,
                                reference_no: DebitVoucherCart.reference_no,
                                to: DebitVoucherCart.to,
                                details: JSON.stringify(DebitVoucherCart)
                            },
                            beforeSend: function() {
                                $('#debit-voucher-form .voucher-submit-btn').html(
                                    'Submitting...');
                            },
                            success: function(previewUrl, status) {
                                DebitVoucherCart.trn_date = moment(new Date()).format(
                                    'DD-MM-YYYY');
                                DebitVoucherCart.voucher_no = '';
                                DebitVoucherCart.bank_id = '';
                                DebitVoucherCart.receive_bank_id = '';
                                DebitVoucherCart.cheque_no = '';
                                DebitVoucherCart.receive_cheque_no = '';
                                DebitVoucherCart.cheque_due_date = '';
                                DebitVoucherCart.factory_id = '';
                                DebitVoucherCart.items = [];
                                DebitVoucherCart.total_debit = 0;
                                DebitVoucherCart.total_credit = 0;
                                DebitVoucherCart.voucher_amount = 0;
                                DebitVoucherCart.general_particulars = null;
                                DebitVoucherCart.credit_account = 0;
                                DebitVoucherCart.credit_account_name = '';
                                DebitVoucherCart.credit_account_code = '';
                                DebitVoucherCart.unit_id = '';
                                DebitVoucherCart.project_id = '';
                                DebitVoucherCart.currency_id = '';
                                DebitVoucherCart.paymode = '';
                                DebitVoucherCart.to = '';
                                DebitVoucherCart.reference_no = '';

                                if (method) {
                                    location.replace(previewUrl);
                                } else {
                                    voucher.renderCart();

                                    $('#debit-voucher-form .voucher-submit-btn').html(
                                        'Process');
                                    $('#debit-voucher-form').trigger("reset");
                                    $("#debit-voucher-form .select2-input").select2("val", "0");

                                    voucher.showSuccessMessage();

                                    window.location = previewUrl;
                                }
                            },
                            error: function(error) {
                                let errors = {
                                    ...error.responseJSON.errors
                                };
                                $.each(errors, function(key, value) {
                                    $(`#debit-voucher-form #${key}`).addClass(
                                        'invalid');
                                    $(`#debit-voucher-form #${key}`).attr('title',
                                        value[0]);
                                });
                            }
                        });
                    }
                });
            },
            validate: function() {
                let validation = true;

                if (DebitVoucherCart.factory_id == 0) {
                    $('#debit-voucher-form #factory_id').addClass('invalid');
                    validation = false;
                }

                if (DebitVoucherCart.project_id == 0) {
                    $('#debit-voucher-form #project_id').addClass('invalid');
                    validation = false;
                }


                if (DebitVoucherCart.paymode == 0) {
                    $('#debit-voucher-form #paymode').addClass('invalid');
                    validation = false;
                }



                if (DebitVoucherCart.items.length == 0) {
                    $('#debit-voucher-form .voucher-items-form').addClass('text-danger');
                    validation = false;
                }


                return validation;
            },
            refreshForm: function() {
                let voucher = this;
                $('#debit-voucher-form .voucher-refresh-btn').click(function(event) {
                    $('#debit-voucher-form #voucher_type').val(null).trigger('change');
                    $('#debit-voucher-form #voucher_no').val(null);
                    $('#debit-voucher-form #currency_id').val(1).trigger('change');
                    $('#debit-voucher-form #paymode').val(null).trigger('change');
                    $('#debit-voucher-form input[name="reference_no"]').val(null);
                    $('#debit-voucher-form input[name="to"]').val(null);
                    $('#debit-voucher-form #receive_bank_id').val(null).trigger('change');
                    $('#debit-voucher-form input[name="bank_id"]').val(null);
                    $('#debit-voucher-form input[name="bank_name"]').val(null);
                    $('#debit-voucher-form input[name="cheque_name"]').val(null);
                    $('#debit-voucher-form input[name="cheque_no"]').val(null);

                    $('#debit-voucher-form input[name="account_code[]"]').val(null);
                    $('#debit-voucher-form input[name="account[]"]').val(0).trigger('change');
                    $('#debit-voucher-form input[name="fc_debit[]"]').val(null);
                    $('#debit-voucher-form input[name="fc_credit[]"]').val(null);
                    $('#debit-voucher-form input[name="bdt_credit[]"]').val(null);
                    $('#debit-voucher-form input[name="bdt_credit[]"]').val(null);

                    $('.total-fc-debit, .total-fc-credit, .total-bdt-debit, .total-bdt-credit').html(
                        '0.00');

                    $('.tr_clone').hide();
                    $('#row_0').show();
                    DebitVoucherCart.items = [];
                });
            },

            showSuccessMessage: function() {
                var successMessage = [
                    '<div class="col-lg-12">',
                    '<div class="alert alert-success alert-dismissible show" role="alert">',
                    '<strong>Voucher has been created successfully!</strong>',
                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">',
                    '<span aria-hidden="true">&times;</span>',
                    '</button>',
                    '</div>',
                    '</div>'
                ].join('');

                $('#debit-voucher-form div.message').html(successMessage);
            },
            errorMessage: function(message) {
                let errorMessage = [
                    '<div class="col-lg-12">',
                    '<div class="alert alert-danger alert-dismissible show" role="alert">',
                    '<strong>' + message + '</strong>',
                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">',
                    '<span aria-hidden="true">&times;</span>',
                    '</button>',
                    '</div>',
                    '</div>'
                ].join('');

                $('#debit-voucher-form div.message').html(errorMessage);
            },
            init: function() {
                $("#debit-voucher-form select#currency_id").val();

                if ($('#debit-voucher-form #currency_id').val() != 1) {
                    $('#debit-voucher-form input[name="dr_bd"]').attr('readonly', 'true');
                } else {
                    $('#debit-voucher-form input[name="conversion_rate"]').attr('readonly', 'true');
                    $('#debit-voucher-form input[name="dr_fc"]').attr('readonly', 'true');
                }

                this.changeVocherType();
                this.changeCompany();
                this.changePayMode();
                this.changeCreditAccount();
                paymodeChange();
                this.fetchBankOfDebitAcc();
                this.fetchCreditAccountWiseChequeNo();
                this.factory();
                this.project();
                this.unit();
                this.paymode();
                this.account();
                this.ledger_account();
                this.constCenter();
                this.currency();
                this.conversionRate();
                this.debit();
                this.credit();
                this.addToCart();
                this.removeFromCart();
                this.editFromCart();
                this.submitForm();
                this.submitAndCopyForm();
                this.refreshForm();
            }
        }

        DebitVoucher.init();

        jQuery(document).on('change', '#paymode', function() {
            paymodeChange();
        })
        @if (isset($voucher))
            $(document).on(['load', 'change'], function() {
                this.fetchPayModeWiseCreditAccount({{ $voucher->paymode }});
            });
            $(window).on(['load', 'change'], function() {
                this.fetchPayModeWiseCreditAccount({{ $voucher->paymode }});
            });
        @endif
        function paymodeChange() {
            let paymode = $("#paymode");
            let bank_id = jQuery('#bank_id');
            let cheque_no = jQuery('#cheque_no');
            let cheque_due_date = jQuery('#cheque_due_date');

        }
    </script>
@endsection
