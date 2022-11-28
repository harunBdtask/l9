@extends('iedroplets::layout')
@section('title', 'Inspection date & quantity update')
@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
            <h2>Inspection date & quantity update</h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body">
            <div class="js-response-message text-center"> </div>
            <form>
              <div class="form-group">
                <div class="row m-b">
                    <div class="col-sm-2">
                      <label>Buyer</label>
                      {!! Form::select('buyer_id', $buyers, null, ['id' => 'buyer', 'class' => 'inspection-buyer form-control form-control-sm select2-input', 'placeholder' => 'Select a Buyer']) !!}
                    </div>

                    <div class="col-sm-2">
                      <label>Style/ Order No</label>
                      {!! Form::select('order_id', [], null, ['id' => 'style', 'class' => 'inspection-style form-control form-control-sm select2-input', 'placeholder' => 'Select Order']) !!}
                    </div>
                </div>
              </div>

              <table class="reportTable">
                <thead class="text-center">
                  <tr>
                    <th width="20%">Style</th>
                    <th width="20%">Inspection Date</th>
                    <th width="20%">Inspection Quantity</th>
                    <th width="20%">Remarks</th>
                    <th width="20%">Action</th>
                  </tr>
                </thead>
                <tbody class="inspection-update-rows">
                </tbody>
              </table>

	          </form>

          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
