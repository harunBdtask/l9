@extends('sewingdroplets::layout')
@section('title', 'Line Wise Capacity Entry')
@section('content')
  <div class="padding sewing-line-capacity-page">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
            <h2>Line Wise Capacity Entry</h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body">
            @include('partials.response-message')
            <div class="box-body">

              <form autocomplete="off" action="{{ url('/line-capacity-entry-action') }}" method="post">
                @csrf
                <div class="form-group">
                  <div class="row m-b">
                    <div class="col-sm-2">
                      <label>Floor</label>
                      {!! Form::select('floor_id', $floors, null, ['class' => 'line-capacity-floor-select form-control form-control-sm select2-input', 'placeholder' => 'Select a Floor']) !!}
                    </div>
                    <div class="col-sm-offset-9 col-sm-1">
                      <label>Copy</label>
                      {!! Form::checkbox('copy_status', 'copy_status') !!}
                    </div>
                  </div>
                </div>
                <div class=" table-responsive">
                  <table class="reportTable sewing-line-capacity-table">
                    <thead>
                    <tr>
                      <th width="5%">Line</th>
                      <th width="6%">Operator</th>
                      <th width="6%">Helper</th>
                      <th width="6%">Absent(%)</th>
                      <th width="6%">Working Hour</th>
                      <th width="6%">Line Efficiency(%)</th>
                      <th width="6%">Capacity Available(min)</th>
                    </tr>
                    </thead>
                    <tbody class="sewing-line-capacity-form" style="font-size: 11px !important">
                    </tbody>
                  </table>
                  <tr>
                    <td colspan="5">
                      <button type="submit" style="display: none; margin-left: 500px"
                              class="btn white line-capacity-submit-btn">Submit
                      </button>
                    </td>
                  </tr>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('scripts')
<script>
  $(document).on('change', '.line-capacity-floor-select', function (e) {
    e.preventDefault();
    $('.sewing-line-capacity-form').empty();
    $('.line-capacity-submit-btn').hide();
    var floor_id = $('.line-capacity-floor-select').val();
    if (floor_id) {
        $.ajax({
            type: 'GET',
            url: '/get-floor-wise-capacity-entry-form/' + floor_id,
            dataType: 'json',
            success: function (response) {
                $('.line-capacity-submit-btn').show();
                if (response) {
                    $('.sewing-line-capacity-form').html(response.view)
                }
            }
        });
    }
  });

  var copyStatus = false;

  var formElements = {
    'operator' : '[name="operator[]"]',
    'helper' : '[name="helper[]"]',
    'absent_percent' : '[name="absent_percent[]"]',
    'working_hour' : '[name="working_hour[]"]',
    'line_efficiency' : '[name="line_efficiency[]"]',
    'capacity_available_minutes' : '[name="capacity_available_minutes[]"]',
  };

  $(document).on("click", '[name="copy_status"]', (e) => {
    copyStatus = !copyStatus;
  });

  Object.keys(formElements).forEach(key => {
    $(document).on("keyup", formElements[key], (e) => {
      let elemClass = `.${key}`;
      pasteCopiedData(e.target.value, elemClass)
      calculateCapacityMinutes()
    });
  })

  function pasteCopiedData(value, elemClass) {
    if (copyStatus) {
      let allElems = document.querySelectorAll(elemClass);
      allElems.forEach(item => {
        item.value = value;
      });
    }
  }

  function calculateCapacityMinutes() {
    let allElems = document.querySelectorAll('.operator');

    allElems.forEach((item, key) => {
      let operator = parseFloat($(".sewing-line-capacity-form tr:eq(" + key + ")").find('[name="operator[]"]').val()) || 0;
      let helper = parseFloat($(".sewing-line-capacity-form tr:eq(" + key + ")").find('[name="helper[]"]').val()) || 0;
      let working_hour = parseFloat($(".sewing-line-capacity-form tr:eq(" + key + ")").find('[name="working_hour[]"]').val()) || 0;
      let line_efficiency = parseFloat($(".sewing-line-capacity-form tr:eq(" + key + ")").find('[name="line_efficiency[]"]').val()) || 0;
      let capacity_available_minutes = Math.round(((operator + helper) * working_hour * 60 * line_efficiency) / 100)

      $(".sewing-line-capacity-form tr:eq(" + key + ")").find('[name="capacity_available_minutes[]"]').val(capacity_available_minutes)
    });
  }
</script>
@endsection