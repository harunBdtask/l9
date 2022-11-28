@extends('general-store::layout')
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h2> Stock Out ({{get_store_name($store)}})</h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body">
                        <div class="flash-message">
                            @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                                @if(Session::has('alert-' . $msg))
                                    <div class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</div>
                                @endif
                            @endforeach
                        </div>
                        {!! Form::open([
                           'route' => 'general-store.voucher_stock_out',
                           'method' => isset($voucher) ? 'PUT' : 'POST',
                           'autocomplete' => 'off',
                           "id" => 'voucher-form'
                           ])
                        !!}

                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="barcode">Barcode <dfn>*</dfn></label>
                                    <input type="text" name="barcode" id="barcode" class="form-control">
                                </div>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="trn_with">Consumer</label>
                                    {!! Form::select('trn_with', $consumers, isset($voucher) ? $voucher->trn_with : null, ['class'=>'form-control select-option ', 'id' => 'trn_with', 'placeholder' => 'Search & Select', 'required']) !!}
                                    {!! Form::hidden('store', $store) !!}
                                    {!! Form::hidden('type', $type) !!}
                                    @isset($voucher)
                                        {{ Form::hidden('id', $voucher->id) }}
                                    @endisset
                                    @component('general-store::alert', ['name' => 'trn_with']) @endcomponent
                                </div>
                            </div>


                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="trn_with">Department</label>
                                    {!! Form::select('trn_customer', $customer, isset($voucher) ? $voucher->trn_customer : null, ['class'=>'form-control select-option ', 'id' => 'trn_customer', 'placeholder' => 'Search & Select', 'required']) !!}
                                    @component('general-store::alert', ['name' => 'trn_customer']) @endcomponent
                                </div>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-group">
                                    @php
                                        $trnDate = isset($voucher) ? \Carbon\Carbon::parse($voucher->trn_date)->format('Y-m-d') : \Carbon\Carbon::now()->format('Y-m-d');
                                    @endphp
                                    <label for="trn_date">Delivery Date <dfn>*</dfn></label>
                                    {!! Form::text('trn_date', $trnDate, ['class'=>'form-control select-option ', 'id' => isset($voucher) ? '' : 'trn_date', 'placeholder' => 'Delivery Date', isset($voucher) ? 'readonly' : null]) !!}
                                    @component('general-store::alert', ['name' => 'trn_date']) @endcomponent
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="requisition_s_code">Requisition</label>
                                    {!! Form::text('requisition_s_code', $voucher->requisition_s_code ?? null, ['class'=>'form-control select-option ', 'id' => 'requisition_s_code', 'placeholder' => 'Requisition']) !!}
                                    @component('general-store::alert', ['name' => 'requisition_s_code']) @endcomponent
                                </div>
                            </div>

                        </div>

                        <div class="row" id="add_item">

                            <div class="col-md-3">
                                {{--                  <label for="item">Item*</label>--}}
                                {{--                  {!! Form::select('item_id[]', $items, null, ['class'=>'form-control select-option', 'id' => 'item', 'placeholder' => 'Search & Select']) !!}--}}

                                <label for="item">Item*</label>

                                <select name="item_id"
                                        value=""
                                        class="form-control select-option"
                                        id="item">

                                    <option value="">Search & Select</option>
                                    @foreach($items as $item)
                                        <option value="{{ $item->id }}"
                                                data-uom="{{ $item->uom ?? ''}}"
                                                data-uom_value="{{$item->uomDetails->name ?? ''}}"
                                                data-brand_value="{{$item->brand->name ?? ''}}"
                                                data-brand_id="{{$item->brand->id ?? ''}}"
                                                data-category_id="{{ $item->category->id ?? '' }}"
                                                data-category_name="{{ $item->category->name ?? '' }}"
                                        >{{ $item->name }}</option>
                                    @endforeach

                                </select>
                            </div>

                            <div class="col-md-2">
                                <label for="category">Category</label>
                                {!! Form::text("category", null, ['class'=>'form-control', 'readonly'=>true,'id' => 'category']) !!}

                            </div>

                            <div class="col-md-1">
                                <label for="brand">Brand</label>
                                {!! Form::text("brand", null, ['class'=>'form-control', 'readonly'=>true,'id' => 'brand']) !!}
                                {!! Form::hidden("brand_id", null, ['class'=>'form-control', 'readonly'=>true,'id' => 'brand_id']) !!}
                            </div>

                            <div class="col-md-2 form-group">
                                <label for="qty">Dlv. Qty*</label>
                                {!! Form::text('qty', null, ['class'=>'form-control', 'id' => 'delivery_qty']) !!}
                                {!! Form::hidden('qty', null, ['class'=>'form-control', 'id' => 'qty']) !!}
                            </div>

                            <div class="col-md-1">
                                <label for="rate">Rate*</label>
                                {!! Form::text('rate', null, ['class'=>'form-control', 'id' => 'rate', 'readonly']) !!}
                            </div>

                            <div class="col-md-1">
                                <label for="">UoM</label>
                                {!! Form::text('uom[]', null, [ 'class'=>'form-control', 'id' => 'uom', 'readonly' ]) !!}
                            </div>

                            <div class="col-md-1">
                                <label for="remarks">Remarks</label>
                                {!! Form::text('remarks', null, ['class'=>'form-control', 'id' => 'remarks']) !!}
                            </div>

                            <div class="col-md-1">
                                <label>&nbsp;</label>
                                <div>
                                    <a class="btn btn-primary btn-icon add-to-cart" style="width: 100%"><i
                                            class="fa fa-plus"></i></a>
                                </div>
                            </div>


                        </div>


                        <div class="row m-t-2">
                            <div class="col-sm-12 table-responsive">
                                <table class="reportTable" id="voucher-table">
                                    <thead>
                                    <tr>
                                        <th>Barcode</th>
                                        <th>Item</th>
                                        <th>Category</th>
                                        <th>Brand</th>
                                        <th width="10%">Available Qty.</th>
                                        <th width="20%">Delivery Qty. <dfn>*</dfn></th>
                                        <th>Rate</th>
                                        <th>Remarks</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>

                            </div>
                        </div>

                        <div class="row m-t-2">
                            <div class="form-group text-center">

                                <button type="button" class="{{ isset($voucher) ? 'btn btn-sm btn-primary':'btn btn-sm btn-success'}}" id="submit-button">
                                    {{ isset($voucher) ? 'Update' : 'Save' }}
                                </button>

                                <a href="{{ URL::previous() }}" class="btn btn-sm btn-danger">
                                    Cancel
                                </a>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('script-head')

    <script>
        const body = $('body');

        let details = [];
        let edit = false;
        @php
            if (isset($voucher)) {
                echo 'edit ='.true.';';
               echo 'details = ' . json_encode($voucher->details) . ';' ;
            }
        @endphp

        const codes = [];

        const store = $('input[name=store]').val();
        const type = $('input[name=type]').val();
        const id = $("input[name=id]").val() || null;
        let trn_with = null;
        let trn_customer = null;
        let trn_date = null;


        $(document).ready(function () {

            init();

            // register events
            body.on('keyup', '#barcode', barcodeHandler);

            body.on('change', '#item', itemChangeHandler);

            body.on('click', '.add-to-cart', addToCartHandler);

            body.on('click', '.remove-button', removeFromCartHandler);

            body.on('click', '#submit-button', submitHandler);
            body.on("keyup", ".delivery_qty", deliveryQty);
        });

        function deliveryQty() {
            const item_id = $(this).data("item_id");
            const delivery_qty = parseInt($(this).val()) || 1;
            let findItemIndex = details.findIndex(detail => parseInt(detail.item_id) === parseInt(item_id));
            if (findItemIndex !== -1) {
                if (details[findItemIndex].qty < delivery_qty) {
                    toastr.warning("Delivery Quantity Can't Greater than Available Quantity");
                    $(this).val(null);
                    return {};
                } else {
                    details[findItemIndex].delivery_qty = delivery_qty
                }
            }
        }

        function getItemValues() {
            const item_id = $('#item').val();
            const item = $('#item').find('option:selected').text();
            const category = $('#category').val();
            const brand_id = parseInt($('#brand_id').val()) || null;
            let brand = $('#brand').val();
            const rate = parseFloat($('#rate').val()) || null;
            const qty = parseFloat($('#qty').val()) || null;
            const delivery_qty = parseFloat($('#delivery_qty').val()) || null;
            const remarks = $('#remarks').val();

            if (brand === 'Select & Search') {
                brand = '';
            }

            if (!item_id || !rate || !delivery_qty) {
                toastr.warning('Fill all the fields');
                return {};
            }

            return {
                item_id,
                brand_id,
                item,
                category,
                brand,
                rate,
                qty,
                delivery_qty,
                remarks
            }
        }

        const preparedRow = ({
                                 item_id,
                                 item,
                                 category,
                                 brand,
                                 qty,
                                 delivery_qty,
                                 rate,
                                 remarks,
                                 code,
                                 new_row = false
                             }, idx) => {
            let button = `<button type="button" class="btn btn-sm btn-default remove-button">
                        <i class="fa fa-trash text-danger"></i>
                      </button>`;
            let delivery_qty_field;
            if (edit && !new_row) {
                button = '';
                delivery_qty_field = `<input type="text" style="text-align: end;" readonly value=${delivery_qty} data-item_id=${item_id} id="delivery_qty" class="form-control delivery_qty">`;
            } else {
                delivery_qty_field = `<input type="text"  style="text-align: end;" value=${delivery_qty ? delivery_qty : qty} data-item_id=${item_id} id="delivery_qty" class="form-control delivery_qty">`;
            }
            return `<tr data-idx="${idx}">
                        <td class="text-left" style="padding: 1%">${code || 'N/A'}</td>
                        <td class="text-left" style="padding: 1%">${item}</td>
                        <td class="text-left" style="padding: 1%">${category}</td>
                        <td class="text-left" style="padding: 1%">${brand || 'N/A'}</td>
                        <td class="text-right" style="padding: 1%">${parseFloat(qty).toFixed(2)}</td>
                        <td class="text-right" style="padding: 1%">${delivery_qty_field}</td>
                        <td>${parseFloat(rate).toFixed(2)}</td>
                        <td class="text-left" style="padding: 1%">${remarks || 'N/A'}</td>
                        <td>${button}</td>
                 </tr>`;
        };

        const renderItemInTable = (item, idx) => {
            const tableRow = preparedRow(item, idx);
            $('#voucher-table tbody').prepend(tableRow);
        };

        const reRenderItemTable = () => {
            let rows = '';
            details.forEach((detail, idx) => {
                rows += preparedRow(detail, idx);
            });
            $('#voucher-table tbody').html(rows);
        };

        const emptyFields = () => {
            $('#item').select2('val', null);
            $('#brand').val(null);
            $('#rate').val(null);
            $('#qty').val(null);
            $('#delivery_qty').val(null);
            $('#remarks').val(null);
        };

        const throttle = (func, limit) => {
            let inThrottle;
            return function () {
                const args = arguments;
                const context = this;
                if (!inThrottle) {
                    func.apply(context, args);
                    inThrottle = true;
                    setTimeout(() => inThrottle = false, limit)
                }
            }
        };

        // event handlers
        function removeFromCartHandler() {
            let idx = $(this).closest('tr').attr('data-idx');
            details.splice(idx, 1);
            reRenderItemTable();
            toastr.success('Removed!');
        }

        function itemChangeHandler() {

            let itemId = $(this).val();


            $('#uom').val($(':selected', $(this)).data('uom_value'));
            $('#brand').val($(':selected', $(this)).data('brand_value'));
            $('#brand_id').val($(':selected', $(this)).data('brand_id'));
            $('#category').val($(':selected', $(this)).data('category_name'));

            // const brandDropdown = $('#brand');

            // const fetchBrandsSuccessHandler = (res) => brandDropdown.empty().html(res.data);
            //
            // if (itemId) {
            //     brandDropdown.val(null).select2();
            //     getBrandsFormItem(itemId)
            //         .then(fetchBrandsSuccessHandler)
            //         .catch(console.error)
            // }
            const rateInput = $('#rate');
            let deliveryDate = $('input[name=trn_date]').val();

            if (itemId && deliveryDate) {
                getOutRate(itemId, deliveryDate).then(res => {
                    rateInput.val(res.data.rate)
                }).catch(err => {
                    rateInput.val(null);
                    console.log(err.toString());
                });
            }
            let brandId = $("#brand_id").val();
            let item = {item_id: itemId, brand_id: brandId};
            console.log(item)
            getItemAvailableQty(item)
                .then(({data}) => {
                    $("#delivery_qty").attr("placeholder", `Rcv.${data}`)
                    $("#qty").val(data)
                })
                .catch(error => {
                    console.log(error)
                })
        }

        function barcodeHandler(event) {
            event.preventDefault();
            const barcode = event.target.value;
            const deliveryDate = $('input[name=trn_date]').val();

            if (event.keyCode !== 13 /* 'Enter' */) {
                return;
            }

            if (!barcode) {
                return;
            }

            if (!deliveryDate) {
                toastr.error('Provide Delivery Date for out rate!');
                return;
            }

            $('input[name=barcode]').val(null);

            if (codes.includes(trim(barcode))) {
                toastr.warning('You\'ve scanned this barcode already!');
                return;
            }


            getDataForBarcode(barcode, deliveryDate)
                .then(res => {
                    if (res.data.error) {
                        throw new Error(res.data.msg);
                    }
                    res.data.new_row = true;
                    console.log(res.data);
                    details.push(res.data);
                    codes.push(trim(barcode));
                    //new_row = true;
                    reRenderItemTable();
                    //new_row = false;
                    toastr.success('Successfully Scanned Barcode!')
                })
                .catch(err => {
                    toastr.error(err.toString())
                })
        }

        function trim(str) {
            return str.trim();
        }

        function addToCartHandler(event) {
            let item = getItemValues();
            if (Object.keys(item).length === 0) {
                return;
            }
            getItemAvailableQty(item)
                .then(response => {
                    item.new_row = true;
                    item.qty = response.data;
                    item.delivery_qty = item.delivery_qty || response.data;
                    details.push(item);
                    renderItemInTable(item, details.length - 1);
                    emptyFields();
                    toastr.success('Added!');
                })
                .catch(error => {
                    console.log(error)
                });


        }

        function submitHandler(event) {
            event.preventDefault();
            trn_with = $('#trn_with').val();
            trn_customer = $('#trn_customer').val();

            if (!trn_with && !trn_customer) {
                toastr.warning('Please Select a consumer or customer!');
                return;
            }

            if (details.length === 0) {
                toastr.warning('Item is not available in the cart!');
                return;
            }
            const store = {{ $store }};
            saveVoucher()
                .then(res => {
                    console.log(res.data);
                    toastr.success(res.data.message);
                    setTimeout(() => window.location = `/general-store/vouchers/${store}`, 1000);
                }).catch(err => toastr.error(err.toString()));
        }


        // validators


        // api-calls
        function getBrandsFormItem(itemId) {
            return axios.get(`/general-store/brand/${itemId}`);
        }

        function getDataForBarcode(barcode, deliveryDate) {
            return axios.get('/general-store/barcode_scan', {
                params: {
                    barcode,
                    deliveryDate
                }
            })
        }

        function getItemAvailableQty({item_id, brand_id}) {
            return axios.get("/general-store/get_item_qty", {
                params: {
                    item_id,
                    brand_id
                }
            })
        }

        function getOutRate(itemId, deliveryDate) {
            return axios.get('/general-store/get_out_rate', {
                params: {
                    itemId,
                    deliveryDate
                }
            });
        }

        function saveVoucher() {
            trn_date = $('input[name=trn_date]').val()
            let requisition_s_code = $('#requisition_s_code').val()
            details.forEach(function (v) {
                delete v.new_row
            });
            return axios.post('/general-store/voucher_stock_out', {
                id,
                store,
                type,
                details,
                trn_date,
                trn_with,
                trn_customer,
                requisition_s_code
            })
        }

        // init

        function init() {
            registerDate();
            registerSelect2();
            disableItem();
            reRenderItemTable();
        }

        function registerDate() {
            $('#trn_date').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                title: 'Delivery Date',
            }).on('hide', function (e) {
                let date = e.target.value;
                if (date) {
                    trn_date = date;
                    $(this).attr('readonly', true);
                    $('#item').prop('disabled', false);
                    $(this).datepicker('remove');
                } else {
                    $('#item').prop('disabled', true);
                }
            })
        }

        function registerSelect2() {
            $('#item').select2();
            $('#trn_customer').select2();
            //$('#brand').select2();
            //$('#uom').select2();
            $('#trn_with').select2();
        }

        function disableItem() {
            const deliveryDate = $('input[name=trn_date]').val();
            if (!deliveryDate) {
                $('#item').prop('disabled', true);
            }
        }
    </script>

@endpush
