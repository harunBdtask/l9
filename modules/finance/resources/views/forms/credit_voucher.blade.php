<style>
    .custom-padding {
        padding: 0 80px 0 80px;
    }

    #tabular-form table, #tabular-form thead, #tabular-form tbody, #tabular-form th, #tabular-form td {
        padding: 3px !important;
        vertical-align: middle !important;
        font-size: 12px;
        text-align: center;
        border-color: black;
    }
</style>

<!--Credit Voucher Form -->
{!! Form::open([
    "method" => isset($voucher) ? 'PUT' : 'POST',
    "url" => isset($voucher) ? url('finance/vouchers/'.$voucher->id) : url('finance/vouchers'),
    "id" => 'credit-voucher-form'
])
!!}
<input type="hidden" value="{{isset($voucher) ? $voucher->id : null}}" id="id">
<input type="hidden" value="{{ $voucherType }}" id="voucher_type">
<div class="from-group row message"></div>
<input type="hidden" name="type_id" value="{{ \SkylarkSoft\GoRMG\BasicFinance\Models\Voucher::CREDIT_VOUCHER }}">

<div class="col-md-4">
    <div class="form-group row">
        <label class="col-sm-4 form-control-label">Company:</label>
        <div class="col-sm-8">
            @php
                $companyId = isset($voucher) ? $voucher->factory_id : '';
            @endphp
            {!! Form::select('factory_id', $companies, $companyId, [
                'class' => 'form-control select2-input', 'id' => 'factory_id', 'placeholder' => 'Select a Company'
            ]) !!}
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 form-control-label">Project Name:</label>
        <div class="col-sm-8">
            {!! Form::select('project_id', $projects ?? [], $voucher->project_id ?? null, [
                'class' => 'form-control select2-input', 'id' => 'project_id', 'placeholder' => 'Select a Project'
            ]) !!}
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 form-control-label">Unit Name:</label>
        <div class="col-sm-8">
            {!! Form::select('unit_id', $units ?? [], $voucher->unit_id ?? null, [
                'class' => 'form-control select2-input', 'id' => 'unit_id', 'placeholder' => 'Select a Unit'
            ]) !!}
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 form-control-label">Receive Mode:</label>
        {{--        Here Receive Mode is Actually PayMode In the Backgroound Task As Per Al-Amin Vai's Requirmrnts--}}
        <div class="col-sm-8">
            <select class="form-control c-select select2-input" name="paymode" id="paymode">
                <option value="0">Select Receive Mode</option>
                <option
                    value="{{ \SkylarkSoft\GoRMG\BasicFinance\Models\Voucher::BANK }}" {{(isset($voucher) ? $voucher->paymode : false) === \SkylarkSoft\GoRMG\BasicFinance\Models\Voucher::BANK ? 'selected' : ''}}>
                    Bank
                </option>
                <option
                    value="{{ \SkylarkSoft\GoRMG\BasicFinance\Models\Voucher::CASH }}" {{(isset($voucher) ? $voucher->paymode : false) === \SkylarkSoft\GoRMG\BasicFinance\Models\Voucher::CASH ? 'selected' : ''}}>
                    Cash
                </option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 form-control-label">Received By</label>
        <div class="col-sm-8">
            @php
                $accountId = isset($voucher) ? $voucher->details->debit_account : 0;
            @endphp
            <select class="form-control c-select select2-input" name="debit_account" id="debit_account">
                <option value="0">Select</option>
            </select>
        </div>
    </div>
</div>

<div class="col-md-4">
    <div class="form-group row">
        <label class="col-sm-4 form-control-label">Server Date:</label>
        <div class="col-sm-8">
            @php
                $serverDate = isset($voucher) ? $voucher->created_at : $created_date;
            @endphp
            <input type="datetime" class="form-control" name="server_date" value="{{ $serverDate }}" readonly>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 form-control-label">Voucher No:</label>
        <div class="col-sm-8">
            <input name="voucher_no" type="text" class="form-control" autocomplete="off"
                   value="<?php echo e($voucherNo); ?>" readonly>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 form-control-label">Currency:</label>
        <div class="col-sm-8">
            <?php
            echo Form::select('currency_id', $currencies, $voucher->currency_id ?? 1, [
                'class' => 'form-control select2-input', 'id' => 'currency_id', 'placeholder' => 'Select a Currency'
            ])
            ?>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 form-control-label">Transaction Date:</label>
        <div class="col-sm-8">
            @php
                $trnDate = isset($voucher) ? $voucher->trn_date->format('Y-m-d') : $today_date;
            @endphp
            <input type="date" class="form-control" name="trn_date" value="<?php echo e($trnDate); ?>">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 form-control-label">Recieved From:</label>
        <div class="col-sm-8">
            <input name="to" id="to" type="text" class="form-control" value="{{isset($voucher) ? $voucher->to : null}}">
        </div>
    </div>
</div>

<div class="col-md-4">
    <div class="form-group row">
        <label class="col-sm-4 form-control-label">Debtor Bank Name:</label>
        <div class="col-sm-8">
            <input type="text" class="form-control" name="bank_name" id="bank_name" readonly disabled
                   value="{{isset($voucher) ? $voucher->bank_id : null}}">
            <input type="hidden" class="form-control" name="bank_id" id="bank_id" readonly disabled
                   value="{{isset($voucher) ? $voucher->bank_id : null}}">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 form-control-label">Cheque Bank:</label>
        <div class="col-sm-8">
            @php
                $receiveBankId = isset($voucher) ? $voucher->receive_bank_id : '';
            @endphp
            {!! Form::select('receive_bank_id', $receiveBanks, $receiveBankId, [
                'class' => 'form-control select2-input', 'id' => 'receive_bank_id', 'placeholder' => 'Select a Bank' , 'disabled'
            ]) !!}
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 form-control-label">Cheque No:</label>
        <div class="col-sm-8">
            <input name="receive_cheque_no" id="receive_cheque_no" type="text" class="form-control"
                   value="{{isset($voucher) ? $voucher->receive_cheque_no : null}}" disabled>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 form-control-label">Due Date:</label>
        <div class="col-sm-8">
            {!! Form::date('cheque_due_date', isset($voucher) ? $voucher->cheque_due_date : null, ['class'=>'form-control', 'id'=>'cheque_due_date', 'disabled']) !!}
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 form-control-label">Reference No:</label>
        <div class="col-sm-8">
            @php
                $referenceNo = isset($voucher) ? $voucher->reference_no : '';
            @endphp
            <input type="text" class="form-control" name="reference_no" value="<?php echo e($referenceNo); ?>">
        </div>
    </div>
</div>

<div class="form-group row">
    <div class="col-lg-12">
        <div class="table-responsive">
            <table class="table reportTable" id="tabular-form">
                <thead class="thead-light" style="background-color: deepskyblue;">
                <tr>
                    <th rowspan="2" style="width: 10%;">AC CODE</th>
                    <th rowspan="2" style="width: 10%;">AC HEAD</th>
                    <th rowspan="2" style="width: 10%;">DEPARTMENT</th>
                    <th rowspan="2" style="width: 8%;">COST CENTER</th>
                    <th rowspan="2" style="width: 5%;">CON. RATE</th>
                    <th colspan="2">CREDIT</th>
                    <th class="text-right" rowspan="2" style="width: 30%;">NARRATION</th>
                    <th class="text-center" width="2%" rowspan="2">ACTION</th>
                </tr>
                <tr>
                    <th>FC</th>
                    <th>BDT</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>
                        <input type="text" name="account_code" id="account_code" class="form-control" autocomplete="off"
                               readonly>
                    </td>
                    <td>
                        <select class="form-control c-select select2-input" name="account" id="account">
                            <option value="0">Select</option>
                            <?php $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            @php
                                $info = optional($account)->accountInfo;
                            @endphp
                            @if($info->ledgerAccount->name !== "N\A")
                                <option value="{{ $account->id }}" data-id="{{ $account->id }}"
                                        data-name="{{ $info->ledgerAccount->name }} ( {{ $account->name }} ) - {{ $info->controlAccount->name }}"
                                        data-code="{{ $account->code }}">
                                    {{ $info->ledgerAccount->name }} ( {{ $account->name }} )
                                    - {{ $info->controlAccount->name }}
                                </option>
                            @else
                                <option value="{{ $account->id }}" data-id="{{ $account->id }}"
                                        data-name="{{ $account->name }} - {{ $info->controlAccount->name }}"
                                        data-code="{{ $account->code }}">
                                    {{ $account->name }} - ({{ $info->controlAccount->name }})
                                </option>
                            @endif
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </td>
                    <td>
                        <select class="form-control c-select select2-input" name="department_id" id="department_id">
                            <option value="0">Select</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" data-id="{{ $department->id }}"
                                        data-name="{{ $department->department }}">
                                    {{ $department->department }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select class="form-control c-select select2-input" name="const_center" id="const_center">
                            <option value="0">Select</option>
                            @foreach($cost_centers as $costCenter)
                                <option value="{{ $costCenter->id }}" data-id="{{ $costCenter->id }}"
                                        data-name="{{ $costCenter->cost_center }}">
                                    {{ $costCenter->cost_center }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="number" name="conversion_rate" class="form-control" autocomplete="off">
                    </td>
                    <td>
                        <input type="number" name="cr_fc" id="cr_fc" class="form-control" autocomplete="off">
                    </td>
                    <td>
                        <input type="number" name="cr_bd" id="cr_bd" class="form-control" autocomplete="off">
                    </td>
                    <td>
                        <input type="text" name="narration" class="form-control" autocomplete="off">
                    </td>
                    <td>
                        <a class="btn btn-primary btn-icon btn-sm add-to-cart">
                            <i class="fa fa-plus"></i>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td colspan="11" class="text-center">&nbsp;</td>
                </tr>
                </tbody>
                <tbody class="voucher-items">
                <tr>
                    <td colspan="11" class="text-center">No Items in the Cart</td>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                    <td class="text-right" colspan="5"><strong>TOTAL</strong></td>
                    <td class="text-right total-cr-fc" data-total-cr-fc="0.00">0.00</td>
                    <td class="text-right total-credit" data-total-credit="0.00">0.00</td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<div class="form-group row">
    <div class="col-md-12 text-right">
        @if(!isset($voucher))
            <span class="btn btn-success voucher-submit-copy-btn">Process & Copy</span>
        @endif
        <span class="btn btn-primary voucher-submit-btn">{{ isset($voucher) ? 'Update' : 'Process' }}</span>
        @if(!isset($voucher))
            <span class="btn btn-info voucher-refresh-btn">Refresh</span>
        @endif
        <a class="btn btn-danger" href="{{ url('finance/vouchers') }}">
            Cancel
        </a>
    </div>
</div>
{!! Form::close() !!}

@section('scripts')
    <script type="text/javascript">
        // Credit Voucher

        @php
            if(isset($voucher)) {
                echo 'const Cart = '.json_encode($voucher->details).';';
            }
        @endphp

        const CreditVoucherCart = {
            'trn_date': null,
            'voucher_no': '',
            'bank_id': '',
            'receive_bank_id': '',
            'cheque_no': '',
            'receive_cheque_no': '',
            'cheque_due_date': '',
            'factory_id': '',
            'items': [],
            'total_debit': 0,
            'total_credit_fc': 0,
            'total_credit': 0,
            'general_particulars': null,
            'debit_account': 0,
            'debit_account_name': '',
            'debit_account_code': '',
            'unit_id': '',
            'department_id': '',
            'currency_id': '',
            'paymode': '',
            'to': '',
            'from': '',
            'reference_no': '',
        };

        if (typeof Cart !== 'undefined') {
            CreditVoucherCart.trn_date = Cart.trn_date;
            CreditVoucherCart.voucher_no = Cart.voucher_no;
            CreditVoucherCart.paymode = Cart.paymode;
            CreditVoucherCart.bank_id = Cart.bank_id;
            CreditVoucherCart.receive_bank_id = Cart.receive_bank_id;
            CreditVoucherCart.cheque_no = Cart.cheque_no;
            CreditVoucherCart.receive_cheque_no = Cart.receive_cheque_no;
            CreditVoucherCart.cheque_due_date = Cart.cheque_due_date;
            CreditVoucherCart.factory_id = Cart.factory_id;
            CreditVoucherCart.project_id = Cart.project_id;
            CreditVoucherCart.unit_id = Cart.unit_id;
            CreditVoucherCart.currency_id = Cart.currency_id;
            CreditVoucherCart.items = Cart.items;
            CreditVoucherCart.total_debit = Cart.total_debit;
            CreditVoucherCart.total_credit = Cart.total_credit;
            CreditVoucherCart.general_particulars = Cart.general_particulars;
            CreditVoucherCart.debit_account = Cart.debit_account;
            CreditVoucherCart.debit_account_name = Cart.debit_account_name;
            CreditVoucherCart.debit_account_code = Cart.debit_account_code;
        }

        let editIndex = null;

        var CreditVoucher = {
            whenEditIdFound: function () {
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
            changeCompany: function () {
                let voucher = this;
                $('select[name="factory_id"]').change(function () {
                    let companyId = $(this).val();
                    voucher.fetchProjects(companyId);
                })
            },
            fetchProjects: function (companyId) {
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
            changeProject: function () {
                let voucher = this;
                $('select[name="project_id"]').change(function () {
                    let companyId = $('#factory_id').val();
                    let projectId = $(this).val();
                    voucher.fetchUnits(companyId, projectId);
                })
            },
            fetchUnits: function (companyId, projectId) {
                if (companyId && projectId) {
                    let unitId = $(`#unit_id`).val();
                    axios.get(`/finance/api/v1/fetch-project-wise-units/${companyId}/${projectId}`).then((response) => {
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
                            jQuery('#credit-voucher-form select[name="const_center"]').select2('val', CreditVoucherCart.items[editIndex].const_center);
                        }
                    })
                }
            },
            factory: function () {
                $('#credit-voucher-form select[name="factory_id"]').change(function () {
                    $(this).removeClass('invalid');
                });
            },
            project: function () {
                $('#credit-voucher-form select[name="project_id"]').change(function () {
                    $(this).removeClass('invalid');
                });
            },
            unit: function () {
                $('#credit-voucher-form select[name="unit_id"]').change(function () {
                    $(this).removeClass('invalid');
                });
            },
            // paymode: function () {
            //     $('#credit-voucher-form select[name="paymode"]').change(function () {
            //         $(this).removeClass('invalid');
            //     });
            // },
            trnDate: function () {
                $('#credit-voucher-form input[name="trn_date"]').focusin(function () {
                    $(this).removeClass('invalid');
                });
            },
            account: function () {
                $('#credit-voucher-form select[name="account"]').change(function () {
                    $(this).removeClass('invalid');
                    let account = $('#credit-voucher-form select#account option:selected')
                    $('#credit-voucher-form input[name="account_code"]').val(account.attr('data-code'));
                });
            },
            department: function () {
                $('#credit-voucher-form select[name="department_id"]').change(function () {
                    $(this).removeClass('invalid');
                });
            },
            constCenter: function () {
                $('#credit-voucher-form select[name="const_center"]').change(function () {
                    $(this).removeClass('invalid');
                });
            },
            currency: function () {
                $('#credit-voucher-form select[name="currency_id"]').change(function () {
                    $(this).removeClass('invalid');
                    let currency = $(this).val();
                    if (currency == 1) {
                        $('#credit-voucher-form input[name="conversion_rate"]').val(1).attr('readonly', 'true');
                        $('#credit-voucher-form input[name="cr_fc"]').val(null).attr('readonly', 'true');
                        $('#credit-voucher-form input[name="cr_bd"]').val(null).removeAttr('readonly');
                    } else {
                        $('#credit-voucher-form input[name="conversion_rate"]').val(null).removeAttr('readonly');
                        $('#credit-voucher-form input[name="cr_fc"]').val(null).removeAttr('readonly');
                        $('#credit-voucher-form input[name="cr_bd"]').val(null).attr('readonly', 'true');
                    }
                });
            },
            conversionRate: function () {
                let voucher = this;
                $('#credit-voucher-form input[name="conversion_rate"]').keyup(function () {
                    let conversionRate = $(this).val();
                    if (isNaN(conversionRate)) {
                        voucher.errorMessage('Conversion Rate must be a number');
                        $(this).val('');
                    }
                });
            },
            changePayMode: function () {
                let voucher = this;
                $('select[name="paymode"]').change(function () {
                    let payMode = $(this).val();
                    voucher.fetchPayModeWiseDebitAccount(payMode);
                });
            },
            fetchPayModeWiseDebitAccount: function (payMode) {
                axios.get(`/finance/api/v1/get-pay-mode-wise-accounts/${payMode}`)
                    .then((response) => {
                        let accounts = response.data;
                        let options = [];
                        $('#debit_account').html(`<option value="0">Select</option>`);
                        $('#credit-voucher-form select[name="debit_account"]').select2('val', '0');
                        accounts.forEach((account) => {
                            options.push([
                                `<option  value="${account.id}" data-id="${account.id}" data-bank-ac="${account.id}" data-name="${account.name}" data-code="${account.code}">${account.text}</option>`
                            ].join(''));
                        });
                        $('#debit_account').append(options);
                    })
                    .finally(() => {
                        jQuery('#credit-voucher-form select[name="debit_account"]').select2('val', CreditVoucherCart.debit_account);
                    })
            },
            changeCreditAccount: function () {
                let voucher = this;
                $('select[name="debit_account"]').change(function () {
                    let payMode = $('#paymode').val();
                    if (payMode == 1) {
                        let bankAccountId = $('#credit-voucher-form select#debit_account option:selected').attr('data-bank-ac');
                        voucher.fetchBankOfDebitAcc(bankAccountId);
                    }
                });
            },
            fetchBankOfDebitAcc: function (bankAccountId) {
                if (bankAccountId) {
                    axios.get(`/finance/api/v1/get-parent-bank-acc-no/${bankAccountId}`)
                        .then((response) => {
                            let bankName = response.data;
                            if (bankName) {
                                $('#credit-voucher-form input[name="bank_name"]').val(bankName.text);
                                $('#credit-voucher-form input[name="bank_id"]').val(bankName.id);
                            }
                        })
                }
            },
            credit: function () {
                let voucher = this;

                $('#credit-voucher-form input[name="cr_fc"]').keyup(function () {
                    let conversionRate = $('#credit-voucher-form input[name="conversion_rate"]').val()
                    let crFc = $(this).val();
                    if (isNaN(crFc)) {
                        voucher.errorMessage('FC must be a number');
                        return;
                    }
                    let crBd = parseFloat(conversionRate) * parseFloat(crFc);
                    $('#credit-voucher-form input[name="cr_bd"]').val(crBd || '');
                });

                $('#credit-voucher-form input[name="conversion_rate"]').keyup(function () {
                    let conversionRate = $(this).val();
                    let crFc = $('#credit-voucher-form input[name="conversion_rate"]').val();
                    if (isNaN(crFc)) {
                        voucher.errorMessage('Conversion Rate must be a number');
                        return;
                    }
                    let crBd = parseFloat(conversionRate) * parseFloat(crFc);
                    $('#credit-voucher-form input[name="cr_bd"]').val(crBd || '');
                });
            },
            debit: function () {
                $('#credit-voucher-form select[name="debit_account"]').change(function () {
                    $(this).removeClass('invalid');
                });
            },
            addToCart: function () {
                var voucher = this;

                $('#credit-voucher-form .add-to-cart').click(function () {
                    let account = $('#credit-voucher-form select#account option:selected'),
                        account_code = $('#credit-voucher-form input[name="account_code"]').val(),
                        department_id = $('#credit-voucher-form select#department_id option:selected'),
                        const_center = $('#credit-voucher-form select#const_center option:selected'),
                        conversion_rate = $('#credit-voucher-form input[name="conversion_rate"]').val(),
                        // credit = parseFloat($('#credit-voucher-form input[name="credit"]').val()),
                        cr_fc = $('#credit-voucher-form input[name="cr_fc"]').val(),
                        cr_bd = $('#credit-voucher-form input[name="cr_bd"]').val(),
                        narration = $('#credit-voucher-form input[name="narration"]').val(),
                        // particulars = $('#credit-voucher-form input[name="particulars"]').val(),
                        validItem = true;

                    if (!parseInt(account.attr('data-id'))) {
                        $('#credit-voucher-form select[name="account"]').addClass('invalid');
                        validItem = false;
                    }
                    if (!parseInt(department_id.attr('data-id'))) {
                        $('#credit-voucher-form select[name="department_id"]').addClass('invalid');
                        validItem = false;
                    }

                    if (!parseInt(const_center.attr('data-id'))) {
                        $('#credit-voucher-form select[name="const_center"]').addClass('invalid');
                        validItem = false;
                    }

                    if (!cr_bd) {
                        validItem = false;
                    }

                    if (!validItem) {
                        return;
                    }

                    $('#credit-voucher-form input[name="account"]').removeClass('invalid');
                    // $('#credit-voucher-form input[name="credit"]').removeClass('invalid');
                    $('#credit-voucher-form input[name="account_code"]').removeClass('invalid');
                    $('#credit-voucher-form input[name="const_center"]').removeClass('invalid');

                    let data = {
                        'account_id': account.attr('data-id'),
                        'account_code': account_code,
                        'account_name': account.attr('data-name'),
                        'const_center_name': const_center.attr('data-name'),
                        'department_id': department_id.attr('data-id'),
                        'department_name': department_id.attr('data-name'),
                        'const_center': const_center.attr('data-id'),
                        'conversion_rate': conversion_rate ? conversion_rate : 1,
                        'cr_fc': cr_fc ? cr_fc : 0,
                        'cr_bd': cr_bd ? cr_bd : 0,
                        'credit': cr_bd ? cr_bd : 0,
                        'narration': narration,
                        // 'particulars': particulars
                    };

                    if (!editIndex) {
                        CreditVoucherCart.items.push(data);
                    } else {
                        CreditVoucherCart.items[editIndex] = {...data};
                        editIndex = null;
                    }

                    $('#credit-voucher-form select[name="account"]').val('');
                    $('#credit-voucher-form select[name="account"]').select2('val', '0');
                    $('#credit-voucher-form select[name="account_code"]').val('');
                    // $('#credit-voucher-form select[name="department_id"]').val('');
                    // $('#credit-voucher-form select[name="department_id"]').select2('val', '0');
                    // $('#credit-voucher-form select[name="const_center"]').val('');
                    // $('#credit-voucher-form select[name="const_center"]').select2('val', '0');
                    // $('#credit-voucher-form input[name="conversion_rate"]').val('');
                    // $('#credit-voucher-form input[name="cr_fc"]').val('');
                    // $('#credit-voucher-form input[name="cr_bd"]').val('');
                    // $('#credit-voucher-form input[name="credit"]').val('');
                    // $('#credit-voucher-form input[name="narration"]').val('');

                    voucher.renderCart();

                    $('#credit-voucher-form input[name="account"]').focus();
                });
            },
            removeFromCart: function () {
                var voucher = this;

                $('#credit-voucher-form').on('click', '.remove-from-cart', function (event) {
                    var dataIndex = $(this).attr('data-index');

                    CreditVoucherCart.items = CreditVoucherCart.items.filter(function (item, key) {
                        return key != dataIndex;
                    });

                    voucher.renderCart();
                });
            },
            editFromCart: function () {
                $('#credit-voucher-form').on('click', '.edit-from-cart', function () {
                    editIndex = $(this).attr('data-index');
                    $('#credit-voucher-form select[name="account"]').select2('val', CreditVoucherCart.items[editIndex].account_id);
                    $('#credit-voucher-form input[name="account_code"]').val(CreditVoucherCart.items[editIndex].account_code);
                    $('#credit-voucher-form select[name="department_id"]').select2('val', CreditVoucherCart.items[editIndex].department_id);
                    $('#credit-voucher-form select[name="const_center"]').select2('val', CreditVoucherCart.items[editIndex].const_center);
                    $('#credit-voucher-form input[name="conversion_rate"]').val(CreditVoucherCart.items[editIndex].conversion_rate);
                    $('#credit-voucher-form input[name="cr_fc"]').val(CreditVoucherCart.items[editIndex].cr_fc);
                    $('#credit-voucher-form input[name="cr_bd"]').val(CreditVoucherCart.items[editIndex].cr_bd);
                    $('#credit-voucher-form input[name="narration"]').val(CreditVoucherCart.items[editIndex].narration);
                });
            },
            renderCart: function () {
                var cartLength = CreditVoucherCart.items.length,
                    trs = [],
                    totalCrFc = 0,
                    totalCredit = 0;

                $('#credit-voucher-form .voucher-items').removeClass('text-danger');

                for (let i = 0; i < cartLength; i++) {
                    trs.push([
                        '<tr>',
                        '<td>' + CreditVoucherCart.items[i].account_code + '</td>',
                        '<td>' + CreditVoucherCart.items[i].account_name + '</td>',
                        '<td>' + CreditVoucherCart.items[i].department_name + '</td>',
                        '<td>' + CreditVoucherCart.items[i].const_center_name + '</td>',
                        '<td>' + CreditVoucherCart.items[i].conversion_rate + '</td>',
                        '<td class="text-right">' + (CreditVoucherCart.items[i].cr_fc ? parseFloat(CreditVoucherCart.items[i].cr_fc).toFixed(2) : '') + '</td>',
                        '<td class="text-right">' + (CreditVoucherCart.items[i].cr_bd ? parseFloat(CreditVoucherCart.items[i].cr_bd).toFixed(2) : '') + '</td>',
                        '<td>' + CreditVoucherCart.items[i].narration + '</td>',
                        '<td class="text-center">',
                        '<a class="pointer-cursor text-danger remove-from-cart" data-id="' + CreditVoucherCart.items[i].account_id + '"data-index="' + i + '"><i class="fa fa-remove"></i></a>',
                        '<a class="pointer-cursor text-info edit-from-cart" style="margin-left: 5px;" data-id="' + CreditVoucherCart.items[i].account_id + '"data-index="' + i + '"><i class="fa fa-pencil"></i></a>',
                        '</td>',
                        '</tr>',
                    ].join(''));

                    totalCrFc += parseFloat(CreditVoucherCart.items[i].cr_fc);
                    totalCredit += parseFloat(CreditVoucherCart.items[i].credit);
                }

                if (!cartLength) {
                    trs.push([
                        '<tr>',
                        '<td colspan="11" class="text-center">No Items in the Cart</td>',
                        '</tr>'
                    ].join(''));
                }

                $('#credit-voucher-form .voucher-items').html(trs.join(''));
                $('#credit-voucher-form .total-credit').html(totalCredit.toFixed(2));
                $('#credit-voucher-form .total-cr-fc').html(totalCrFc.toFixed(2));

                $('#credit-voucher-form .total-credit').removeClass('text-danger');

                CreditVoucherCart.total_credit_fc = parseFloat(totalCrFc.toFixed(2));
                CreditVoucherCart.total_credit = parseFloat(totalCredit.toFixed(2));
            },
            submitAndCopyForm: function () {
                var voucher = this;
                $('#credit-voucher-form .voucher-submit-copy-btn').click(function (event) {
                    var debitAccount = $('#credit-voucher-form select#debit_account option:selected');
                    var debitAccountParent = $('#credit-voucher-form select#bank_id option:selected');
                    CreditVoucherCart.type_id = $('#credit-voucher-form input[name="type_id"]').val();
                    CreditVoucherCart.trn_date = $('#credit-voucher-form input[name="trn_date"]').val();
                    CreditVoucherCart.file_no = $('#credit-voucher-form input[name="file_no"]').val();
                    CreditVoucherCart.voucher_no = $('#credit-voucher-form input[name="voucher_no"]').val();
                    CreditVoucherCart.paymode = $('#credit-voucher-form #paymode').val();
                    if (CreditVoucherCart.paymode == 2) {
                        CreditVoucherCart.bank_id =  null;
                        CreditVoucherCart.receive_bank_id =  null;
                        CreditVoucherCart.receive_cheque_no =  null;
                        CreditVoucherCart.cheque_due_date = null;
                    } else {
                        CreditVoucherCart.bank_id = $('#credit-voucher-form input[name="bank_id"]').val();
                        CreditVoucherCart.receive_bank_id = $('#credit-voucher-form #receive_bank_id').val();
                        CreditVoucherCart.receive_cheque_no = $('#credit-voucher-form input[name="receive_cheque_no"]').val();
                        CreditVoucherCart.cheque_due_date = $('#credit-voucher-form input[name="cheque_due_date"]').val();
                    }
                    CreditVoucherCart.cheque_no = null;
                    CreditVoucherCart.factory_id = $('#factory_id').val();
                    CreditVoucherCart.general_particulars = $('#credit-voucher-form input[name="general_particulars"]').val();
                    CreditVoucherCart.total_debit = CreditVoucherCart.total_credit;
                    CreditVoucherCart.debit_account = debitAccount.attr('data-id');
                    CreditVoucherCart.debit_account_name = debitAccount.attr('data-name');
                    CreditVoucherCart.debit_account_code = debitAccount.attr('data-code');
                    CreditVoucherCart.unit_id = $('#credit-voucher-form #unit_id').val();
                    CreditVoucherCart.project_id = $('#credit-voucher-form #project_id').val();
                    CreditVoucherCart.currency_id = $('#credit-voucher-form #currency_id').val();
                    CreditVoucherCart.to = $('#to').val();
                    CreditVoucherCart.reference_no = $('#credit-voucher-form input[name="reference_no"]').val();
                    if (voucher.validate()) {
                        let method = $('#credit-voucher-form input[name="_method"]').val();
                        $.ajax({
                            url: $('#credit-voucher-form').attr('action'),
                            type: "POST",
                            data: {
                                _token: $('#credit-voucher-form input[name="_token"]').val(),
                                _method: method ? method : 'POST',
                                type_id: CreditVoucherCart.type_id,
                                trn_date: CreditVoucherCart.trn_date,
                                voucher_no: CreditVoucherCart.voucher_no,
                                debit_account: CreditVoucherCart.debit_account,
                                bank_id: CreditVoucherCart.bank_id,
                                receive_bank_id: CreditVoucherCart.receive_bank_id,
                                cheque_no: CreditVoucherCart.cheque_no,
                                receive_cheque_no: CreditVoucherCart.receive_cheque_no,
                                cheque_due_date: CreditVoucherCart.cheque_due_date,
                                factory_id: CreditVoucherCart.factory_id,
                                amount: CreditVoucherCart.total_credit,
                                general_particulars: CreditVoucherCart.general_particulars,
                                project_id: CreditVoucherCart.project_id,
                                unit_id: CreditVoucherCart.unit_id,
                                currency_id: CreditVoucherCart.currency_id,
                                paymode: CreditVoucherCart.paymode,
                                reference_no: CreditVoucherCart.reference_no,
                                to: CreditVoucherCart.to,
                                details: JSON.stringify(CreditVoucherCart)
                            },
                            beforeSend: function () {
                                $('#credit-voucher-form .voucher-submit-copy-btn').html('Submitting...');
                            },
                            success: function (previewUrl, status) {
                                CreditVoucherCart.trn_date = moment(new Date()).format('DD-MM-YYYY');
                                CreditVoucherCart.items = [];
                                CreditVoucherCart.total_debit = 0;
                                CreditVoucherCart.total_credit = 0;
                                CreditVoucherCart.voucher_amount = 0;
                                CreditVoucherCart.general_particulars = null;
                                CreditVoucherCart.debit_account = 0;
                                var voucherType = $('#voucher_type').val();
                                axios.get(`/finance/api/v1/get-voucher-no?voucher_type=${voucherType}`).then(response => {
                                    CreditVoucherCart.voucher_no = $('#credit-voucher-form input[name="voucher_no"]').val(response.data);
                                    voucher.renderCart();
                                    $('#credit-voucher-form .voucher-submit-copy-btn').html('Process & Copy');
                                    voucher.showSuccessMessage();
                                });
                            },
                            error: function (error) {
                                let errors = {...error.responseJSON.errors};
                                $.each(errors, function (key, value) {
                                    $(`#credit-voucher-form #${key}`).addClass('invalid');
                                    $(`#credit-voucher-form #${key}`).attr('title', value[0]);
                                });
                            }
                        });
                    }
                });
            },
            submitForm: function () {
                var voucher = this;
                $('#credit-voucher-form .voucher-submit-btn').click(function (event) {
                    var debitAccount = $('#credit-voucher-form select#debit_account option:selected');
                    var debitAccountParent = $('#credit-voucher-form select#bank_id option:selected');
                    CreditVoucherCart.type_id = $('#credit-voucher-form input[name="type_id"]').val();
                    CreditVoucherCart.trn_date = $('#credit-voucher-form input[name="trn_date"]').val();
                    CreditVoucherCart.file_no = $('#credit-voucher-form input[name="file_no"]').val();
                    CreditVoucherCart.voucher_no = $('#credit-voucher-form input[name="voucher_no"]').val();
                    CreditVoucherCart.paymode = $('#credit-voucher-form #paymode').val();
                    if (CreditVoucherCart.paymode == 2) {
                        CreditVoucherCart.bank_id =  null;
                        CreditVoucherCart.receive_bank_id =  null;
                        CreditVoucherCart.receive_cheque_no =  null;
                        CreditVoucherCart.cheque_due_date = null;
                    } else {
                        CreditVoucherCart.bank_id = $('#credit-voucher-form input[name="bank_id"]').val();
                        CreditVoucherCart.receive_bank_id = $('#credit-voucher-form #receive_bank_id').val();
                        CreditVoucherCart.receive_cheque_no = $('#credit-voucher-form input[name="receive_cheque_no"]').val();
                        CreditVoucherCart.cheque_due_date = $('#credit-voucher-form input[name="cheque_due_date"]').val();
                    }
                    CreditVoucherCart.cheque_no = null;
                    CreditVoucherCart.factory_id = $('#factory_id').val();
                    CreditVoucherCart.general_particulars = $('#credit-voucher-form input[name="general_particulars"]').val();
                    CreditVoucherCart.total_debit = CreditVoucherCart.total_credit;
                    CreditVoucherCart.debit_account = debitAccount.attr('data-id');
                    CreditVoucherCart.debit_account_name = debitAccount.attr('data-name');
                    CreditVoucherCart.debit_account_code = debitAccount.attr('data-code');
                    CreditVoucherCart.unit_id = $('#credit-voucher-form #unit_id').val();
                    CreditVoucherCart.project_id = $('#credit-voucher-form #project_id').val();
                    CreditVoucherCart.currency_id = $('#credit-voucher-form #currency_id').val();
                    CreditVoucherCart.to = $('#to').val();
                    CreditVoucherCart.reference_no = $('#credit-voucher-form input[name="reference_no"]').val();
                    if (voucher.validate()) {
                        let method = $('#credit-voucher-form input[name="_method"]').val();
                        $.ajax({
                            url: $('#credit-voucher-form').attr('action'),
                            type: "POST",
                            data: {
                                _token: $('#credit-voucher-form input[name="_token"]').val(),
                                _method: method ? method : 'POST',
                                type_id: CreditVoucherCart.type_id,
                                trn_date: CreditVoucherCart.trn_date,
                                voucher_no: CreditVoucherCart.voucher_no,
                                debit_account: CreditVoucherCart.debit_account,
                                bank_id: CreditVoucherCart.bank_id,
                                receive_bank_id: CreditVoucherCart.receive_bank_id,
                                cheque_no: CreditVoucherCart.cheque_no,
                                receive_cheque_no: CreditVoucherCart.receive_cheque_no,
                                cheque_due_date: CreditVoucherCart.cheque_due_date,
                                factory_id: CreditVoucherCart.factory_id,
                                amount: CreditVoucherCart.total_credit,
                                general_particulars: CreditVoucherCart.general_particulars,
                                project_id: CreditVoucherCart.project_id,
                                unit_id: CreditVoucherCart.unit_id,
                                currency_id: CreditVoucherCart.currency_id,
                                paymode: CreditVoucherCart.paymode,
                                reference_no: CreditVoucherCart.reference_no,
                                to: CreditVoucherCart.to,
                                details: JSON.stringify(CreditVoucherCart)
                            },
                            beforeSend: function () {
                                $('#credit-voucher-form .voucher-submit-btn').html('Submitting...');
                            },
                            success: function (previewUrl, status) {
                                CreditVoucherCart.trn_date = moment(new Date()).format('DD-MM-YYYY');
                                CreditVoucherCart.voucher_no = '';
                                CreditVoucherCart.bank_id = '';
                                CreditVoucherCart.receive_bank_id = '';
                                CreditVoucherCart.cheque_no = '';
                                CreditVoucherCart.receive_cheque_no = '';
                                CreditVoucherCart.cheque_due_date = '';
                                CreditVoucherCart.factory_id = '';
                                CreditVoucherCart.items = [];
                                CreditVoucherCart.total_debit = 0;
                                CreditVoucherCart.total_credit = 0;
                                CreditVoucherCart.voucher_amount = 0;
                                CreditVoucherCart.general_particulars = null;
                                CreditVoucherCart.debit_account = 0;
                                CreditVoucherCart.debit_account_name = '';
                                CreditVoucherCart.debit_account_code = '';
                                CreditVoucherCart.unit_id = '';
                                CreditVoucherCart.project_id = '';
                                CreditVoucherCart.currency_id = '';
                                CreditVoucherCart.paymode = '';
                                CreditVoucherCart.to = '';
                                CreditVoucherCart.reference_no = '';

                                if (method) {
                                    location.replace(previewUrl);
                                } else {
                                    voucher.renderCart();

                                    $('#credit-voucher-form .voucher-submit-btn').html('Submit');

                                    $('#credit-voucher-form').trigger("reset");
                                    $("#credit-voucher-form .select2-input").select2("val", "0");

                                    voucher.showSuccessMessage();

                                    // window.open(previewUrl, '_blank');
                                    window.location = previewUrl;
                                }
                            },
                            error: function (error) {
                                let errors = {...error.responseJSON.errors};
                                $.each(errors, function (key, value) {
                                    $(`#credit-voucher-form #${key}`).addClass('invalid');
                                    $(`#credit-voucher-form #${key}`).attr('title', value[0]);
                                });
                            }
                        });
                    }
                });
            },
            validate: function () {
                let validation = true;

                if (CreditVoucherCart.factory_id == 0) {
                    $('#credit-voucher-form #factory_id').addClass('invalid');
                    validation = false;
                }

                if (CreditVoucherCart.project_id == 0) {
                    $('#credit-voucher-form #project_id').addClass('invalid');
                    validation = false;
                }

                if (CreditVoucherCart.unit_id == 0) {
                    $('#credit-voucher-form #unit_id').addClass('invalid');
                    validation = false;
                }

                if (CreditVoucherCart.paymode == 0) {
                    $('#credit-voucher-form #paymode').addClass('invalid');
                    validation = false;
                }

                if ((CreditVoucherCart.paymode == 1) && (CreditVoucherCart.bank_id == 0)) {
                    $('#credit-voucher-form #bank_id').addClass('invalid');
                    validation = false;
                } else {
                    $('#credit-voucher-form #bank_id').removeClass('invalid');
                }

                if ((CreditVoucherCart.paymode == 1) && (CreditVoucherCart.receive_bank_id == 0)) {
                    $('#credit-voucher-form #receive_bank_id').addClass('invalid');
                    validation = false;
                } else {
                    $('#credit-voucher-form #receive_bank_id').removeClass('invalid');
                }

                if ((CreditVoucherCart.paymode == 1) && (CreditVoucherCart.cheque_no == '')) {
                    $('#credit-voucher-form input[name="cheque_no"]').addClass('invalid');
                    validation = false;
                } else {
                    $('#credit-voucher-form input[name="cheque_no"]').removeClass('invalid');
                }

                if ((CreditVoucherCart.paymode == 1) && (CreditVoucherCart.cheque_due_date == '')) {
                    $('#credit-voucher-form input[name="cheque_due_date"]').addClass('invalid');
                    validation = false;
                } else {
                    $('#credit-voucher-form input[name="cheque_due_date"]').removeClass('invalid');
                }

                if (CreditVoucherCart.trn_date == '') {
                    $('#credit-voucher-form input[name="trn_date"]').addClass('invalid');
                    validation = false;
                }

                if (CreditVoucherCart.items.length == 0) {
                    $('#credit-voucher-form .voucher-items').addClass('text-danger');
                    validation = false;
                }

                if (CreditVoucherCart.debit_account == undefined) {
                    $('#credit-voucher-form #debit_account').addClass('invalid');
                    validation = false;
                }

                return validation;
            },
            refreshForm: function () {
                let voucher = this;
                $('#credit-voucher-form .voucher-refresh-btn').click(function (event) {
                    $('#credit-voucher-form #factory_id').val(null).trigger('change');
                    $('#credit-voucher-form #project_id').val(null).trigger('change');
                    $('#credit-voucher-form #unit_id').val(null).trigger('change');
                    $('#credit-voucher-form #currency_id').val(1).trigger('change');
                    $('#credit-voucher-form #paymode').val(null).trigger('change');
                    $('#credit-voucher-form input[name="reference_no"]').val(null);
                    $('#credit-voucher-form .voucher-items').find('tr').remove();
                    CreditVoucherCart.items = [];
                    voucher.renderCart();
                });
            },
            showSuccessMessage: function () {
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

                $('#credit-voucher-form div.message').html(successMessage);
            },
            errorMessage: function (message) {
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

                $('#credit-voucher-form div.message').html(errorMessage);
            },
            init: function () {
                $("#credit-voucher-form select#currency_id").val();

                if ($('#credit-voucher-form #currency_id').val() != 1) {
                    $('#credit-voucher-form input[name="cr_bd"]').attr('readonly', 'true');
                } else {
                    $('#credit-voucher-form input[name="conversion_rate"]').attr('readonly', 'true');
                    $('#credit-voucher-form input[name="cr_fc"]').attr('readonly', 'true');
                }
                this.changeCompany();
                this.changeProject();
                this.changePayMode();
                this.changeCreditAccount();
                this.fetchPayModeWiseDebitAccount($('#credit-voucher-form select[name="paymode"]').val());
                paymodeChange();
                this.factory();
                this.project();
                this.unit();
                // this.paymode();
                this.trnDate();
                this.account();
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
                this.renderCart();
            }
        }

        CreditVoucher.init();

        jQuery(document).on('change', '#paymode', function () {
            paymodeChange();
        })

        @if(isset($voucher))
        $(document).on(['load', 'change'], function () {
            this.fetchPayModeWiseCreditAccount({{$voucher->paymode}});
        });
        $(window).on(['load', 'change'], function () {
            this.fetchPayModeWiseCreditAccount({{$voucher->paymode}});
        });
        @endif

        function paymodeChange() {
            let paymode = $("#paymode");
            let bank_id = jQuery('#bank_id');
            let receive_bank_id = jQuery('#receive_bank_id');
            var receive_cheque_no = jQuery('#receive_cheque_no')
            var cheque_due_date = jQuery('#cheque_due_date')
            if (jQuery(paymode).val() == 1) {
                bank_id.prop('disabled', false);
                receive_bank_id.prop('disabled', false);
                receive_cheque_no.prop('disabled', false);
                cheque_due_date.prop('disabled', false);
            } else {
                bank_id.prop('disabled', true).val(null);
                receive_bank_id.prop('disabled', true).val(null);
                receive_cheque_no.prop('disabled', true).val(null);
                cheque_due_date.prop('disabled', true).val(null);
            }
        }
    </script>
@endsection
