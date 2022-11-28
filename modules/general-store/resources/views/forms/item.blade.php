@extends('general-store::layout')
@section('title','Create Items')
@section('content')
    {{--    @component('inv::pbox')--}}
    <style type="text/css">
        .parsley-required {
            color: red;
        }

        #parsley-id-9, #parsley-id-22 {
            margin-top: 42px;
            margin-bottom: -60px;
        }


    </style>

    <div class="padding">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h2>Item</h2>
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

                        {!! Form::open(['route' => 'items.store', 'method' => 'post', 'id' => 'form']) !!}

                        <div class="row">
                            <div class="col-md-3 form-group">
                                {{ Form::label('name', 'Name*') }}
                                {{ Form::text('name', old('name') ?? null, ['class' => 'form-control', "required", "data-parsley-required-message" => "Name is required", 'id' => 'name', 'placeholder' => 'Item Name']) }}
                                @component('general-store::alert', ['name' => 'name']) @endcomponent
                            </div>
                            <div class="col-md-3 form-group">
                                {{ Form::label('description', 'Description') }}
                                {{ Form::text('description', old('description') ?? null, ['class' => 'form-control', "required", 'id' => 'name', 'placeholder' => 'Item Description']) }}
                                @component('general-store::alert', ['name' => 'description']) @endcomponent
                            </div>
                            <div class="col-md-3">
                                @php
                                    $uoms_data = [];
                                @endphp
                                @foreach($uoms as $key=>$uom)
                                    @php
                                        $uoms_data[$uom->id] = $uom->name;
                                    @endphp
                                @endforeach
                                {{ Form::label('uom', 'UoM*') }}
                                {{ Form::select('uom', $uoms_data,null, ['class' => 'form-control select-option',  'required', "data-parsley-required-message" => "UoM is required", 'id' => 'uom_select', 'placeholder' => 'Unit Of Measurement','style'=>'height:38px']) }}
                                @component('general-store::alert', ['name' => 'uom']) @endcomponent
                            </div>
                            <div class="col-md-3">
                                {{ Form::label('store', 'Store*') }}
                                {{ Form::select('store', $stores, old('store') ?? null, ['class' => 'form-control', 'required', "data-parsley-required-message" => "Store is required", 'id' => 'store','style'=>'height:38px']) }}
                                @component('general-store::alert', ['name' => 'store']) @endcomponent
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 form-group">
                                <label for="barcode">Barcode Enabled</label><br>
                                <label class="ui-check ui-check-md ui-check-color">
                                    <input type="radio" name="barcode" value="yes"
                                           class="has-value form-control" {{ old('barcode') == 'yes' ? 'checked' : null }}>
                                    <i class="indigo"></i>
                                    Yes
                                </label>
                                <br>
                                <label class="ui-check ui-check-md ui-check-color">
                                    <input type="radio" name="barcode" value="no"
                                           class="has-value form-control" {{ old('barcode') ?? 'checked' }} {{ old('barcode') == 'no' ? 'checked' : null }}>
                                    <i class="indigo"></i>
                                    No
                                </label>
                            </div>

                            <div class="col-md-3">
                                {{ Form::label('abbr', 'Abbr*') }}
                                {{ Form::text('abbr', old('abbr') ?? null, ['class' => 'form-control', 'required', "data-parsley-required-message" => "Abbr is required", 'id' => 'abbr', 'placeholder' => 'max 10']) }}
                                @component('general-store::alert', ['name' => 'abbr']) @endcomponent
                            </div>
                            <div class="col-md-3">
                                {{ Form::label('qty', 'Qty') }}
                                {{ Form::text('qty', null, ['class' => 'form-control', 'id' => 'qty', 'placeholder' => 'Qty Per Barcode (If Enabled)']) }}
                                @component('general-store::alert', ['name' => 'qty']) @endcomponent
                            </div>
                            <div class="col-md-3">

                                {{ Form::label('category', 'Category*') }}
                                {{ Form::select('category_id', $items_category,null, ['class' => 'form-control',"required", "data-parsley-required-message" => "Category is required", 'id' => 'category_id', 'placeholder' => 'Select Category','style'=>'height:38px']) }}
                                @component('general-store::alert', ['name' => 'category_id']) @endcomponent
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
                                {{ Form::select('brand_id', $brands_data,null, ['class' => 'form-control', 'id' => 'brand_id', 'placeholder' => 'Select Brand','style'=>'height:38px']) }}
                                @component('general-store::alert', ['name' => 'brand_id']) @endcomponent
                            </div>

                        </div>
                        <br>

                        <div class="row">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                                <a href="{{ route('items.index') }}" class="btn btn-danger btn-sm">Cancel</a>
                            </div>
                        </div>

                    </div>

                    <br>


                    {!! Form::close() !!}


                </div>
            </div>
            {{Form::close()}}
        </div>
    </div>
    </div>



@endsection

@push('script-head')
    <script>
        $(document).ready(function () {
            $("#uom_select").select2();
            $("#store").select2();
            $("#category_id").select2();
            $("#brand_id").select2();
            $("#form").parsley();
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

            //$('#form').ajaxForm();

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


        });

    </script>
@endpush
