@php
    $tableHeadColorClass = 'tableHeadColor';
    if (isset($type) || request()->has('type') || request()->route('type')) {
      $tableHeadColorClass = '';
    }
@endphp
@extends('finishingdroplets::layout')
@section('title', 'Color Wise Get Up Finishing Report')
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header text-center">
                        <h2>Color Wise Get Up Finishing Report || {{ date("jS F,  Y") }} <span class="pull-right"><a
                                    download-type="pdf" class="size-wise-finishing-report-dwnld-btn"><i
                                        style="color: #DC0A0B" class="fa fa-file-pdf-o"></i></a> | <a
                                    download-type="xls" class="size-wise-finishing-report-dwnld-btn"><i
                                        style="color: #0F733B" class="fa fa-file-excel-o"></i></a></span></h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body color-finishing">
                        <div class="form-group">
                            <div class="row m-b">
                                <div class="col-sm-2">
                                    <label>Buyer</label>
                                    {!! Form::select('buyer_id', $buyers, null, ['class' => 'finishing-color-select-buyer form-control form-control-sm select2-input', 'placeholder' => 'Select a Buyer']) !!}
                                </div>
                                <div class="col-sm-2">
                                    <label>Style/Order No</label>
                                    {!! Form::select('order_id', [], null, ['class' => 'form-control form-control-sm']) !!}
                                </div>
                                <div class="col-sm-2">
                                    <label>PO</label>
                                    {!! Form::select('purchase_order_id', [], null, ['class' => 'finishing-color-select-order form-control form-control-sm select2-input', 'placeholder' => 'Select a PO']) !!}
                                </div>
                                <div class="col-sm-2">
                                    <label>Color</label>
                                    {!! Form::select('color_id', [], null, ['class' => 'finishing-color-select-color form-control form-control-sm select2-input', 'placeholder' => 'Select a Color']) !!}
                                </div>
                            </div>
                        </div>

                        <div id="parentTableFixed" class="table-responsive">
                            <table class="reportTable" id="fixTable">
                                <thead>
                                <tr>
                                    <th>Size</th>
                                    <th>Order Qty</th>
                                    <th>Cutting Prod.</th>
                                    <th>WIP in Cutting/Print/Embr.</th>
                                    <th>Total Input</th>
                                    <th>Finishing Received</th>
                                    <th>Total Rejection</th>
                                    <th>In_line WIP</th>
                                    <th>Order 2 Cut(%)</th>
                                    <th>Order 2 Input(%)</th>
                                    <th>Order 2 Sewing(%)</th>
                                    <th>Get up</th>
                                    <th>+/- To Order</th>
                                    <th>Order 2 Getup (%)</th>
                                </tr>
                                </thead>
                                <tbody class="finishing-report-color-wise">

                                </tbody>
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
  $(function() {
    const buyerSelectDom = $('[name="buyer_id"]');
    const orderSelectDom = $('[name="order_id"]');
    const poSelectDom = $('[name="purchase_order_id"]');
    const colorSelectDom = $('[name="color_id"]');

    buyerSelectDom.change(() => {
        orderSelectDom.empty().val('').change();
        poSelectDom.empty().val('').change();
    });

    orderSelectDom.select2({
      ajax: {
        url: function (params) {
          return `/utility/get-styles-for-select2-search`
        },
        data: function (params) {
          const buyerId = buyerSelectDom.val();
          return {
            search: params.term,
            buyer_id: buyerId,
          }
        },
        processResults: function (data, params) {
          return {
            results: data.results,
            pagination: {
                more: false
            }
          }
        },
        cache: true,
        delay: 250
      },
      placeholder: 'Select a Style/Order',
      allowClear: true
    });

    orderSelectDom.change(() => {
      poSelectDom.empty().val('').change();
      let orderId = orderSelectDom.val();
      if (orderId) {
        $.ajax({
          url: "/utility/get-pos-for-select2-search",
          type: "get",
          data: {'order_id': orderId},
          success({results}) {
            poSelectDom.empty();
            poSelectDom.html(`<option selected>SELECT</option>`);
            results.forEach(el => {
              let html = `<option value="${el.id}">${el.text}</option>`;
              poSelectDom.append(html);
            });
          }
        })
      }
    });
    poSelectDom.change(() => {
      colorSelectDom.empty().val('').change();
      let purchaseOrderId = poSelectDom.val()
      if (purchaseOrderId) {
        $.ajax({
          url: "/utility/get-colors-for-po-select2-search",
          type: "get",
          data: {'purchase_order_id': purchaseOrderId},
          success({results}) {
            colorSelectDom.empty();
            colorSelectDom.html(`<option selected >SELECT</option>`);
            results.forEach(el => {
              let html = `<option value="${el.id}">${el.text}</option>`;
              colorSelectDom.append(html);
            });
          }
        })
      }
    });
    colorSelectDom.change(function (e) {
        e.preventDefault();
        $('.finishing-report-color-wise').empty();
        var purchase_order_id = poSelectDom.val();
        var color_id = $(this).val();
        if (purchase_order_id && color_id) {
            $('.loader').html(loader);
            $.ajax({
                type: 'GET',
                url: '/color-wise-finishing-report-action/' + purchase_order_id + '/' + color_id,
                success: function (response) {
                    $('.loader').empty();
                    if (Object.keys(response.report_size_wise).length > 0) {
                        $.each(response.report_size_wise, function (index, report) {
                            var resultRows = '<tr><td>' + report.size + '</td><td>' + report.size_order_qty + '</td><td>'
                                + report.size_cutting_qty + '</td><td>' + report.wip + '</td><td>' + report.total_input + '</td><td>' + report.finished_qty + '</td><td>' + report.rejection + '</td><td>' + report.in_line_wip + '</td><td>' + report.total_cutt_order + '</td><td>'
                                + report.order_to_input + '</td><td>' + report.ratio + '</td><td>' + report.goq + '</td><td>' + report.balance + '</td><td>' + report.gpercent + '</td></tr>';

                            $('.finishing-report-color-wise').append(resultRows);
                        });

                        var totalRow = '<tr style="font-weight:bold"><td>Total</td><td>' + response.total_report.total_order_qty + '</td><td>'
                            + response.total_report.total_cutting_qty + '</td><td>' + response.total_report.total_wip + '</td><td>' + response.total_report.total_total_input +
                            '</td><td>' + response.total_report.total_finished_qty + '</td><td>' + response.total_report.total_rejection + '</td><td>'
                            + response.total_report.total_in_line_wip + '</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>';
                        $('.finishing-report-color-wise').append(totalRow);

                    } else {
                        var resultRows = '<tr><td colspan="14" class="text-danger text-center" >Not found</td></tr>';
                        $('.finishing-report-color-wise').append(resultRows);
                    }
                }
            });
        }
    });
  });
</script>
@endsection
