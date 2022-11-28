@extends('dyes-store::layout')
@section('title','Edit Item')
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h2>Item Edit</h2>
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

                        {!! Form::open(['url' => "/dyes-store/items/$item->id", 'method' => 'put']) !!}

                        <div class="row">
                            <div class="col-md-3 form-group">
                                {{ Form::label('name', 'Name*') }}
                                {{ Form::text('name', old('name') ?? $item->name, ['class' => 'form-control', 'id' => 'name', 'placeholder' => 'Item Name']) }}
                                @component('dyes-store::alert', ['name' => 'name']) @endcomponent
                            </div>
                            <div class="col-md-3 form-group">
                                {{ Form::label('description', 'Description') }}
                                {{ Form::text('description', $item->description ?? null, ['class' => 'form-control', "required", 'id' => 'name', 'placeholder' => 'Item Description']) }}
                                @component('dyes-store::alert', ['name' => 'description']) @endcomponent
                            </div>
                            <div class="col-md-3 form-group">
                                @php
                                    $uoms_data = [];
                                @endphp
                                @foreach($uoms as $key=>$uom)
                                    @php
                                        $uoms_data[$uom->id] = $uom->name;
                                    @endphp
                                @endforeach
                                {{ Form::label('uom', 'UoM*') }}
                                {{ Form::select('uom', $uoms_data, $item->uom,['class' => 'form-control', 'id' => 'uom', 'placeholder' => 'Unit Of Measurement','style'=>'height:38px']) }}
                                @component('dyes-store::alert', ['name' => 'uom']) @endcomponent
                            </div>

                            <div class="col-md-3" hidden>
                                {{ Form::select('store', $stores, $item->store, ['class' => 'form-control', 'required', "data-parsley-required-message" => "Store is required", 'id' => 'store','style'=>'height:38px']) }}
                                @component('dyes-store::alert', ['name' => 'store']) @endcomponent
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 form-group">
                                <label for="barcode">Barcode Enabled</label><br>
                                <label class="ui-check ui-check-md ui-check-color">
                                    <input type="radio" name="barcode" value="yes"
                                           class="has-value form-control"
                                        {{ $item->barcode == 1 ? 'checked' : null }}>
                                    <i class="indigo"></i>
                                    Yes
                                </label>
                                <br>
                                <label class="ui-check ui-check-md ui-check-color">
                                    <input type="radio" name="barcode" value="no"
                                           class="has-value form-control" {{ $item->barcode != 1 ? 'checked' : null }}>
                                    <i class="indigo"></i>
                                    No
                                </label>
                            </div>
                            <div class="col-md-3 from-group">
                                {{ Form::label('abbr', 'Abbr*') }}
                                {{ Form::text('abbr', old('abbr') ?? substr($item->prefix, 1), ['class' => 'form-control', 'id' => 'abbr', 'placeholder' => 'max 3']) }}
                                @component('dyes-store::alert', ['name' => 'abbr']) @endcomponent
                            </div>

                            <div class="col-md-3">
                                {{ Form::label('qty', 'Qty') }}
                                {{ Form::text('qty', $item->qty, ['class' => 'form-control', 'id' => 'qty', 'placeholder' => 'Qty Per Barcode (If Enabled)']) }}
                                @component('dyes-store::alert', ['name' => 'qty']) @endcomponent
                            </div>

                            <div class="col-md-3">
                                @php
                                    $items_category_data = [];
                                @endphp
                                @foreach($items_category as $key=>$items_category_value)
                                    @php
                                        $items_category_data[$items_category_value->id] = $items_category_value->name;
                                    @endphp
                                @endforeach
                                {{ Form::label('category', 'Category*') }}
                                {{ Form::select('category_id', $items_category_data,$item->category_id, ['class' => 'form-control', 'id' => 'category_id', 'placeholder' => 'Select Parent','style'=>'height:38px']) }}
                                @component('dyes-store::alert', ['name' => 'category_id']) @endcomponent
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-md-3">
                                @php
                                    $brands_data = [];
                                @endphp
                                @foreach($brands as $key=>$brand)
                                    @php
                                        $brands_data[$brand->id] = $brand->name;
                                    @endphp
                                @endforeach
                                {{ Form::label('brand', 'Brand') }}
                                {{ Form::select('brand_id', $brands_data,$item->brand_id, ['class' => 'form-control', 'id' => 'brand_id', 'placeholder' => 'Select Brand','style'=>'height:38px']) }}
                                @component('dyes-store::alert', ['name' => 'parent_id']) @endcomponent
                            </div>


                        </div>
                        <br>

                        <div class="row">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary btn-sm">Update</button>
                                <a href="{{ url('/dyes-store/items') }}" class="btn btn-danger btn-sm">Cancel</a>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>


            @endsection

            @push('script-head')
                <script>
                    $(document).ready(function () {

                        $("#uom").select2();
                        $("#category_id").select2();
                        $("#brand_id").select2();


                        $('input[name=barcode]').on('change', function () {
                            const isBarcode = $(this).val();
                            let qtyInput = $('input[name=qty]');

                            if (isBarcode === 'yes') {
                                qtyInput
                                    .attr('required', true)
                                    .attr('data-parsley-required-message', 'Qty is required when barcode is enabled')
                            } else {
                                qtyInput
                                    .removeAttr('required')
                                    .removeAttr('data-parsley-required-message')
                            }
                        })

                        $('#form').ajaxForm();

                        $('#form').submit(function () {
                            // submit the form
                            if ($(this).parsley().isValid()) {
                                const options = {
                                    clearForm: true,
                                    success: function () {
                                        toastr.success('Successfully Created')
                                    },
                                };
                                $(this).ajaxSubmit(options);
                            }
                            return false;
                        });

                    })
                </script>
    @endpush
