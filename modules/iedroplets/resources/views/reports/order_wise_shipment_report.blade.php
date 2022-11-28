@php
  $tableHeadColorClass = 'tableHeadColor';
  if (isset($type) || request()->has('type') || request()->route('type')) {
    $tableHeadColorClass = '';
  }
@endphp
@extends('iedroplets::layout')
@section('title', 'All Orders Shipment Summary')
@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
            @php
              $currentPage = $shipments ? $shipments->currentPage() : 1;
            @endphp
            <h2>All Order's Shipment Summary || {{ date("jS F, Y") }} <span class="pull-right"><a
                    href="{{url('/all-orders-shipment-summary-report-download?type=pdf&order_id='.$order_id.'&page='.$currentPage)}}"><i
                      style="color: #DC0A0B"
                      class="fa fa-file-pdf-o"></i></a> | <a
                    href="{{url('/all-orders-shipment-summary-report-download?type=xls&order_id='.$order_id.'&page='.$currentPage)}}"><i
                      style="color: #0F733B"
                      class="fa fa-file-excel-o"></i></a></span>
            </h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body">
            <div class="row">
              <div class="col-md-12">
                {!! Form::open(['url' => '/all-orders-shipment-summary', 'method' => 'get', 'class' => 'noprint']) !!}
                <div class="col-md-2">
                  <div class="form-group">
                    <label>Style/Order</label>
                    {!! Form::select('order_id', $orders, $order_id, ['class' => 'form-control form-control-sm select2-input','id' => 'booking_no', 'placeholder' => 'Select a Booking', 'onchange' => 'this.form.submit();']) !!}
                  </div>
                </div>
                {!! Form::close() !!}
              </div>
            </div>
            <div id="parentTableFixed" class="table-responsive">

              @include('iedroplets::reports.order_wise_shipment_report_table')

            </div>

            <div class="text-center">
              {{ $shipments->links() }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('scripts')
  <script>
    $(function () {
      $('#booking_no').select2({
        ajax: {
          url: '/get-booking-nos-for-select2-search',
          delay: 250,
          data: function (params) {
            var query = {
              search: params.term,
              type: 'public'
            }

            // Query parameters will be ?search=[term]&type=public
            return query;
          },
          processResults: function (data) {
            return data;
          },
          cache: true
        },
        placeholder: 'Search Style/Order',
        minimumInputLength: 1
      });
    });
  </script>
@endsection
