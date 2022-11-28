@php
    $tableHeadColorClass = 'tableHeadColor';
    if (isset($type) || request()->has('type') || request()->route('type')) {
    $tableHeadColorClass = '';
    }
@endphp
@extends('cuttingdroplets::layout')
@section('title', 'Daily Basis Cutting Report')
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header text-center">
                        <h2>Daily Basis Cutting Report
                            <span class="pull-right">
                                <a href="#" class="print-btn">
                                    <i style="color: #DC0A0B" class="fa fa-file-pdf-o"></i>
                                </a>
                            </span>
                        </h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body">
                        <form>
                            <div class="form-group">
                                <div class="row m-b">
                                    <div class="col-sm-2">
                                        <label>Buyer</label>
                                        {!! Form::select('buyer_id', [], request('buyer_id'), ['class' => 'order-color-size-wise-buyer form-control
                                        form-control-sm']) !!}
                                        <span>{{ $errors->first('buyer_id') }}</span>
                                    </div>
                                    <div class="col-sm-2">
                                        <label>Order/Style</label>
                                        {!! Form::select('order_id', [], request('order_id'), ['class' => 'order-color-size-wise-booking form-control
                                        form-control-sm']) !!}
                                        <span>{{ $errors->first('order_id') }}</span>
                                    </div>
                                    <div class="col-sm-2">
                                        <label>PO No</label>
                                        {!! Form::select('po_id', [], request('po_id'), ['class' => 'order-color-size-wise-po form-control
                                        form-control-sm']) !!}
                                        <span>{{ $errors->first('po_id') }}</span>
                                    </div>
                                    <div class="col-sm-2">
                                        <label>Color</label>
                                        {!! Form::select('color_id', [], request('color_id'), ['class' => 'order-color-size-wise-color form-control
                                        form-control-sm']) !!}
                                    </div>
                                    <div class="col-sm-2">
                                        <label>&nbsp;</label>
                                        <button class="btn btn-sm btn-block btn-info">
                                            <i class="fa fa-search"></i>&nbsp;Search
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div id="parentTableFixed" class="table-responsive">
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
            const garmentItemSelectDom = $('[name="item_id"]');
            const poSelectDom = $('[name="po_id"]');
            const colorSelectDom = $('[name="color_id"]');
            const orderWiseReportHead = $('.order-wise-input-report-head');
            const poWiseReportHead = $('.po-wise-input-report-head');
            const colorWiseReportHead = $('.color-wise-input-report-head');
            const orderColorSizeWiseReportDom = $('#parentTableFixed');
            const domLoader = $('.loader');
            let orders;
            let items;

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
                        orders = data;
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

            poSelectDom.select2({
                ajax: {
                    url: '/utility/get-pos-for-select2-search',
                    data: function (params) {
                        const orderId = orderSelectDom.val();
                        const itemId = garmentItemSelectDom.val();
                        return {
                            order_id: orderId,
                            item_id: itemId,
                            search: params.term
                        }
                    },
                    processResults: function (data, params) {
                        let resData = [];
                        data.results.map(data => {
                            resData.push(data);
                        });
                        return {
                            results: resData,
                            pagination: {
                                more: false
                            }
                        }
                    },
                    cache: true,
                    delay: 250
                },
                placeholder: 'Select PO',
                allowClear: true
            });

            colorSelectDom.select2({
                ajax: {
                    url: '/utility/get-colors-for-po-select2-search',
                    data: function (params) {
                        const orderId = orderSelectDom.val();
                        const itemId = garmentItemSelectDom.val();
                        const purchaseOrderId = poSelectDom.val();
                        return {
                            order_id: orderId,
                            garments_item_id: itemId,
                            purchase_order_id: purchaseOrderId,
                            search: params.term
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
                placeholder: 'Select Color',
                allowClear: true
            });

            $(document).on('change', '[name="buyer_id"]', function (e) {
                let orderId = orderSelectDom.val();
                let poId = poSelectDom.val();
                let colorId = colorSelectDom.val();
                if (orderId) {
                    orderSelectDom.val('').change();
                }
                if (poId) {
                    poSelectDom.val('').change();
                }
                if (colorId) {
                    colorSelectDom.val('').change();
                }
                orderColorSizeWiseReportDom.empty();
            });

            function generateDailyBasisReport() {
                let buyer_id = buyerSelectDom.val();
                let order_id = poSelectDom.val();
                let poId = poSelectDom.val();
                let colorId = colorSelectDom.val();

                if (buyer_id && order_id && poId) {
                    poWiseReportHead.hide();
                    colorWiseReportHead.hide();
                    orderWiseReportHead.show();
                    domLoader.html(loader);
                    $.ajax({
                        type: 'POST',
                        url: '/daily-basis-cutting-report',
                        data: {
                            po_id: poId,
                            color_id: colorId
                        },
                        success: function (response) {
                            domLoader.empty();
                            orderColorSizeWiseReportDom.empty().append(response)
                        }
                    });
                }
            }

            $(document).on('change', '[name="order_id"]', function (e) {
                let orderId = $(this).val();
                let poId = poSelectDom.val();
                let colorId = colorSelectDom.val();
                let itemId = garmentItemSelectDom.val();

                if (itemId) {
                    garmentItemSelectDom.val('').change();
                }
                if (poId) {
                    poSelectDom.val('').change();
                }
                if (colorId) {
                    colorSelectDom.val('').change();
                }
                orderColorSizeWiseReportDom.empty();
            });

            $('form').submit(function(e){
                e.preventDefault();
                generateDailyBasisReport();
            });

            $(document).on('click', '.print-btn', function () {
                let purchase_order_id = poSelectDom.val() ?? '';
                let color_id = colorSelectDom.val() ?? '';
                if (purchase_order_id) {
                    window.open(`/daily-basis-cutting-report/pdf?po_id=${purchase_order_id}&color_id=${color_id}`, '_blank')
                    return true;
                } else {
                    alert('Please view report first');
                    return false;
                }
            });
        });
    </script>
@endsection
