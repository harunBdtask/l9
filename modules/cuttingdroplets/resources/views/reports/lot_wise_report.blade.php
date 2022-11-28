@php
$tableHeadColorClass = 'tableHeadColor';
if (isset($type) || request()->has('type') || request()->route('type')) {
$tableHeadColorClass = '';
}
@endphp
@extends('cuttingdroplets::layout')
@section('title', 'Lot Wise Cutting Production Report')
@section('content')
<div class="padding">
  <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header text-center">
          <h2>Lot Wise Cutting Production Report
            <span class="pull-right">
              <a download-type="pdf" class="lot-wise-cutting-report-dwnld-btn">
                <i style="color: #DC0A0B" class="fa fa-file-pdf-o"></i>
              </a>
              |
              <a download-type="xls" class="lot-wise-cutting-report-dwnld-btn">
                <i style="color: #0F733B" class="fa fa-file-excel-o"></i>
              </a>
            </span>
          </h2>
        </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body cutting-lot">
          @include('partials.response-message')
          <div class="form-group">
            <div class="row m-b">
              <div class="col-sm-2">
                <label>Buyer</label>
                {!! Form::select('buyer_id', [], null, ['class' => 'form-control form-control-sm', 'placeholder' => 'Select Buyer']) !!}
              </div>
              <div class="col-sm-2">
                <label>Order/Style</label>
                {!! Form::select('order_id', [], null, ['class' => 'form-control form-control-sm', 'placeholder' => 'Select Style'])
                !!}
              </div>
              <div class="col-sm-2">
                <label>PO</label>
                {!! Form::select('purchase_order_id', [], null, ['class' => 'form-control form-control-sm', 'placeholder' => 'Select
                PO']) !!}
              </div>
              <div class="col-sm-2">
                <label>Color</label>
                {!! Form::select('color_id', [], null, ['class' => 'form-control form-control-sm', 'placeholder' => 'Select Color']) !!}
              </div>
            </div>
          </div>

          <div id="parentTableFixed" class="table-responsive">
            <table class="reportTable" id="fixTable">
              <thead>
                <tr>
                  <th>Serial No.</th>
                  <th>Lot No.</th>
                  <th>Size</th>
                  <th>Quantity</th>
                </tr>
              </thead>
              <tbody class="lot-wise-report">
              </tbody>
            </table>
            <span class="loader"></span>
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
  $(function () {
			const buyerSelectDom = $('[name="buyer_id"]');
			const orderSelectDom = $('[name="order_id"]');
			const orderStyleNoDom = $('[name="order_style_no"]');
			const poSelectDom = $('[name="purchase_order_id"]');
			const colorSelectDom = $('[name="color_id"]');
			const lotWiseReportDom = $('.lot-wise-report');
			let orders;

			buyerSelectDom.select2({
				ajax: {
					url: '/utility/get-buyers-for-select2-search',
					data: function (params) {
						return {
							search: params.term,
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
				placeholder: 'Select Buyer',
				allowClear: true
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
						orders = data;
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

			$(document).on('change', '[name="order_id"]', function () {
				let orderId = $(this).val();
				$.each(orders, (key, val) => {
					if (val.id == orderId) {
						orderStyleNoDom.val(val.order_style_no);
					}
				})
			});

			poSelectDom.select2({
				ajax: {
					url: '/utility/get-pos-for-select2-search',
					data: function (params) {
						const orderId = orderSelectDom.val();
						return {
							order_id: orderId,
							search: params.term
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
				placeholder: 'Select PO',
				allowClear: true
			});

			colorSelectDom.select2({
				ajax: {
					url: '/utility/get-colors-for-po-select2-search',
					data: function (params) {
						const purchaseOrderId = poSelectDom.val();
						return {
							purchase_order_id: purchaseOrderId,
							search: params.term
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
				placeholder: 'Select Color',
				allowClear: true
			});

			$(document).on('change', '[name="buyer_id"]', function (e) {
				let orderId = orderSelectDom.val();
				let poId = poSelectDom.val();
				let colorId = colorSelectDom.val();
				if (orderId) {
					orderSelectDom.val('').change();
				}
				if (poId) {
					poSelectDom.val('').change();
				}
				if (colorId) {
					colorSelectDom.val('').change();
				}
				lotWiseReportDom.empty();
			});

			$(document).on('change', '[name="order_id"]', function (e) {
				let poId = poSelectDom.val();
				let colorId = colorSelectDom.val();
				if (poId) {
					poSelectDom.val('').change();
				}
				if (colorId) {
					colorSelectDom.val('').change();
				}
				lotWiseReportDom.empty();
			});

			$(document).on('change', '[name="po_id"]', function (e) {
				let colorId = colorSelectDom.val();
				if (colorId) {
					colorSelectDom.val('').change();
				}
				lotWiseReportDom.empty();
			});


			$(document).on('change', '[name="color_id"]', function (e) {
				e.preventDefault();
				lotWiseReportDom.empty();
				let po_id = poSelectDom.val();
				let color_id = $(this).val();
				if (po_id && color_id) {
					$('.loader').html(loader);
					$.ajax({
						type: 'GET',
						url: '/get-lot-wise-cutting-report',
						data: {
							purchase_order_id: po_id,
							color_id: color_id,
						},
						success: function (response) {
							$('.loader').empty();
							let i = 0;
							let tr = '';
							let totalQty = 0;
							if (Object.keys(response).length > 0) {
								$.each(response, function (index, report) {
                  totalQty += parseInt(report.qunatity || 0)
									tr += [
										'<tr>',
										'<td>' + ++i + '</td>',
										'<td>' + report.lot_no + '</td>',
										'<td>' + report.size_name + '</td>',
										'<td>' + report.qunatity + '</td>',
										'</tr>'
									].join();
								});
                tr += [
										'<tr>',
										'<th colspan="3">Total</th>',
										'<th>' + totalQty + '</th>',
										'</tr>'
									].join();
                lotWiseReportDom.html(tr);
							} else {
								tr += '<tr><td colspan="4" class="text-danger tr-height text-center" >Not found</td></tr>';
								lotWiseReportDom.html(tr);
							}
						},
						error: function (error) {
							$('.loader').empty();
							let tr = '<tr><td colspan="4" class="text-danger tr-height text-center" >Not found</td></tr>';
							lotWiseReportDom.html(tr);
						}
					});
				}
			});

			// lot wise cutting report download
			$(document).on('click', '.lot-wise-cutting-report-dwnld-btn', function () {
				let purchase_order_id = poSelectDom.val();
				let color_id = colorSelectDom.val();
				let type = $(this).attr("download-type");
				if (purchase_order_id && color_id && type) {
					let href = window.location.protocol + "//" + window.location.host + "/lot-wise-cutting-report-download?type="  + type + '&purchase_order_id=' + purchase_order_id + '&color_id=' + color_id;
					window.open(href, '_blank');
				} else {
					alert('Please view report first');
				}
			});
		});

</script>
@endsection
