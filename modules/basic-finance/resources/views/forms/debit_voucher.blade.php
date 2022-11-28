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
    .fontSize{
        font-size: 13px;
    }
    .d-none{
        display: none;
    }
</style>

<!--Debit Voucher Form -->
{!! Form::open([
    "method" => isset($voucher) ? 'PUT' : 'POST',
    "url" => isset($voucher) ? url('basic-finance/vouchers/'.$voucher->id) : url('basic-finance/vouchers'),
    "id" => 'debit-voucher-form'
])
!!}
<input type="hidden" value="{{isset($voucher) ? $voucher->id : null}}" id="id">
<input type="hidden" value="{{ $voucherType }}" id="voucher_type">
<div class="from-group row message"></div>
<input type="hidden" name="type_id" value="{{ \SkylarkSoft\GoRMG\BasicFinance\Models\Voucher::DEBIT_VOUCHER }}">

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
        <label class="col-sm-4 form-control-label">Pay Mode:</label>
        <div class="col-sm-8">
            <select class="form-control c-select select2-input" name="paymode" id="paymode">
                <option value="0">Select Pay Mode</option>
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
        <label class="col-sm-4 form-control-label">Paid From:</label>
        <div class="col-sm-8">
            @php
                $accountId = isset($voucher) ? $voucher->details->credit_account : 0;
            @endphp
            <select class="form-control c-select select2-input" name="credit_account" id="credit_account" required>
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
        <label class="col-sm-4 form-control-label">Paid To:</label>
        <div class="col-sm-8">
            <input name="to" id="to" type="text" class="form-control" value="{{isset($voucher) ? $voucher->to : null}}">
        </div>
    </div>
</div>

<div class="col-md-4">
    <div class="form-group row">
        <label class="col-sm-4 form-control-label">Creditor Bank Name:</label>
        <div class="col-sm-8">
            <input type="text" class="form-control" name="bank_name" id="bank_name" readonly disabled
                   value="{{isset($voucher) ? $voucher->bank_id : null}}">
            <input type="hidden" class="form-control" name="bank_id" id="bank_id" readonly disabled
                   value="{{isset($voucher) ? $voucher->bank_id : null}}">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 form-control-label">Cheque No:</label>
        <div class="col-sm-1">
            @php
                $isChecked = false;
            @endphp
            {!! Form::checkbox('show_cheque_no', 1, $isChecked, ['id' => 'show_cheque_no', 'disabled' => $isChecked]) !!}
        </div>
        <div class="col-sm-7">
            {!! Form::select('cheque_no', (isset($selectedChequeList)?$selectedChequeList:[]), isset($voucher) ? $voucher->cheque_no : null, ['class'=> 'form-control select2-input', 'id' => 'cheque_no']) !!}

            {{-- <input type="text" class="form-control" name="cheque_name" id="cheque_name" readonly disabled
                   value="{{isset($voucher) ? $cheque_name->implode(', ') : null}}">
            <input type="hidden" class="form-control" name="store_cheque_name" id="store_cheque_name" readonly disabled
                   value="{{isset($voucher) ? $cheque_name->implode(', ') : null}}"> --}}
            {{-- <input type="hidden" class="form-control" name="cheque_no" id="cheque_no" readonly disabled
                   value="{{isset($voucher) ? $voucher->cheque_no : null}}"> --}}
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
    <div class="form-group row">
        <label class="col-sm-4 form-control-label">Bill No:</label>
        <div class="col-sm-8">
            @php
                $bill_no = isset($voucher) ? $voucher->bill_no : '';
            @endphp
            <input type="text" class="form-control" name="bill_no" id="bill_no"  value="<?php echo e($bill_no); ?>">
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
                    <th rowspan="2" style="width: 20%;">AC HEAD</th>
                    <th rowspan="2" style="width: 10%;">DEPARTMENT</th>
                    <th rowspan="2" style="width: 8%;">COST CENTER</th>
                    <th rowspan="2" style="width: 5%;">CON. RATE</th>
                    <th colspan="2" style="width: 30%;">DEBIT</th>
                    <th class="text-right" style="width: 15%;" rowspan="2">NARRATION</th>
                    <th class="text-center" width="2%" rowspan="2">ACTION</th>
                </tr>
                <tr>
                    <th style="width: 15%">FC</th>
                    <th style="width: 15%">BDT</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>
                        <input type="text" name="account_code" id="account_code" class="form-control" autocomplete="off"
                               readonly>
                    </td>
                    <td>
                        <select style="width: 50%" class="form-control c-select select2-input" name="account" id="account">
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
                                        data-name="{{ $account->name }}"
                                        data-code="{{ $account->code }}">
                                    {{ $account->name }} - ({{ $account->parentAc->name }})
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
                        <input type="number" name="conversion_rate" id="conversion_rate" class="form-control fontSize"
                               autocomplete="off">
                    </td>
                    <td>
                        <input type="number" name="dr_fc" id="dr_fc" class="form-control fontSize" autocomplete="off">
                    </td>
                    <td>
                        <input type="number" name="dr_bd" id="dr_bd" class="form-control fontSize" autocomplete="off">
                    </td>
                    <td>
                        <input type="text" name="narration" class="form-control fontSize" autocomplete="off">
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
                    <td class="text-right total-dr-fc" data-total-dr-fc="0.00">0.00</td>
                    <td class="text-right total-debit" data-total-debit="0.00">0.00</td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<div class="form-group row" id="actionBtn">
    <div class="col-md-12 text-right">
        @if(!isset($voucher))
            <span class="btn btn-success voucher-submit-copy-btn">Process & Copy</span>
        @endif
        <span class="btn btn-primary voucher-submit-btn">{{ isset($voucher) ? 'Update' : 'Process' }}</span>
        @if(!isset($voucher))
            <span class="btn btn-info voucher-refresh-btn">Refresh</span>
        @endif
        <a class="btn btn-danger" href="{{ url('basic-finance/vouchers') }}">
            Cancel
        </a>
    </div>
</div>
{!! Form::close() !!}


<div style="text-align: center;">
    <img class="loader" src="{{asset('loader.gif')}}" style="display: none;" alt="loader">
</div>

@section('scripts')
    <script type="text/javascript">
        //Bill no exist checking start
        $("#bill_no").blur(function(){
            var bill_no = $('#bill_no').val();

            $.ajax({
                url: "/basic-finance/api/v1/get-bf-vouchers-by-bill-no",
                type: "get",
                dataType: "json",
                data: {
                    'bill_no': bill_no,
                    'type_id': 1,
                    'id': "{{ $voucher->id ?? null }}",
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
                    if (data.length > 0) {
                        // $( '#actionBtn' ).addClass( 'd-none' );
                        alert('Bill No Already Exist !')
                    }else{
                        // $( '#actionBtn' ).removeClass( 'd-none' );
                    }
                },
                error(errors) {
                    alert("Something Went Wrong");
                }
            })
        });
        //end

        @php
            if(isset($voucher)) {
                echo 'const Cart = '.json_encode($voucher->details).';';
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
            'factory_id': '',
            'items': [],
            'total_debit_fc': 0,
            'total_debit': 0,
            'total_credit': 0,
            'general_particulars': null,
            'credit_account': 0,
            'credit_account_name': '',
            'credit_account_code': '',
            'unit_id': '',
            'department_id': '',
            'const_center': '',
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
                    axios.get(`/basic-finance/api/v1/fetch-company-wise-projects/${companyId}`).then((response) => {
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
                        $('#unit_id').select2('val', 0);
                    }).finally(() => {
                        if (editIndex) {
                            jQuery('#debit-voucher-form select[name="const_center"]').select2('val', DebitVoucherCart.items[editIndex].const_center);
                        }
                    })
                }
            },
            factory: function () {
                $('#debit-voucher-form select[name="factory_id"]').change(function () {
                    $(this).removeClass('invalid');
                });
            },
            project: function () {
                $('#debit-voucher-form select[name="project_id"]').change(function () {
                    $(this).removeClass('invalid');
                });
            },
            unit: function () {
                $('#debit-voucher-form select[name="unit_id"]').change(function () {
                    $(this).removeClass('invalid');
                });
            },
            paymode: function () {
                $('#debit-voucher-form select[name="paymode"]').change(function () {
                    $(this).removeClass('invalid');
                });
            },
            trnDate: function () {
                $('#debit-voucher-form input[name="trn_date"]').focusin(function () {
                    $(this).removeClass('invalid');
                });
            },
            showChequeNo: function () {
                $('#debit-voucher-form input[name="show_cheque_no"]').click(function () {
                    if ($(this).is(":checked")) {
                        $('#debit-voucher-form input[name="cheque_name"]').val('');
                    } else {
                        const chequeName = $('#debit-voucher-form input[name="store_cheque_name"]').val();
                        $('#debit-voucher-form input[name="cheque_name"]').val(chequeName);
                    }
                });
            },
            account: function () {
                $('#debit-voucher-form select[name="account"]').change(function () {
                    $(this).removeClass('invalid');
                    let account = $('#debit-voucher-form select#account option:selected')
                    $('#debit-voucher-form input[name="account_code"]').val(account.attr('data-code'));
                });
            },
            department: function () {
                $('#debit-voucher-form select[name="department_id"]').change(function () {
                    $(this).removeClass('invalid');
                });
            },
            constCenter: function () {
                $('#debit-voucher-form select[name="const_center"]').change(function () {
                    $(this).removeClass('invalid');
                });
            },
            currency: function () {
                $('#debit-voucher-form select[name="currency_id"]').change(function () {
                    $(this).removeClass('invalid');
                    let currency = $(this).val();
                    if (currency == 1) {
                        $('#debit-voucher-form input[name="conversion_rate"]').val(1).attr('readonly', 'true');
                        $('#debit-voucher-form input[name="dr_fc"]').val(null).attr('readonly', 'true');
                        $('#debit-voucher-form input[name="dr_bd"]').val(null).removeAttr('readonly');
                    } else {
                        $('#debit-voucher-form input[name="conversion_rate"]').val(null).removeAttr('readonly');
                        $('#debit-voucher-form input[name="dr_fc"]').val(null).removeAttr('readonly');
                        $('#debit-voucher-form input[name="dr_bd"]').val(null).attr('readonly', 'true');
                    }
                });
            },
            conversionRate: function () {
                let voucher = this;
                $('#debit-voucher-form input[name="conversion_rate"]').keyup(function () {
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
                    voucher.fetchPayModeWiseCreditAccount(payMode);
                });
            },
            fetchPayModeWiseCreditAccount: function (payMode) {
                if (payMode < 1) {
                    return;
                }
                axios.get(`/basic-finance/api/v1/get-pay-mode-wise-accounts/${payMode}`)
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
                        jQuery('#debit-voucher-form select[name="credit_account"]').select2('val', DebitVoucherCart.credit_account);
                    })
            },
            changeCreditAccount: function () {
                let voucher = this;
                $('select[name="credit_account"]').change(function () {
                    let payMode = $('#paymode').val();
                    if (payMode == 1) {
                        let bankAccountId = $('#debit-voucher-form select#credit_account option:selected').attr('data-bank-ac');
                        voucher.fetchBankOfDebitAcc(bankAccountId, payMode);
                    }
                });
            },
            fetchBankOfDebitAcc: function (bankAccountId, payMode) {
                if (bankAccountId && payMode) {
                    axios.get(`/basic-finance/api/v1/get-parent-bank-acc-no/${bankAccountId}`)
                        .then((response) => {
                            let bankName = response.data;
                            if (bankName) {
                                $('#debit-voucher-form input[name="bank_name"]').val(bankName.text);
                                $('#debit-voucher-form input[name="bank_id"]').val(bankName.id);
                                if ((window.location.pathname == '/basic-finance/vouchers/create') || (payMode == 1)) {
                                    this.fetchCreditAccountWiseChequeNo(bankName.bankAccId);
                                }
                            }
                        })
                }
            },
            fetchCreditAccountWiseChequeNo: function (bankAccountId) {
                if (bankAccountId) {
                    axios.get(`/basic-finance/api/v1/get-cheque-no/${bankAccountId}`)
                        .then((response) => {
                            let chequeList = response.data;
                            if (chequeList) {
                                chequeList.forEach(item => {
                                    var newOption = new Option(item.text, item.id, false, false);
                                    $('#cheque_no').append(newOption).trigger('change');
                                });


                                // $('#debit-voucher-form input[name="cheque_name"]').val(chequeNo.text);
                                // $('#debit-voucher-form input[name="store_cheque_name"]').val(chequeNo.text);
                                // $('#debit-voucher-form input[name="cheque_no"]').val(chequeNo.id);
                            } else {
                                $('#debit-voucher-form input[name="cheque_name"]').val('');
                                $('#debit-voucher-form input[name="cheque_no"]').val(null).trigger('change');
                            }
                        })
                }
            },

            debit: function () {
                let voucher = this;
                // $('#debit-voucher-form input[name="debit"]').focusin(function () {
                //     $(this).removeClass('invalid');
                // });
                $('#debit-voucher-form input[name="dr_fc"]').keyup(function () {
                    let conversionRate = $('#debit-voucher-form input[name="conversion_rate"]').val();
                    let drFc = $(this).val();
                    if (isNaN(drFc)) {
                        voucher.errorMessage('FC must be a number');
                        return;
                    }
                    let drBd = parseFloat(conversionRate) * parseFloat(drFc);
                    $('#debit-voucher-form input[name="dr_bd"]').val(drBd || '');
                });
                $('#debit-voucher-form input[name="conversion_rate"]').keyup(function () {
                    let conversionRate = $(this).val();
                    let drFc = $('#debit-voucher-form input[name="dr_fc"]').val();
                    if (isNaN(drFc)) {
                        voucher.errorMessage('Conversion Rate must be a number');
                        return;
                    }
                    let drBd = parseFloat(conversionRate) * parseFloat(drFc);
                    $('#debit-voucher-form input[name="dr_bd"]').val(drBd || '');
                });

            },
            credit: function () {
                $('#debit-voucher-form select[name="credit_account"]').change(function () {
                    $(this).removeClass('invalid');
                });
            },
            addToCart: function () {
                let voucher = this;

                $('#debit-voucher-form .add-to-cart').click(function () {
                    let account = $('#debit-voucher-form select#account option:selected'),
                        account_code = $('#debit-voucher-form input[name="account_code"]').val(),
                        department_id = $('#debit-voucher-form select#department_id option:selected'),
                        const_center = $('#debit-voucher-form select#const_center option:selected'),
                        conversion_rate = $('#debit-voucher-form input[name="conversion_rate"]').val(),
                        // debit = parseFloat($('#debit-voucher-form input[name="debit"]').val()),
                        dr_fc = $('#debit-voucher-form input[name="dr_fc"]').val(),
                        dr_bd = $('#debit-voucher-form input[name="dr_bd"]').val(),
                        narration = $('#debit-voucher-form input[name="narration"]').val(),
                        currencyId = $('#currency_id').val(),
                        // particulars = $('#debit-voucher-form input[name="particulars"]').val(),
                        validItem = true;

                    console.log(currencyId, typeof conversion_rate, typeof dr_fc);

                    if (!parseInt(account.attr('data-id'))) {
                        $('#debit-voucher-form select[name="account"]').addClass('invalid');
                        validItem = false;
                    }

                    if (!parseInt(department_id.attr('data-id'))) {
                        $('#debit-voucher-form select[name="department_id"]').addClass('invalid');
                        validItem = false;
                    }

                    if (!parseInt(const_center.attr('data-id'))) {
                        $('#debit-voucher-form select[name="const_center"]').addClass('invalid');
                        validItem = false;
                    }

                    if (!dr_bd) {
                        validItem = false;
                    }

                    // if (isNaN(debit) && debit <= 0) {
                    //     $('#debit-voucher-form input[name="debit"]').addClass('invalid');
                    //     validItem = false;
                    // }

                    if (!validItem) {
                        return;
                    }

                    $('#debit-voucher-form input[name="account"]').removeClass('invalid');
                    $('#debit-voucher-form input[name="account_code"]').removeClass('invalid');
                    $('#debit-voucher-form input[name="const_center"]').removeClass('invalid');
                    let data = {
                        'account_id': account.attr('data-id'),
                        'account_code': account_code,
                        'account_name': account.attr('data-name'),
                        'const_center_name': const_center.attr('data-name'),
                        'department_id': department_id.attr('data-id'),
                        'department_name': department_id.attr('data-name'),
                        'const_center': const_center.attr('data-id'),
                        'conversion_rate': conversion_rate ? conversion_rate : 1,
                        'dr_fc': dr_fc ? dr_fc : 0,
                        'dr_bd': dr_bd ? dr_bd : 0,
                        'debit': dr_bd ? dr_bd : 0,
                        'narration': narration,
                        // 'particulars': particulars
                    };

                    if (!editIndex) {
                        DebitVoucherCart.items.push(data);
                    } else {
                        DebitVoucherCart.items[editIndex] = {...data};
                        editIndex = null;
                    }

                    $('#debit-voucher-form select[name="account"]').val('');
                    $('#debit-voucher-form select[name="account"]').select2('val', '0');
                    $('#debit-voucher-form select[name="account_code"]').val('');
                    // $('#debit-voucher-form select[name="department_id"]').val('');
                    // $('#debit-voucher-form select[name="department_id"]').select2('val', '0');
                    // $('#debit-voucher-form select[name="const_center"]').val('');
                    // $('#debit-voucher-form select[name="const_center"]').select2('val', '0');
                    // $('#debit-voucher-form input[name="conversion_rate"]').val('');
                    // $('#debit-voucher-form input[name="dr_fc"]').val('');
                    // $('#debit-voucher-form input[name="dr_bd"]').val('');
                    // $('#debit-voucher-form input[name="debit"]').val('');
                    // $('#debit-voucher-form input[name="narration"]').val('');

                    voucher.renderCart();

                    $('#debit-voucher-form input[name="account"]').focus();
                });
            },
            removeFromCart: function () {
                var voucher = this;

                $('#debit-voucher-form').on('click', '.remove-from-cart', function (event) {
                    var dataIndex = $(this).attr('data-index');

                    DebitVoucherCart.items = DebitVoucherCart.items.filter(function (item, key) {
                        return key != dataIndex;
                    });

                    voucher.renderCart();
                });
            },
            editFromCart: function () {
                $('#debit-voucher-form').on('click', '.edit-from-cart', function () {
                    editIndex = $(this).attr('data-index');
                    $('#debit-voucher-form select[name="account"]').select2('val', DebitVoucherCart.items[editIndex].account_id);
                    $('#debit-voucher-form input[name="account_code"]').val(DebitVoucherCart.items[editIndex].account_code);
                    $('#debit-voucher-form select[name="department_id"]').select2('val', DebitVoucherCart.items[editIndex].department_id);
                    $('#debit-voucher-form select[name="const_center"]').select2('val', DebitVoucherCart.items[editIndex].const_center);
                    $('#debit-voucher-form input[name="conversion_rate"]').val(DebitVoucherCart.items[editIndex].conversion_rate);
                    $('#debit-voucher-form input[name="dr_fc"]').val(DebitVoucherCart.items[editIndex].dr_fc);
                    $('#debit-voucher-form input[name="dr_bd"]').val(DebitVoucherCart.items[editIndex].dr_bd);
                    $('#debit-voucher-form input[name="narration"]').val(DebitVoucherCart.items[editIndex].narration);
                });
            },
            renderCart: function () {
                var cartLength = DebitVoucherCart.items.length,
                    trs = [],
                    totalDrFc = 0,
                    totalDebit = 0;

                $('#debit-voucher-form .voucher-items').removeClass('text-danger');

                for (let i = 0; i < cartLength; i++) {
                    trs.push([
                        '<tr>',
                        '<td>' + DebitVoucherCart.items[i].account_code + '</td>',
                        '<td>' + DebitVoucherCart.items[i].account_name + '</td>',
                        '<td>' + DebitVoucherCart.items[i].department_name + '</td>',
                        '<td>' + DebitVoucherCart.items[i].const_center_name + '</td>',
                        '<td>' + DebitVoucherCart.items[i].conversion_rate + '</td>',
                        '<td class="text-right">' + (DebitVoucherCart.items[i].dr_fc ? parseFloat(DebitVoucherCart.items[i].dr_fc).toFixed(2) : '') + '</td>',
                        '<td class="text-right">' + (DebitVoucherCart.items[i].dr_bd ? parseFloat(DebitVoucherCart.items[i].dr_bd).toFixed(2) : '') + '</td>',
                        '<td>' + DebitVoucherCart.items[i].narration + '</td>',
                        '<td class="text-center">',
                        '<a class="pointer-cursor text-danger remove-from-cart" data-id="' + DebitVoucherCart.items[i].account_id + '"data-index="' + i + '"><i class="fa fa-remove"></i></a>',
                        '<a class="pointer-cursor text-info edit-from-cart" style="margin-left: 5px;" data-id="' + DebitVoucherCart.items[i].account_id + '"data-index="' + i + '"><i class="fa fa-pencil"></i></a>',
                        '</td>',
                        '</tr>',
                    ].join(''));

                    totalDrFc += parseFloat(DebitVoucherCart.items[i].dr_fc);
                    totalDebit += parseFloat(DebitVoucherCart.items[i].debit);
                }

                if (!cartLength) {
                    trs.push([
                        '<tr>',
                        '<td colspan="11" class="text-center">No Items in the Cart</td>',
                        '</tr>'
                    ].join(''));
                }

                $('#debit-voucher-form .voucher-items').html(trs.join(''));
                $('#debit-voucher-form .total-debit').html(totalDebit.toFixed(2));
                $('#debit-voucher-form .total-dr-fc').html(totalDrFc.toFixed(2));

                $('#debit-voucher-form .total-debit').removeClass('text-danger');

                DebitVoucherCart.total_debit_fc = parseFloat(totalDrFc.toFixed(2));
                DebitVoucherCart.total_debit = parseFloat(totalDebit.toFixed(2));
            },
            updateChequeDetails: function (chequeId, to, amount, trnDate, dueDate) {
                if (chequeId && to && amount && trnDate && dueDate) {
                    axios.get(`/basic-finance/api/v1/update-cheque-details/${chequeId}/${to}/${amount}/${trnDate}/${dueDate}`)
                }
            },
            submitAndCopyForm: function () {
                var voucher = this;
                $('#debit-voucher-form .voucher-submit-copy-btn').click(function (event) {
                    let creditAccount = $('#debit-voucher-form select#credit_account option:selected');
                    DebitVoucherCart.type_id = $('#debit-voucher-form input[name="type_id"]').val();
                    DebitVoucherCart.trn_date = $('#debit-voucher-form input[name="trn_date"]').val();
                    DebitVoucherCart.file_no = $('#debit-voucher-form input[name="file_no"]').val();
                    DebitVoucherCart.voucher_no = $('#debit-voucher-form input[name="voucher_no"]').val();
                    DebitVoucherCart.show_cheque_no = $('#debit-voucher-form input[name="show_cheque_no"]').val();
                    DebitVoucherCart.paymode = $('#debit-voucher-form #paymode').val();
                    if (DebitVoucherCart.paymode == 2) {
                        DebitVoucherCart.bank_id = null;
                        DebitVoucherCart.cheque_no = null;
                        DebitVoucherCart.cheque_due_date = null;
                    } else {
                        DebitVoucherCart.bank_id = $('#debit-voucher-form input[name="bank_id"]').val();
                        DebitVoucherCart.cheque_no =  $('#debit-voucher-form #cheque_no').val();
                        DebitVoucherCart.cheque_due_date = $('#debit-voucher-form input[name="cheque_due_date"]').val();
                    }
                    DebitVoucherCart.receive_bank_id = null;
                    DebitVoucherCart.receive_cheque_no = null;
                    DebitVoucherCart.factory_id = $('#factory_id').val();
                    DebitVoucherCart.general_particulars = $('#debit-voucher-form input[name="general_particulars"]').val();
                    DebitVoucherCart.total_credit = DebitVoucherCart.total_debit;
                    DebitVoucherCart.credit_account = creditAccount.attr('data-id');
                    DebitVoucherCart.credit_account_name = creditAccount.attr('data-name');
                    DebitVoucherCart.credit_account_code = creditAccount.attr('data-code');
                    DebitVoucherCart.unit_id = $('#debit-voucher-form #unit_id').val();
                    DebitVoucherCart.project_id = $('#debit-voucher-form #project_id').val();
                    DebitVoucherCart.currency_id = $('#debit-voucher-form #currency_id').val();
                    DebitVoucherCart.to = $('#to').val();
                    DebitVoucherCart.reference_no = $('#debit-voucher-form input[name="reference_no"]').val();
                    DebitVoucherCart.bill_no = $('#debit-voucher-form input[name="bill_no"]').val();
                    DebitVoucherCart.department_id = (DebitVoucherCart.items[0] ? DebitVoucherCart.items[0].department_id:'');
                    DebitVoucherCart.department_name = (DebitVoucherCart.items[0] ? DebitVoucherCart.items[0].department_name:'');
                    DebitVoucherCart.const_center = (DebitVoucherCart.items[0] ? DebitVoucherCart.items[0].const_center: '');
                    DebitVoucherCart.const_center_name = (DebitVoucherCart.items[0] ? DebitVoucherCart.items[0].const_center_name: '');

                    if (DebitVoucherCart.paymode == 1) {
                        voucher.updateChequeDetails(DebitVoucherCart.cheque_no, DebitVoucherCart.to, DebitVoucherCart.total_debit, DebitVoucherCart.trn_date, DebitVoucherCart.cheque_due_date);
                    }
                    if (voucher.validate()) {
                        let method = $('#debit-voucher-form input[name="_method"]').val();
                        $.ajax({
                            url: `store-and-copy`,
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
                                factory_id: DebitVoucherCart.factory_id,
                                amount: DebitVoucherCart.total_debit,
                                general_particulars: DebitVoucherCart.general_particulars,
                                project_id: DebitVoucherCart.project_id,
                                unit_id: DebitVoucherCart.unit_id,
                                currency_id: DebitVoucherCart.currency_id,
                                paymode: DebitVoucherCart.paymode,
                                reference_no: DebitVoucherCart.reference_no,
                                bill_no: DebitVoucherCart.bill_no,
                                to: DebitVoucherCart.to,
                                details: JSON.stringify(DebitVoucherCart)
                            },
                            beforeSend: function () {
                                $('#debit-voucher-form .voucher-submit-copy-btn').html('Submitting...');
                            },
                            success: function (previewUrl, status) {
                                DebitVoucherCart.trn_date = moment(new Date()).format('DD-MM-YYYY');
                                DebitVoucherCart.items = [];
                                DebitVoucherCart.total_debit = 0;
                                DebitVoucherCart.total_credit = 0;
                                DebitVoucherCart.voucher_amount = 0;
                                DebitVoucherCart.general_particulars = null;
                                DebitVoucherCart.credit_account = 0;
                                DebitVoucherCart.credit_account = 0;
                                let bankAccountId = $('#debit-voucher-form input[name="bank_id"]').val();
                                axios.get(`/basic-finance/api/v1/get-cheque-no/${bankAccountId}`)
                                    .then((response) => {
                                        let chequeList = response.data;
                                        if (chequeList) {

                                            chequeList.forEach(item => {
                                                var newOption = new Option(item.text, item.id, false, false);
                                                $('#cheque_no').append(newOption).trigger('change');
                                            });

                                            // $('#debit-voucher-form input[name="cheque_name"]').val(chequeNo.text);
                                            // $('#debit-voucher-form input[name="store_cheque_name"]').val(chequeNo.text);
                                            // $('#debit-voucher-form input[name="cheque_no"]').val(chequeNo.id);
                                        } else {
                                            $('#debit-voucher-form input[name="cheque_name"]').val('');
                                            $('#debit-voucher-form input[name="cheque_no"]').val();
                                        }
                                    });
                                var voucherType = $('#voucher_type').val();
                                axios.get(`/basic-finance/api/v1/get-voucher-no?voucher_type=${voucherType}`).then(response => {
                                    DebitVoucherCart.voucher_no = $('#debit-voucher-form input[name="voucher_no"]').val(response.data);
                                    voucher.renderCart();
                                    $('#debit-voucher-form .voucher-submit-copy-btn').html('Process & Copy');
                                    voucher.showSuccessMessage();
                                    $('#debit-voucher-form div.message').html();
                                });
                            },
                            error: function (error) {
                                let errors = {...error.responseJSON.errors};
                                $.each(errors, function (key, value) {
                                    $(`#debit-voucher-form #${key}`).addClass('invalid');
                                    $(`#debit-voucher-form #${key}`).attr('title', value[0]);
                                });
                            }
                        });
                    }
                });
            },
            submitForm: function () {
                var voucher = this;


                $('#debit-voucher-form .voucher-submit-btn').click(function (event) {

                    let creditAccount = $('#debit-voucher-form select#credit_account option:selected');
                    DebitVoucherCart.type_id = $('#debit-voucher-form input[name="type_id"]').val();
                    DebitVoucherCart.trn_date = $('#debit-voucher-form input[name="trn_date"]').val();
                    DebitVoucherCart.file_no = $('#debit-voucher-form input[name="file_no"]').val();
                    DebitVoucherCart.voucher_no = $('#debit-voucher-form input[name="voucher_no"]').val();
                    DebitVoucherCart.show_cheque_no = $('#debit-voucher-form input[name="show_cheque_no"]').val();
                    DebitVoucherCart.paymode = $('#debit-voucher-form #paymode').val();
                    if (DebitVoucherCart.paymode == 2) {
                        DebitVoucherCart.bank_id = null;
                        DebitVoucherCart.cheque_no = null;
                        DebitVoucherCart.cheque_due_date = null;
                    } else {
                        DebitVoucherCart.bank_id = $('#debit-voucher-form input[name="bank_id"]').val();
                        DebitVoucherCart.cheque_no =  $('#debit-voucher-form #cheque_no').val();
                        DebitVoucherCart.cheque_due_date = $('#debit-voucher-form input[name="cheque_due_date"]').val();
                    }
                    DebitVoucherCart.receive_bank_id = null;
                    DebitVoucherCart.receive_cheque_no = null;
                    DebitVoucherCart.factory_id = $('#factory_id').val();
                    DebitVoucherCart.general_particulars = $('#debit-voucher-form input[name="general_particulars"]').val();
                    DebitVoucherCart.total_credit = DebitVoucherCart.total_debit;
                    DebitVoucherCart.credit_account = creditAccount.attr('data-id');
                    DebitVoucherCart.credit_account_name = creditAccount.attr('data-name');
                    DebitVoucherCart.credit_account_code = creditAccount.attr('data-code');
                    DebitVoucherCart.unit_id = $('#debit-voucher-form #unit_id').val();
                    DebitVoucherCart.project_id = $('#debit-voucher-form #project_id').val();
                    DebitVoucherCart.currency_id = $('#debit-voucher-form #currency_id').val();
                    DebitVoucherCart.to = $('#to').val();
                    DebitVoucherCart.reference_no = $('#debit-voucher-form input[name="reference_no"]').val();
                    DebitVoucherCart.bill_no = $('#debit-voucher-form input[name="bill_no"]').val();
                    DebitVoucherCart.department_id = (DebitVoucherCart.items[0] ? DebitVoucherCart.items[0].department_id:'');
                    DebitVoucherCart.department_name = (DebitVoucherCart.items[0] ? DebitVoucherCart.items[0].department_name:'');
                    DebitVoucherCart.const_center = (DebitVoucherCart.items[0] ? DebitVoucherCart.items[0].const_center: '');
                    DebitVoucherCart.const_center_name = (DebitVoucherCart.items[0] ? DebitVoucherCart.items[0].const_center_name: '');

                    if (DebitVoucherCart.paymode == 1) {
                        voucher.updateChequeDetails(DebitVoucherCart.cheque_no, DebitVoucherCart.to, DebitVoucherCart.total_debit, DebitVoucherCart.trn_date, DebitVoucherCart.cheque_due_date);
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
                                factory_id: DebitVoucherCart.factory_id,
                                amount: DebitVoucherCart.total_debit,
                                general_particulars: DebitVoucherCart.general_particulars,
                                project_id: DebitVoucherCart.project_id,
                                unit_id: DebitVoucherCart.unit_id,
                                currency_id: DebitVoucherCart.currency_id,
                                paymode: DebitVoucherCart.paymode,
                                reference_no: DebitVoucherCart.reference_no,
                                bill_no: DebitVoucherCart.bill_no,
                                to: DebitVoucherCart.to,
                                details: JSON.stringify(DebitVoucherCart)
                            },
                            beforeSend: function () {
                                $('#debit-voucher-form .voucher-submit-btn').html('Submitting...');
                            },
                            success: function (previewUrl, status) {
                                DebitVoucherCart.trn_date = moment(new Date()).format('DD-MM-YYYY');
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

                                    $('#debit-voucher-form .voucher-submit-btn').html('Process');
                                    $('#debit-voucher-form').trigger("reset");
                                    $("#debit-voucher-form .select2-input").select2("val", "0");

                                    voucher.showSuccessMessage();

                                    // window.open(previewUrl, '_blank');
                                    window.location = previewUrl;
                                }
                            },
                            error: function (error) {
                                let errors = {...error.responseJSON.errors};
                                $.each(errors, function (key, value) {
                                    $(`#debit-voucher-form #${key}`).addClass('invalid');
                                    $(`#debit-voucher-form #${key}`).attr('title', value[0]);
                                });
                            }
                        });
                    }
                });
            },
            validate: function () {
                let validation = true;
                if (DebitVoucherCart.factory_id == 0) {
                    $('#debit-voucher-form #factory_id').addClass('invalid');
                    validation = false;
                }

                if (DebitVoucherCart.project_id == 0) {
                    $('#debit-voucher-form #project_id').addClass('invalid');
                    validation = false;
                }

                if (DebitVoucherCart.unit_id == 0) {
                    $('#debit-voucher-form #unit_id').addClass('invalid');
                    validation = false;
                }

                if (DebitVoucherCart.paymode == 0) {
                    $('#debit-voucher-form #paymode').addClass('invalid');
                    validation = false;
                }

                // if ((DebitVoucherCart.paymode == 1) && (DebitVoucherCart.bank_id == 0)) {
                //     $('#debit-voucher-form input[name="bank_name"]').addClass('invalid');
                //     validation = false;
                // } else {
                //     $('#debit-voucher-form input[name="bank_name"]').removeClass('invalid');
                // }

                // let chequeNoCheck = $('#show_cheque_no').is(":checked");
                // if ((DebitVoucherCart.paymode == 1) && (DebitVoucherCart.cheque_no == '') && !chequeNoCheck) {
                //     $('#cheque_no').addClass('invalid');
                //     $('#cheque_name').addClass('invalid');
                //     validation = false;
                // } else {
                //     $('#cheque_no').removeClass('invalid');
                //     $('#cheque_name').removeClass('invalid');
                // }

                // if ((DebitVoucherCart.paymode == 1) && (DebitVoucherCart.cheque_due_date == '')) {
                //     $('#debit-voucher-form input[name="cheque_due_date"]').addClass('invalid');
                //     validation = false;
                // } else {
                //     $('#debit-voucher-form input[name="cheque_due_date"]').removeClass('invalid');
                // }

                if ((DebitVoucherCart.paymode == 1) && (DebitVoucherCart.to == '')) {
                    $('#debit-voucher-form input[name="to"]').addClass('invalid');
                    validation = false;
                } else {
                    $('#debit-voucher-form input[name="to"]').removeClass('invalid');
                }
                if (DebitVoucherCart.trn_date == '') {
                    $('#debit-voucher-form input[name="trn_date"]').addClass('invalid');
                    validation = false;
                }

                if (DebitVoucherCart.items.length == 0) {
                    $('#debit-voucher-form .voucher-items').addClass('text-danger');
                    validation = false;
                }

                if (DebitVoucherCart.credit_account == undefined) {
                    $('#debit-voucher-form #credit_account').addClass('invalid');
                    validation = false;
                }

                return validation;
            },
            refreshForm: function () {
                let voucher = this;
                $('#debit-voucher-form .voucher-refresh-btn').click(function (event) {
                    $('#debit-voucher-form #factory_id').val(null).trigger('change');
                    $('#debit-voucher-form #project_id').val(null).trigger('change');
                    $('#debit-voucher-form #unit_id').val(null).trigger('change');
                    $('#debit-voucher-form #currency_id').val(1).trigger('change');
                    $('#debit-voucher-form #paymode').val(null).trigger('change');
                    $('#debit-voucher-form input[name="reference_no"]').val(null);
                    $('#debit-voucher-form input[name="to"]').val(null);
                    $('#debit-voucher-form input[name="bank_id"]').val(null);
                    $('#debit-voucher-form input[name="bank_name"]').val(null);
                    $('#debit-voucher-form input[name="cheque_name"]').val(null);
                    $('#debit-voucher-form input[name="cheque_no"]').val(null);
                    $('#debit-voucher-form .voucher-items').find('tr').remove();
                    DebitVoucherCart.items = [];
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

                $('#debit-voucher-form div.message').html(successMessage);
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

                $('#debit-voucher-form div.message').html(errorMessage);
            },
            init: function () {
                $("#debit-voucher-form select#currency_id").val();

                if ($('#debit-voucher-form #currency_id').val() != 1) {
                    $('#debit-voucher-form input[name="dr_bd"]').attr('readonly', 'true');
                } else {
                    $('#debit-voucher-form input[name="conversion_rate"]').attr('readonly', 'true');
                    $('#debit-voucher-form input[name="dr_fc"]').attr('readonly', 'true');
                }

                this.changeCompany();
                this.changeProject();
                this.changePayMode();
                this.changeCreditAccount();
                this.fetchPayModeWiseCreditAccount($('#debit-voucher-form  select[name="paymode"]').val());
                paymodeChange();
                this.fetchBankOfDebitAcc();
                this.fetchCreditAccountWiseChequeNo();
                this.factory();
                this.project();
                this.unit();
                this.paymode();
                this.trnDate();
                this.showChequeNo();
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

        DebitVoucher.init();

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
            let cheque_no = jQuery('#cheque_no');
            let cheque_due_date = jQuery('#cheque_due_date');
            if (jQuery(paymode).val() == 1) {
                bank_id.prop('disabled', false);
                cheque_no.prop('disabled', false);
                cheque_due_date.prop('disabled', false);
            } else {
                bank_id.prop('disabled', true).val(null);
                cheque_no.prop('disabled', true).val(null);
                cheque_due_date.prop('disabled', true).val(null);
            }
        }
    </script>
@endsection
