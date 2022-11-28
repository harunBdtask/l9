@extends('finishingdroplets::layout')
@section('title', 'Packing List Generate')
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header text-center">
                        <h2>Packing List Generate Table</h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body">
                        @include('partials.response-message')

                        <form action="{{url('packing-list-generate-action')}}" method="post">
                            @csrf
                            <input type="hidden" name="challan_no" value="{{ $challan_no ?? 1 }}">

                            <div class="form-group">
                                <div class="row m-b">
                                    <div class="col-sm-2">
                                        <label>Buyer</label>

                                        {!! Form::select('buyer_id', $buyers, null, ['class' => 'packing-list-buyer-select form-control select2-input form-control-sm', 'placeholder' => 'Select a Buyer']) !!}
                                    </div>
                                    <div class="col-sm-2">
                                        <label>Style/Order</label>
                                        {!! Form::select('order_id', [], null, ['class' => 'packing-list-order-select form-control select2-input form-control-sm', 'placeholder' => 'Select a Style/Order']) !!}
                                    </div>
                                    <div class="col-sm-2">
                                        <label>PO</label>
                                        {!! Form::select('purchase_order_id', [], null, ['class' => ' form-control select2-input form-control-sm', 'placeholder' => 'Select a PO']) !!}
                                    </div>
                                    <div class="col-sm-2">
                                        <label>Color</label>
                                        {!! Form::select('color_id', [], null, ['class' => 'packing-list-color-select select2-input form-control form-control-sm']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="box-body table-responsive">
                                <table class="reportTable">
                                    <thead>
                                    <tr>
                                        <th colspan="4"><b>Packing Challan No: {{ $challan_no ?? '' }}</b></th>
                                    </tr>
                                    <tr class="text-center">
                                        <th>Size Name</th>
                                        <th>Order Qty</th>
                                        <th>Previous Qty</th>
                                        <th>Add Pack Qty</th>
                                    </tr>
                                    </thead>
                                    <tbody class="packing-list-generate-form">
                                    </tbody>
                                </table>

                                <div class="sutton-area text-center">
                                    <button type="submit" style="display: none;"
                                            class="btn white sewing-target-btn">
                                        Submit
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('protracker/custom.js') }}"></script>
    <script>
        const buyerSelectDom = $('[name="buyer_id"]');
        const orderSelectDom = $('[name="order_id"]');
        const poSelectDom = $('[name="purchase_order_id"]');
        const colorSelectDom = $('[name="color_id"]');
        const packingListGenerateDom = $('.packing-list-generate-form');
        const submitBtn = $('.sewing-target-btn');

        buyerSelectDom.change(() => {
            orderSelectDom.empty().val('').select2();
            poSelectDom.empty().val('').select2();
            colorSelectDom.empty().val('').select2();
            packingListGenerateDom.empty();
            submitBtn.hide();
            $.ajax({
                url: "/utility/get-styles-for-select2-search",
                type: "get",
                data: {'buyer_id': buyerSelectDom.val()},
                success({results}) {
                    orderSelectDom.empty();
                    orderSelectDom.html(`<option selected>SELECT</option>`);
                    results.forEach(el => {
                        let html = `<option value="${el.id}">${el.text}</option>`;
                        orderSelectDom.append(html);
                    });
                }
            })
        });
        orderSelectDom.change(() => {
            poSelectDom.empty().val('').select2();
            colorSelectDom.empty().val('').select2();
            packingListGenerateDom.empty();
            submitBtn.hide();

            $.ajax({
                url: "/utility/get-pos-for-select2-search",
                type: "get",
                data: {'order_id': orderSelectDom.val()},
                success({results}) {
                    poSelectDom.empty();
                    poSelectDom.html(`<option selected>SELECT</option>`);
                    results.forEach(el => {
                        let html = `<option value="${el.id}">${el.text}</option>`;
                        poSelectDom.append(html);
                    });
                }
            })
        });
        poSelectDom.change(() => {
            colorSelectDom.empty().val('').select2();
            packingListGenerateDom.empty();
            submitBtn.hide();

            $.ajax({
                url: "/utility/get-colors-for-po-select2-search",
                type: "get",
                data: {'purchase_order_id': poSelectDom.val()},
                success({results}) {
                    colorSelectDom.empty();
                    colorSelectDom.html(`<option selected >SELECT</option>`);
                    results.forEach(el => {
                        let html = `<option value="${el.id}">${el.text}</option>`;
                        colorSelectDom.append(html);
                    });
                }
            })
        });

        $(document).on('change', '.packing-list-color-select', function () {
            packingListGenerateDom.empty();
            let po_id = poSelectDom.val();
            let color_id = $(this).val();
            if (po_id && color_id) {
                $.ajax({
                    type: 'GET',
                    url: '/packing-list-generate-view/' + po_id + '/' + color_id,
                    success: function (response) {
                        if (response) {
                            packingListGenerateDom.html(response.view);
                            $('.sewing-target-btn').show();
                        }
                    }
                });
            }
        });
    </script>
@endsection
