@extends('iedroplets::layout')
@section('title', 'Date wise cutting target')
@section('styles')
  <style type="text/css">
    @media screen and (-webkit-min-device-pixel-ratio: 0) {
      input[type=date].form-control form-control-sm {
          height: 33px !important;
      }
    }
    .select2-container .select2-selection--single {
      height: 33px;
      padding-top: 3px !important;
    }
    .form-control form-control-sm {
      line-height: 1;
      min-height: 1rem !important;
    }
  </style>
@endsection
@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
            <h2>Cutting Target Update</h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body">
          @include('partials.response-message')
          <div class="box-body table-responsive">
            <form autocomplete="off" action="{{ url('/date-wise-cutting-target-post') }}" method="post">
              @csrf
              <div class="form-group">
                <div class="row m-b">
                    <div class="col-sm-2">
                      <label>Target Date</label>
                      <input type="date" class="form-control form-control-sm" style="height: 15px" disabled="disabled" value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-sm-2">
                      <label>Cutting Floor</label>
                      {!! Form::select('cutting_floor_id', $cutting_floors, null, ['class' => 'cutting-floor-select form-control form-control-sm select2-input', 'placeholder' => 'Select a Floor']) !!}
                    </div>
                  </div>
              </div>

              <table class="reportTable">
                <thead>
                 <tr>
                    <th>Table No.</th>
                    <th>Target</th>
                    <th>Manpower</th>
                    <th>WH</th>
                    {{-- <th>+Add Man Minute</th>
                    <th>-Substract Man Min</th>--}}
                    <th>Total NPT</th>
                  </tr>
                </thead>
                <tbody class="cutting-target-form">

                </tbody>
              </table>
            </form>
           </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <style type="text/css">
    @media screen and (-webkit-min-device-pixel-ratio: 0){
      input[type=date].form-control form-control-sm{
        line-height: 1;
      }
    }
  </style>
@endsection
@section('scripts')
<script src="{{ asset('protracker/custom.js') }}"></script>
<script src="{{ asset('protracker/inspection-unit-price.js') }}"></script>
@endsection