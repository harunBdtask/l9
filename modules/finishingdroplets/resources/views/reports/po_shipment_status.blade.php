@php
    $tableHeadColorClass = 'tableHeadColor';
    if (isset($type) || request()->has('type') || request()->route('type')) {
      $tableHeadColorClass = '';
    }
@endphp
@extends('finishingdroplets::layout')
@section('title', "PO & Shipment Status Report")
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header text-center">
                        <h2>PO &amp; Shipment Status Report || {{ date("jS F, Y") }}
                            <span class="pull-right"><a href="{{$pdf_download_link}}">
                                <i style="color: #DC0A0B" class="fa fa-file-pdf-o"></i></a>
                                | <a href="{{$excel_download_link}}">
                                    <i style="color: #0F733B" class="fa fa-file-excel-o"></i>
                                </a>
                            </span>
                        </h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body" id="po-shipment-report">
                        <div class="form-group">
                            <div class="row m-b">
                                <form action="{{ url('/po-shipment-status') }}" method="get">
                                    @csrf
                                    <div class="col-sm-2">
                                        <label>Buyer</label>
                                        {!! Form::select('buyer_id', $buyers, $buyer_id, ['class' => 'form-control form-control-sm select2-input']) !!}
                                    </div>
                                    <div class="col-sm-2">
                                        <label>Style/Order No</label>
                                        {!! Form::select('order_id', $order_style_list, $order_id, ['class' => 'form-control form-control-sm select2-input']) !!}
                                    </div>
                                    <div class="col-sm-2">
                                        <label>&nbsp;</label>
                                        <button type="submit" class="btn btn-sm btn-info form-control form-control-sm">Search</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div id="parentTableFixed" class="table-responsive">
                            <table class="reportTable" id="fixTable">
                                <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th>PO</th>
                                    <th>Order Qty</th>
                                    <th>Shipped Qty</th>
                                    <th>Shipment Date</th>
                                    <th>Shipment Status</th>
                                    <th>Excess/Short Qty</th>
                                    <th>Shipment(%)</th>
                                </tr>
                                </thead>
                                @if($order_report)
                                    <tbody class="po-shipment-report-table">
                                    @if(!$order_report->getCollection()->isEmpty())
                                        @php
                                            $total_order_qty = 0;
                                            $total_shipped_qty = 0;
                                        @endphp
                                        @foreach($order_report->getCollection() as $order)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{$order->po_no ?? ''}}</td>
                                                <td>{{$order->po_quantity ?? ''}}</td>
                                                <td>
                                                    @php
                                                        $shipment_qty = 0;
                                                        $shipment_status = 'Pending';
                                                         if($order->shipments){
                                                             foreach($order->shipments as $shipment) {
                                                                $shipment_qty += $shipment->ship_quantity ?? 0;
                                                                if($shipment->status == 1){
                                                                    $shipment_status = 'Shipped Out';
                                                                }
                                                             }
                                                         }
                                                        $excess_short_cut = $order->po_quantity ? $shipment_qty - $order->po_quantity : $shipment_qty;
                                                        $excess_short_cut_percent = $order->po_quantity != 0 ? number_format(($excess_short_cut/$order->po_quantity), 4) : '';
                                                        $total_order_qty += $order->po_quantity ?? 0;
                                                        $total_shipped_qty += $shipment_qty;
                                                    @endphp
                                                    {{$shipment_qty}}
                                                </td>
                                                <td>{{date('d M, Y',strtotime($order->shipment_date))}}</td>
                                                <td>{{ $shipment_status }}</td>
                                                <td>{{$excess_short_cut}}</td>
                                                <td>{{$excess_short_cut_percent}}</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <th colspan="2" align="center">Total</th>
                                            <th>{{$total_order_qty}}</th>
                                            <th>{{$total_shipped_qty}}</th>
                                            <th colspan="4">&nbsp;</th>
                                        </tr>
                                    @else
                                        <tr>
                                            <td colspan="8" align="center">No Data
                                            <td>
                                        </tr>
                                    @endif
                                    </tbody>
                                @else
                                    <tbody class="po-shipment-report-table">
                                    <tr>
                                        <td colspan="8" align="center">No Data</td>
                                    </tr>
                                    </tbody>
                                @endif
                                <tfoot>
                                @if($order_report)
                                    @if($order_report->total() > 15)
                                        <tr>
                                            <td colspan="8"
                                                align="center">{{ $roles->appends(request()->except('page'))->links() }}</td>
                                        </tr>
                                    @endif
                                @endif
                                </tfoot>
                            </table>
                        </div>
                        <div class="loader"></div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('protracker/custom.js') }}"></script>
    <script>
        const buyerSelectDom = $('[name="buyer_id"]');
        const orderSelectDom = $('[name="order_id"]');
        const poSelectDom = $('[name="purchase_order_id"]');

        buyerSelectDom.change(() => {
            orderSelectDom.empty().val('').select2();
            poSelectDom.empty().val('').select2();

            $.ajax({
                url: "/utility/get-styles-for-select2-search",
                type: "get",
                data: {'buyer_id': buyerSelectDom.val()},
                success({results}) {
                    orderSelectDom.empty();
                    orderSelectDom.html(`<option selected>SELECT</option>`);
                    results.forEach(el => {
                        let html = `<option value="${el.id}">${el.text}</option>`;
                        orderSelectDom.append(html);
                    });
                }
            })
        });
        orderSelectDom.change(() => {
            poSelectDom.empty().val('').select2();

            $.ajax({
                url: "/utility/get-pos-for-select2-search",
                type: "get",
                data: {'order_id': orderSelectDom.val()},
                success({results}) {
                    poSelectDom.empty();
                    poSelectDom.html(`<option selected>SELECT</option>`);
                    results.forEach(el => {
                        let html = `<option value="${el.id}">${el.text}</option>`;
                        poSelectDom.append(html);
                    });
                }
            })
        });
        poSelectDom.change(function (e) {
            e.preventDefault();
            $('.order-wise-finishing-report').empty();
            let order_id = $(this).val();
            if (order_id) {
                $('.loader').html(loader);
                $.ajax({
                    type: 'GET',
                    url: '/finishing-report-order-wise-view/' + order_id,
                    success: function (response) {
                        $('.loader').empty();
                        if (Object.keys(response.report_size_wise).length > 0) {
                            $.each(response.report_size_wise, function (index, report) {
                                let resultRows = '<tr><td>' + report.color + '</td><td>' + report.size + '</td><td>' + report.size_order_qty + '</td><td>'
                                    + report.size_cutting_qty + '</td><td>' + report.total_input + '</td><td>' + report.total_output + '</td><td>' + report.finished_qty + '</td><td>' + report.in_line_wip + '</td></tr>';

                                $('.order-wise-finishing-report').append(resultRows);
                            });
                            let totalData = response.total_report;
                            let totalRow = '<tr style="font-weight:bold"><td colspan="2">Total</td><td>' + totalData.total_order_qty + '</td><td>'
                                + totalData.total_cutting_qty + '</td><td>' + totalData.total_total_input + '</td><td>' + totalData.total_total_output + '</td><td>'
                                + totalData.total_finished_qty + '</td><td>' + totalData.total_in_line_wip + '</td></tr>';
                            $('.order-wise-finishing-report').append(totalRow);
                        } else {
                            let resultRows = '<tr><td colspan="8" class="text-danger text-center" >Not found</td></tr>';
                            $('.order-wise-finishing-report').append(resultRows);
                        }
                    }
                });
            }
        });
    </script>
@endsection
