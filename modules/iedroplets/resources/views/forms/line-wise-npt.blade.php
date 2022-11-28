@extends('iedroplets::layout')
@section('title', 'Line Wise Target/Manpower/Input Plan Update')
@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
            <h2>Line Wise Target/Manpower/Input Plan Update</h2>
            @include('partials.response-message')
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body">
            <div class="form-group">
              <div class="row m-b">
                  <div class="col-sm-2">
                    <label>Floor</label>
                    {!! Form::select('floor_id', $floors, null, ['class' => 'npt-floor-select form-control form-control-sm select2-input', 'placeholder' => 'Select a Floor']) !!}
                  </div>
                </div>
            </div>
            <div class="box-body table-responsive">
	            <form autocomplete="off" action="{{ url('/get-line-wise-npt-update-action') }}" method="post">
	              @csrf

	              <table class="reportTable">
                  <thead>
                    <tr style="font-weight:bold;">
                      <th>Line</th>
                      <th>OP</th>
                      <th>Helper</th>
                      <th>Working Hour</th>
                      <th>+Add Man Minute</th>
                      <th>- Substract Man Min</th>
                      <th>Machine Breakdown</th>
                      <th>Shading Prob.</th>
                      <th>Late Decision</th>
                      <th>Cutting Prob.</th>
                      <th> Input Prob</th>
                      <th>Late to Get M/C</th>
                      <th>Print Mistakes</th>
                      {{--<th style="background-color:#666666;color:#FFFF00;">Current Line Status</th>--}}
                    </tr>
                  </thead>
                  <tbody class="npt-floor-select-update-row">

                  </tbody>
                </table>

                <div class="sutton-area">
	                  <button type="submit" style="display: none; margin-left: 500px" class="btn white sewing-target-btn">Update</button>
                </div>

	            </form>
           </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
