@extends('commercial::layout')
@section('title','Sales Contract Amendment Entry')

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
        }

        li.parsley-required {
            color: red;
            list-style: none;
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

        .select2-container {
            width: 100% !important;
        }

    </style>
@endpush

@section('content')
    <div class="padding">


        <div class="box" >
            <div class="box-header text-center" >
                <h2 style="font-weight: 400; ">Sales Contract Amendment</h2>
            </div>

            <div class="box-body">

                @include('commercial::partials.flash')
                @include('commercial::sales-contract-amendment.partials.form')
            </div>
        </div>
    </div>
@endsection

@push('script-head')
    <script>
        $(document).ready(function () {

            const $selectedStyle = $('#selectedStyle');
            const $selectedPurchaseOrder = $('#selectedPurchaseOrder');
            const $detailSelect = $('#detail-select');
            const $detailFormView = $('#detail-form-view');

            const FETCH_DETAILS_LIST = 'FETCH_DETAILS_LIST';

            const fetchDetails = async () => {
                const contractId = $("[name='contract_id']").val();
                const res = await axios.get(`/commercial/sales-contracts-details/${contractId}`)
                $('#detail-list').html(res.data);
            }



            const $body = $('body');

            $selectedStyle.on('change', function () {
                // $selectedPurchaseOrder.val(null).trigger('change');
            })

            $body.on('keypress', '#sales_contract_no', async function (e) {
                if (e.keyCode === 13) {
                    const fileNo = $(this).val();
                    if (fileNo) {
                        const res = await axios.get(`/commercial/sales-contract-amendment/${fileNo}/form`)
                        $('#form-area').html(null).html(res.data);
                    }
                }
            })

            $body.on('change', '#selectedStyle, #selectedPurchaseOrder', async function (e) {

                $detailSelect.html(null)

                const TARGET_NAME = e.target.name;
                const ID = e.target.value;

                try {
                    let res;

                    if (TARGET_NAME === 'selectedStyle') {
                        res = await axios.get(`/sales-contract-details/style/${ID}`);
                    } else if (TARGET_NAME === 'selectedPurchaseOrder') {
                        res = await axios.get(`/sales-contract-details/po/${ID}`);
                    }

                    $('#detail-select').html(res.data)
                } catch (e) {
                    console.log(e);
                }
            })

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

            $body.on('change', '#selectedOrder', async function () {
                const orderId = $(this).val();

                if (orderId) {
                    const res = await axios.get(`/order-details/${orderId}/form`)
                    $('#detail-form')
                        .empty()
                        .html(res.data);
                }

            });

            $body.on('click', '.remove-po', async function () {
                const poId = $(this).data('id');
                $(this).parents('tr').remove();
            });

            const toggleSubmitButtonDisabled = (selector) => $(selector)
                .prop('disabled', function(i, v) { return !!!v; });

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

            $body.on('click', '.close-po', async function () {
                const po_ids = [];

                $("[name='po_id[]']:checked").each(function () {
                    po_ids.push($(this).val());
                });

                if (po_ids.length) {
                    const res = await axios.get(`/get-sales-detail-form-create?ids=${JSON.stringify(po_ids)}`)
                    $('#detail-form-view').html(res.data);
                    $('#detail-select').remove();
                }
            });

            $body.on('change, input', '.detail-tr', function () {
                console.log('okay');
                const $tr = $(this);
                const poValue = $tr.find("[name='po_value[]']").val() || 0;
                const attachQty = $tr.find("[name='attach_qty[]']").val() || 0;
                const rate = $tr.find("[name='rate[]']").val() || 0;


                $tr.find("[name='attach_value[]']").val(attachQty * rate);
            });

            $body.on('click', '.delete-detail', async function () {
                const id = $(this).data('id');

                if (confirm('Are you sure')) {
                    const res = await axios.delete(`/commercial/sales-contract-details/${id}`)
                    if (res?.status === 200) {
                        toastr.success('Successfully Deleted!')
                        await fetchDetails();
                        // window.scrollTo('#detail-list');
                    }
                }
            });

            $body.on('click', '.edit-detail', async function () {
                const poId = $(this).data('po-id');

                $detailFormView.html(null);

                if (poId) {
                    const res = await axios.get(`/get-sales-detail-form-create?ids=${JSON.stringify([poId])}`)
                    $detailFormView.html(res.data);
                    // $('#detail-select').remove();
                }
            });


            $('#form').parsley();
        })
    </script>
@endpush
