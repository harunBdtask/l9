@extends('skillmatrix::layout')

@section('title', $sewingOperator ? 'Update Sewing Operator' : 'New Sewing Operator')
@section('styles')
  <style type="text/css">    
    .processes {
      margin-top: 0px;
      margin-bottom: 0px;
    }
  </style>
@endsection
@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header">
            <h2>{{ $sewingOperator ? 'Update Sewing Operator' : 'New Sewing Operator' }}</h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body">
            {!! Form::model($sewingOperator, ['url' => $sewingOperator ? 'sewing-operators/'.$sewingOperator->id : 'sewing-operators', 'method' => $sewingOperator ? 'PUT' : 'POST', 'enctype' => 'multipart/form-data']) !!}             
              <div class="form-group row">
                <div class="col-sm-3">
                  <label for="title">Title <span class="text-danger">*</span></label>
                  {!! Form::text('title', null, ['class' => 'form-control form-control-sm', 'required', 'id' => 'title']) !!}

                   @if($errors->has('title'))
                    <span class="text-danger">{{ $errors->first('title') }}</span>
                   @endif
                </div>
                <div class="col-sm-3">
                  <label for="name">Operator Name <span class="text-danger">*</span></label>
                  {!! Form::text('name', null, ['class' => 'form-control form-control-sm', 'required', 'id' => 'name']) !!}

                   @if($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                   @endif
                </div>
                <div class="col-sm-3">
                  <label for="operator_id">Operator Id <span class="text-danger">*</span></label>
                  {!! Form::text('operator_id', null, ['class' => 'form-control form-control-sm', 'required','id' => 'operator_id']) !!}

                   @if($errors->has('operator_id'))
                    <span class="text-danger">{{ $errors->first('operator_id') }}</span>
                   @endif
                </div>
                <div class="col-sm-3">
                  <label for="operator_grade">Grade <span class="text-danger">*</span></label>
                  {!! Form::text('operator_grade', null, ['class' => 'form-control form-control-sm', 'required', 'id' => 'operator_grade']) !!}

                   @if($errors->has('operator_grade'))
                    <span class="text-danger">{{ $errors->first('operator_grade') }}</span>
                   @endif
                </div>
              </div>
              <div class="form-group row">
                <div class="col-sm-3">
                  <label for="floor">Floor <span class="text-danger">*</span></label>
                  {!! Form::select('floor_id', $floors, null, ['class' => 'form-control form-control-sm c-select select2-input', 'id' => 'floor', 'placeholder' => 'Select a Floor', 'required', 'style' => $errors->has("floor_id") ? 'border: 1px solid red;' : '']) !!}

                   @if($errors->has('floor_id'))
                    <span class="text-danger">{{ $errors->first('floor_id') }}</span>
                   @endif
                </div>
                @php
                    if (old('floor_id') || $sewingOperator) {
                      $floorId = old('floor_id') ?? $sewingOperator->floor_id;
                      $lines = \SkylarkSoft\GoRMG\SystemSettings\Models\Line::where('floor_id', $floorId)
                        ->pluck('line_no', 'id')
                        ->all();

                      if (old('line_id') || $sewingOperator) {
                        $lineId = old('line_id') ?? $sewingOperator->line_id;
                      }
                    }
                @endphp
                <div class="col-sm-3">
                  <label for="line">Line <span class="text-danger">*</span></label>
                  {!! Form::select('line_id', $lines ?? [], old('line_id') ?? null, ['class' => 'form-control form-control-sm c-select select2-input',   'id' => 'line', 'required', 'placeholder' => 'Select a Line', 'style' => $errors->has("line_id") ? 'border: 1px solid red;' : '']) !!}

                   @if($errors->has('line_id'))
                    <span class="text-danger">{{ $errors->first('line_id') }}</span>
                   @endif
                </div>
                <div class="col-sm-2">
                  <label for="present_salary">Present Salary <span class="text-danger">*</span></label>
                  {!! Form::number('present_salary', null, ['class' => 'form-control form-control-sm', 'required', 'id' => 'present_salary']) !!}

                   @if($errors->has('present_salary'))
                    <span class="text-danger">{{ $errors->first('present_salary') }}</span>
                   @endif
                </div>
                <div class="col-sm-2">
                  <label for="joinning_date">Joinning Date <span class="text-danger">*</span></label>
                  {!! Form::date('joinning_date', null, ['class' => 'form-control form-control-sm', 'required', 'id' => 'joinning_date']) !!}

                   @if($errors->has('joinning_date'))
                    <span class="text-danger">{{ $errors->first('joinning_date') }}</span>
                   @endif
                </div>
                <div class="col-sm-2">
                  <label for="image">Photo</label>                
                  <div class="form-file">
                    {!! Form::file('image', null, ['class' => '']) !!}
                    <button style="height: 33px; padding-top: 4px" class="btn tbn-primary">Select a file ...</button>
                  </div>

                  @if($errors->has('image'))
                    <span class="text-danger">{{ $errors->first('image') }}</span>
                   @endif
                </div>
              </div>

              <div class="form-group row m-t-md">
                <div class="text-center">
                  <button type="submit" class="{{ $sewingOperator ? 'btn btn-sm btn-primary' : 'btn btn-sm btn-success' }}"><i class="fa fa-save"></i>&nbsp;{{ $sewingOperator ? 'Update' : 'Create' }}</button>
                  <a class="btn btn-sm btn-danger" href="{{ url('sewing-operators') }}"><i class="fa fa-times"></i> &nbsp;Cancel</a>
                </div>
              </div>
            {!! Form::close() !!}           
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('scripts')
  <script type="text/javascript">
    $(document).on('change', '#floor', function (e) {
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
            $('#line').val('').change()
          }
        });
      }
    });
  </script>
@endsection