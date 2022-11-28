@extends('manual-production::layout')
@section('title', 'Date Wise Cutting Production Summary')
@section('content')
    <div class="padding">
        <div class="row manual-date-wise-cutting-report">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header text-center">
                        <h2>Color Wise Date Wise Sewing Output in Pieces Report</h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body">
                        <div class="row heading">
                            <form action="" method="GET">
                                <div class="col-sm-2">
                                    <label for="factory_id">Factory</label>
                                    <select name="factory_id" id="factory_id" class="form-control form-control-sm select2-input">
                                        @foreach($factories as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <label for="buyer_id">Buyer</label>
                                    <select name="buyer_id" id="buyer_id" class="form-control form-control-sm" required>

                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <label for="order_id">Order/Style</label>
                                    <select name="order_id" id="order_id" class="form-control form-control-sm" required>

                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <label for="color_id">Color</label>
                                    <select name="color_id" id="color_id" class="form-control form-control-sm">

                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <button style="margin-top: 27px" type="submit" class="btn btn-sm btn-info">Search
                                    </button>
                                </div>
                                <div class="col-sm-2">
                                    <div class="pull-right" style="margin-top: 40px">
                                        <button id="pdf" class="btn btn-xs btn-info"><i class="fa fa-file-pdf-o"></i>
                                        </button>
                                        <button id="excel" class="btn btn-xs btn-primary"><i
                                                class="fa fa-file-excel-o"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        @includeIf('manual-production::reports.dailySewingOutputReport.data')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $('.heading select[name="buyer_id"]').select2({
            ajax: {
                url: '/manual-product/common-api/get-buyers-data',
                data: function (params) {
                    return {
                        search_query: params.term,
                        factory_id: $("#factory_id").val() || ''
                    };
                },
                processResults: function (response, params) {
                    return {
                        results: response.data,
                        pagination: {
                            more: false
                        }
                    };
                },
                cache: true,
                delay: 250
            },
            placeholder: 'Buyer',
            allowClear: true
        });

        $('.heading select[name="order_id"]').select2({
            ajax: {
                url: '/manual-product/common-api/get-buyers-orders',
                data: function (params) {
                    return {
                        search_query: params.term,
                        buyer_id: $("#buyer_id").val() || ''
                    };
                },
                processResults: function (response, params) {
                    return {
                        results: response.data,
                        pagination: {
                            more: false
                        }
                    };
                },
                cache: true,
                delay: 250
            },
            placeholder: 'Orders',
            allowClear: true
        });

        $('.heading select[name="color_id"]').select2({
            ajax: {
                url: '/manual-product/common-api/get-orders-colors',
                data: function (params) {
                    return {
                        search_query: params.term,
                        order_id: $("#order_id").val() || ''
                    };
                },
                processResults: function (response, params) {
                    return {
                        results: response.data,
                        pagination: {
                            more: false
                        }
                    };
                },
                cache: true,
                delay: 250
            },
            placeholder: 'Colors',
            allowClear: true
        });

        $(document).on('click', '#pdf', function () {
            event.preventDefault();
            const urlSearchParams = new URLSearchParams(window.location.search);
            let factoryId = Object.fromEntries(urlSearchParams.entries()).factory_id;
            let buyerId = Object.fromEntries(urlSearchParams.entries()).buyer_id;
            let orderId = Object.fromEntries(urlSearchParams.entries()).order_id;
            let colorId = Object.fromEntries(urlSearchParams.entries()).color_id;
            colorId = colorId == undefined ? '' : colorId;
            let url = `?factory_id=${factoryId}&buyer_id=${buyerId}&order_id=${orderId}&color_id=${colorId}`;
            window.location = '{{ url('buyer-style-color-wise-daily-sewing-output-report-pdf') }}' + url;
        });

        $(document).on('click', '#excel', function () {
            event.preventDefault();
            const urlSearchParams = new URLSearchParams(window.location.search);
            let factoryId = Object.fromEntries(urlSearchParams.entries()).factory_id;
            let buyerId = Object.fromEntries(urlSearchParams.entries()).buyer_id;
            let orderId = Object.fromEntries(urlSearchParams.entries()).order_id;
            let colorId = Object.fromEntries(urlSearchParams.entries()).color_id;
            colorId = colorId == undefined ? '' : colorId;
            let url = `?factory_id=${factoryId}&buyer_id=${buyerId}&order_id=${orderId}&color_id=${colorId}`;
            window.location = '{{ url('buyer-style-color-wise-daily-sewing-output-report-excel') }}' + url;
        });
    </script>
@endsection
