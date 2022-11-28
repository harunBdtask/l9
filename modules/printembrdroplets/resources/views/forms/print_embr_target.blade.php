@extends('printembrdroplets::layout')
@section('title', 'Date Wise Print Target')
@section('styles')
  <style type="text/css">
    @media screen and (-webkit-min-device-pixel-ratio: 0) {
      input[type=date].form-control form-control-sm {
        height: 33px !important;
      }
    }
    td {
      padding: 4px;
    }
  </style>
@endsection
@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
            <h2>Date Wise Print Target</h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body">
            @include('partials.response-message')

            {!! Form::open(['url' => 'print-embroidery-target-post', 'method' => 'post', 'id' => 'print-target-form']) !!}
              <div class="form-group">
                <div class="row">
                    <div class="col-sm-3">
                      <label>Target Date</label>
                      {!! Form::date('target_date', $target_date, ['class' => 'form-control form-control-sm', 'id' => 'print-target-date']) !!}
                    </div>
                  </div>
              </div>
              <span class="loader"></span>

              <table class="reportTable">
                <thead>
                 <tr>
                    <th>Table No.</th>
                    <th>Manpower</th>
                    <th>Target Qty</th>
                    <th>Working Hour</th>
                    <th>Remarks</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($print_tables as $table)
                    <tr>
                      <td>
                        {{ $table->name }}
                        {!! Form::hidden('print_factory_table_ids[]', $table->id) !!}
                      </td>
                      <td>
                        {!! Form::text('man_power[]', $table->print_embr_target->man_power ?? null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm text-right']) !!}
                      </td>
                      <td>
                        {!! Form::text('target_qty[]', $table->print_embr_target->target_qty ?? null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm text-right']) !!}
                      </td>
                      <td>
                        {!! Form::text('working_hour[]', $table->print_embr_target->working_hour ?? null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm text-right']) !!}
                      </td>
                      <td>
                        {!! Form::textarea('remarks[]', $table->print_embr_target->remarks ?? null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'rows' => '1']) !!}
                      </td>
                    </tr>
                  @empty
                    <tr class="text-danger text-center">
                      <td colspan="5">Not Found</td>
                    </tr>
                  @endforelse
                  @if ($print_tables->count())
                    <tr>
                      <td colspan="5">
                        <button type="submit" class="btn btn-sm btn-success">Submit</button>
                      </td>
                    </tr>
                  @endif
                </tbody>
              </table>
            {!! Form::close() !!}
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('scripts')
  <script type="text/javascript">
    $(".text-right").bind("keypress", function (e) {
      var keyCode = e.which ? e.which : e.keyCode;
      if (!(keyCode >= 48 && keyCode <= 57)) {
        return false;
      }
    });
    $("#print-target-date").change(function() {
      $('.loader').html(loader);
      $('.btn-success').attr('disabled', true);
      let target_date = $(this).val();
      window.location = '/print-embroidery-target?target_date=' + target_date;
    });
  </script>
@endsection
