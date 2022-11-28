@extends('iedroplets::layout')
@section('title', 'Sewing Line target')
@section('styles')
<style type="text/css">
  @media screen and (-webkit-min-device-pixel-ratio: 0) {
    input[type=date].form-control form-control-sm {
      height: 33px !important;
      line-height: 1;
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

  .tr-height > td {
    padding-left: 15px !important;
    font-size: 12px !important;
  }
</style>
@endsection
@section('content')
<div class="padding sewing-line-target-page">
  <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header text-center">
          <h2>Line Wise Target/Manpower/Input Plan Update</h2>
        </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          @include('partials.response-message')
          <div class="box-body table-responsive">

            {!! Form::open(['url' => '/v2/line-target-action', 'method' => 'POST']) !!}
              <div class="form-group">
                <div class="row m-b">
                  <div class="col-sm-3">
                    <label>Target Date</label>
                    {!! Form::date('target_date', date('Y-m-d'), ['class' => 'form-control form-control-sm target-date'] ) !!}
                  </div>
                  <div class="col-sm-3">
                    <label>Floor</label>
                    {!! Form::select('floor_id', $floors, null, ['class' => 'floor-select form-control form-control-sm
                    select2-input', 'placeholder' => 'Select a Floor']) !!}
                  </div>
                </div>
              </div>

              <table class="reportTable sewing-line-target-table">
                <thead>
                  <tr>
                    <th width="5%">Line</th>
                    <th width="7%">Operator</th>
                    <th width="7%">Helper</th>
                    <th width="8%">SMV</th>
                    <th width="10%">Required Efficiency(&#37;)</th>
                    <th width="10%">Hourly Target</th>
                    <th width="8%">Working Hours</th>
                    <th width="10%">Total Target</th>
                    <th width="15%">Input&nbsp;Requirement Plan</th>
                    <th width="10%">Remarks</th>
                    <th width="10%">Action</th>
                  </tr>
                </thead>
                <tbody class="sewing-line-target-form" style="font-size: 11px !important">
                </tbody>
              </table>
            {!! Form::close() !!}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@push('script-head')
<script type="text/javascript">
  $(document).on('change', '.floor-select, .target-date', function (e) {
    e.preventDefault();
    $('.sewing-line-target-form').empty();
    $('.sewing-target-btn').hide();
    var floor_id = $('.floor-select').val();
    var target_date = $('.target-date').val();
    if (floor_id && target_date) {
      $.ajax({
        type: 'GET',
        url: '/v2/get-line-target-form/' + floor_id + '/' + target_date,
        dataType: 'json',
        success: function (response) {
          $('.sewing-target-btn').show();
          if (response) {
            $('.sewing-line-target-form').html(response.view);
          }
        }

      });
    }
  });

  const inputFields = [
    '[name="operator[]"]',
    '[name="helper[]"]',
    '[name="smv[]"]',
    '[name="efficiency[]"]',
    '[name="wh[]"]',
  ];

  inputFields.forEach(element => {
    $(document).on('keyup', element, function(e) {
      let thisValue = $(this).val();
      if (thisValue) {
        calculateTarget($(this));
      }
    });
  });

  function calculateTarget(thisHtml)
  {
    let operator = thisHtml.parents('tr').find('[name="operator[]"]').val();
    let helper = thisHtml.parents('tr').find('[name="helper[]"]').val();
    let smv = thisHtml.parents('tr').find('[name="smv[]"]').val();
    let efficiency = thisHtml.parents('tr').find('[name="efficiency[]"]').val();
    let wh = thisHtml.parents('tr').find('[name="wh[]"]').val();

    let hourly_target = 0;
    let day_target = 0;
    if (operator && helper && smv > 0 && efficiency) {
      hourly_target = Math.ceil(((parseInt(operator) + parseInt(helper)) * 60 * parseFloat(efficiency)) / (parseFloat(smv) * 100));
    }
    if (hourly_target && wh) {
      day_target = parseInt(hourly_target) * parseInt(wh);
    }
    thisHtml.parents('tr').find('[name="target[]"]').val(hourly_target);
    thisHtml.parents('tr').find('.hourly_target_value').html(hourly_target);
    thisHtml.parents('tr').find('.day_target_value').html(day_target);
  }

  $(document).on('click', '.duplicate-line-target', function(){
    var currentTr = $(this).closest('tr');
    var new_line = currentTr.clone().insertAfter(currentTr);
  });
  // delete duplicate line target row
  $(document).on('click', '.del-duplicate-line-target', function(){
    if(confirm('are you sure to delete this?') == true) {
      var currentTr = $(this).closest('tr').remove();
    }
  });
</script>
@endpush