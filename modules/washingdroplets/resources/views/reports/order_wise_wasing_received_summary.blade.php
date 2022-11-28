@php
  $tableHeadColorClass = 'tableHeadColor';
  if (isset($type) || request()->has('type') || request()->route('type')) {
    $tableHeadColorClass = '';
  }
@endphp
@extends('washingdroplets::layout')
@section('title', 'Order Wise Received Summary')

@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
            @php
              $currentPage = $order_wise_report ? $order_wise_report->currentPage() : 1;
            @endphp
            <h2>All Order's Washing Sent &amp; Received Summary || {{ date("D\ - F d- Y") }} <span class="pull-right"><a
                    href="{{url('order-wise-received-from-wash-download/pdf/'.$currentPage)}}"><i style="color: #DC0A0B"
                                                                                                  class="fa fa-file-pdf-o"></i></a> | <a
                    href="{{url('order-wise-received-from-wash-download/xls/'.$currentPage)}}"><i style="color: #0F733B"
                                                                                                  class="fa fa-file-excel-o"></i></a></span>
            </h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body">
            <div id="parentTableFixed" class="table-responsive">
              <table class="reportTable" id="fixTable">
                @include('washingdroplets::reports.table.order_wise_wasing_received_summary_table')
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
