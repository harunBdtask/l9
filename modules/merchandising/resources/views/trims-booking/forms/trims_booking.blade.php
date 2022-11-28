@extends('skeleton::layout')
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-12">
                <div class="box dyes-chemical-receive">
                    <div class="box-header">
                        <h2>{{ $trims_booking ? 'Update Trims Requisition' : 'New  Trims Requisition' }}</h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body">
                        @include('partials.response-message')

                        {!! Form::model($trims_booking, ['url' => $trims_booking ? 'trims-booking/'.$requisition_no : 'trims-booking', 'method' => $trims_booking ? 'PUT' : 'POST']) !!}

                        {!! Form::hidden('po_id', $po_id ?? '') !!}
                        {!! Form::hidden('requisition_no', $requisition_no ?? '') !!}

                        <div class="col-md-12 table-responsive purchase-req-table">
                            <table class="reportTable">
                                <thead>
                                <tr style="height:30px; font-weight: bold; font-size: 25px;">
                                    <th colspan="8">
                                        Requisition No. : {{ $requisition_no ?? '' }}
                                    </th>
                                </tr>
                                <tr>
                                    <th width="10%">Item Group</th>
                                    <th width="10%">Item</th>
                                    <th width="10%">Desciption</th>
                                    <th width="15%">Amount</th>
                                    <th width="8%">Brand</th>
                                    <th width="10%">Nominated Supplier</th>
                                    <th width="10%">Apvl. Rqst.</th>
                                    <th width="5%"></th>
                                </tr>
                                </thead>
                                <tbody class="table-body" id="addedRow">
                                @if(!old('item_group_id') && !$trims_booking)
                                    <tr class="table-row" style="height: 50px !important;">
                                        <td>
                                            {!! Form::select('item_group_id[]', $item_groups, null, ['class' => 'form-control form-control-sm c-select', 'id' => 'itemGroupId','placeholder'=>'Item Group', 'required' => 'required']) !!}

                                            @if($errors->has('item_group_id'))
                                                <span class="text-danger">{{ $errors->first('item_group_id') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            {!! Form::select('item_id[]', $trims_booking ? $items : [] , null, ['class' => 'form-control form-control-sm c-select', 'id' => 'bookingItemId','placeholder'=>'Select Item', 'required' => 'required']) !!}

                                            @if($errors->has('item_id'))
                                                <span class="text-danger">{{ $errors->first('item_id') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="itemDetails">{{ $trims_booking->item->item_desc ?? '' }}</span>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                {!! Form::number('amount[]', null, ['class' => 'form-control form-control-sm', 'id' => 'amount', 'placeholder'=>'Amount', 'required' => 'required']) !!}
                                                <div class="input-group-addon itemUom">
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            {!! Form::select('brand_id[]', $brands, null, ['class' => 'form-control form-control-sm c-select', 'id' => 'brand_id','placeholder'=>'Brand']) !!}

                                            @if($errors->has('brand_id'))
                                                <span class="text-danger">{{ $errors->first('brand_id') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            {!! Form::select('nominated_supplier_id[]', $nominated_suppliers, null, ['class' => 'form-control form-control-sm c-select', 'id' => 'nominated_supplier_id','placeholder'=>'Supplier']) !!}

                                            @if($errors->has('nominated_supplier_id'))
                                                <span
                                                    class="text-danger">{{ $errors->first('nominated_supplier_id') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            {!! Form::select('is_approved[]', IS_APPROVED, $trims_booking ? $trims_booking->is_approved : null, ['class' => 'form-control form-control-sm c-select', 'id' => 'is_approved']) !!}

                                            @if($errors->has('is_approved'))
                                                <span class="text-danger">{{ $errors->first('is_approved') }}</span>
                                            @endif
                                            @if($errors->has('is_approved'))
                                                <span class="text-danger">{{ $errors->first('is_approved') }}</span>
                                            @endif
                                        </td>
                                        <td align="left">
                                            <button type="button" class="btn btn-white btn-xs add-more-trims-booking"><i
                                                    class="fa fa-plus"></i></button>
                                            <button type="button" style="display: none"
                                                    class="btn btn-danger btn-xs remove-trims-bookings"><i
                                                    class="fa fa-times"></i></button>
                                        </td>
                                    </tr>
                                @elseif(old('item_group_id'))
                                    @php
                                        $trimsItemList = old('item_group_id') ?? '' ;
                                    @endphp
                                    @foreach($trimsItemList as $key => $trimsItem)
                                        <tr class="table-row">
                                            <td>
                                                {!! Form::select("item_group_id[$key]", $item_groups, old('item_group_id')[$key], ['class' => 'form-control form-control-sm c-select', 'id' => 'itemGroupId','placeholder'=>'Select Item Group', 'style' => $errors->has("item_group_id.$key") ? 'border: 1px solid red;' : '']
                                                  ) !!}
                                            </td>
                                            <td>
                                                {!! Form::select("item_id[$key]", $items, old('item_id')[$key], ['class' => 'form-control form-control-sm c-select', 'id' => 'bookingItemId','placeholder'=>' Select Item', 'style' => $errors->has("item_id.$key") ? 'border: 1px solid red;' : '']) !!}
                                            </td>
                                            @php
                                                $trimsDetails = \SkylarkSoft\GoRMG\SystemSettings\Models\Item::find(old('item_id')[$key]);
                                            @endphp
                                            <td>
                                                <span class="itemDetails">{{ $trimsDetails->item_desc ?? '' }}</span>
                                            </td>
                                            <td>
                                                <div class="row">
                                                    <div class="col-sm-8">
                                                        {!! Form::number("amount[$key]",  old('amount')[$key], ['class' => 'form-control form-control-sm', 'style' => 'width: 100%;', 'id' => 'amount','placeholder'=>'Select Amount','style' => $errors->has("amount.$key") ? 'border: 1px solid red;' : '']) !!}
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <span style="margin-top: 15px"
                                                              class="itemUom"><b>{{ $trimsDetails->uom_data->unit_of_measurements ?? '' }}</b></span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                {!! Form::select("brand_id[$key]", $brands, old('brand_id')[$key] ?? null, ['class' => 'form-control form-control-sm c-select', 'id' => 'brand_id','placeholder'=>'Select Brand']) !!}
                                            </td>
                                            <td>
                                                {!! Form::select("nominated_supplier_id[$key]", $nominated_suppliers, old('nominated_supplier_id')[$key] ?? null, ['class' => 'form-control form-control-sm c-select', 'id' => 'nominated_supplier_id','placeholder'=>'Select Supplier']) !!}
                                            </td>
                                            <td>
                                                {!! Form::select("is_approved[$key]", IS_APPROVED, $trims_booking ? $trims_booking->is_approved : null, ['class' => 'form-control form-control-sm c-select', 'id' => 'is_approved']) !!}
                                            </td>
                                            <td>
                                                <button type="button"
                                                        class="btn btn-white btn-xs add-more-trims-booking"><i
                                                        class="fa fa-plus"></i></button>
                                                <button type="button"
                                                        class="btn btn-danger btn-xs remove-trims-bookings"><i
                                                        class="fa fa-times"></i></button>
                                            </td>
                                        </tr>
                                    @endforeach

                                @elseif($trims_booking)
                                    {!! Form::hidden("po_id", $trims_booking->first()->po_id) !!}
                                    {!! Form::hidden("delete_id[]", null, ['class' => 'delItemId']) !!}

                                    @foreach($trims_booking as $key => $trimsItem)
                                        <tr class="table-row">
                                            {!! Form::hidden("id[]", $trimsItem->id, ['class' => 'itemId']) !!}
                                            <td>
                                                {!! Form::select("item_group_id[]", $item_groups, $trimsItem->item_group_id, ['class' => 'form-control form-control-sm c-select', 'required' => 'required', 'id' => 'itemGroupId','placeholder'=>'Item Group', 'style' => $errors->has("item_group_id.$key") ? 'border: 1px solid red;' : '']
                                                  ) !!}
                                            </td>
                                            @php
                                                $items = Skylarksoft\Systemsettings\Models\ItemGroupAssign::
                                                    where('item_group_id', $trimsItem->item_group_id)
                                                    ->join('items', 'items_group_assign.item_id', 'items.id')
                                                    ->pluck('items.item_name', 'items.id')
                                                    ->all();
                                            @endphp
                                            <td>
                                                {!! Form::select("item_id[]", $items, $trimsItem->item_id, ['class' => 'form-control form-control-sm c-select', 'required' => 'required', 'id' => 'bookingItemId','placeholder'=>' Select Item',  'style' => $errors->has("item_id.$key") ? 'border: 1px solid red;' : '']) !!}
                                            </td>
                                            <td>
                                                <span class="itemDetails">{{ $trimsItem->item->item_desc ?? '' }}</span>
                                            </td>
                                            <td>
                                                <div class="input-group">
                                                    {!! Form::number('amount[]', $trimsItem->amount, ['class' => 'form-control form-control-sm', 'id' => 'amount', 'placeholder'=>'Amount', 'required' => 'required']) !!}
                                                    <div class="input-group-addon itemUom">
                                                        {{ $trimsItem->item->uom_data->unit_of_measurements ?? '' }}
                                                    </div>
                                                </div>
                                                {{--
                                                <div class="input-group">
                                                    {!! Form::number('amount[]', null, ['class' => 'form-control form-control-sm', 'id' => 'amount', 'placeholder'=>'Amount', 'required' => 'required']) !!}
                                                    <div class="input-group-addon itemUom">
                                                    </div>
                                                </div>
                                                --}}
                                                {{--
                                                <div class="row">
                                                    <div class="col-sm-8">
                                                        {!! Form::number("amount[]",  $trimsItem->amount, null, ['class' => 'form-control form-control-sm', 'style' => 'width: 100%;' , 'required' => 'required', 'id' => 'amount','placeholder'=>'Amount','style' => $errors->has("amount.$key") ? 'border: 1px solid red;' : '']) !!}
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <span style="margin-top: 15px" class="itemUom"><b>{{ $trimsItem->item->uom_data->unit_of_measurements ?? '' }}</b></span>
                                                    </div>
                                                </div>
                                                --}}
                                            </td>
                                            <td>
                                                {!! Form::select("brand_id[]", $brands, $trimsItem->brand_id ?? null, ['class' => 'form-control form-control-sm c-select', 'id' => 'brand_id','placeholder'=>'Brand']) !!}
                                            </td>
                                            <td>
                                                {!! Form::select("nominated_supplier_id[]", $nominated_suppliers, $trimsItem->nominated_supplier_id ?? null, ['class' => 'form-control form-control-sm c-select', 'id' => 'nominated_supplier_id','placeholder'=>'Supplier']) !!}
                                            </td>
                                            <td>
                                                {!! Form::select("is_approved[]", IS_APPROVED, $trimsItem->is_approved ?? null, ['class' => 'form-control form-control-sm c-select', 'id' => 'is_approved']) !!}
                                            </td>
                                            <td align="left">
                                                @if($loop->last)
                                                    <button type="button"
                                                            class="btn btn-white btn-xs edit-add-more-trims-booking"><i
                                                            class="fa fa-plus"></i></button>
                                                @endif
                                                <button type="button" value="{{ $trimsItem->id }}"
                                                        class="btn btn-danger btn-xs remove-edit-trims-bookings"><i
                                                        class="fa fa-times"></i></button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>


                        <div style="padding-left: 12px" class="form-group m-t-md">
                            <div class="col-sm-3">
                                <button type="submit"
                                        class="btn white">{{ $trims_booking ? 'Update' : 'Create' }}</button>
                                <button type="button" class="btn white"><a
                                        href="{{ url('purchase-order/trims-requisition-list?purchase_order_id='.$po_id) }}">Cancel</a>
                                </button>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style type="text/css">
        td {
            padding: 3px !important;
        }
    </style>
@endsection

@section('scripts')
    <script src="{{ mix('js/merchandising/trims_booking.js') }}"></script>
@endsection
