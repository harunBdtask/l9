@extends('skillmatrix::layout')

@section('title', 'Operator Skill Inventory List')

@section('content')
<div class="padding"> 
  <div class="box">
    <div class="box-header">
      <h2>Operator Skill Inventory List</h2>
    </div>
    <div class="box-body b-t">
      @include('partials.response-message')
      <div class="js-response-message text-center"></div>      
        {!! Form::open(['url' => '/operator-skill-inventory', 'method' => 'GET', 'file' => true]) !!}
          <div class="row form-group">
            <div class="col-md-3">
              {!! Form::select('floor_id', $floors ?? [], request()->get('floor_id') ?? null, ['id' => 'floor', 'class' => 'form-control select2-input']) !!}
            </div>
            @php
              if (request()->get('floor_id')) {
                $floorId = request()->get('floor_id');
                $lines = \SkylarkSoft\GoRMG\SystemSettings\Models\Line::where('floor_id', $floorId)
                  ->pluck('line_no', 'id')
                  ->all();
              }
            @endphp
            <div class="col-md-3">
              {!! Form::select('line_id', $lines ?? [], request()->get('line_id') ?? null, ['id' => 'line', 'class' => 'form-control select2-input', 'placeholder' => 'Select a line']) !!}
            </div>           
            <div class="col-md-3">
              {!! Form::select('sewing_machine_id', $sewingMachines ?? [], request()->get('sewing_machine_id') ?? null, ['class' => 'form-control select2-input']) !!}
            </div>
            <div class="col-md-3">
              {!! Form::select('sewing_process_id', $processes ?? [], request()->get('sewing_process_id') ?? null, ['class' => 'form-control select2-input']) !!}
            </div>
          </div>
          <div class="row form-group">
            <div class="col-md-3 operator-column">
              {!! Form::select('operator_search_column', $operatorSearchColumns ?? [], request()->get('operator_search_column') ?? null, ['class' => 'form-control operator-column-field select2-input', 'placeholder' => 'Select a column']) !!}
            </div>
            <div class="col-md-3 operator-search " style="{{ !request()->get('operator_search_column') ? 'display: none;' : '' }}">
              <input type="text" class="form-control operator-search-q" name="q" value="{{ $q ?? '' }}">
            </div>
            <div class="col-md-3">
              <input type="submit" class="btn btn-md btn-info form-control" value="Search">
            </div>
          </div>
        {!! Form::close() !!}
        <div class="table-responsive" id="parentTableFixed">
        <table class="reportTable" id="fixTable">
          <thead>
            <tr>
              <th>SL</th>
              <th>Name</th>
              <th>Title</th>
              <th>Operator Id</th>
              <th>Grade</th>
              <th>Floor</th>
              <th>Line</th>
              <th>Machine</th>
              <th>Process</th>
              <th>Standard Capacity</th>
              <th>OP. Capacity</th>
              <th>Efficiency</th>
            </tr>
          </thead>
          <tbody>
            @if(!$sewingOperators->getCollection()->isEmpty())
              @foreach($sewingOperators->getCollection() as $operator)
              @php
                  $standard_capacity = $operator->sewingProcess ? $operator->sewingProcess->standard_capacity : 0;
                  $efficiency = 0;
                  if ($standard_capacity > 0) {
                    $operator_process_capacity = $operator->capacity ?? 0;
                    $efficiency = round((($operator_process_capacity * 100) / $standard_capacity), 1);
                  }
              @endphp
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $operator->sewingOperator->name }}</td>
                  <td>{{ $operator->sewingOperator->title }}</td>
                  <td>{{ $operator->sewingOperator->operator_id }}</td>
                  <td>{{ $operator->sewingOperator->operator_grade }}</td>
                  <td>{{ $operator->sewingOperator->floor->floor_no }}</td>
                  <td>{{ $operator->sewingOperator->line->line_no }}</td>
                  <td>{{ $operator->sewingMachine->name }}</td>
                  <td>{{ $operator->sewingProcess->name }}</td>
                  <td>{{ $operator->sewingProcess->standard_capacity }}</td>
                  <td>{{ $operator->capacity }}</td>
                  <td>{{ $efficiency }} &#37;</td>
                </tr>
              @endforeach
            @else
              <tr style="height: 35px !important">
                <td colspan="12" class="text-danger" align="center">No Sewing Operator</td>
              </tr>
            @endif
          </tbody>
          <tfoot>
            @if($sewingOperators->total() > 15)
              <tr>
                <td colspan="12" align="center">{{ $sewingOperators->appends(request()->except('page'))->links() }}</td>
              </tr>
            @endif
          </tfoot>
        </table>
        </div>
    </div>
  </div>
</div>
@endsection
@section('scripts')
  <script type="text/javascript">
    $(function () {
      $('[data-toggle="tooltip"]').tooltip();
      $('[data-toggle="tooltip"]').on('shown.bs.tooltip', function () {
        $('.tooltip').addClass('animated fadeIn');
      })

      // if search buyer operator info then show input filed
      $("select[name=operator_search_column]").change(function() {
        if ($(this).val()) {   
          $('.operator-search').show();    
        } else {
          $('.operator-column-field').val('');
          $('.operator-search-q').val('');
          $('.operator-search').hide();
        }
      });

      $('#floor').change(function (e) {
        e.preventDefault();
        $('#line').empty().select2();
        var floor_id = $(this).val();
        if (floor_id) {
          $.ajax({
            type: 'GET',
            url: '/get-lines/' + floor_id,
            success: function (response) {
              var lineDropdown = '<option value="">Select a Line</option>';
              if (Object.keys(response.data).length > 0) {
                  $.each(response.data, function (index, val) {
                    lineDropdown += '<option value="' + val.id + '">' + val.line_no + '</option>';
                  });
                  $('#line').html(lineDropdown);
              }
            }
          });
        }
      });
    });    
  </script>
@endsection