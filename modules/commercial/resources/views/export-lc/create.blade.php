@extends('commercial::layout')
@section('title','Export LC Entry')

@push('style')
    <style>
        .form-control form-control-sm {
            border: 1px solid #909ac8 !important;
            border-radius: 10px 0 0 0;
        }

        input, select {
            min-height: 30px !important;
        }

        .form-control form-control-sm:focus {
            border: 2px solid #909ac8 !important;
        }

        .req {
            font-size: 1rem;
        }

        .mainForm td, .mainForm th {
            border: none !important;
            padding: .3rem !important;
            text-align: left;
        }

        li.parsley-required, li.parsley-type {
            color: red;
            list-style: none;
            text-align: left;
        }
        th {
           text-align: left;
        }
        input.parsley-error,
        select.parsley-error,
        textarea.parsley-error {
            border-color: #843534;
            box-shadow: none;
        }


        input.parsley-error:focus,
        select.parsley-error:focus,
        textarea.parsley-error:focus {
            border-color: #843534;
            box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075), 0 0 6px #ce8483
        }

        .remove-po {
            border: none;
            display: block;
            width: 100%;
            background-color: #843534;
            color: whitesmoke;
        }

        .close-po {
            border: none;
            display: block;
            width: 100%;
            background-color: #6cc788;
            color: whitesmoke;
        }

        .error + .select2-container .select2-selection--single {
            border: 1px solid red;
        }
        .select2-container--default .select2-selection--multiple {
            border-color: rgba(120, 130, 140, 0.2) !important;
        }

    </style>
@endpush

@section('content')
    <div class="padding">


        <div class="box" >
            <div class="box-header text-center">
                <h2 style="font-weight: 400; ">Export LC Entry</h2>
            </div>

            <div class="box-body">

                @include('commercial::partials.flash')

                @include('commercial::export-lc.partials.form-main')

                @if(isset($contract)  && $contract)
                    @include('commercial::export-lc.partials.details')
                @endif

            </div>
        </div>
    </div>
@endsection

@push('script-head')
    <script>
        $(document).ready(function () {

            const $selectedStyle = $('#selectedStyle');
            const $selectedPurchaseOrder = $('#selectedPurchaseOrder');
            const $detailsSelect = $('#detail-select')
            const $detailFormView = $('#detail-form-view');
            const btb_limit_percent = $('#btb_limit_percent')
            const $body = $('body');

            const FETCH_DETAILS_LIST = 'FETCH_DETAILS_LIST';

            $body.on('change', '#beneficiary_id', async function (e) {
                const factoryId = e.target.value;
                const value = 'btb_limit_percent';
                const res = await axios.get(`/commercial/fetch-btb-percent-value?value=${value}&factory=${factoryId}`);
                if (res.data === "") {
                    btb_limit_percent.val(0)
                } else {
                    btb_limit_percent.val(res.data.value)
                }
            })

            const fetchDetails = async () => {
                const contractId = $("[name='contract_id']").val();
                const res = await axios.get(`/commercial/export-lc-details/${contractId}`)
                $('#detail-list').html(res.data);
            }

            fetchDetails();


            $selectedStyle.select2({
                ajax: {
                    url: params => '/orders-for-sales-contract/' + $('#buyer').val(),
                    data: params => ({
                        search: params.term
                    }),
                    processResults: (data, params) => {
                        let results;
                        return {
                            results: data,
                            pagination: {
                                more: false
                            }
                        }
                    },
                    delay: 250
                }
            })

            $selectedPurchaseOrder.select2({
                ajax: {
                    url: params => '/purchase-orders-for-sales-contract/' + $selectedStyle.val(),
                    data: params => ({
                        search: params.term
                    }),
                    processResults: (data, params) => {
                        let results;
                        return {
                            results: data,
                            pagination: {
                                more: false
                            }
                        }
                    },
                    delay: 250
                }
            })

            $body.on('change', '#selectedStyle, #selectedPurchaseOrder', async function (e) {
                $detailsSelect.html(null)

                const TARGET_NAME = e.target.name;
                const ID = e.target.value;

                try {
                    let res;
                    if (TARGET_NAME === 'selectedStyle') {
                        res = await axios.get(`/export-lc-details/style/${ID}`);
                    } else if (TARGET_NAME === 'selectedPurchaseOrder') {
                        res = await axios.get(`/export-lc-details/po/${ID}`);
                    }
                    $('#detail-select').html(res.data)
                } catch (e) {
                    console.log(e);
                }
            })

            $body.on('click', '.close-po', async function () {
                const po_ids = [];

                $("[name='po_id[]']:checked").each(function () {
                    po_ids.push($(this).val());
                });

                if (po_ids.length) {
                    const export_lc_id = $("[name='export_lc_id']").val();
                    const res = await axios.get(`/get-export-detail-form-create?ids=${JSON.stringify(po_ids)}&export_lc_id=${export_lc_id}`)
                    $('#detail-form-view').html(res.data);
                    $('#detail-select').html(null);
                }
            });


            const toggleSubmitButtonDisabled = (selector) => $(selector)
                .prop('disabled', function (i, v) {
                    return !!!v;
                });

            $body.on('submit', '#detail-form', function (e) { // detail-form
                e.preventDefault();
                const form = $(this);
                const url = form.attr('action');
                const method = form.attr('method');

                $('.text-danger').html('');
                $('#loader').show();
                $.ajax({
                    type: method,
                    url: url,
                    data: form.serialize()
                }).done(function (response) {
                    $('#loader').hide();
                    if (response.status === 'success') {
                        toastr.success('Successfully Stored!')
                        window.scrollTo(0, 0);

                        toggleSubmitButtonDisabled('#detail-save');
                        $('#detail-form-view').html(null);
                        fetchDetails();
                    }

                    if (response.status === 'danger') {
                        toastr.error('Something Went Wrong!')
                    }

                }).fail(function (response) {
                    toggleSubmitButtonDisabled();
                    $('#loader').hide();
                    $.each(response.responseJSON.errors, function (errorIndex, errorValue) {
                        let errorDomElement, error_index, errorMessage;
                        errorDomElement = '' + errorIndex;
                        errorDomIndexArray = errorDomElement.split(".");
                        errorDomElement = '.' + errorDomIndexArray[0];
                        console.log(errorDomElement);
                        error_index = errorDomIndexArray[1];
                        errorMessage = errorValue[0];
                        if (errorDomIndexArray.length === 1) {
                            $(`${errorDomElement}_error`).html(errorMessage);
                        } else {
                            $("#detail-body tr:eq(" + error_index + ")")
                                .find(`${errorDomElement}_error`).html(errorMessage);
                        }
                    });
                }).always(function (message, xhr) {
                    // toggleSubmitButtonDisabled();
                });
            });

            $body.on('change, input', '.detail-tr', function () {
                console.log('okay');
                const $tr = $(this);
                const poValue = $tr.find("[name='po_value[]']").val() || 0;
                const attachQty = $tr.find("[name='attach_qty[]']").val() || 0;
                const rate = $tr.find("[name='rate[]']").val() || 0;


                $tr.find("[name='attach_value[]']").val(Number(attachQty * rate).toFixed(2));
            });
            $body.on('click', '.delete-detail', async function () {
                const id = $(this).data('id');

                if (confirm('Are you sure')) {
                    const res = await axios.delete(`/commercial/export-lc-details/${id}`)
                    if (res?.status === 200) {
                        toastr.success('Successfully Deleted!')
                        $('#detail-form-view').html(null);
                        await fetchDetails();
                        // window.scrollTo('#detail-list');
                    }
                }
            });

            $body.on('click', '.edit-detail', async function () {
                const poId = $(this).data('po-id');

                $detailFormView.html(null);

                if (poId) {
                    const res = await axios.get(`/get-export-detail-form-create?ids=${JSON.stringify([poId])}`)
                    $detailFormView.html(res.data);
                    // $('#detail-select').remove();
                }
            });

            // $('#form').parsley();

            $body.on('change', '#buying_agent_id', async function (e) {
                const buyer_id = e.target.value;
                const res = await axios.get(`/commercial/primary-master-contract/agent-primary-contracts/${buyer_id}`);
                $('#primary_contract_id').html(res.data);
                $('#primary_contract_value').val('');
            });

            $body.on('change', '#primary_contract_id', async function (e) {
                const primary_contract = e.target.value;
                if(primary_contract){
                    const res2 = await axios.get(`/commercial/primary-master-contract/primary-contract-value/${primary_contract}`);
                    if(res2.data){
                        $('#primary_contract_value').val(res2.data.contract_value);
                    }else{
                        $('#primary_contract_value').val('');
                    }
                }else{
                    $('#primary_contract_value').val('');
                }
            });


        })
    </script>
@endpush
