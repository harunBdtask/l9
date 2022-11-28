@extends('finishingdroplets::layout')
@section('styles')
    <style type="text/css">
        .number-right {
            width: 85%;
        }

        tbody > tr > td {
            width: 7px !important;
        }

        @media screen and (-webkit-min-device-pixel-ratio: 0) {
            input[type=date].form-control form-control-sm {
                height: 33px !important;
            }
        }

        .processes {
            margin-top: 0px;
            margin-bottom: 0px;
        }

        .form-control form-control-sm {
            line-height: 1;
            min-height: 1rem !important;
        }

        .select2-container .select2-selection--single {
            height: 33px;
            padding-top: 2px !important;
        }
    </style>
@endsection
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header text-center">
                        <h3>Add Iron, Poly & Packing</h3>
                        <div class="box-tool">
                            <a href="/iron-poly-packings" class="btn btn-sm btn-danger m-b">Back</a>
                        </div>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body color-finishing">
                        <div class="box-body">
                            <span class="js-response-message text-center"></span>
                            {!! Form::open(['url' => 'poly-iron-packings', 'method' => 'POST', 'id' => 'finishingEntryForm']) !!}
                            <div class="form-group">
                                <div class="row m-b">
                                    <div class="col-sm-2">
                                        <label>Production Date<dfn class="text-warning">*</dfn></label>
                                        {!! Form::date('production_date', date('Y-m-d'), ['class' => 'form-control form-control-sm']) !!}
                                        @if($errors->has('production_date'))
                                            <span class="text-danger">{{ $errors->first('production_date') }}</span>
                                        @endif
                                    </div>
                                    <div class="col-sm-2">
                                      <label>Finishing Floor<dfn class="text-warning">*</dfn></label>
                                      {!! Form::select('finishing_floor_id', [], old('finishing_floor_id') ?? null, ['class' => 'select-finishing-floor-poly form-control form-control-sm','placeholder' => 'Finishing Floor', 'required']) !!}
                                      @if($errors->has('finishing_floor_id'))
                                          <span class="text-danger">{{ $errors->first('finishing_floor_id') }}</span>
                                      @endif
                                    </div>
                                    <div class="col-sm-2">
                                        <label>Buyer<dfn class="text-warning">*</dfn></label>
                                        {!! Form::select('buyer_id', [], old('buyer_id') ?? null, ['class' => 'select-buyer-poly form-control form-control-sm', 'placeholder' => 'Select a buyer']) !!}
                                        @if($errors->has('buyer_id'))
                                            <span class="text-danger">{{ $errors->first('buyer_id') }}</span>
                                        @endif
                                    </div>
                                    <div class="col-sm-2">
                                        <label>Style/Order<dfn class="text-warning">*</dfn></label>
                                        {!! Form::select('order_id', [], old('order_id') ?? null, ['class' => 'select-booking-poly form-control form-control-sm','placeholder' => 'Select a Booking']) !!}
                                        @if($errors->has('order_id'))
                                            <span class="text-danger">{{ $errors->first('order_id') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="table-responsive">
                                  <table class="reportTable">
                                    <thead class="text-center">
                                    <tr>
                                        <th>PO</th>
                                        <th>Color</th>
                                        <th>Order<br/>Qty</th>
                                        <th>Sewing<br/>Qty</th>
                                        <th>Prev.<br/>Iron Qty</th>
                                        <th>Prev.<br/>Poly Qty</th>
                                        <th>Prev.<br/>Packing Qty</th>
                                        <th>
                                          <div style="width: 80px;">Iron Qty</div>
                                        </th>
                                        <th>
                                          <div style="width: 50px">Iron Rej. Qty</div>
                                        </th>
                                        <th>
                                          <div style="width: 80px;">Poly Qty</div>
                                        </th>
                                        <th>
                                          <div style="width: 50px">Poly Rej. Qty</div>
                                        </th>
                                        <th>
                                          <div style="width: 50px">Packing Qty</div>
                                        </th>
                                        <th>
                                          <div style="width: 50px">Packing Rej. Qty</div>
                                        </th>
                                        <th width="7%">Reason</th>
                                        <th width="6%">Remarks</th>
                                    </tr>
                                    </thead>
                                    <tbody class="poly-status-update-form">
                                    </tbody>
                                    <span class="loader"></span>
                                </table>
                                </div>
                                
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('protracker/custom.js') }}"></script>
    <script>
        $(function () {
            const buyerSelectDom = $('[name="buyer_id"]');
            const orderSelectDom = $('[name="order_id"]');
            const finishingFloorSelectDom = $('[name="finishing_floor_id"]');
            const polyStatusUpdateDom = $('.poly-status-update-form');
            const loaderDom = $('.loader');

            buyerSelectDom.select2({
                ajax: {
                    url: '/utility/get-buyers-for-select2-search',
                    data: function (params) {
                        return {
                            search: params.term,
                        }
                    },
                    processResults: function (data, params) {
                        return {
                            results: data.results,
                            pagination: {
                                more: false
                            }
                        }
                    },
                    cache: true,
                    delay: 250
                },
                placeholder: 'Select Buyer',
                allowClear: true
            });

            orderSelectDom.select2({
                ajax: {
                    url: function (params) {
                        return `/utility/get-styles-for-select2-search`
                    },
                    data: function (params) {
                        const buyerId = buyerSelectDom.val();
                        return {
                            search: params.term,
                            buyer_id: buyerId,
                        }
                    },
                    processResults: function (data, params) {
                        return {
                            results: data.results,
                            pagination: {
                                more: false
                            }
                        }
                    },
                    cache: true,
                    delay: 250
                },
                placeholder: 'Select Style',
                allowClear: true
            });

            finishingFloorSelectDom.select2({
                ajax: {
                    url: function (params) {
                        return `/fetch-finishing-floors`
                    },
                    data: function (params) {
                        return {
                            search: params.term,
                        }
                    },
                    processResults: function (data, params) {
                        return {
                            results: data,
                            pagination: {
                                more: false
                            }
                        }
                    },
                    cache: true,
                    delay: 250
                },
                placeholder: 'Finishing Floor',
                allowClear: true
            });

            $(document).on('change', '[name="buyer_id"]', function (e) {
                let orderId = orderSelectDom.val();
                if (orderId) {
                    orderSelectDom.val('').change();
                }
                polyStatusUpdateDom.empty();
            })

            $(document).on('change', '[name="order_id"]', function (e) {
                let orderId = orderSelectDom.val();
                polyStatusUpdateDom.empty();
                getFinishingData(orderId);
            })

            function getFinishingData(orderId) {
                $.ajax({
                    type: 'GET',
                    url: '/get-orders-for-iron-poly-packings/' + orderId,
                    beforeSend() {
                        loaderDom.html(loader);
                    },
                    success(response) {
                        var result;
                        if (Object.keys(response).length > 0) {
                            $.each(response, function (index, purchase_order) {
                                result += '<tr>' + '<td rowspan="' + purchase_order.rowspan + '">' + purchase_order.po_no + '</td></tr>';
                                $.each(purchase_order.color_wise_info, function (index1, color_info) {
                                    result += '<tr style="height: 40px">' +
                                        '<td>' + color_info.color_name +
                                        '<input type="hidden" name="purchase_order_id[]" value="' + purchase_order.purchase_order_id + '">' +
                                        '<input type="hidden" name="color_id[]" value="' + color_info.color_id + '"></td>' +
                                        '<td>' + color_info.color_order_qty + '</td>' +
                                        '<td>' + color_info.total_sewing_output + '</td>' +
                                        '<td>' + color_info.total_iron + '</td>' +
                                        '<td>' + color_info.total_poly + '</td>' +
                                        '<td>' + color_info.total_packing + '</td>' +
                                        '<td><input type="text" class="form-control form-control-sm text-right" name="iron_qty[]"></td>' +
                                        '<td><input type="text" class="form-control form-control-sm text-right" name="iron_rejection_qty[]"></td>' +
                                        '<td><input type="text" class="form-control form-control-sm text-right" name="poly_qty[]"></td>' +
                                        '<td><input type="text" class="form-control form-control-sm text-right" name="poly_rejection_qty[]"></td>' +
                                        '<td><input type="text" class="form-control form-control-sm text-right" name="packing_qty[]"></td>' +
                                        '<td><input type="text" class="form-control form-control-sm text-right" name="packing_rejection_qty[]"></td>' +
                                        '<td><textarea class="form-control form-control-sm" name="reason[]" rows="1" maxlength="191"></textarea></td>' +
                                        '<td><textarea class="form-control form-control-sm" name="remarks[]" rows="1"></textarea></td>' +
                                        '</tr>';
                                });
                            });
                            result += '<tr style="height: 50px">' +
                                '<td colspan="15" class="text-center">' +
                                '<button type="button" class="btn btn-sm btn-success finishing-update-btn">Submit</button>' +
                                '</td>' +
                                '</tr>';
                        } else {
                            result += '<tr style="height: 50px"><td colspan="14" class="text-danger">Not found</td></tr>';
                        }
                        polyStatusUpdateDom.html(result);
                    }, complete() {
                        loaderDom.empty();
                    }
                });
            }

            $(document).on('click', '.finishing-update-btn', function () {
                if (!$('input[type=date]').val()) {
                    $('.js-response-message')
                        .html(getMessage('Please select production date', 'danger'))
                        .fadeIn()
                        .delay(2000)
                        .fadeOut(2000);

                    return false;
                }


                let current = $(this);
                current.attr("disabled", true);
                let finishingInputData = $('#finishingEntryForm').serialize();
                let orderId = orderSelectDom.val();
                if (orderId) {

                    $.ajax({
                        type: 'POST',
                        url: '/iron-poly-packings',
                        data: finishingInputData,
                        beforeSend() {
                            loaderDom.html(loader);
                        },
                        success(response) {
                            if (response.status == 200) {
                                $('.js-response-message')
                                    .html(getMessage(response.message, 'success'))
                                    .fadeIn()
                                    .delay(2000)
                                    .fadeOut(2000);
                                getFinishingData(orderId);
                            } else if (response.status == 403) {
                                $('.js-response-message')
                                    .html(getMessage(response.message, 'danger'))
                                    .fadeIn()
                                    .delay(2000)
                                    .fadeOut(2000);
                            } else {
                                $('.js-response-message')
                                    .html(getMessage(response.message, 'success'))
                                    .fadeIn()
                                    .delay(2000)
                                    .fadeOut(2000);
                            }
                            current.attr("disabled", false);
                        }, error: function (error) {
                            current.attr("disabled", false);
                            $('.js-response-message')
                                .html(getMessage(response.message, 'danger'))
                                .fadeIn()
                                .delay(2000)
                                .fadeOut(2000);
                        },
                        complete() {
                            loaderDom.empty();
                        }
                    });
                    window.scrollTo(0, 0);
                }
            });
        })
    </script>
@endsection
