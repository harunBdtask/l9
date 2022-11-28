@extends('iedroplets::layout')
@section('title', 'Next Schedule Update')
@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
            <h2>Next Schedule Update</h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body">
            <div class="js-response-message text-center"> </div>
            <div class="form-group">
              <div class="row m-b">
                <div class="col-sm-2">
                  <label>Floor</label>
                  {!! Form::select('floor_id', $floors, null, ['class' => 'floor-next-schedule form-control form-control-sm select2-input', 'placeholder' => 'Select a Floor']) !!}
                </div>
              </div>
            </div>

            <table class="reportTable">
              <thead>
                <tr>
                  <th width="20%">Line</th>
                  <th width="20%">Buyer</th>
                  <th width="20%">Style/Order No</th>
                  <th width="20%">Output Finish Date</th>
                  <th width="20%">Actions</th>
                </tr>
              </thead>
              <tbody id="tbody-rows">
              </tbody>
            </table>

          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
