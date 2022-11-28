@php
  $tableHeadColorClass = 'tableHeadColor';
  if (isset($type) || request()->has('type') || request()->route('type')) {
    $tableHeadColorClass = '';
  }
@endphp
@extends('cuttingdroplets::layout')
@section('title', 'Buyer Wise Cutting Production Report')
@section('content')
<div class="padding">
  <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header text-center">
          <h2>Buyer Wise Cutting Production Report
            <span class="pull-right">
              <a href="" id="buyer-wise-pdf">
                <i style="color: #DC0A0B" class="fa fa-file-pdf-o"></i>
              </a>
              |
              <a id="buyer-wise-xls" href="">
                <i style="color: #0F733B" class="fa fa-file-excel-o"></i>
              </a>
            </span>
          </h2>
        </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body cutting-color-wise">
          @include('partials.response-message')
          <div class="form-group">
            <div class="row m-b">
              <div class="col-sm-2">
                <label>Buyer</label>
                {!! Form::select('buyer_id', $buyers, null, ['id' => 'cllr-buyer-select', 'class' => 'clr-buyer-select form-control form-control-sm select2-input', 'placeholder' => 'Select a Buyer',  'onChange=donloadLink()']) !!}
              </div>
            </div>
          </div>

          <div id="parentTableFixed" class="table-responsive">
            <table class="reportTable" id="fixTable">
              <thead>
                <tr>
                  <th>Order/Style</th>
                  <th>PO</th>
                  <th>Color Name</th>
                  <th>Order Quantity</th>
                  <th>Today's Cutting</th>
                  <th>Total Cutting</th>
                  <th>Left Quantity</th>
                  <th>Extra Cuttting (%)</th>
                </tr>
              </thead>
              <tbody class="color-wise-report">
                  <tr>
                    <td colspan="14" align="center"><td>
                  </tr>
              </tbody>
            </table>
            <span class="loader"></span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  function donloadLink()
  {
      var buyer_id = document.getElementById("cllr-buyer-select").value;
      document.getElementById("buyer-wise-pdf").href="{{ url('/buyer-wise-cutting-report-download/pdf') }}/"+buyer_id;
      document.getElementById("buyer-wise-xls").href="{{ url('/buyer-wise-cutting-report-download/xls') }}/"+buyer_id;
  }
</script>
@endsection
