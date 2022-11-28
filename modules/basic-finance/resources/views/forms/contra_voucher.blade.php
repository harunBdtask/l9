<style>
    .custom-padding {
        padding: 0 200px 0 200px;
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
</style>

<!--Credit Voucher Form -->
<?php echo Form::open([
    "method" => isset($voucher) ? 'PUT' : 'POST',
    "url" => isset($voucher) ? url('basic-finance/vouchers/' . $voucher->id) : url('basic-finance/vouchers'),
    "id" => 'journal-voucher-form'
]); ?>
<input type="hidden" value="{{isset($voucher) ? $voucher->id : null}}" id="id">
<input type="hidden" value="{{ $voucherType }}" id="voucher_type">
<div class="from-group row message"></div>
<input type="hidden" name="type_id"
       value="<?php echo e(\SkylarkSoft\GoRMG\BasicFinance\Models\Voucher::CONTRA_VOUCHER); ?>">

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
        <label class="col-sm-4 form-control-label">Transaction Date:</label>
        <div class="col-sm-8">
            @php
                $trnDate = isset($voucher) ? $voucher->trn_date->format('Y-m-d') : $today_date;
            @endphp
            <input type="date" class="form-control" name="trn_date" value="<?php echo e($trnDate); ?>">
        </div>
    </div>
</div>

<div class="col-md-4"></div>

<div class="col-md-4">
    <div class="form-group row">
        <label class="col-sm-4 form-control-label">Server Date:</label>
        <div class="col-sm-8">
            @php
                $trnDate = isset($voucher) ? $voucher->created_at : $created_date;
            @endphp
            <input type="datetime" class="form-control" name="server_date" id="server_date" value="{{ $trnDate }}" readonly>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 form-control-label">Voucher No:</label>
        <div class="col-sm-8">
            <input name="voucher_no" id="voucher_no" type="text" class="form-control" autocomplete="off"
                   value="<?php echo e($voucherNo); ?>" readonly>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 form-control-label">Reference No:</label>
        <div class="col-sm-8">
            @php
                $referenceNo = isset($voucher) ? $voucher->reference_no : '';
            @endphp
            <input type="text" class="form-control" name="reference_no" id="reference_no" value="<?php echo e($referenceNo); ?>">
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
                    <th rowspan="2" style="width: 7%;">CON. RATE</th>
                    <th colspan="2" style="width: 20%;">FC</th>
                    <th colspan="2" style="width: 20%;">BDT</th>
                    <th class="text-right" rowspan="2"  style="width: 13%;">NARRATION</th>
                    <th class="text-center" width="2%" rowspan="2">ACTION</th>
                </tr>
                <tr>
                    <th style="width: 10%;">DEBIT</th>
                    <th style="width: 10%;">CREDIT</th>
                    <th style="width: 10%;">DEBIT</th>
                    <th style="width: 10%;">CREDIT</th>
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
                            <option value="null">Select</option>
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
                                    {{ $account->name }} - ({{ $account->parentAc->name }})
                                </option>
                            @endif
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </td>
                    <td>
                        <select class="form-control c-select select2-input" name="department_id" id="department_id">
                            <option value="null">Select</option>
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
                        <input type="text" name="conversion_rate" class="form-control fontSize" autocomplete="off">
                    </td>
                    <td>
                        <input type="text" name="dr_fc" id="dr_fc" class="form-control fontSize" autocomplete="off">
                    </td>
                    <td>
                        <input type="text" name="cr_fc" id="cr_fc" class="form-control fontSize" autocomplete="off">
                    </td>
                    <td>
                        <input type="text" name="dr_bd" id="dr_bd" class="form-control fontSize" autocomplete="off">
                    </td>
                    <td>
                        <input type="text" name="cr_bd" id="cr_bd" class="form-control fontSize" autocomplete="off">
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
                    <td colspan="12" class="text-center">&nbsp;</td>
                </tr>
                </tbody>
                <tbody class="voucher-items">
                <tr>
                    <td colspan="12" class="text-center">No Items in the Cart</td>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                    <td class="text-right" colspan="5"><strong>TOTAL</strong></td>
                    <td class="text-right total-dr-fc" data-total-dr-fc="0.00">0.00</td>
                    <td class="text-right total-cr-fc" data-total-cr-fc="0.00">0.00</td>
                    <td class="text-right total-debit" data-total-debit="0.00">0.00</td>
                    <td class="text-right total-credit" data-total-credit="0.00">0.00</td>
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
        <span class="btn btn-primary voucher-submit-btn">Process</span>
            @if(!isset($voucher))
        <span class="btn btn-info voucher-refresh-btn">Refresh</span>
            @endif
        <a class="btn btn-danger" href="<?php echo e(url('basic-finance/vouchers')); ?>">
            Cancel
        </a>
    </div>
</div>
{!! Form::close(); !!}


<?php $__env->startSection('scripts'); ?>
<script type="text/javascript">
    // Journal Voucher

    <?php
    if (isset($voucher)) {
        echo 'const Cart = ' . json_encode($voucher->details) . ';';
    }
    ?>

    const JournalVoucherCart = {
        'project_id': null,
        'unit_id': null,
        'voucher_no': null,
        'trn_date': null,
        'factory_id': null,
        'reference_no': null,
        'items': [],
        'total_debit_fc': 0,
        'total_credit_fc': 0,
        'total_debit': 0,
        'total_credit': 0,
        'file_no': '',
        'server_date' : null,
        'currency_id': null,
        'general_particulars': null
    };

    if (typeof Cart !== 'undefined') {
        JournalVoucherCart.trn_date = Cart.trn_date;
        JournalVoucherCart.items = Cart.items;
        JournalVoucherCart.total_debit_fc = Cart.total_debit_fc;
        JournalVoucherCart.total_credit_fc = Cart.total_credit_fc;
        JournalVoucherCart.total_debit = Cart.total_debit;
        JournalVoucherCart.total_credit = Cart.total_credit;
        JournalVoucherCart.file_no = Cart.file_no;
        JournalVoucherCart.general_particulars = Cart.general_particulars;
    }

    let editIndex = null;

    var JournalVoucher = {
        whenEditIdFound: function () {
            let voucher = this;
            let id = $('#id').val();
            let companyId = $('#factory_id').val();
            let projectId = $('#project_id').val();
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
                    jQuery(`#project_id`).find('option').not(':first').remove();
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
                    jQuery(`#unit_id`).find('option').not(':first').remove();
                    units.forEach((unit) => {
                        options.push([
                            `<option value="${unit.id}" data-id="${unit.id}" data-name="${unit.text}">${unit.text}</option>`
                        ].join(''));
                    });
                    $('#unit_id').append(options);
                    $('#unit_id').select2('val', 0);
                }).finally(() => {
                    if (editIndex) {
                        jQuery('#journal-voucher-form select[name="const_center"]').select2('val', JournalVoucherCart.items[editIndex].const_center);
                    }
                })
            }
        },
        trnDate: function () {
            $('#journal-voucher-form input[name="trn_date"]').focusin(function () {
                $(this).removeClass('invalid');
            });
        },
        account: function () {
            $('#journal-voucher-form select[name="account"]').change(function () {
                $(this).removeClass('invalid');
                let account = $('#journal-voucher-form select#account option:selected')
                $('#journal-voucher-form input[name="account_code"]').val(account.attr('data-code'));
            });
        },
        department: function () {
            $('#journal-voucher-form select[name="department_id"]').change(function () {
                $(this).removeClass('invalid');
            });
        },
        constCenter: function () {
            $('#journal-voucher-form select[name="const_center"]').change(function () {
                $(this).removeClass('invalid');
            });
        },
        currency: function () {
            $('#journal-voucher-form select[name="currency_id"]').change(function () {
                $(this).removeClass('invalid');
                let currency = $(this).val();
                if (currency == 1) {
                    $('#journal-voucher-form input[name="conversion_rate"]').attr('readonly', 'true');
                    $('#journal-voucher-form input[name="dr_fc"]').attr('readonly', 'true');
                    $('#journal-voucher-form input[name="cr_fc"]').attr('readonly', 'true');
                    $('#journal-voucher-form input[name="dr_bd"]').removeAttr('readonly');
                    $('#journal-voucher-form input[name="cr_bd"]').removeAttr('readonly');
                } else {
                    $('#journal-voucher-form input[name="conversion_rate"]').removeAttr('readonly');
                    $('#journal-voucher-form input[name="dr_fc"]').removeAttr('readonly');
                    $('#journal-voucher-form input[name="cr_fc"]').removeAttr('readonly');
                    $('#journal-voucher-form input[name="dr_bd"]').attr('readonly', 'true');
                    $('#journal-voucher-form input[name="cr_bd"]').attr('readonly', 'true');
                }
            });
        },
        conversionRate: function () {
            let voucher = this;
            $('#journal-voucher-form input[name="conversion_rate"]').keyup(function () {
                let conversionRate = $(this).val();
                if (isNaN(conversionRate)) {
                    voucher.errorMessage('Conversion Rate must be a number');
                    $(this).val('');
                }
            });
        },
        debit: function () {
            let voucher = this;
            $('#journal-voucher-form input[name="dr_fc"]').keyup(function () {
                $('#journal-voucher-form input[name="cr_fc"]').val('');
                $('#journal-voucher-form input[name="cr_bd"]').val('');
                let conversionRate = $('#journal-voucher-form input[name="conversion_rate"]').val();
                let drFc = $(this).val();
                if (isNaN(drFc)) {
                    voucher.errorMessage('FC must be a number');
                    return;
                }
                let drBd = parseFloat(conversionRate) * parseFloat(drFc);
                $('#journal-voucher-form input[name="dr_bd"]').val(drBd || '');
            });

            $('#journal-voucher-form input[name="conversion_rate"]').keyup(function () {
                let conversionRate = $(this).val();
                let drFc =  $('#journal-voucher-form input[name="dr_fc"]').val();
                if (isNaN(drFc)) {
                    voucher.errorMessage('Conversion Rate must be a number');
                    return;
                }
                let drBd = parseFloat(conversionRate) * parseFloat(drFc);
                $('#journal-voucher-form input[name="dr_bd"]').val(drBd || '');
            });

            $('#journal-voucher-form input[name="dr_bd"]').keyup(function () {
                $('#journal-voucher-form input[name="cr_bd"]').val('');
            });
        },
        credit: function () {
            let voucher = this;
            $('#journal-voucher-form input[name="cr_fc"]').keyup(function () {
                $('#journal-voucher-form input[name="dr_fc"]').val('');
                $('#journal-voucher-form input[name="dr_bd"]').val('');
                let conversionRate = $('#journal-voucher-form input[name="conversion_rate"]').val()
                let crFc = $(this).val();
                if (isNaN(crFc)) {
                    voucher.errorMessage('FC must be a number');
                    return;
                }
                let crBd = parseFloat(conversionRate) * parseFloat(crFc);
                $('#journal-voucher-form input[name="cr_bd"]').val(crBd || '');
            });
            $('#journal-voucher-form input[name="conversion_rate"]').keyup(function () {
                let conversionRate = $(this).val();
                let crFc = $('#journal-voucher-form input[name="cr_fc"]').val();
                if (isNaN(crFc)) {
                    voucher.errorMessage('Conversion Rate must be a number');
                    return;
                }
                let crBd = parseFloat(conversionRate) * parseFloat(crFc);
                $('#journal-voucher-form input[name="cr_bd"]').val(crBd || '');
            });

            $('#journal-voucher-form input[name="cr_bd"]').keyup(function () {
                $('#journal-voucher-form input[name="dr_bd"]').val('');
            });
        },
        addToCart: function () {
            let voucher = this;

            $('#journal-voucher-form .add-to-cart').click(function () {
                let account = $('#journal-voucher-form select#account option:selected'),
                    account_code = $('#journal-voucher-form input[name="account_code"]').val(),
                    department_id = $('#journal-voucher-form select#department_id option:selected'),
                    const_center = $('#journal-voucher-form select#const_center option:selected'),
                    conversion_rate = $('#journal-voucher-form input[name="conversion_rate"]').val(),
                    dr_fc = $('#journal-voucher-form input[name="dr_fc"]').val(),
                    dr_bd = $('#journal-voucher-form input[name="dr_bd"]').val(),
                    cr_fc = $('#journal-voucher-form input[name="cr_fc"]').val(),
                    cr_bd = $('#journal-voucher-form input[name="cr_bd"]').val(),
                    narration = $('#journal-voucher-form input[name="narration"]').val(),
                    validItem = true;

                if (!parseInt(account.attr('data-id'))) {
                    $('#journal-voucher-form select[name="account"]').addClass('invalid');
                    validItem = false;
                }

                if (!parseInt(department_id.attr('data-id'))) {
                    $('#journal-voucher-form select[name="department_id"]').addClass('invalid');
                    validItem = false;
                }

                if (!parseInt(const_center.attr('data-id'))) {
                    $('#journal-voucher-form select[name="const_center"]').addClass('invalid');
                    validItem = false;
                }

                if (!dr_bd && !cr_bd) {
                    validItem = false;
                }

                if (!validItem) {
                    return;
                }

                $('#journal-voucher-form input[name="account"]').removeClass('invalid');
                $('#journal-voucher-form input[name="account_code"]').removeClass('invalid');
                $('#journal-voucher-form input[name="const_center"]').removeClass('invalid');

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
                    'cr_fc': cr_fc ? cr_fc : 0,
                    'cr_bd': cr_bd ? cr_bd : 0,
                    'credit': cr_bd ? cr_bd : 0,
                    'narration': narration,
                };

                if (!editIndex) {
                    JournalVoucherCart.items.push(data);
                } else {
                    JournalVoucherCart.items[editIndex] = {...data};
                    editIndex = null;
                }

                $('#journal-voucher-form select[name="account"]').val('');
                $('#journal-voucher-form select[name="account"]').select2('val', '0');
                $('#journal-voucher-form select[name="account_code"]').val('');
                // $('#journal-voucher-form select[name="department_id"]').val('');
                // $('#journal-voucher-form select[name="department_id"]').select2('val', '0');
                // $('#journal-voucher-form select[name="const_center"]').val('');
                // $('#journal-voucher-form select[name="const_center"]').select2('val', '0');
                // $('#journal-voucher-form input[name="conversion_rate"]').val('');
                // $('#journal-voucher-form input[name="dr_fc"]').val('');
                // $('#journal-voucher-form input[name="dr_bd"]').val('');
                // $('#journal-voucher-form input[name="cr_fc"]').val('');
                // $('#journal-voucher-form input[name="cr_bd"]').val('');
                // $('#journal-voucher-form input[name="particulars"]').val('');

                voucher.renderCart();

                $('#journal-voucher-form input[name="account"]').focus();
            });
        },
        removeFromCart: function () {
            var voucher = this;

            $('#journal-voucher-form').on('click', '.remove-from-cart', function (event) {
                var dataIndex = $(this).attr('data-index');

                JournalVoucherCart.items = JournalVoucherCart.items.filter(function (item, key) {
                    return key != dataIndex;
                });

                voucher.renderCart();
            });
        },
        editFromCart: function () {
            $('#journal-voucher-form').on('click', '.edit-from-cart', function () {
                editIndex = $(this).attr('data-index');
                $('#journal-voucher-form select[name="account"]').select2('val', JournalVoucherCart.items[editIndex].account_id);
                $('#journal-voucher-form input[name="account_code"]').val(JournalVoucherCart.items[editIndex].account_code);
                $('#journal-voucher-form select[name="department_id"]').select2('val', JournalVoucherCart.items[editIndex].department_id);
                $('#journal-voucher-form select[name="const_center"]').select2('val', JournalVoucherCart.items[editIndex].const_center);
                $('#journal-voucher-form input[name="conversion_rate"]').val(JournalVoucherCart.items[editIndex].conversion_rate);
                $('#journal-voucher-form input[name="dr_fc"]').val(JournalVoucherCart.items[editIndex].dr_fc);
                $('#journal-voucher-form input[name="dr_bd"]').val(JournalVoucherCart.items[editIndex].dr_bd);
                $('#journal-voucher-form input[name="cr_fc"]').val(JournalVoucherCart.items[editIndex].cr_fc);
                $('#journal-voucher-form input[name="cr_bd"]').val(JournalVoucherCart.items[editIndex].cr_bd);
                $('#journal-voucher-form input[name="narration"]').val(JournalVoucherCart.items[editIndex].narration);
            });
        },
        renderCart: function () {
            let cartLength = JournalVoucherCart.items.length,
                trs = [],
                totalDebit = 0,
                totalDrFc = 0,
                totalCredit = 0,
                totalCrFc = 0;

            $('#journal-voucher-form .voucher-items').removeClass('text-danger');

            for (let i = 0; i < cartLength; i++) {
                trs.push([
                    '<tr>',
                    '<td>' + JournalVoucherCart.items[i].account_code + '</td>',
                    '<td>' + JournalVoucherCart.items[i].account_name + '</td>',
                    '<td>' + JournalVoucherCart.items[i].department_name + '</td>',
                    '<td>' + JournalVoucherCart.items[i].const_center_name + '</td>',
                    '<td>' + JournalVoucherCart.items[i].conversion_rate + '</td>',
                    '<td class="text-right">' + (JournalVoucherCart.items[i].dr_fc ? parseFloat(JournalVoucherCart.items[i].dr_fc).toFixed(2) : '') + '</td>',
                    '<td class="text-right">' + (JournalVoucherCart.items[i].cr_fc ? parseFloat(JournalVoucherCart.items[i].cr_fc).toFixed(2) : '') + '</td>',
                    '<td class="text-right">' + (JournalVoucherCart.items[i].dr_bd ? parseFloat(JournalVoucherCart.items[i].dr_bd).toFixed(2) : '') + '</td>',
                    '<td class="text-right">' + (JournalVoucherCart.items[i].cr_bd ? parseFloat(JournalVoucherCart.items[i].cr_bd).toFixed(2) : '') + '</td>',
                    '<td>' + JournalVoucherCart.items[i].narration + '</td>',
                    '<td class="text-center">',
                    '<a class="pointer-cursor text-danger remove-from-cart" data-id="' + JournalVoucherCart.items[i].account_id + '"data-index="' + i + '"><i class="fa fa-remove"></i></a>',
                    '<a class="pointer-cursor text-info edit-from-cart" style="margin-left: 5px;" data-id="' + JournalVoucherCart.items[i].account_id + '"data-index="' + i + '"><i class="fa fa-pencil"></i></a>',
                    '</td>',
                    '</tr>',
                ].join(''));

                totalDebit += parseFloat(JournalVoucherCart.items[i].debit);
                totalDrFc += parseFloat(JournalVoucherCart.items[i].dr_fc);
                totalCredit += parseFloat(JournalVoucherCart.items[i].credit);
                totalCrFc += parseFloat(JournalVoucherCart.items[i].cr_fc);
            }

            if (!cartLength) {
                trs.push([
                    '<tr>',
                    '<td colspan="12" class="text-center text-danger">No Items in the Cart</td>',
                    '</tr>'
                ].join(''));
            }

            $('#journal-voucher-form .voucher-items').html(trs.join(''));
            $('#journal-voucher-form .total-debit').html(totalDebit.toFixed(2));
            $('#journal-voucher-form .total-dr-fc').html(totalDrFc.toFixed(2));
            $('#journal-voucher-form .total-credit').html(totalCredit.toFixed(2));
            $('#journal-voucher-form .total-cr-fc').html(totalCrFc.toFixed(2));

            $('#journal-voucher-form .total-debit').removeClass('text-danger');
            $('#journal-voucher-form .total-credit').removeClass('text-danger');

            JournalVoucherCart.total_debit_fc = parseFloat(totalDrFc.toFixed(2));
            JournalVoucherCart.total_credit_fc = parseFloat(totalCrFc.toFixed(2));
            JournalVoucherCart.total_debit = parseFloat(totalDebit.toFixed(2));
            JournalVoucherCart.total_credit = parseFloat(totalCredit.toFixed(2));
        },
        submitAndCopyForm: function () {
            var voucher = this;
            $('#journal-voucher-form .voucher-submit-copy-btn').click(function (event) {
                JournalVoucherCart.type_id = $('#journal-voucher-form input[name="type_id"]').val();
                JournalVoucherCart.project_id = $('#journal-voucher-form #project_id').val();
                JournalVoucherCart.unit_id = $('#journal-voucher-form #unit_id').val();
                JournalVoucherCart.voucher_no = $('#journal-voucher-form input[name="voucher_no"]').val();
                JournalVoucherCart.trn_date = $('#journal-voucher-form input[name="trn_date"]').val();
                JournalVoucherCart.factory_id = $('#journal-voucher-form #factory_id').val();
                JournalVoucherCart.server_date = $('#journal-voucher-form #server_date').val();
                JournalVoucherCart.currency_id = $('#journal-voucher-form #currency_id').val();
                JournalVoucherCart.reference_no = $('#journal-voucher-form input[name="reference_no"]').val();
                JournalVoucherCart.general_particulars = $('#journal-voucher-form input[name="general_particulars"]').val();

                if (voucher.validate()) {
                    let method = $('#journal-voucher-form input[name="_method"]').val();
                    $.ajax({
                        url: $('#journal-voucher-form').attr('action'),
                        type: "POST",
                        data: {
                            _token: $('#journal-voucher-form input[name="_token"]').val(),
                            _method: method ? method : 'POST',
                            project_id: $('#journal-voucher-form #project_id').val(),
                            unit_id: $('#journal-voucher-form #unit_id').val(),
                            voucher_no: JournalVoucherCart.voucher_no,
                            type_id: JournalVoucherCart.type_id,
                            trn_date: JournalVoucherCart.trn_date,
                            factory_id: JournalVoucherCart.factory_id,
                            currency_id: $('#journal-voucher-form #currency_id').val(),
                            reference_no: JournalVoucherCart.reference_no,
                            amount: JournalVoucherCart.total_debit,
                            general_particulars: JournalVoucherCart.general_particulars,
                            details: JSON.stringify(JournalVoucherCart)
                        },
                        beforeSend: function () {
                            $('#journal-voucher-form .voucher-submit-copy-btn').html('Submitting...');
                        },
                        success: function (data, status) {
                            JournalVoucherCart.trn_date = moment(new Date()).format('DD-MM-YYYY');
                            JournalVoucherCart.items = [];
                            JournalVoucherCart.total_debit = 0;
                            JournalVoucherCart.total_credit = 0;
                            JournalVoucherCart.voucher_amount = 0;
                            JournalVoucherCart.general_particulars = null;
                            var voucherType = $('#voucher_type').val();
                            axios.get(`/basic-finance/api/v1/get-voucher-no?voucher_type=${voucherType}`).then(response => {
                                JournalVoucherCart.voucher_no = $('#journal-voucher-form input[name="voucher_no"]').val(response.data);
                                voucher.renderCart();
                                $('#journal-voucher-form .voucher-submit-copy-btn').html('Process & Copy');
                                voucher.showSuccessMessage();
                            });
                        },
                        error: function (error) {
                            let errors = {...error.responseJSON.errors};
                            $.each(errors, function (key, value) {
                                $(`#journal-voucher-form #${key}`).addClass('invalid');
                                $(`#journal-voucher-form #${key}`).attr('title', value[0]);
                            });
                        }
                    });
                } else {
                    console.log('response');
                }
            });
        },
        submitForm: function () {
            var voucher = this;
            $('#journal-voucher-form .voucher-submit-btn').click(function (event) {
                JournalVoucherCart.type_id = $('#journal-voucher-form input[name="type_id"]').val();
                JournalVoucherCart.project_id = $('#journal-voucher-form #project_id').val();
                JournalVoucherCart.unit_id = $('#journal-voucher-form #unit_id').val();
                JournalVoucherCart.voucher_no = $('#journal-voucher-form input[name="voucher_no"]').val();
                JournalVoucherCart.trn_date = $('#journal-voucher-form input[name="trn_date"]').val();
                JournalVoucherCart.factory_id = $('#journal-voucher-form #factory_id').val();
                JournalVoucherCart.server_date = $('#journal-voucher-form #server_date').val();
                JournalVoucherCart.currency_id = $('#journal-voucher-form #currency_id').val();
                JournalVoucherCart.reference_no = $('#journal-voucher-form input[name="reference_no"]').val();
                JournalVoucherCart.general_particulars = $('#journal-voucher-form input[name="general_particulars"]').val();

                if (voucher.validate()) {
                    let method = $('#journal-voucher-form input[name="_method"]').val();
                    $.ajax({
                        url: $('#journal-voucher-form').attr('action'),
                        type: "POST",
                        data: {
                            _token: $('#journal-voucher-form input[name="_token"]').val(),
                            _method: method ? method : 'POST',
                            project_id: $('#journal-voucher-form #project_id').val(),
                            unit_id: $('#journal-voucher-form #unit_id').val(),
                            voucher_no: JournalVoucherCart.voucher_no,
                            type_id: JournalVoucherCart.type_id,
                            trn_date: JournalVoucherCart.trn_date,
                            factory_id: JournalVoucherCart.factory_id,
                            currency_id: $('#journal-voucher-form #currency_id').val(),
                            reference_no: JournalVoucherCart.reference_no,
                            amount: JournalVoucherCart.total_debit,
                            general_particulars: JournalVoucherCart.general_particulars,
                            details: JSON.stringify(JournalVoucherCart)
                        },
                        beforeSend: function () {
                            $('#journal-voucher-form .voucher-submit-btn').html('Submitting...');
                        },
                        success: function (data, status) {
                            JournalVoucherCart.trn_date = moment(new Date()).format('DD-MM-YYYY');
                            JournalVoucherCart.items = [];
                            JournalVoucherCart.total_debit = 0;
                            JournalVoucherCart.total_credit = 0;
                            JournalVoucherCart.voucher_amount = 0;
                            JournalVoucherCart.general_particulars = null;

                            if (method) {
                                location.replace(data);
                            } else {
                                voucher.renderCart();

                                $('#journal-voucher-form .voucher-submit-btn').html('Submit');

                                $('#journal-voucher-form').trigger("reset");
                                $("#journal-voucher-form .select2-input").select2("val", "0");

                                voucher.showSuccessMessage();

                                // window.open(previewUrl, '_blank');
                                window.location = data;
                            }
                        },
                        error: function (error) {
                            let errors = {...error.responseJSON.errors};
                            $.each(errors, function (key, value) {
                                $(`#journal-voucher-form #${key}`).addClass('invalid');
                                $(`#journal-voucher-form #${key}`).attr('title', value[0]);
                            });
                        }
                    });
                } else {
                    console.log('response');
                }
            });
        },
        validate: function () {
            let validation = true;

            if (JournalVoucherCart.factory_id == '') {
                $('#journal-voucher-form #factory_id').addClass('invalid');
                console.log('factory_id');
                validation = false;
            } else {
                $('#journal-voucher-form #factory_id').removeClass('invalid');
            }

            if (JournalVoucherCart.project_id == '') {
                $('#journal-voucher-form #project_id').addClass('invalid');
                console.log('project_id');
                validation = false;
            } else {
                $('#journal-voucher-form #project_id').removeClass('invalid');
            }

            if (JournalVoucherCart.unit_id == '') {
                $('#journal-voucher-form #unit_id').addClass('invalid');
                console.log('unit_id');
                validation = false;
            } else {
                $('#journal-voucher-form #unit_id').removeClass('invalid');
            }

            if (JournalVoucherCart.server_date == '') {
                $('#journal-voucher-form #server_date').addClass('invalid');
                console.log('server_date');
                validation = false;
            } else {
                $('#journal-voucher-form #server_date').removeClass('invalid');
            }

            if (JournalVoucherCart.currency_id == '') {
                $('#journal-voucher-form #currency_id').addClass('invalid');
                console.log('currency_id');
                validation = false;
            } else {
                $('#journal-voucher-form #currency_id').removeClass('invalid');
            }

            if (JournalVoucherCart.trn_date == '') {
                $('#journal-voucher-form input[name="trn_date"]').addClass('invalid');
                console.log('trn_date');
                validation = false;
            } else {
                $('#journal-voucher-form #trn_date').removeClass('invalid');
            }

            if (JournalVoucherCart.items.length == 0) {
                $('#journal-voucher-form .voucher-items').addClass('text-danger');
                console.log('items');
                validation = false;
            }

            if (JournalVoucherCart.total_debit != JournalVoucherCart.total_credit) {
                $('#journal-voucher-form .total-debit').addClass('text-danger');
                $('#journal-voucher-form .total-credit').addClass('text-danger');
                console.log('total_debit');
                validation = false;
            }

            return validation;
        },
        refreshForm: function () {
            let voucher = this;
            $('#journal-voucher-form .voucher-refresh-btn').click(function (event) {
                $('#journal-voucher-form #factory_id').val(null).trigger('change');
                $('#journal-voucher-form #project_id').val(null).trigger('change');
                $('#journal-voucher-form #unit_id').val(null).trigger('change');
                $('#journal-voucher-form #currency_id').val(null).trigger('change');
                $('#journal-voucher-form input[name="reference_no"]').val(null);
                $('#journal-voucher-form .voucher-items').find('tr').remove();
                JournalVoucherCart.items = [];
                voucher.renderCart();
            });
        },
        showSuccessMessage: function () {
            let successMessage = [
                '<div class="col-lg-12">',
                '<div class="alert alert-success alert-dismissible show" role="alert">',
                '<strong>Voucher has been created successfully!</strong>',
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close">',
                '<span aria-hidden="true">&times;</span>',
                '</button>',
                '</div>',
                '</div>'
            ].join('');

            $('#journal-voucher-form div.message').html(successMessage);
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

            $('#journal-voucher-form div.message').html(errorMessage);
        },
        init: function () {
            $('#journal-voucher-form #currency_id').val(1);
                if ($('#journal-voucher-form #currency_id').val() != 1) {
                    $('#journal-voucher-form input[name="dr_bd"]').attr('readonly', 'true');
                    $('#journal-voucher-form input[name="cr_bd"]').attr('readonly', 'true');
                } else {
                    $('#journal-voucher-form input[name="conversion_rate"]').attr('readonly', 'true');
                    $('#journal-voucher-form input[name="dr_fc"]').attr('readonly', 'true');
                    $('#journal-voucher-form input[name="cr_fc"]').attr('readonly', 'true');
                }
            // this.whenEditIdFound();
            this.changeCompany();
            this.changeProject();
            this.trnDate();
            this.account();
            this.constCenter();
            this.department();
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

    JournalVoucher.init();
</script>
<?php $__env->stopSection(); ?>
<?php /**PATH /var/www/modules/basic-basic-finance/resources/views/forms/journal_voucher.blade.php ENDPATH**/ ?>
<?php /**PATH /var/www/modules/basic-basic-finance/resources/views/forms/journal_voucher.blade.php ENDPATH**/ ?>
