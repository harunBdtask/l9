@extends('general-store::layout')
@section('title','Stock In')
@section('styles')
    <style>

    </style>
@endsection

@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h2>Stock In ({{get_store_name($store)}})</h2>
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
                        {!! Form::open(['url' => 'voucher_in', 'method' => 'post', 'autocomplete' => 'off', 'id' => 'form-in'])!!}

                        <div class="row">


                            {{--                            <div class="col-sm-3">--}}
                            {{--                                <div class="form-group">--}}
                            {{--                                    <label for="trn_with">Store <dfn>*</dfn></label>--}}
                            {{--                                    {!! Form::select('store', $stores, isset($store) ? $store : null, ['class'=>'form-control select-option', 'disabled'=>true, 'id' => 'store_data', 'placeholder' => 'Search & Select', 'required']) !!}--}}
                            {{--                                    {!! Form::hidden('type', $type) !!}--}}
                            {{--                                    @isset($voucher)--}}
                            {{--                                        {{ Form::hidden('id', $voucher->id) }}--}}
                            {{--                                    @endisset--}}
                            {{--                                    @component('inventory::alert', ['name' => 'store']) @endcomponent--}}
                            {{--                                </div>--}}
                            {{--                            </div>--}}

                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="trn_with">Supplier <dfn>*</dfn></label>
                                    {!! Form::select('trn_with', $suppliers, isset($voucher) ? $voucher->trn_with : null, ['class'=>'form-control select-option ', 'style'=>'height:38px;','id' => 'trn_with', 'placeholder' => 'Search & Select', 'required']) !!}
                                    {!! Form::hidden('store', $store) !!}
                                    {!! Form::hidden('type', $type) !!}
                                    @isset($voucher)
                                        {{ Form::hidden('id', $voucher->id) }}
                                    @endisset
                                    @component('general-store::alert', ['name' => 'trn_with']) @endcomponent
                                    <span id="supplier_error" class="text-danger"></span>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="form-group">
                                    @php
                                        $trnDate = isset($voucher) ? \Carbon\Carbon::parse($voucher->trn_date)->format('Y-m-d') : \Carbon\Carbon::now()->format('Y-m-d');
                                    @endphp
                                    <label for="trn_date">Receive Date <dfn>*</dfn></label>
                                    {!! Form::text('trn_date', $trnDate, ['class'=>'form-control select-option ', 'id' => 'trn_date', 'placeholder' => 'Delivery Date']) !!}
                                    @component('general-store::alert', ['name' => 'trn_date']) @endcomponent
                                    <span id="date_error" class="text-danger"></span>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="reference">Reference / Challan No.</label>
                                    {!! Form::text('reference', $voucher->reference ?? null, ['class'=>'form-control select-option', 'id' => 'reference', 'placeholder' => 'Reference']) !!}
                                    @component('general-store::alert', ['name' => 'reference']) @endcomponent
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="rack_id">Rack No</label>
                                    {!! Form::select('rack_id', $racks, $voucher->rack_id ?? null, ['class'=>'form-control select-option ', 'id' => 'rack_id', 'placeholder' => 'Rack No','style'=>'height:38px;']) !!}
                                    @component('general-store::alert', ['name' => 'rack_id']) @endcomponent
                                </div>
                            </div>

                        </div>



                        <div class="row" id="add_item">

                            <div class="col-md-3">

                                <label for="item">Item*
                                    <span class="text-success" id="barcode_enable"
                                          style="font-size: 14px;display: none;"><i
                                            class="fa fa-barcode"></i></span>
                                </label>

                                <select name="item_id"
                                        value=""
                                        class="form-control select-option"
                                        id="item"
                                >

                                    <option value="">Search & Select</option>
                                    @foreach($items as $item)
                                        <option value="{{ $item->id }}"
                                                data-uom="{{ $item->uom }}"
                                                data-uom_value="{{$item->uomDetails->name ?? ''}}"
                                                data-brand_value="{{$item->brand->name ?? ''}}"
                                                data-brand_id="{{$item->brand->id ?? ''}}"
                                                data-category_id="{{ $item->category->id ?? '' }}"
                                                data-category_name="{{ $item->category->name ?? '' }}"
                                                data-enable_barcode="{{ $item->barcode ?? '' }}"
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
                                <label for="qty">Rcv.Qty*</label>
                                {!! Form::text('qty', null, ['class'=>'form-control field-rcv-qty', 'id' => 'qty']) !!}
                            </div>

                            <div class="col-md-1">
                                <label for="rate">Rate*</label>
                                {!! Form::text('rate', null, ['class'=>'form-control field-rate', 'id' => 'rate']) !!}
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
                                        <th>Item</th>
                                        <th>Category</th>
                                        <th>Brand</th>
                                        <th>Qty</th>
                                        <th>Rate</th>
                                        <th>Remarks</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>

                            </div>
                        </div>

                        <div class="row m-t-2">
                            <div class="form-group text-center">
                                <button type="button" class="{{isset($voucher) ? 'btn btn-sm btn-primary' : 'btn btn-sm btn-success'}}" id="submit-button">
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

        @php
            if (isset($voucher)) {
               echo 'details = ' . json_encode($voucher->details) . ';' ;
            }
        @endphp

        const codes = [];

        const store = $('input[name=store]').val();
        const type = $('input[name=type]').val();
        let trn_with = null;
        let trn_date = null;

        console.log('hello world');


        $(document).ready(function () {

            init();

            // register events

            body.on('change', '#item', itemChangeHandler);

            body.on('click', '.add-to-cart', addToCartHandler);

            body.on('click', '.remove-button', removeFromCartHandler);

            body.on('click', '#submit-button', submitHandler);
        });

        function getItemValues() {
            const item_id = $('#item').val();
            const item = $('#item').find('option:selected').text();
            const category = $('#category').val();
            const brand_id = parseInt($('#brand_id').val()) || null;
            let brand = $('#brand').val();
            const rate = parseFloat($('#rate').val()) || null;
            const qty = parseFloat($('#qty').val()) || null;
            const remarks = $('#remarks').val();

            // if (brand === 'Select & Search') {
            //     brand = '';
            // }

            if (!item_id || !rate || !qty) {
                toastr.warning('Fill all the fields');
                return {};
            }

            return {
                item_id,
                item,
                category,
                brand_id,
                brand,
                rate,
                qty,
                remarks
            }
        }

        const preparedRow = ({item, category, brand, qty, rate, remarks}, idx) => {
            return `<tr data-idx="${idx}">
                        <td class="text-left" style="padding: 1%">${item}</td>
                        <td class="text-left" style="padding: 1%">${category}</td>
                        <td class="text-left" style="padding: 1%">${brand || 'N/A'}</td>
                        <td class="text-right" style="padding: 1%">${parseFloat(qty).toFixed(2)}</td>
                        <td class="text-right" style="padding: 1%">${parseFloat(rate).toFixed(2)}</td>
                        <td class="text-left" style="padding: 1%">${remarks || 'N/A'}</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-default remove-button"><i class="fa fa-trash text-danger"></i></button>
                        </td>
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
            $('#remarks').val(null);
        };

        // event handlers
        function removeFromCartHandler() {
            let idx = $(this).closest('tr').attr('data-idx');
            let item = details.splice(idx, 1);
            reRenderItemTable();
            toastr.info(`Removed ${item[0].item}!`);
        }

        function itemChangeHandler() {

            let itemId = $(this).val();

            //const brandDropdown = $('#brand');
            //const fetchBrandsSuccessHandler = (res) => brandDropdown.empty().html(res.data);

            $('#uom').val($(':selected', $(this)).data('uom_value'));
            $('#brand').val($(':selected', $(this)).data('brand_value'));
            $('#brand_id').val($(':selected', $(this)).data('brand_id'));
            $('#category').val($(':selected', $(this)).data('category_name'));

            let barcode = $(':selected', $(this)).data('enable_barcode');
            if (barcode === 1) {
                $("#barcode_enable").show();
                toastr.info(`Barcode is enabled for this item`);
            } else {
                $("#barcode_enable").hide();
                toastr.warning(`Barcode is disabled for this item`);
            }
            let deliveryDate = $('input[name=trn_date]').val();
            const rateInput = $('#rate');
            if (itemId && deliveryDate) {
                getOutRate(itemId, deliveryDate).then(res => {
                    console.log(res)
                    rateInput.attr("placeholder", res.data.rate)
                }).catch(err => {
                    rateInput.val(null);
                    console.log(err.toString());
                });
            }
            //$('#brand').val($(this).data('brand_value'));
            //console.log($(this).data('brand_value'));
            // if (itemId) {
            //     brandDropdown.val(null).select2();
            //     getBrandsFormItem(itemId)
            //         .then(fetchBrandsSuccessHandler)
            //         .catch(console.error)
            // }
        }

        function trim(str) {
            return str.trim();
        }

        function addToCartHandler(event) {
            let item = getItemValues();

            //console.log(item);

            if (Object.keys(item).length === 0) {
                return;
            }

            details.push(item);

            renderItemInTable(item, details.length - 1);

            emptyFields();

            toastr.success(`Added ${item.item}!`);
        }

        function submitHandler(event) {
            event.preventDefault()
            trn_with = $('#trn_with').val()
            trn_date = $('#trn_date').val()
            let error = false;
            $("#date_error").text("");
            $("#supplier_error").text("");
            if (!trn_date) {
                //toastr.warning('Please select a date!')
                $("#date_error").text("Please select a date");
                error = true;
            }
            if (!trn_with) {
                //toastr.warning('Please Select a supplier!')
                $("#supplier_error").text("Please Select a Supplier");
                error = true;
            }
            if (details.length === 0) {
                toastr.warning('Item is not available in the cart!')
                error = true;
            }
            if (!error) {
                const store = {{ $store }};
                saveVoucher()
                    .then(res => {
                        console.log(res)
                        toastr.success(res.data.message);
                        setTimeout(() => window.location = `/general-store/vouchers/${store}`, 1000);
                    }).catch(err => toastr.error(err.toString()));
            }


        }


        // validators


        // api-calls
        function getBrandsFormItem(itemId) {
            return axios.get(`/general-store/brand_for_items/${itemId}`);
        }

        function getDataForBarcode(barcode, deliveryDate) {
            return axios.get('/general-store/barcode', {
                params: {
                    barcode,
                    deliveryDate
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
            const id = $("input[name='id']").val() || null;
            const rack_id = $('#rack_id').val();
            const reference = $('#reference').val();
            return axios.post('/general-store/voucher_stock_in', {
                store,
                type,
                id,
                details,
                trn_date,
                trn_with,
                rack_id,
                reference,
            })
        }

        // init

        function init() {
            registerDate();
            registerSelect2();
            reRenderItemTable();
        }

        function registerDate() {
            $('#trn_date').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                title: 'Delivery Date',
            });
        }

        function registerSelect2() {
            $('#item').select2();
            $('#store_data').select2();
            $('#trn_with').select2();
            $('#rack_id').select2();
        }

    </script>

@endpush
