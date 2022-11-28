@php
    $tableHeadColorClass = 'tableHeadColor';
    if (isset($type) || request()->has('type') || request()->route('type')) {
      $tableHeadColorClass = '';
    }
@endphp
@extends('finishingdroplets::layout')
@section('title', 'Order Wise Get Up Finished Report')
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header text-center">
                        <h2>Order Wise Get Up Finished Report || {{ date("jS, F Y") }}<span class="pull-right"><a
                                    download-type="pdf" class="finishing-receieved-report-dwnld-btn"><i
                                        style="color: #DC0A0B" class="fa fa-file-pdf-o"></i></a> | <a
                                    download-type="xls" class="finishing-receieved-report-dwnld-btn"><i
                                        style="color: #0F733B" class="fa fa-file-excel-o"></i></a></span></h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body color-finishing">
                        <div class="form-group">
                            <div class="row m-b">
                                <div class="col-sm-2">
                                    <label>Buyer</label>
                                    {!! Form::select('buyer_id', $buyers, null, ['class' => 'order-finishing-buyer-select form-control form-control-sm select2-input', 'placeholder' => 'Select a Buyer']) !!}
                                </div>
                                <div class="col-sm-2">
                                    <label>Style/Order No</label>
                                    {!! Form::select('order_id', [], null, ['class' => 'order-finishing-style-select form-control form-control-sm']) !!}
                                </div>
                                <div class="col-sm-2">
                                    <label>PO</label>
                                    {!! Form::select('purchase_order_id', [], null, ['class' => 'order-finishing-order-select form-control form-control-sm select2-input']) !!}
                                </div>
                            </div>
                        </div>

                        <div id="parentTableFixed" class="table-responsive">
                            <table class="reportTable" id="fixTable">
                                <thead>
                                <tr>
                                    <th>Colour</th>
                                    <th>Size</th>
                                    <th>Order Qty</th>
                                    <th>Total Cutting</th>
                                    <th>Total Input</th>
                                    <th>Total Output</th>
                                    <th>Total Finishing Received</th>
                                    <th>In_line WIP</th>
                                </tr>
                                </thead>
                                <tbody class="order-wise-finishing-report">

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
        placeholder: 'Select Style',
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
      poSelectDom.change(function (e) {
          e.preventDefault();
          $('.order-wise-finishing-report').empty();
          let order_id = $(this).val();
          if (order_id) {
              showLoader();
              $.ajax({
                  type: 'GET',
                  url: '/finishing-report-order-wise-view/' + order_id,
                  success: function (response) {
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
                  },
                  complete: function() {
                    hideLoader();
                  }
              });
          }
      });
    });
  </script>
@endsection
