@extends('warehouse-management::layout')
@section('styles')
    <style>
        .select2-container .select2-selection--single {
            height: 40px;
            border-radius: 0px;
            line-height: 50px;
            border: 1px solid #e7e7e7;
        }

        .reportTable .select2-container .select2-selection--single {
            border: 1px solid #e7e7e7;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 40px;
            width: 150px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 8px;
        }

        .select2-container--default .select2-selection--multiple {
            min-height: 40px !important;
            border-radius: 0px;
            width: 100%;
        }
    </style>
@endsection
@section('title', 'Allocate Carton to Rack')
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h2>Allocate Carton to Rack</h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body">
                        <div class="flash-message" style="margin-bottom: 20px;">
                            @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                                @if(Session::has('alert-' . $msg))
                                    <p class="text-center alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</p>
                                @endif
                            @endforeach
                        </div>
                        {!! Form::open(['url' => '/warehouse-carton-allocation', 'method' => 'POST', 'id' => 'warehouse-carton-allocation-form']) !!}
                        <div class="form-group row">
                            <div class="col-sm-3">
                                {!! Form::select('warehouse_floor_id', $warehouse_floors ?? [], null, ['class' => 'form-control select2-input', 'id' => 'warehouse_floor_id', 'placeholder' => 'Select Floor', 'required' => true]) !!}
                                <span class="text-danger warehouse_floor_id"></span>
                            </div>
                            <div class="col-sm-3">
                                {!! Form::select('warehouse_rack_id', [], null, ['class' => 'form-control select2-input', 'id' => 'warehouse_rack_id', 'placeholder' => 'Select Rack', 'required' => true]) !!}
                                <span class="text-danger warehouse_rack_id"></span>
                            </div>
                            <div class="col-sm-6">
                                {!! Form::text('barcode_no', null, ['class' => 'form-control', 'id' => 'barcode_no', 'placeholder' => 'Scan barcode here', 'required' => true]) !!}
                                <span class="text-danger barcode_no"></span>
                                <span class="text-danger available_rack_qty"></span>
                            </div>
                        </div>
                        {!! Form::close() !!}
                        <div class="table-responsive allocation-table" style="margin-top: 20px;">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        var messageElement = $('.flash-message');
        var htmlElement = $('.allocation-table');

        $(document).on('change', '#warehouse_floor_id', function () {
            var warehouse_floor_id = $(this).val();
            $('.available_rack_qty').html('');
            if (warehouse_floor_id) {
                $.ajax({
                    type: 'GET',
                    url: '/get-warehouse-racks/' + warehouse_floor_id,
                    success: function (response) {
                        var rackDropdown = '<option value="">Select Rack</option>';
                        if (Object.keys(response).length > 0) {
                            $.each(response, function (index, val) {
                                rackDropdown += '<option value="' + index + '">' + val + '</option>';
                            });
                        }
                        $('#warehouse_rack_id').html(rackDropdown);
                        $('#warehouse_rack_id').val('').select2();
                    }
                });
            }
        });

        $(document).on('change', '#warehouse_rack_id', function () {
            var warehouse_rack_id = $(this).val();
            htmlElement.html('');
            if (warehouse_rack_id) {
                showLoader();
                getWarehouseRackAllocatedCartonData(warehouse_rack_id);
            }
        });

        $(document).on('submit', '#warehouse-carton-allocation-form', function (e) {
            e.preventDefault();
            var warehouse_rack_id = $('#warehouse_rack_id').val();
            var form = $(this);
            showLoader();

            $.ajax({
                type: form.attr('method'),
                url: form.attr('action'),
                data: form.serialize()
            }).done(function (response) {
                hideLoader();
                if (response.status == 'error') {
                    $.each(response.errors, function (errorIndex, errorValue) {
                        let errorDomElement, error_index, errorMessage;
                        errorDomElement = '' + errorIndex;
                        errorDomIndexArray = errorDomElement.split(".");
                        errorDomElement = '.' + errorDomIndexArray[0];
                        error_index = errorDomIndexArray[1];
                        errorMessage = errorValue[0];
                        $(errorDomElement).html(errorMessage);
                    });
                }

                if (response.status == 'success') {
                    messageElement.html(response.message);
                    getWarehouseRackAllocatedCartonData(warehouse_rack_id);
                }

                if (response.status == 'danger') {
                    messageElement.html(response.message);
                }
                messageElement.fadeIn().delay(2000).fadeOut(2000);
                $('#barcode_no').val('');
            });
        });

        function getWarehouseRackAllocatedCartonData(warehouse_rack_id) {
            $.ajax({
                type: 'GET',
                url: '/get-warehouse-rack-allocated-cartons/' + warehouse_rack_id,
                success: function (response) {
                    hideLoader();
                    htmlElement.html(response.html);
                    $('.available_rack_qty').html('Rack available Qty ' + response.rack_available_qty);
                }
            });
        }
    </script>
@endsection