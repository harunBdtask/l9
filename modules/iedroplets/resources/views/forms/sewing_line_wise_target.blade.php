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
  </style>
@endsection
@section('content')
  <div class="padding sewing-line-target-page">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
            <h2>Line Wise Target/Manpower/Input Plan Update
              <span class="pull-right">
              {{--   <a class="sewing-line-target-download" download-type="pdf" ><i style="color: #DC0A0B" class="fa fa-file-pdf-o"></i></a>
              | <a class="sewing-line-target-download" download-type="excel" ><i style="color: #0F733B" class="fa fa-file-excel-o"></i></a>
              --}}</span>
          </h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body">
          @include('partials.response-message')
          <div class="box-body table-responsive">

          <form autocomplete="off" action="{{ url('/line-target-action') }}" method="post">
            @csrf

            <div class="form-group">
              <div class="row m-b">
                  <div class="col-sm-2">
                    <label>Target Date</label>
                    <input type="date" name="target_date" class="form-control form-control-sm target-date" style="height: 15px" value="{{ date('Y-m-d') }}">
                  </div>
                  <div class="col-sm-2">
                    <label>Floor</label>
                    {!! Form::select('floor_id', $floors, null, ['class' => 'floor-select form-control form-control-sm select2-input', 'placeholder' => 'Select a Floor']) !!}
                  </div>
                </div>
            </div>

                <table class="reportTable sewing-line-target-table">
                  <thead>
                    <tr>
                      <th width="5%">Line</th>
                     {{--  <th>Buyer</th>
                      <th>Order</th>
                      <th>PO</th> --}}
                      <th width="10%">Operator</th>
                      <th width="10%">Helper</th>
                      <th width="10%">Target /Hour</th>
                      <th width="10%">Working Hours</th>
                      <th width="15%">Input&nbsp; Requirement Plan</th>
                      <th width="25%">Remarks</th>
                      <th width="20">Action</th>
                    </tr>
                  </thead>
                  <tbody class="sewing-line-target-form" style="font-size: 11px !important">
                  </tbody>
                </table>
               {{--  <tr>
                  <td colspan="5">
                    <button type="submit" style="display: none; margin-left: 500px" class="btn white sewing-target-btn">Submit</button>
                  </td>
                </tr>  --}}
              </form>
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
                url: '/get-line-target-form/' + floor_id + '/' + target_date,
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
