@extends('cuttingdroplets::layout')
@section('title', 'Bundle Card Form')
@section('styles')
    <style type="text/css">
        .form-control form-control-sm {
            min-height: 25px !important;
            max-height: 32px !important;
        }

        #bundleInfo div[class^="col-"] {
            padding-right: 0px !important;
        }

        .duplicate-me {
            width: 100%;
        }

        .remove-me {
            width: 100%;
        }


        .col-sm-1 {
            padding-right: 3px !important;
            padding-left: 3px !important;
        }

        #bundleInfo .col-sm-2 {
            padding-right: 3px !important;
            padding-left: 3px !important;
        }
    </style>
@endsection

@section('content')
    @php
        if (old('buyer_id')) {
          $order_styles = \SkylarkSoft\GoRMG\Merchandising\Models\Order::getBuyerOrders(old('buyer_id'));
        }

        if (old('order_id')) {
          $lots = \SkylarkSoft\GoRMG\SystemSettings\Models\Lot::lots(old('order_id'));
          $pos = \SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder::purchaseOrderByOrder(old('order_id'));
        }

        if (old('order_id')) {
          $garments_items = SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsItem::getGarmentsItemsByOrder(old('order_id'));
        }

        if (old('purchase_order_id')) {
          $sizesData = \SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrderDetail::getSizes((array) old('purchase_order_id'));
          foreach ($sizesData as $size) {
            $sizes[$size->id] = $size->name ?? '';
          }
        }

        if (old('cutting_floor_id')) {
          $cutting_tables = \SkylarkSoft\GoRMG\SystemSettings\Models\CuttingTable::getCuttingTables(old('cutting_floor_id'));
        }

        if (old('order_id') && array_sum(old('lot'))) {
          $cuttingNos = \SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard::getLastCuttingNo(old('order_id'), old('lot'));
        }
    @endphp
    <div class="padding">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h2>{{ 'Bundle Card Generation [Manual]' }}</h2>
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
                        {!! Form::hidden('style_filter_option', getStyleFilterOption(), ['disabled' => true]) !!}
                        {!! Form::model($bundleCard, ['url' => $bundleCard ? 'bundle-card-generation-manual/'.$bundleCard->id :
                          'bundle-card-generation-manual', 'method' => $bundleCard ? 'PUT' : 'POST', 'id' =>
                          'bundleCardGenerationFormManual']) !!}
                        <div class="row">
                            <div class="col-md-5">
                                <div class="row">
                                    <div class="{{ $sizeSuffixSerialEnabled ? 'col-md-4' : 'hide' }}">
                                        <div class="form-group">
                                            <label for="sticker_serial_option" class="text-sm">Sticker Serial
                                                Opt.</label>
                                            {!! Form::select('sticker_serial_option', [0 => 'Lot and Size Wise Serial', 1 => 'Size Suffix Wise Serial', 2 => 'Size Wise Serial'], 0, ['class' => 'form-control form-control-sm c-select', 'id'
                                            => 'sticker_serial_option','style' => $errors->has('sticker_serial_option') ? 'border: 1px solid red;' : '']) !!}
                                        </div>
                                    </div>
                                    <div class="{{ $sizeSuffixSerialEnabled ? 'col-md-4' : 'col-md-6' }}">
                                        <div class="form-group">
                                            <label for="bookingConsumption" class="text-sm">Booking Cons.</label>
                                            {!! Form::text('booking_consumption', null, ['class' => 'form-control form-control-sm non-negative number-right',
                                            'id' => 'bookingConsumption', 'placeholder' => 'Booking consumption', 'style' =>
                                            $errors->has('booking_consumption') ? 'border: 1px solid red;' : '']) !!}
                                        </div>
                                    </div>
                                    <div class="{{ $sizeSuffixSerialEnabled ? 'col-md-4' : 'col-md-6' }}">
                                        <div class="form-group">
                                            <label for="bookingDia" class="text-sm">Booking Dia</label>
                                            {!! Form::text('booking_dia', null, ['class' => 'form-control form-control-sm non-negative number-right', 'id' =>
                                            'bookingDia', 'placeholder' => 'Enter booking consumption value here.', 'style' =>
                                            $errors->has('booking_dia') ? 'border: 1px solid red;' : '']) !!}
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="buyer">Buyer</label>
                                            {!! Form::select('buyer_id', $buyers, null, ['class' => 'form-control form-control-sm c-select select2-input', 'id'
                                            => 'buyer', 'placeholder' => 'Select a buyer', 'style' => $errors->has('buyer_id') ? 'border: 1px
                                            solid red;' : '']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="style">{{ localizedFor('Style') }}</label>
                                            {!! Form::select('order_id', $order_styles ?? [], old('order_id') ?? null,
                                                [
                                                    'class' => 'form-control form-control-sm c-select select2-input',
                                                    'id' => 'order',
                                                    'placeholder' => 'Select '.localizedFor('Style'),
                                                    'style' => $errors->has('order_id') ? 'border: 1px solid red;' : ''
                                                ])
                                            !!}
                                            <span class="text-danger font-italic font-weight-bold" id="ref_no"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="garments_item_id">Item</label>
                                            {!! Form::select('garments_item_id',
                                                $garments_items ?? [],
                                                old('garments_item_id') ?? null,
                                                [
                                                    'class' => 'form-control form-control-sm c-select',
                                                    'id' => 'garments_item_id',
                                                    'placeholder' => 'Select item',
                                                    'style' => $errors->has('garments_item_id')
                                                                ? 'border: 1px solid red;' : ''
                                                ])
                                            !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="style">Is Tube?</label>
                                            {!! Form::select('is_tube', [0 => 'No', 1 => 'Yes'], 0, ['class' => 'form-control form-control-sm c-select', 'id' =>
                                            'is_tube']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="part">Part</label>
                                            {!! Form::select('part_id', $parts, null, ['class' => 'form-control form-control-sm c-select select2-input', 'id' =>
                                            'part', 'placeholder' => 'Select a part', 'style' => $errors->has('part_id') ? 'border: 1px solid
                                            red;' : '']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="type">Type</label>
                                            {!! Form::select('type_id', $types, null, ['class' => 'form-control form-control-sm c-select select2-input', 'id' =>
                                            'type', 'placeholder' => 'Select a type', 'style' => $errors->has('type_id') ? 'border: 1px solid
                                            red;' : '']) !!}
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="floor">Cutting Floor</label>
                                            {!! Form::select('cutting_floor_id', $cuttingFloors, null, ['class' => 'form-control form-control-sm
                                            bundlecard-floor-select select2-input', 'id' => 'floor_id', 'placeholder' => 'Select a cutting
                                            floor', 'style' => $errors->has('cutting_floor_id') ? 'border: 1px solid red;' : '']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="table">Cutting Table</label>
                                            {!! Form::select('cutting_table_id', $cutting_tables ?? [], old('cutting_table_id') ?? null,
                                            ['class' => 'form-control form-control-sm c-select bundlecard-table-select select2-input', 'id' => 'table',
                                            'placeholder' => 'Select a cutting table', 'style' => $errors->has('cutting_table_id') ? 'border:
                                            1px solid red;' : '']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="country">Country</label>
                                            {!! Form::select('country_id', $countries, null, ['class' => 'form-control form-control-sm c-select select2-input',
                                            'id' => 'country', 'placeholder' => 'Select a country']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div id="cuttingNo">
                                    @if (old('order_id') && array_sum(old('lot')))
                                        @include('cuttingdroplets::forms._cutting_no', ['cuttingNos' => $cuttingNos])
                                    @endif
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div>
                                <label class="col-md-12">Bundle Info</label>
                            </div>
                            <div id="bundleInfo">
                                @if(old('bundle_no'))
                                    @foreach(old('bundle_no') as $key => $bundle)
                                        <div class="serial {{ $loop->last ? 'duplicate-me' : 'remove-me' }}"
                                             index={{ $key }}>
                                            <div class="col-sm-1 hide">
                                                <div class="form-group">
                                                    {!! Form::number('bundle_no['.$key.']', old('bundle_no['.$key.']'), ['class' => 'form-control form-control-sm
                                                    non-negative number-right', 'id' => 'bundleNo', 'placeholder' => 'No', 'style' =>
                                                    (($errors->has('bundle_no.'.$key) || $errors->has('bundle_no')) ? 'border: 1px solid red;' : '')])
                                                    !!}
                                                </div>
                                            </div>
                                            <div class="col-sm-1">
                                                <div class="form-group">
                                                    {!! Form::number('size_wise_bundle_no['.$key.']', old('size_wise_bundle_no['.$key.']'), ['class' => 'form-control form-control-sm
                                                    non-negative number-right', 'id' => 'bundleNo', 'placeholder' => 'No', 'style' =>
                                                    (($errors->has('size_wise_bundle_no.'.$key) || $errors->has('size_wise_bundle_no')) ? 'border: 1px solid red;' : '')])
                                                    !!}
                                                </div>
                                            </div>
                                            <div class="col-sm-1">
                                                <div class="form-group">
                                                    {!! Form::number('roll_no['.$key.']', old('roll_no['.$key.']'), ['class' => 'form-control form-control-sm
                                                    non-negative number-right', 'id' => 'roll_no', 'placeholder' => 'Roll', 'style' =>
                                                    ($errors->has('roll_no.'.$key) ? 'border: 1px solid red;' : '')]) !!}
                                                </div>
                                            </div>
                                            <div class="col-sm-1">
                                                <div class="form-group">
                                                    {!! Form::number('quantity['.$key.']', old('quantity['.$key.']'), ['class' => 'quantity form-control form-control-sm
                                                    non-negative number-right', 'id' => 'quantity', 'placeholder' => 'Qty', 'style' =>
                                                    ($errors->has('quantity.'.$key) ? 'border: 1px solid red;' : '')]) !!}
                                                </div>
                                            </div>
                                            <div class="col-sm-1">
                                                <div class="form-group">
                                                    {!! Form::text('sl_start['.$key.']', old('sl_start['.$key.']'), ['class' => 'sl-start form-control form-control-sm
                                                    non-negative number-right', 'id' => 'slStart', 'placeholder' => 'SL Start', 'style' =>
                                                    ($errors->has('sl_start.'.$key) ? 'border: 1px solid red;' : '')]) !!}
                                                </div>
                                            </div>
                                            <div class="col-sm-1">
                                                <div class="form-group">
                                                    {!! Form::text('sl_end['.$key.']', old('sl_end['.$key.']'), ['class' => 'sl-end form-control form-control-sm
                                                    non-negative number-right', 'id' => 'slEnd', 'placeholder' => 'SL End', 'style' =>
                                                    ($errors->has('sl_end.'.$key) ? 'border: 1px solid red;' : '')]) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    {!! Form::select('purchase_order_id['.$key.']', $pos ?? [], old('purchase_order_id['.$key.']') ??
                                                    null, ['class' => 'form-control form-control-sm c-select purchaseOrderSelect', 'placeholder' => 'Select a '. localizedFor('PO'), 'style'
                                                    => ($errors->has('purchase_order_id.'.$key) ? 'border: 1px solid red;' : '')]) !!}
                                                </div>
                                            </div>
                                            <div class="col-sm-1">
                                                <div class="form-group">
                                                    {!! Form::select('size['.$key.']', $sizes ?? [], old('size['.$key.']'), ['class' => 'form-control form-control-sm
                                                    c-select size', 'placeholder' => 'Size', 'id' => 'size', 'style' => ($errors->has('size.'.$key) ?
                                                    'border: 1px solid red;' : '')]) !!}
                                                </div>
                                            </div>
                                            <div class="col-sm-1">
                                                <div class="form-group">
                                                    {!! Form::select('suffix['.$key.']', $suffix, old('suffix['.$key.']') ?? null, ['class' =>
                                                    'form-control form-control-sm c-select sufix', 'placeholder' => 'Suffix']) !!}
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="form-group">
                                                    {!! Form::select('lot['.$key.']', $lots ?? [], old('lot['.$key.']'), ['class' => 'form-control form-control-sm
                                                    c-select lot', 'placeholder' => 'Select a lot', 'style' => ($errors->has('lot.'.$key) ? 'border: 1px
                                                    solid red;' : '')]) !!}
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
                                    <div class="duplicate-me serial" index=0>
                                        <div class="col-sm-1 hide">
                                            <div class="form-group">
                                                {!! Form::text('bundle_no[0]', 1, ['class' => 'form-control form-control-sm non-negative number-right', 'id' =>
                                                'bundleNo', 'placeholder' => 'No']) !!}
                                            </div>
                                        </div>
                                        <div class="col-sm-1">
                                            <div class="form-group">
                                                {!! Form::text('size_wise_bundle_no[0]', 1, ['class' => 'form-control form-control-sm non-negative number-right', 'id' =>
                                                'bundleNo', 'placeholder' => 'No']) !!}
                                            </div>
                                        </div>
                                        <div class="col-sm-1">
                                            <div class="form-group">
                                                {!! Form::text('roll_no[0]', null, ['class' => 'form-control form-control-sm non-negative number-right', 'id' =>
                                                'roll_no', 'placeholder' => 'Roll']) !!}
                                            </div>
                                        </div>
                                        <div class="col-sm-1">
                                            <div class="form-group">
                                                {!! Form::text('quantity[0]', null, ['class' => 'form-control form-control-sm quantity non-negative number-right',
                                                'id' => 'quantity', 'placeholder' => 'Qty']) !!}
                                            </div>
                                        </div>
                                        <div class="col-sm-1">
                                            <div class="form-group">
                                                {!! Form::text('sl_start[0]', null, ['class' => 'sl-start form-control form-control-sm non-negative number-right',
                                                'id' => 'slStart', 'placeholder' => 'SL Start']) !!}
                                            </div>
                                        </div>
                                        <div class="col-sm-1">
                                            <div class="form-group">
                                                {!! Form::text('sl_end[0]', null, ['class' => 'sl-end form-control form-control-sm non-negative number-right', 'id'
                                                => 'slEnd', 'placeholder' => 'SL End']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                {!! Form::select('purchase_order_id[0]', $pos ?? [], null, ['class' => 'form-control form-control-sm c-select
                                                purchaseOrderSelect', 'placeholder' => 'Select a '. localizedFor('PO')]) !!}
                                            </div>
                                        </div>
                                        <div class="col-sm-1">
                                            <div class="form-group">
                                                {!! Form::select('size[0]', [], null, ['class' => 'form-control form-control-sm c-select size', 'id' => 'size',
                                                'placeholder' => 'Size']) !!}
                                            </div>
                                        </div>
                                        <div class="col-sm-1">
                                            <div class="form-group">
                                                {!! Form::select('suffix[0]', $suffix, null, ['class' => 'form-control form-control-sm c-select sufix',
                                                'placeholder' => 'Sufix']) !!}
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                {!! Form::select('lot[0]', [], null, ['class' => 'form-control form-control-sm c-select lot', 'placeholder' =>
                                                'Lot']) !!}
                                            </div>
                                        </div>
                                        <div class="col-sm-1">
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

                        <div class="row form-group m-t-md">
                            <div class="col-md-12">
                                <button type="submit"
                                        class="btn btn-sm white">{{ $bundleCard ? 'Update' : 'Generate' }}</button>
                                <button type="button" class="btn btn-sm btn-dark"><a
                                        href="{{ url('bundle-card-generation-manual') }}">Cancel</a></button>
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
    <script src="{{ asset('protracker/bundlecard-manual.js') }}"></script>
    <script type="text/javascript">
        function autoSerial() {
            let sizeIds = [];
            //let sl_start = 1;
            let sl_end = 1
            $(".serial").each(function (key) {
                let current_size_id = $(this).find('.size').val();
                let sl_start;
                //let sl_end;
                let quantity = parseInt($(this).find('.quantity').val()) || 0;
                let prevRow = $(this).prev('.serial');
                let previous_size_id = prevRow.find('.size').val();

                if ((jQuery.inArray(current_size_id, sizeIds) !== -1) && (previous_size_id == current_size_id)) {
                    let sticker_serial_option = $('#sticker_serial_option').val();
                    let prev_lot = prevRow.find('.lot').val();
                    let current_lot = $(this).find('.lot').val();

                    let prev_suffix = prevRow.find('.sufix').val();
                    let current_suffix = $(this).find('.sufix').val();

                    if (sticker_serial_option == 0 && prev_lot != current_lot) {
                        sl_start = 1;
                        sl_end = quantity;
                    } else if (sticker_serial_option == 1 && prev_suffix != current_suffix) {
                        sl_start = 1;
                        sl_end = quantity;
                    } else {
                        sl_start = 1 + parseInt(prevRow.find('.sl-end').val()) || 0;
                        sl_end += quantity;
                    }
                } else {
                    sl_start = 1;
                    sl_end = quantity;
                }
                sizeIds.push(current_size_id);

                if (quantity) {
                    $(this).find('.sl-start').val(sl_start);
                    $(this).find('.sl-end').val(sl_end);
                }
            });
        }
    </script>
@endsection
