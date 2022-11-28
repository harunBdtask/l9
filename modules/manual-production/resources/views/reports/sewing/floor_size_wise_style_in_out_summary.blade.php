@extends('manual-production::layout')
@section('title', 'Challan Wise Style Input Summary')
@section('content')
    <div class="padding">
        <div class="row manual-challan-report">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header text-center">
                        <h2>Floor Size Wise Style In Out Summary Report
                            <span class="pull-right">
                                <a href="javascript:void(0)" onclick="submitPdf()">
                                    <i style="color: #DC0A0B" class="fa fa-file-pdf-o"></i>
                                </a>
                            |
                                <a href="javascript:void(0)" onclick="submitExcel()">
                                  <i style="color: #0F733B" class="fa fa-file-excel-o"></i>
                                </a>
                            </span>
                        </h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body">
                        {!! Form::open(['url' => '/manual-floor-size-wise-style-in-out-summary', 'method' => 'GET', 'id'=>'search_form']) !!}
                        <div class="form-group">
                            <div class="row m-b">
                                <div class="col-sm-3">
                                    <label>Buyer</label>
                                    {!! Form::select('buyer_id', $buyers ?? [], $buyer_id ?? null, ['class' => 'form-control form-control-sm
                                    select2-input buyer_select', 'placeholder' => 'Select Buyer']) !!}
                                </div>
                                <div class="col-sm-3">
                                    <label>Order/Style</label>
                                    {!! Form::select('order_id', $orders ?? [], $order_id ?? null, ['class' => 'form-control form-control-sm
                                        order_select', 'placeholder' => 'Select Order']) !!}
                                </div>
                                <div class="col-sm-2">
                                    <label>&nbsp;</label>
                                    <button type="submit" class="btn btn-sm btn-info form-control form-control-sm">Search</button>
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}
                        <div class="table-responsive">
                            @include('manual-production::reports.sewing.includes.floor_size_wise_style_in_out_summary_include')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        function submitPdf() {
            $("#search_form").attr('action', '/manual-floor-size-wise-style-in-out-summary/pdf').submit();
        }

        function submitExcel() {
            $("#search_form").attr('action', '/manual-floor-size-wise-style-in-out-summary/excel').submit();
        }

        $(document).on('change', '.buyer_select', function (e) {
            $(`.manual-challan-report select[name="order_id"]`).val('').change();
        });
        $('.manual-challan-report select[name="order_id"]').select2({
            ajax: {
                url: '/manual-product/common-api/get-buyers-orders',
                data: function (params) {
                    return {
                        search_query: params.term,
                        buyer_id: $(".buyer_select").val() || ''
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
    </script>
@endsection
