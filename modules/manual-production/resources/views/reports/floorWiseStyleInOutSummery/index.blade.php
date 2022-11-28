@extends('manual-production::layout')
@section('title', 'Date Wise Cutting Production Summary')
@section('content')
    <div class="padding">
        <div class="row manual-date-wise-cutting-report">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header text-center">
                        <h2>Floor Wise Style In Out Summary</h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body">
                        <div class="row heading">
                            <form action="" method="GET">
                                <div class="col-sm-3">
                                    <label for="factory_id">Factory</label>
                                    {!! Form::select('factory_id', $factories ?? [], $factory_id ?? null, ['class' => 'form-control form-control-sm select2-input', 'id' => 'factory_id']) !!}
                                </div>
                                <div class="col-sm-3">
                                    <label for="buyer_id">Buyer</label>
                                    {!! Form::select('buyer_id', $buyers ?? [], $buyer_id ?? null, ['class' => 'form-control form-control-sm', 'id' => 'buyer_id']) !!}
                                </div>
                                <div class="col-sm-3">
                                    <label for="order_id">Order/Style</label>
                                    {!! Form::select('order_id', $orders ?? [], $order_id ?? null, ['class' => 'form-control form-control-sm', 'id' => 'order_id']) !!}
                                </div>
                                <div class="col-sm-3">
                                    <button style="margin-top: 27px" type="submit" class="btn btn-sm btn-info">Search
                                    </button>
                                    <div class="pull-right" style="margin-top: 40px">
                                        <button id="pdf" class="btn btn-xs btn-info"><i class="fa fa-file-pdf-o"></i></button>
                                        <button id="exel" class="btn btn-xs btn-primary"><i class="fa fa-file-excel-o"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        @php $totalInput = collect($data)->sum('input_qty'); $totalOutput= collect($data)->sum('sewing_output_qty'); @endphp
                        <div class="row m-t">
                            <div class="col-sm-8">
                                <table class="reportTable">
                                    <thead>
                                    <tr>
                                        <th>Factory</th>
                                        <th>Buyer</th>
                                        <th>Style</th>
                                        <th>Color</th>
                                        <th>First Input Date</th>
                                        <th>Last Input Date</th>
                                        <th>Unit</th>
                                        <th>Input Qty.</th>
                                        <th>Output Qty.</th>
                                        <th>Balance Qty.</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse(collect($data)->groupBy('color_id') as $colorWiseData)
                                        @forelse(collect($colorWiseData)->groupBy('floor_id') as $item)
                                            <tr>
                                                @php $input = collect($item)->sum('input_qty'); $output = collect($item)->sum('sewing_output_qty') @endphp
                                                <td>{{ collect($item)->first()['factory']->factory_name }}</td>
                                                <td>{{ collect($item)->first()['buyer']->name }}</td>
                                                <td>{{ collect($item)->first()['order']->style_name }}</td>
                                                <td>{{ collect($item)->first()['color']->name }}</td>
                                                <td>{{ collect($item)->sortBy('production_date')->first()['production_date'] }}</td>
                                                <td>{{ collect($item)->sortByDesc('production_date')->first()['production_date'] }}</td>
                                                <td>{{ collect($item)->first()['floor']['floor_no'] ?? 'N/A' }}</td>
                                                <td>{{ $input }}</td>
                                                <td>{{ $output }}</td>
                                                <td>{{ $input - $output }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <th colspan="10">No Data Found</th>
                                            </tr>
                                        @endforelse
                                    @empty
                                        <tr>
                                            <th colspan="10">No Data Found</th>
                                        </tr>
                                    @endforelse
                                    <tr>
                                        <th colspan="7">Total</th>
                                        <td>{{ $totalInput }}</td>
                                        <td>{{ $totalOutput }}</td>
                                        <td>{{ $totalInput - $totalOutput }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-sm-4">
                                <table class="reportTable">
                                    <thead>
                                    <tr>
                                        <th>Unit</th>
                                        <th>Input Qty.</th>
                                        <th>Output Qty.</th>
                                        <th>Balance Qty.</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse(collect($data)->groupBy('floor_id') as $item)
                                        <tr>
                                            @php $input = collect($item)->sum('input_qty'); $output = collect($item)->sum('sewing_output_qty') @endphp
                                            <td>{{ collect($item)->first()['floor']['floor_no'] ?? 'N/A' }}</td>
                                            <td>{{ $input }}</td>
                                            <td>{{ $output }}</td>
                                            <td>{{ $input - $output }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <th colspan="4">No Data Found</th>
                                        </tr>
                                    @endforelse
                                    <tr>
                                        <th>Total</th>
                                        <td>{{ $totalInput }}</td>
                                        <td>{{ $totalOutput }}</td>
                                        <td>{{ $totalInput - $totalOutput }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
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
            placeholder: 'Buyers',
            allowClear: true
        });

        $(document).on('change', '.heading select[name="buyer_id"]', function(e) {
          $('.heading select[name="order_id"]').val('').change();
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
            placeholder: 'Order & Style',
            allowClear: true
        });

        $(document).on('click', '#pdf', function () {
            event.preventDefault();
            const urlSearchParams = new URLSearchParams(window.location.search);
            let factoryId = Object.fromEntries(urlSearchParams.entries()).factory_id;
            let buyerId = Object.fromEntries(urlSearchParams.entries()).buyer_id;
            let orderId = Object.fromEntries(urlSearchParams.entries()).order_id;
            let url = `?factory_id=${factoryId}&buyer_id=${buyerId}&order_id=${orderId}`;
            window.location = '{{ url('floor-wise-style-in-out-summary-pdf') }}' + url;
        });
    </script>
@endsection
