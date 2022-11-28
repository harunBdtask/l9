@extends('inputdroplets::layout')
@section('title', 'Create Input Challan')
@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
            <h2>Create input challan</h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body">
             @include('partials.response-message')
             <form method="POST" action="{{ url('/create-challan-for-line')}}" accept-charset="UTF-8" onsubmit="submit.disabled = true; return true;">
              @csrf
              <input type="hidden" name="id" value="{{ $challan_info->id ?? '' }}">
               <div class="row form-group">
                <div class="col-sm-4 col-sm-offset-4">
                  {!! Form::select('floor_id', $floors, null, ['class' => 'select2-input input-floor-select form-control form-control-sm', 'id' => 'floor_id', 'placeholder' => 'Select a Floor']) !!}

                  @if($errors->has('floor_id'))
                    <span class="text-danger">{{ $errors->first('floor_id') }}</span>
                   @endif
                </div>
              </div>

              <div class="row form-group">
                <div class="col-sm-4 col-sm-offset-4">
                  @php
                    if (old('floor_id')) {
                      $lines = \SkylarkSoft\GoRMG\SystemSettings\Models\Line::where('floor_id', old('floor_id'))
                        ->pluck('line_no', 'id')
                        ->all();
                    }
                  @endphp
                  {!! Form::select('line_id', $lines ?? [], null, ['class' => 'lines-dropdown form-control form-control-sm select2-input', 'id' => 'line_id', 'placeholder' => 'Select a Line']) !!}

                  @if($errors->has('line_id'))
                    <span class="text-danger">{{ $errors->first('line_id') }}</span>
                   @endif
                </div>
              </div>

              <div class="row form-group m-t-md">
                <div class="text-center">
                  <button name="submit" type="submit" class="btn btn-success">Continue</button>
                </div>
              </div>
            </form>

          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('head-script')
  <script type="text/javascript">
    window.history.forward();

    function noBack() {
      window.history.forward();
    }
  </script>
@endsection
@section('scripts')
  <script>
    // sewing input get line after floor select
    $(document).on('change', '.input-floor-select', function (e) {
      e.preventDefault();
      var floor_id = $(this).val();
      $('.lines-dropdown').empty();
      if (floor_id) {
        $.ajax({
          type: 'GET',
          url: '/get-lines-for-dropdown/' + floor_id,
          success: function (response) {
            var linesDropdown = '<option value="">Select a Lines</option>';
            if (Object.keys(response.data).length > 0) {
              $.each(response.data, function (index, val) {
                linesDropdown += '<option value="' + index + '">' + val + '</option>';
              });
              $('.lines-dropdown').html(linesDropdown);
            }
          }
        });
      }
    });
  </script>
@endsection
