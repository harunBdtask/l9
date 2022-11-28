@php
  $tableHeadColorClass = 'tableHeadColor';
  if (isset($type) || request()->has('type') || request()->route('type')) {
    $tableHeadColorClass = '';
  }
@endphp
@extends('inputdroplets::layout')
@section('title', 'All PO\'s Input Summary')
@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
            @php
              $currentPage = $order_wise_input ? $order_wise_input->currentPage() : 1;
              $bookingNoId = request('booking_no_id');
            @endphp
            <h2>All PO's Input Summary || {{ date("jS F, Y") }}
              <span class="pull-right">
                <a href="{{ url('/order-sewing-line-input-download?type=pdf'.'&page='.$currentPage.'&booking_no_id='.request('booking_no_id')) }}">
                  <i style="color: #DC0A0B" class="fa fa-file-pdf-o"></i></a>
                  |
                  <a href="{{ url('/order-sewing-line-input-download?type=xls'.'&page='.$currentPage.'&booking_no_id='.request('booking_no_id')) }}">
                    <i style="color: #0F733B" class="fa fa-file-excel-o"></i>
                  </a>
                </span>
            </h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body">
            {!! Form::open(['url' => '/order-sewing-line-input', 'method' => 'get']) !!}
            <div class="form-group">
              <div class="row m-b">
                <div class="col-sm-3">
                  {!! Form::select('booking_no_id', $booking_nos, request('booking_no_id') ?? null, ['id' => 'cllr-buyer-select', 'class' => 'clr-buyer-select form-control form-control-sm select2-input', "onChange" => "this.form.submit()"]) !!}
                </div>
              </div>
            </div>
            {!! Form::close() !!}

            <div id="parentTableFixed" class="table-responsive">
              <table class="reportTable" id="fixTable">
                @include('inputdroplets::reports.tables.order-wise-input-tables')
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection


@section('scripts')
  <script src="{{ asset('/js/tableHeadFixer.js') }}"></script>
  <script>
    $(document).ready(function () {
      $("#fixTable").tableHeadFixer();


      function setSelect2() {
        $('select').select2();
        registerSelect2Async();
      }


      const registerSelect2Async = () => {

        $('#cllr-buyer-select').select2({
          ajax: {
            url: '/get-booking-options',
            data: params => ({
              search: params.term
            }),
            processResults: (data, params) => {
              let results;
              return {
                results: data,
                pagination: {
                  more: false
                }
              }
            },
            delay: 250
          }
        })
      }

      setSelect2();
    });
  </script>
@endsection
