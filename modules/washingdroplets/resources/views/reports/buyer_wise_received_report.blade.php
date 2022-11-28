@php
  $tableHeadColorClass = 'tableHeadColor';
  if (isset($type) || request()->has('type') || request()->route('type')) {
    $tableHeadColorClass = '';
  }
@endphp
@extends('washingdroplets::layout')
@section('title', 'Buyer Wise Washing Report')
@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
            @php
              $currentPage = $reportdata ? $reportdata->currentPage() : 1;
            @endphp
            <h2>Buyer Wise Washing Report || {{ date("jS F, Y") }} <span class="pull-right"><a
                    href="{{ $buyer_id ? url('/buyer-wise-receievd-from-wash-download/pdf/'.$buyer_id.'/'.$currentPage) : '#' }}"><i
                      style="color: #DC0A0B" class="fa fa-file-pdf-o"></i></a> | <a
                    href="{{ $buyer_id ? url('/buyer-wise-receievd-from-wash-download/xls/'.$buyer_id.'/'.$currentPage) : '#' }}"><i
                      style="color: #0F733B" class="fa fa-file-excel-o"></i></a></span></h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body color-sewing-output">
            <form action="{{ url('/buyer-wise-receievd-from-wash') }}" method="get">
              @csrf
              <div class="form-group">
                <div class="row m-b">
                  <div class="col-sm-2">
                    <label>Buyer</label>
                    {!! Form::select('buyer_id', $buyers, $buyer_id ?? null, ['class' => 'form-control form-control-sm select2-input', 'placeholder' => 'Select a Buyer', 'onchange' => 'this.form.submit();']) !!}
                  </div>
                </div>
              </div>
            </form>

            <div id="parentTableFixed" class="table-responsive">
              <table class="reportTable" id="fixTable">
                @include('washingdroplets::reports.table.buyer_wise_received_report_table')
              </table>
            </div>

            <div class="loader"></div>

          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
