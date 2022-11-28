@extends('inputdroplets::layout')
@push('style')
  <style>
    .select2-container--default .select2-selection--single {
      height: 35px !important;
      border-radius: 0px !important;
      border-color: rgba(120, 130, 140, 0.2) !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
      line-height: 35px !important;
      /*width: 120px !important;*/
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
      height: 33px;
    }
    #parentTableFixed {
      height: 400px !important;
    }
    .box-header {
      padding-top: .60rem !important;
      padding-bottom: .60rem !important;
    }
    @media screen and (-webkit-min-device-pixel-ratio: 0) {
        input[type=date].form-control form-control-sm{
        line-height: 1;
      }
    }
  </style>
@endpush
@section('title', 'Line Wise Input Inhand Report(Input)')
@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
            <h2> Line Wise Input Inhand Report(Input)
              <span class="pull-right">
                @php
                  $floorId = $floor_id;
                  $fromDate = $from_date;
                  $toDate = $to_date;
                @endphp
                <a href="{{ $floor_id
                  ? url("floor-line-wise-input-report-download?type=xls&floor_id=$floor_id&from_date=$fromDate&to_date=$toDate")
                  : '#'}}">
                  <i style="color: #0F733B" class="fa fa-file-excel-o"></i>
                </a>
              </span>
            </h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body">
            <form action="{{ url('/floor-line-wise-input-report') }}" method="get">
              <div class="form-group">
                <div class="row m-b">
                  <div class="col-sm-2">
                    <label>Floor</label>
                    {!! Form::select('floor_id', $floors, $floor_id ?? null, ['class' => 'select2-input form-control form-control-sm c-select', 'id' => 'floor', 'required', 'onchange' => 'this.form.submit();']) !!}

                    @if($errors->has('floor_id'))
                      <span class="text-danger">{{ $errors->first('floor_id') }}</span>
                    @endif
                  </div>
                </div>
              </div>
            </form>
            <div id="parentTableFixed" class="table-responsive">
              @include('inputdroplets::reports.tables.floor_line_wise_input_report_table_for_view')
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('scripts')
  <script src="{{ asset("protracker/custom.js") }}"></script>
  <script src="{{ asset('/modules/skeleton/flatkit/assets/table_head_fixer/tableHeadFixer.js') }}"></script>
  <script>
    $(document).ready(function() {
      $("#fixTable").tableHeadFixer();
    });
  </script>
@endsection
