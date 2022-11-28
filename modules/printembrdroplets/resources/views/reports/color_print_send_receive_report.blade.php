@php
$tableHeadColorClass = 'tableHeadColor';
if (isset($type) || request()->has('type') || request()->route('type')) {
$tableHeadColorClass = '';
}
@endphp
@extends('printembrdroplets::layout')
@section('title', 'Buyer Wise Print Send & Receive Report')
@section('content')
<div class="padding buyer-wise-send-received-report-page">
  <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header text-center">
          <h2>Buyer Wise Print Send & Receive Report || {{ date("jS F, Y") }} <span class="pull-right"><a
                id="buyer-wise-pdf"><i style="color: #DC0A0B" class="fa fa-file-pdf-o"></i></a> | <a
                id="buyer-wise-xls"><i style="color: #0F733B" class="fa fa-file-excel-o"></i></a></span></h2>
        </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body print-color-wise">
          <div class="form-group">
            <div class="row m-b">
              <div class="col-sm-2">
                <label>Buyer</label>
                {!! Form::select('buyer_id', $buyers, null, ['class' => 'buyer-select-cwp form-control form-control-sm select2-input',
                'placeholder' => 'Select a Buyer']) !!}
              </div>
            </div>
          </div>

          <div id="parentTableFixed" class="table-responsive">
            <table class="reportTable">
              <thead>
                <tr>
                  <th>Style</th>
                  <th>PO</th>
                  <th>Order Qty</th>
                  <th>Cutting Qty</th>
                  <th>Cutting WIP</th>
                  <th>Total Send</th>
                  <th>Total Recieved</th>
                  <th>Fabric Rejection</th>
                  <th>Print Rejection</th>
                  <th>Print WIP/Short</th>
                </tr>
              </thead>
              <tbody class="buyer-wise-send-received-report1">
                <span class="loader"></span>
              </tbody>
            </table>
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
      $(document).on('change', '.buyer-select-cwp', function (e) {
        $('.buyer-wise-send-received-report1').empty();
        var buyer_id = $(this).val();
        if (buyer_id) {
          $('.loader').html(loader);
          $.ajax({
            type: 'GET',
            url: '/get-buyer-print-send-receive-report?buyer_id=' + buyer_id,
          }).done(function (response) {
            var pdf_url = '/buyer-wise-print-send-receive-report-download/pdf/'+buyer_id +'/1';
            var excel_url = '/buyer-wise-print-send-receive-report-download/excel/'+buyer_id +'/1';
            $('.loader').empty();
            if (response.status == 200) {
              $('.buyer-wise-send-received-report-page .buyer-wise-send-received-report1').html(response.html);
              $('.buyer-wise-send-received-report-page #buyer-wise-pdf').attr('href', pdf_url);
              $('.buyer-wise-send-received-report-page #buyer-wise-xls').attr('href', excel_url);
            }
            if (response.status == 500) {
              $('.buyer-wise-send-received-report-page .buyer-wise-send-received-report1').html(response.html);
              $('.buyer-wise-send-received-report-page #buyer-wise-pdf').attr('href', '');
              $('.buyer-wise-send-received-report-page #buyer-wise-xls').attr('href', '');
            }
          }).fail(function (response) {
            $('.loader').empty();
            console.log(response)
          });
        }
      });

      $(document).on('click', '.buyer-wise-send-received-report-page .pagination a', function (event) {
        event.preventDefault();
        $('li').removeClass('active');
        $(this).parent('li').addClass('active');
        var myurl = $(this).attr('href');
        var page = $(this).attr('href').split('page=')[1];
        getBuyerWisePrintingData(page);
      });

      function getBuyerWisePrintingData(page) {
        var buyer_id = $('.buyer-wise-send-received-report-page .buyer-select-cwp').val();
        if (buyer_id) {
          $('.loader').html(loader);
          $.ajax({
            type: 'GET',
            url: '/get-buyer-print-send-receive-report?buyer_id=' + buyer_id+'&page='+ page,
            success: function (response) {
              var pdf_url = '/buyer-wise-print-send-receive-report-download/pdf/'+buyer_id +'/'+page;
              var excel_url = '/buyer-wise-print-send-receive-report-download/excel/'+buyer_id +'/'+page;
              $('.loader').empty();
              if (response.status == 200) {
                $('.buyer-wise-send-received-report-page .buyer-wise-send-received-report1').html(response.html);
                $('.buyer-wise-send-received-report-page #buyer-wise-pdf').attr('href', pdf_url);
                $('.buyer-wise-send-received-report-page #buyer-wise-xls').attr('href', excel_url);
              }
              if (response.status == 500) {
                $('.buyer-wise-send-received-report-page .buyer-wise-send-received-report1').html(response.html);
                $('.buyer-wise-send-received-report-page #buyer-wise-pdf').attr('href', '');
                $('.buyer-wise-send-received-report-page #buyer-wise-xls').attr('href', '');
              }
            }
          });
        }
      }
    });
</script>
@endsection
