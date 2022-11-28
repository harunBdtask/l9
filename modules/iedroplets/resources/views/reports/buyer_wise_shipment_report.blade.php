@php
    $tableHeadColorClass = 'tableHeadColor';
    if (isset($type) || request()->has('type') || request()->route('type')) {
      $tableHeadColorClass = '';
    }
@endphp
@extends('iedroplets::layout')
@section('title', 'Buyer Wise Shipment Report')
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header text-center">
                        <h2>Buyer Wise Shipment Report || {{ date("jS F, Y") }} <span class="pull-right"><a
                                    download-type="pdf"
                                    class="buyer-wise-shipment-report-dwnld-btn"><i
                                        style="color: #DC0A0B" class="fa fa-file-pdf-o"></i></a> | <a
                                    download-type="xls"
                                    class="buyer-wise-shipment-report-dwnld-btn"><i
                                        style="color: #0F733B" class="fa fa-file-excel-o"></i></a></span></h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body buyer-wise-shipment">

                        <div class="form-group">
                            <div class="row m-b">
                                <div class="col-sm-2">
                                    <label>Buyer</label>
                                    {!! Form::select('buyer_id', $buyers, null, ['class' => 'buyer-select form-control form-control-sm select2-input', 'placeholder' => 'Select a Buyer']) !!}
                                </div>
                            </div>
                        </div>

                        <div id="parentTableFixed" class="table-responsive">
                            <table class="reportTable" id="fixTable">
                                <thead>
                                <tr>
                                    <th>Style/Order</th>
                                    <th>PO</th>
                                    <th>Order Qty</th>
                                    <th>Shipout Qty</th>
                                    <th>Shipout Balance Qty</th>
                                    <th>Sewing Balance With 3%</th>
                                    <th>Inspection Date</th>
                                    <th>Total Export Value</th>
                                    <th>Total Shipout Value</th>
                                    <th>Total Export Value Balance Value</th>
                                </tr>
                                </thead>
                                <tbody class="buyer-wise-shipment-report">
                                </tbody>
                            </table>
                            <div class="loader"></div>
                        </div>
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
        buyerSelectDom.change(function () {
            let buyer_id = $(this).val();
            if (buyer_id) {
                $('.loader').html(loader);
                $('.buyer-wise-shipment-report').empty();
                $.ajax({
                    type: 'GET',
                    url: '/get-buyer--wise-shipment-report/' + buyer_id,
                    success: function (response) {
                        $('.loader').empty();
                        var resultRows;
                        if (Object.keys(response).length > 0) {
                            $.each(response.buyer_wise_shipment, function (index, report) {
                                resultRows += '<tr><td>' + report.style_name + '</td><td>' + report.order + '</td><td>' + report.order_qty
                                    + '</td><td>' + report.shipment_qty + '</td><td>' + report.shipment_balance_qty + '</td><td>' + report.sewing_balance_qty
                                    + '</td><td>' + report.shipment_date + '</td><td>' + report.total_export_value + '</td><td>' + report.total_shipout_value + '</td><td>'
                                    + report.total_export_balance + '</td></tr>';
                            });

                            var total_row = response.total_rows;
                            resultRows += '<tr style="font-weight:bold"><td colspan="2"><b>Total</b></td><td>' + total_row.total_color_order_qty + '</td><td>' + total_row.total_shipment_qty
                                + '</td><td>' + total_row.total_shipment_balance_qty + '</td><td>' + total_row.total_sewing_balance_qty + '</td><td></td><td>' + total_row.total_total_export_value +
                                '</td><td>' + total_row.total_total_shipout_value + '</td><td>' + total_row.total_total_export_balance + '</td></tr>';
                        } else {
                            resultRows = '<tr><td colspan="11" class="text-danger text-center" >Not found</td></tr>';
                        }
                        $('.buyer-wise-shipment-report').html(resultRows);
                    }
                });
            }
        });
    </script>
@endsection
