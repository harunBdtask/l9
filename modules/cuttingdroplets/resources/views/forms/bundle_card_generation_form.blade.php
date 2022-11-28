@extends('cuttingdroplets::layout')
@section('title', 'Bundle Card Form')
@section('styles')
    <style type="text/css">
        .form-control form-control-sm {
            min-height: 25px !important;
            max-height: 32px !important;
        }

        .duplicate-me {
            width: 100%;
        }

        .remove-me {
            width: 100%;
        }

        .select2-container .select2-selection--single {
            height: 31px;
            padding-top: 1px !important;
        }

        .padding-right {
            /*padding-left:0px !important; */
            padding-right: 0px !important;
        }

        .padding-right .size {
            padding-left: 2px !important;
        }
    </style>
@endsection

@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h2>{{ 'Bundle Card Generation [Auto]' }}</h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body">
                        @error('order_id')
                        <div class="row">
                          <div class="col-sm-12">
                            <div class="col-md-6 col-md-offset-3 alert alert-danger text-center">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                <small>{{ $message }}</small>
                            </div>
                          </div>
                        </div>
                        @enderror
                        @error('ply.*')
                        <div class="row">
                          <div class="col-sm-12">
                            <div class="col-md-6 col-md-offset-3 alert alert-danger text-center">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                <small>{{ $message }}</small>
                            </div>
                          </div>
                        </div>
                        @enderror

                        @php
                            $ratios=[ 1=> 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5,
                              6 => 6, 7 => 7, 8 => 8, 9 => 9, 10 => 10,
                              11 => 11, 12 => 12
                              ];
                            if (old('buyer_id')) {
                            $order_styles = \SkylarkSoft\GoRMG\Merchandising\Models\Order::getBuyerOrders(old('buyer_id'));
                            }

                            if (old('order_id')) {
                              $garments_items = SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsItem::getGarmentsItemsByOrder(old('order_id'));
                            }

                            if (old('order_id')) {
                            $purchase_orders =
                            \SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder::purchaseOrderByOrder(old('order_id'));
                            $lots = \SkylarkSoft\GoRMG\SystemSettings\Models\Lot::lots(old('order_id'));
                            }

                            if (old('cutting_floor_id')) {
                            $cutting_tables =
                            \SkylarkSoft\GoRMG\SystemSettings\Models\CuttingTable::getCuttingTables(old('cutting_floor_id'));
                            }

                            if (old('order_id') && array_sum(old('lot_id'))) {
                            $cuttingNos = \SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard::getLastCuttingNo(old('order_id'),
                            old('lot_id'));
                            }

                        @endphp
                        {!! Form::hidden('style_filter_option', getStyleFilterOption(), ['disabled' => true]) !!}
                        {!! Form::model($bundleCard, ['url' => $bundleCard ? 'bundle-card-generations/'.$bundleCard->id :
                        'bundle-card-generations', 'method' => $bundleCard ? 'PUT' : 'POST', 'id' => 'bundleCardGenerationForm'])
                        !!}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="{{ $sizeSuffixSerialEnabled ? 'col-md-4' : 'hide' }}">
                                        <div class="form-group">
                                            <label for="size_suffix_sl_status" class="text-sm">Sticker Serial
                                                Opt.</label>
                                            {!! Form::select('size_suffix_sl_status', $sizeSuffixSlOptions, collect($sizeSuffixSlOptions)->take(1)->keys()->first(),
                                                [
                                                    'class' => 'form-control form-control-sm c-select',
                                                    'id' => 'size_suffix_sl_status',
                                                    'style' => $errors->has('size_suffix_sl_status') ? 'border: 1px solid red;' : ''
                                                ]
                                            ) !!}
                                        </div>
                                    </div>
                                    <div class="{{ $sizeSuffixSerialEnabled ? 'col-md-4' : 'col-md-6' }}">
                                        <div class="form-group">
                                            <label for="maxQuantity" class="text-sm">Max Qty/Bundle</label>
                                            {!! Form::number('max_quantity', null, ['class' => 'form-control form-control-sm non-negative number-right', 'id'
                                            => 'maxQuantity', 'placeholder' => 'Max qty per bundle', 'min'=> '0','style' =>
                                            $errors->has('max_quantity') ? 'border: 1px solid red;' : '']) !!}
                                        </div>
                                    </div>
                                    <div class="{{ $sizeSuffixSerialEnabled ? 'col-md-4' : 'col-md-6' }}">
                                        <div class="form-group">
                                            <label for="consValidation" class="text-sm">Cons Validation</label>
                                            {{ Form::select('cons_validation', [1 => 'Default', 2 => 'Costing'], 1,
                                                [
                                                    'class' => 'form-control form-control-sm',
                                                    'style' => 'padding: 0.275rem 0.75rem; min-height: 2rem;',
                                                    'id' => 'cons-validation'
                                                ])
                                            }}
                                        </div>
                                    </div>
                                    <div class='col-md-4'>
                                        <div class="form-group">
                                            <label for="bookingConsumption" class="text-sm">Booking Cons.</label>
                                            {!! Form::text('booking_consumption', null, ['class' => 'form-control form-control-sm non-negative number-right
                                            booking-consumption', 'id' => 'bookingConsumption', 'placeholder' => 'Consumption', 'style' => $errors->has('booking_consumption') ? 'border: 1px solid red;' : '']) !!}
                                        </div>
                                    </div>
                                    <div class='col-md-4'>
                                        <div class="form-group">
                                            <label for="bookingDia" class="text-sm">Booking Dia</label>
                                            {!! Form::text('booking_dia', null, ['class' => 'form-control form-control-sm non-negative number-right', 'id' =>
                                            'bookingDia', 'placeholder' => 'Booking Dia', 'style' =>
                                            $errors->has('booking_dia') ? 'border: 1px solid red;' : '']) !!}
                                        </div>
                                    </div>
                                    <div class='col-md-4'>
                                        <div class="form-group">
                                            <label for="bookingDia" class="text-sm">Booking GSM</label>
                                            {!! Form::text('booking_gsm', null, ['class' => 'form-control form-control-sm non-negative number-right', 'id' =>
                                            'bookingGsm', 'placeholder' => 'Booking GSM', 'style' =>
                                            $errors->has('booking_gsm') ? 'border: 1px solid red;' : '']) !!}
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="buyer">Buyer</label>
                                            {!! Form::select('buyer_id', $buyers, null, ['class' => 'form-control form-control-sm c-select select2-input',
                                            'id' => 'buyer', 'placeholder' => 'Select a buyer', 'style' => $errors->has('buyer_id') ? 'border:
                                            1px solid red;' : '']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="style">{{ localizedFor(getStyleFilterOptionValue()) }}</label>
                                            {{ Form::select('order_id', $order_styles ?? [], old('order_id') ?? null,
                                                [
                                                    'class' => 'form-control form-control-sm c-select select2-input',
                                                    'id' => 'order',
                                                    'placeholder' => 'Select '.localizedFor(getStyleFilterOptionValue()),
                                                    'style' => $errors->has('order_id') ? 'border: 1px solid red;' : '',
                                                ])
                                            }}
                                            <span class="text-danger font-italic font-weight-bold" id="ref_no"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="garments_item_id">Item</label>
                                            {!! Form::select('garments_item_id', $garments_items ?? [], old('garments_item_id') ?? null, ['class' => 'form-control form-control-sm c-select', 'id' => 'garments_item_id', 'placeholder' => 'Select item', 'style' => $errors->has('garments_item_id') ? 'border: 1px solid red;' : '']) !!}
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="order">{{ localizedFor('PO') }}</label>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="color">Color</label>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="size">Size</label>
                                    </div>
                                    {{-- <div class="col-md-2">
                                        <label for="country">Country</label>
                                      </div> --}}
                                    <div class="col-md-2">
                                        <label for="quantity">Quantity</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div id="orderContainer">
                                        @if(old('order_id'))
                                            @foreach(old('purchase_order_id') as $key => $purchaseOrderId)
                                                <div class="{{ $loop->last ? 'duplicate-me' : 'remove-me' }}"
                                                     index={{ $key }}>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            {!! Form::select('purchase_order_id['.$key.']', $purchase_orders ?? [],
                                                            old('purchase_order_id['.$key.']') ?? null, ['class' => 'form-control form-control-sm c-select purchaseOrder',
                                                            'placeholder' => 'Select a '. localizedFor('PO'), 'style' => $errors->has('purchase_order_id.'.$key) ? 'border:
                                                            1px solid red;' : '']) !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 padding-right">
                                                        @php
                                                            if ($purchaseOrderId) {
                                                            $colors =
                                                            \SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrderDetail::getColors(old('purchase_order_id'),
                                                            true);
                                                            }
                                                            if ($purchaseOrderId) {
                                                            $sizesData =
                                                            \SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrderDetail::getSizes(old('purchase_order_id'));
                                                            foreach ($sizesData as $size) {
                                                            $sizes[$size->id] = $size->name ?? '';
                                                            }
                                                            }
                                                        @endphp
                                                        <div class="form-group">
                                                            {!! Form::select('color['.$key.']', $colors ?? [], old('color['.$key.']') ?? null, ['class' =>
                                                            'form-control form-control-sm c-select color', 'placeholder' => 'Select a color', 'style' =>
                                                            $errors->has('color.'.$key) ? 'border: 1px solid red;' : '']) !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2 padding-right">
                                                        <div class="form-group">
                                                            {!! Form::select('size['.$key.']', $sizes ?? [], old('size['.$key.']'), ['class' =>
                                                            'form-control form-control-sm c-select size', 'placeholder' => 'Select a size', 'style' =>
                                                            $errors->has('size.'.$key) ? 'border: 1px solid red;' : '']) !!}
                                                        </div>
                                                    </div>
                                                    {{-- <div class="col-md-2">
                                                            <div class="form-group">
                                                              {!! Form::select('country_id['.$key.']', $countries, old('country_id['.$key.']'), ['class' => 'form-control form-control-sm c-select', 'placeholder' => 'Select a country']) !!}
                                                            </div>
                                                          </div> --}}
                                                    <div class="col-md-2 padding-right">
                                                        <div class="form-group">
                                                            {!! Form::text('quantity['.$key.']', old('quantity['.$key.']'), ['class' => 'form-control form-control-sm
                                                            non-negative number-right', 'placeholder' => 'Qty', 'style' => $errors->has('quantity.'.$key)
                                                            ? 'border: 1px solid red;' : '']) !!}
                                                            @error('quantity.'.$key)
                                                            <span class='text-danger' style="font-size: 10px">
                                                                {{ $message }}
                                                            </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <div class="form-group">
                                                            <button type="button"
                                                                    class="btn btn-sm white {{ $loop->last ? 'duplicate' : 'remove' }}">
                                                                <i class="glyphicon {{ $loop->last ? 'glyphicon-plus' : 'glyphicon-remove' }}"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="duplicate-me" index=0>
                                                <div class="col-md-3 padding-right">
                                                    <div class="form-group">
                                                        {!! Form::select('purchase_order_id[0]', [], null, ['class' => 'form-control form-control-sm c-select
                                                        purchaseOrder', 'placeholder' => 'Select a '. localizedFor('PO')]) !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-3 padding-right">
                                                    <div class="form-group">
                                                        {!! Form::select('color[0]', [], old('color[0]'), ['class' => 'form-control form-control-sm c-select color',
                                                        'placeholder' => 'Select a color']) !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-2 padding-right">
                                                    <div class="form-group">
                                                        {!! Form::select('size[0]', [], null, ['class' => 'form-control form-control-sm c-select size', 'placeholder'
                                                        => 'Select a size']) !!}
                                                    </div>
                                                </div>
                                                {{-- <div class="col-md-2">
                                                      <div class="form-group">
                                                        {!! Form::select('country_id[0]', $countries, null, ['class' => 'form-control form-control-sm c-select', 'placeholder' => 'Select a country']) !!}
                                                      </div>
                                                    </div> --}}
                                                <div class="col-md-2 padding-right">
                                                    <div class="form-group">
                                                        {!! Form::text('quantity[0]', null, ['class' => 'form-control form-control-sm non-negative number-right',
                                                        'placeholder' => 'Qty']) !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <div class="form-group">
                                                        <button type="button" class="btn btn-sm white duplicate">
                                                            <i class="glyphicon glyphicon-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="floor">Cutting Floor</label>
                                            {!! Form::select('cutting_floor_id', $cuttingFloors, null, ['class' => 'form-control form-control-sm c-select
                                            bundlecard-floor-select select2-input', 'id' => 'cutting_floor_id', 'placeholder' => 'Select a
                                            cutting floor', 'style' => $errors->has('cutting_floor_id') ? 'border: 1px solid red;' : '']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="table">Cutting Table</label>
                                            {!! Form::select('cutting_table_id', $cutting_tables ?? [], old('cutting_table_id') ?? null,
                                            ['class' => 'form-control form-control-sm c-select bundlecard-table-select select2-input', 'id' => 'table',
                                            'placeholder' => 'Select a cutting table', 'style' => $errors->has('cutting_table_id') ? 'border:
                                            1px solid red;' : '']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div id="cuttingNo">
                                    @if (old('order_id') && array_sum(old('lot_id')))
                                        @include('cuttingdroplets::forms._cutting_no', ['cuttingNos' => $cuttingNos])
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="style">Is Tube?</label>
                                            {!! Form::select('is_tube', [0 => 'No', 1 => 'Yes'], 0, ['class' => 'form-control form-control-sm c-select']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="part">Part</label>
                                            {!! Form::select('part_id', $parts, null, ['class' => 'form-control form-control-sm c-select select2-input', 'id'
                                            => 'part', 'placeholder' => 'Select a part', 'style' => $errors->has('part_id') ? 'border: 1px
                                            solid red;' : '']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="type">Type</label>
                                            {!! Form::select('type_id', $types, null, ['class' => 'form-control form-control-sm c-select select2-input', 'id'
                                            => 'type', 'placeholder' => 'Select a type', 'style' => $errors->has('type_id') ? 'border: 1px
                                            solid red;' : '']) !!}
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div>
                                        <label class="col-md-12">Lot & Lot Range</label>
                                    </div>
                                    <div id="lotContainer">
                                        @if(old('lot_id'))
                                            @foreach(old('lot_id') as $key => $lotId)
                                                <div class="{{ $loop->last ? 'duplicate-me' : 'remove-me' }}"
                                                     index={{ $key }}>
                                                    <div class="col-md-5">
                                                        <div class="form-group">
                                                            {!! Form::select('lot_id['.$key.']', $lots ?? [], old('lot_id['.$key.']'), ['class' =>
                                                            'form-control form-control-sm c-select lot', 'placeholder' => 'Select a lot', 'style' =>
                                                            $errors->has('lot_id.'.$key) ? 'border: 1px solid red;' : '']) !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            {!! Form::number('from['.$key.']', null, ['class' => 'form-control form-control-sm non-negative number-right',
                                                            'id' => 'from', 'placeholder' => 'From', 'style' => $errors->has('from.'.$key) ? 'border: 1px
                                                            solid red;' : '']) !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            {!! Form::number('to['.$key.']', null, ['class' => 'form-control form-control-sm non-negative number-right',
                                                            'id' => 'to', 'placeholder' => 'To', 'style' => $errors->has('to.'.$key) ? 'border: 1px solid
                                                            red;' : '']) !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <button type="button"
                                                                    class="btn btn-sm white {{ $loop->last ? 'duplicate' : 'remove' }}">
                                                                <i class="glyphicon {{ $loop->last ? 'glyphicon-plus' : 'glyphicon-remove' }}"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="duplicate-me" index=0>
                                                <div class="col-md-5">
                                                    <div class="form-group">
                                                        {!! Form::select('lot_id[0]', [], null, ['class' => 'form-control form-control-sm c-select lot', 'placeholder'
                                                        => 'Select a lot']) !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        {!! Form::number('from[0]', null, ['class' => 'form-control form-control-sm non-negative number-right', 'id'
                                                        => 'from', 'placeholder' => 'From']) !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        {!! Form::number('to[0]', null, ['class' => 'form-control form-control-sm non-negative number-right', 'id' =>
                                                        'to', 'placeholder' => 'To']) !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <div class="form-group">
                                                        <button type="button" class="btn btn-sm white duplicate">
                                                            <i class="glyphicon glyphicon-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div>
                                        <label class="col-md-12">Roll, Ply, Weight, Dia & GSM</label>
                                    </div>
                                    <div id="rollContainer">
                                        @if(old('roll_no'))
                                            @foreach(old('roll_no') as $key => $rollNo)
                                                <div
                                                    class="{{ $loop->last ? 'duplicate-me' : 'remove-me' }} roll-ply-weight"
                                                    index={{ $key }}>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            {!! Form::text('roll_no['.$key.']', 1, ['readonly' => 'readonly', 'class' => 'form-control form-control-sm
                                                            roll-no', 'id' => 'roll', 'placeholder' => 'Roll no.', 'style' =>
                                                            $errors->has('roll_no.'.$key) ? 'border: 1px solid red;' : '']) !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            {!! Form::text('ply['.$key.']', null, ['class' => 'form-control form-control-sm ply-no non-negative
                                                            number-right', 'id' => 'ply', 'placeholder' => 'No. of plys', 'style' =>
                                                            $errors->has('ply.'.$key) ? 'border: 1px solid red;' : '']) !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            {!! Form::text('weight['.$key.']', null, ['class' => 'form-control form-control-sm weight non-negative
                                                            number-right', 'id' => 'weigth', 'placeholder' => 'Weight', 'style' =>
                                                            $errors->has('weight.'.$key) ? 'border: 1px solid red;' : '']) !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            {!! Form::text('dia['.$key.']', null, ['class' => 'form-control form-control-sm non-negative number-right',
                                                            'id' => 'dia', 'placeholder' => 'Dia', 'style' => $errors->has('dia.'.$key) ? 'border: 1px
                                                            solid red;' : '']) !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            {!! Form::text('gsm['.$key.']', null, ['class' => 'form-control form-control-sm non-negative number-right',
                                                            'id' => 'gsm', 'placeholder' => 'GSM', 'style' => $errors->has('gsm.'.$key) ? 'border: 1px
                                                            solid red;' : '']) !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <button type="button"
                                                                    class="btn btn-sm white {{ $loop->last ? 'duplicate' : 'remove' }}">
                                                                <i class="glyphicon {{ $loop->last ? 'glyphicon-plus' : 'glyphicon-remove' }}"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="duplicate-me roll-ply-weight" index=0>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        {!! Form::text('roll_no[0]', 1, ['readonly' => 'readonly', 'class' => 'form-control form-control-sm roll-no
                                                        non-negative number-right', 'id' => 'roll', 'placeholder' => 'Roll no.']) !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        {!! Form::number('ply[0]', null, ['class' => 'form-control form-control-sm ply-no non-negative number-right',
                                                        'id' => 'ply', 'placeholder' => 'No. of plys']) !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        {!! Form::text('weight[0]', null, ['class' => 'form-control form-control-sm weight non-negative number-right',
                                                        'id' => 'weigth', 'placeholder' => 'Weight']) !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        {!! Form::text('dia[0]', null, ['class' => 'form-control form-control-sm non-negative number-right', 'id' =>
                                                        'dia', 'placeholder' => 'Dia']) !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        {!! Form::text('gsm[0]', null, [
                                                            'class' => 'form-control form-control-sm non-negative number-right',
                                                            'id' => 'gsm',
                                                            'placeholder' => 'GSM'
                                                        ]) !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <button type="button" class="btn btn-sm white duplicate">
                                                            <i class="glyphicon glyphicon-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    {{--  @php
                                        if(old('purchase_order_id') && old('color_id')) {
                                          $sizes = \SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrderDetail::where('purchase_order_id', old('purchase_order_id'))
                                            ->where('color_id', old('color_id'))
                                            ->get()
                                            ->map(function($item) {
                                                return $item->size;
                                            })->unique('id')->values()->pluck('name', 'id')->all();
                                        }
                                      @endphp --}}

                                    <div>
                                        <label class="col-md-12">Serial, Size, Suffix & Ratio</label>
                                    </div>
                                    <div id="ratioContainer">
                                        @if(old('serial_no'))
                                            @foreach(old('serial_no') as $key => $oldSerial)
                                                <div class="{{ $loop->last ? 'duplicate-me' : 'remove-me' }}"
                                                     index={{ $key }}>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            {!! Form::text('serial_no['.$key.']', 1, ['readonly' => 'readonly', 'class' => 'form-control form-control-sm
                                                            serial-no', 'id' => 'serial', 'placeholder' => 'SL', 'style' =>
                                                            $errors->has('serial_no.'.$key) ? 'border: 1px solid red;' : '']) !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            {!! Form::select('size_id['.$key.']', $sizes ?? [], old('size_id['.$key.']') ?? null, ['class'
                                                            => 'form-control form-control-sm c-select size', 'id' => 'size', 'placeholder' => 'Select a size', 'style' =>
                                                            $errors->has('size_id.'.$key) ? 'border: 1px solid red;' : '']) !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            {!! Form::select('suffix['.$key.']', $suffix, old('suffix['.$key.']') ?? null, ['class' =>
                                                            'form-control form-control-sm c-select', 'id' => 'suffix', 'placeholder' => 'Suffix', 'style' =>
                                                            $errors->has('suffix.'.$key) ? 'border: 1px solid red;' : '']) !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            {!! Form::select('ratio['.$key.']', $ratios, null, ['class' => 'form-control form-control-sm c-select
                                                            size-ratio', 'id' => 'ratio', 'placeholder' => 'Ratio', 'style' => $errors->has('ratio.'.$key)
                                                            ? 'border: 1px solid red;' : '']) !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <button type="button"
                                                                    class="btn btn-sm white {{ $loop->last ? 'duplicate' : 'remove' }}">
                                                                <i class="glyphicon {{ $loop->last ? 'glyphicon-plus' : 'glyphicon-remove' }}"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="duplicate-me" index=0>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        {!! Form::text('serial_no[0]', 1, ['readonly' => 'readonly', 'class' => 'form-control form-control-sm
                                                        serial-no', 'id' => 'serial', 'placeholder' => 'SL']) !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        {!! Form::select('size_id[0]', [], null, ['class' => 'form-control form-control-sm c-select size', 'id' =>
                                                        'size', 'placeholder' => 'Select a size']) !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        {!! Form::select('suffix[0]', $suffix, null, ['class' => 'form-control form-control-sm c-select', 'id' =>
                                                        'suffix', 'placeholder' => 'Suffix']) !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        {!! Form::select('ratio[0]', $ratios, null, ['class' => 'form-control form-control-sm c-select size-ratio',
                                                        'id' => 'ratio', 'placeholder' => 'Ratio']) !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <button type="button" class="btn btn-sm white duplicate">
                                                            <i class="glyphicon glyphicon-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row form-group m-t-md">
                            <div class="col-md-12">
                                {{--  <button type="button" class="btn btn-primary">Calculate</button> --}}
                                <button type="submit"
                                        class="btn btn-sm white">{{ $bundleCard ? 'Update' : 'Generate' }}</button>
                                <button type="button" class="btn btn-sm btn-dark"><a
                                        href="{{ url('bundle-card-generations') }}">Cancel</a></button>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('protracker/bundlecard.js') }}"></script>
    <script type="text/javascript">
        $(document).on('keyup', '.non-negative', function () {
            let currentVal = $(this).val();
            // check all field value positive number
            if (currentVal && currentVal < 0) {
                alert('Please Enter positive value greater than 0');
                if (currentVal == 0) {
                    $(this).val('');
                } else {
                    $(this).val(Math.abs(currentVal));
                }
            } else if (isNaN(currentVal)) {
                $(this).val('');
            }

            // check mentioned field value integer or not
            let attrName = $(this).attr('name');
            attrName = attrName.split('[')[0];
            if ((attrName == 'max_quantity' || attrName == 'ply'
                    || attrName == 'from' || attrName == 'to'
                    || attrName == 'quantity')
                && (currentVal > parseInt(currentVal))) {

                alert('Please enter integer value');
                $(this).val(parseInt(currentVal));
            }
        });

        $(document).on('change', '#cons-validation', fetchCosting);
        $(document).on('change', '#order', fetchCosting);

        function fetchCosting() {
            const order = $('#order').val();
            const consValidation = $('#cons-validation').val();
            if (!order || consValidation != 2) {
                return;
            }
            const queryString = new URLSearchParams({
                order,
            });

            $.ajax({
                url: `/budget-costing-details?${queryString}`,
                type: 'get',
                success({data, status}) {
                    if (status == 200) {
                        $('#bookingConsumption').val(data.cons);
                        $('#bookingDia').val(data.dia);
                        $('#bookingGsm').val(data.gsm);
                    }
                }
            });
        }
    </script>
@endsection
