@extends('iedroplets::layout')
@section('title', $operation_bulletin ? 'Update operation bulletin' : 'New Operation Bulletin')
@section('styles')
<style>
  .bulletine-duplicate-me>td>.form-control form-control-sm {
    width: 80px;
    font-size: 11px;
    min-height: 1.375rem;
  }

  .bulletine-duplicate-me>td>.selectize-control {
    width: 240px !important;
    text-align: left;
    font-size: 11px;
  }

  .bulletine-duplicate-me>td>.form-width-custom {
    /*width: 58px !important;*/
  }

  .operation-bulatin-details-table-div {
    /*max-width: 1400px !important;*/
    min-height: 200px !important;
    display: block;
    overflow-x: auto;
    /*white-space: nowrap;*/
  }

  .headcol {
    position: sticky;
    left: 0;
    background-color: #fff;
  }

  .selectize-dropdown,
  .selectize-dropdown.form-control form-control-sm {
    position: static;
  }

  .reportTable th,
  .reportTable td {
    font-size: 9px;
    white-space: normal !important;
  }

  .large-col {
    max-width: 280px !important;
    min-width: 120px !important;
  }

  .mid-col {
    max-width: 140px !important;
    min-width: 100px !important;
  }

  .semi-small-col {
    max-width: 35px !important;
    min-width: 22px !important;
  }

  .small-col {
    max-width: 40px !important;
    min-width: 20px !important;
  }

  .semi-small-col-input {
    max-width: 30px !important;
    padding: 0px 2px !important;
    margin: 0px 3px;
  }

  .small-col-input {
    max-width: 33px !important;
    padding: 0px 1px !important;
    margin: 0px 2px;
    text-align: center;
  }

  .remarks-input {
    width: 95% !important;
    margin: 0 auto !important;
  }

  .btn-xs {
    font-size: 0.61rem !important;
    padding: 0.02rem 0.2rem;
  }

  .form-control form-control-sm {
    line-height: 1;
    min-height: 1rem !important;
  }

  /* .select2-container .select2-selection--single {
    height: 33px;
    padding-top: 3px !important;
  } */

  @media (min-width: 1401px) {
    .small-col-input {
      max-width: 62px !important;
    }

    .semi-small-col-input {
      max-width: 50px !important;
    }
  }

  @media screen and (-webkit-min-device-pixel-ratio: 0) {
    input[type=date].form-control form-control-sm {
      height: 33px !important;
    }
  }
</style>
@endsection
@section('content')
<div class="padding">
  <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header">
          <h2>{{ $operation_bulletin ? 'Update operation bulletin' : 'New Operation Bulletin' }}</h2>
        </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          {!! Form::model($operation_bulletin, ['url' => $operation_bulletin ?
          'operation-bulletins/'.$operation_bulletin->id : 'operation-bulletins', 'method' => $operation_bulletin ?
          'PUT' : 'POST', 'id' => 'operationBulletinForm', 'files' => true]) !!}
          <div id="bulletinBasic">
            <div class="row form-group">
              <div class="col-sm-3">
                <label for="buyer">Floor No</label>
                {!! Form::select('floor_id', $floors, null, ['class' => 'form-control form-control-sm c-select select2-input', 'id' =>
                'floor', 'placeholder' => 'Select a Floor', 'required', 'style' => $errors->has("floor_id") ? 'border:
                1px solid red;' : '']) !!}

                <span class="text-danger">{{ $errors->first('floor_id') }}</span>
              </div>

              @php
              if (old('floor_id') || $operation_bulletin) {
              $floorId = old('floor_id') ?? $operation_bulletin->floor_id;
              $lines = \SkylarkSoft\GoRMG\SystemSettings\Models\Line::where('floor_id', $floorId)
              ->pluck('line_no', 'id')
              ->all();

              if (old('line_id') || $operation_bulletin) {
              $lineId = old('line_id') ?? $operation_bulletin->line_id;
              }
              }
              @endphp

              <div class="col-sm-3">
                <label for="buyer">Line No</label>
                {!! Form::select('line_id', $lines ?? [], old('line_id') ?? null, ['class' => 'form-control form-control-sm c-select
                select2-input', 'id' => 'line', 'required', 'placeholder' => 'Select a Line', 'style' =>
                $errors->has("line_id") ? 'border: 1px solid red;' : '']) !!}
                <span class="text-danger">{{ $errors->first('line_id') }}</span>
              </div>

              <div class="col-sm-2">
                <label for="orderCode">Input Date</label>
                {!! Form::date('input_date', $operation_bulletin->input_date ?? null, ['class' => 'form-control form-control-sm', 'id'
                => 'inputDate', 'placeholder' => 'input_date', 'style' => $errors->has("input_date") ? 'border: 1px
                solid red;' : '']) !!}
              </div>

              <div class="col-sm-2">
                <label for="orderCode">Prepared Date</label>
                {!! Form::date('prepared_date', $operation_bulletin->prepared_date ?? date('Y-m-d'), ['class' =>
                'form-control form-control-sm', 'id' => 'preparedDate', 'placeholder' => 'prepared_date', 'style' =>
                $errors->has("prepared_date") ? 'border: 1px solid red;' : '']) !!}
              </div>

              <div class="col-sm-2">
                <label for="orderCode">Pattern Status</label>
                {!! Form::text('pattern_status', $operation_bulletin->pattern_status ?? null, ['class' =>
                'form-control form-control-sm', 'id' => 'patternStatus', 'placeholder' => 'pattern status', 'style' =>
                $errors->has("prepared_date") ? 'border: 1px solid red;' : '']) !!}
              </div>

            </div>
            <div class="row form-group">
              @php
              if (old('buyer_id') || $operation_bulletin) {
                $buyer_id = old('buyer_id') ?? $operation_bulletin->buyer_id;
                $buyers = SkylarkSoft\GoRMG\SystemSettings\Models\Buyer::where('id', $buyer_id)->pluck('name', 'id');
              }
              if (old('order_id') || $operation_bulletin) {
                $order_id = old('order_id') ?? $operation_bulletin->order_id;
                $orders = SkylarkSoft\GoRMG\Merchandising\Models\Order::where('id', $order_id)->pluck('style_name', 'id');
              }
              @endphp
              <div class="col-sm-3">
                <label for="buyer">Buyer</label>
                {!! Form::select('buyer_id', $buyers ?? [], $buyer_id ?? null, ['class' => 'form-control form-control-sm', 'id' => 'buyer', 'required', 'style' => $errors->has("buyer_id") ? 'border: 1px solid red;' : '']) !!}

                <span class="text-danger">{{ $errors->first('buyer_id') }}</span>
              </div>
              <div class="col-sm-3">
                <label for="style">Order</label>
                {!! Form::select('order_id', $orders ?? [], $order_id ?? null, ['class' => 'form-control form-control-sm', 'id' => 'order', 'required', 'style' => $errors->has('order_id') ? 'border: 1px solid red;' : '']) !!}

                <span class="text-danger">{{ $errors->first('order_id') }}</span>
              </div>

              <div class="col-sm-2">
                <label for="ods">Porposed Target(/hr)</label>
                {!! Form::number('proposed_target', null, ['class' => 'form-control form-control-sm', 'id' => 'proposedTarget',
                'placeholder' => 'Enter proposed tgt. here', 'style' => $errors->has("proposed_target") ? 'border: 1px
                solid red;' : '']) !!}
              </div>

              <div class="col-sm-2">
                <label for="ods">Sketch</label>
                {!! Form::file('sketch') !!}
                @if($errors->has("sketch"))
                <span class="text-danger">{{ $errors->first('sketch') }}</span>
                @endif
              </div>
              <div class="col-sm-1">
                @if(isset($operation_bulletin->sketch))
                <img height="60px" width="60px"
                  src="{{ asset('/storage/sketch_images/'.$operation_bulletin->sketch) }}">
                @endif
              </div>
            </div>
          </div>

          <hr>
          <div class="operation-bulatin-details-table-div">
            <div class="">
              <table class="reportTable">
                <thead>
                  <tr>
                    <th>SL.</th>
                    <th class="headcol large-col">Task</th>
                    <th class="mid-col">Machine Type</th>
                    <th>Operator Skill</th>
                    <th>Guide/Folder</th>
                    <th class="small-col">Work Station</th>
                    <th class="small-col">Time</th>
                    <th class="small-col">Idle Time</th>
                    <th class="small-col">New Work S</th>
                    <th class="small-col">New Time</th>
                    <th class="small-col">New Idle Time</th>
                    <th class="semi-small-col">Target/ Hr.</th>
                    <th class="large-col">Remarks</th>
                    <th colspan="2">Actions</th>
                  </tr>
                </thead>
                <tbody id="tbody-rows" class="bulletinDetail">
                  @if($operation_bulletin && !old('task_id'))
                  @foreach($operation_bulletin->operationBulletinDetails as $key => $operationDetail)
                  <tr class="bulletine-duplicate-me" row-number="{{ $key }}">
                    <td class="sl">{{$key + 1}}</td>
                    <td class="headcol large-col">
                      <div style="display: flex; justify-content: space-between; align-items: center;">
                      {!! Form::checkbox('special_task_check','1',$operationDetail->special_task == 1 ? true :
                      false,['class' => 'special_task_check']) !!}
                      {!! Form::hidden("special_task[]",$operationDetail->special_task,['class' => 'special_task']) !!}
                      {!! Form::select("task_id[]", $tasks, $operationDetail->task_id ?? null, ['class' => 'form-control form-control-sm
                      c-select combobox', 'required', 'id' => 'task_id', 'placeholder' => 'Select a Task', 'style' =>
                      $errors->has("task_id") ? 'border: 1px solid red;' : '']) !!}
                      <br>
                      @if($errors->has("task_id.$key"))
                      <span class="text-danger">Duplicate entry found!</span>
                      @endif
                      </div>
                    </td>
                    <td class="mid-col">
                      <div style="display: flex; justify-content: space-between; align-items: center;">
                      {!! Form::checkbox("special_machine_check", 1, $operationDetail->special_machine == 1 ? true :
                      false,['class' => 'special_machine_check']) !!}
                      {!! Form::hidden("special_machine[]", $operationDetail->special_machine, ['class' =>
                      'special_machine']) !!}
                      {!! Form::select("machine_type_id[]", $machine_types, $operationDetail->machine_type_id ?? null,
                      ['class' => 'form-control form-control-sm c-select', 'required', 'id' => 'machineTypeId', 'placeholder' => 'Select
                      a Machine Type', 'required', 'style' => $errors->has("machine_type_id") ? 'border: 1px solid red;'
                      : '']) !!}
                      </div>
                    </td>
                    <td>
                      {!! Form::select("operator_skill_id[]", $operator_skills ?? [],
                      $operationDetail->operator_skill_id ?? null, ['class' => 'form-control form-control-sm c-select ', 'required',
                      'id' => 'operatorSkillId', 'placeholder' => 'Select a Skill', 'required', 'style' =>
                      $errors->has("operator_skill_id") ? 'border: 1px solid red;' : '']) !!}
                    </td>
                    <td>
                      {!! Form::select("guide_or_folder_id[]", $guide_or_folders ?? [],
                      $operationDetail->guide_or_folder_id ?? null, ['class' => 'form-control form-control-sm c-select ', 'id' =>
                      'guideOrFolderId', 'placeholder' => 'Select a folder', 'style' =>
                      $errors->has("guide_or_folder_id") ? 'border: 1px solid red;' : '']) !!}
                    </td>
                    <td class="small-col">
                      {!! Form::number("work_station[]", $operationDetail->work_station ?? null, ['class' =>
                      'form-control form-control-sm work-station form-width-custom small-col-input', 'required', 'id' => 'workStation',
                      'placeholder' => 'Work station']) !!}
                    </td>
                    <td class="small-col">
                      {!! Form::number("time[]", $operationDetail->time ?? null, ['class' => 'form-control form-control-sm time
                      form-width-custom small-col-input', 'required', 'id' => 'time', 'required', 'placeholder' =>
                      'Time']) !!}
                    </td>
                    <td class="small-col">
                      {!! Form::number("idle_time[]", $operationDetail->idle_time ?? null, ['class' => 'form-control form-control-sm
                      idle-time form-width-custom small-col-input', 'required', 'readonly', 'placeholder' =>
                      'idle time']) !!}
                    </td>
                    <td class="small-col">
                      {!! Form::text("new_work_station[]", $operationDetail->new_work_station ?? null, ['class' =>
                      'form-control form-control-sm new-work-station form-width-custom small-col-input', 'required', 'id' =>
                      'newWorkStation', 'placeholder' => 'New Work sStation']) !!}
                    </td>
                    <td class="small-col">
                      {!! Form::text("new_time[]", $operationDetail->new_time ?? null, ['class' => 'form-control form-control-sm
                      new-time form-width-custom small-col-input', 'required', 'readonly',
                      'placeholder' => 'New Time']) !!}
                    </td>
                    <td class="small-col">
                      {!! Form::text("new_idle_time[]", $operationDetail->new_idle_time ?? null, ['class' =>
                      'form-control form-control-sm new-idle-time form-width-custom small-col-input', 'required', 'readonly', 'id' =>
                      'newIdleTime', 'placeholder' => 'New Idle Time']) !!}
                    </td>
                    <td class="semi-small-col">
                      {!! Form::text("hourly_target[]", $operationDetail->hourly_target ?? null, ['class' =>
                      'form-control form-control-sm task-hourly-target form-width-custom semi-small-col-input',
                      'placeholder' => 'Target/Hr.','readonly']) !!}
                    </td>
                    <td class="large-col">
                      {!! Form::text("remarks[]", $operationDetail->remarks ?? null, ['class' => 'form-control form-control-sm
                      remarks-input', 'id' => 'gsm', 'placeholder' => 'Remarks']) !!}
                    </td>
                    <td>
                      <button type="button" class="btn btn-xs bg-danger remove-bulletin-duplicate">
                        <i class="fa fa-times"></i>
                      </button>
                    </td>
                    <td>
                      <button type="button" class="btn btn-xs bg-success bulletin-duplicate">
                        <i class="fa fa-plus"></i>
                      </button>
                    </td>
                  </tr>
                  @endforeach
                  @elseif(old('task_id'))
                  @php
                  $tasksRow = old('task_id') ?? '' ;
                  @endphp
                  @foreach($tasksRow as $key => $tasksRow)
                  <tr class="bulletine-duplicate-me" row-number="{{ $key }}">
                    <td class="sl">{{$key + 1}}</td>
                    <td class="headcol large-col">
                      <div style="display: flex; justify-content: space-between; align-items: center;">
                      {!! Form::checkbox('special_task_check','1',old('special_task')[$key] == 1 ? true : false,['class'
                      => 'special_task_check']) !!}
                      {!! Form::hidden("special_task[$key]",old('special_task')[$key],['class' => 'special_task']) !!}
                      {!! Form::select("task_id[$key]", $tasks, old('task_id')[$key] ?? null, ['class' => 'form-control form-control-sm
                      c-select combobox', 'required', 'id' => 'task_id', 'placeholder' => 'Select a Task', 'style' =>
                      $errors->has("task_id.$key") ? 'border: 1px solid red;' : '']) !!}
                      <br>
                      @if($errors->has("task_id.$key"))
                      <span class="text-danger">Duplicate entry found!</span>
                      @endif
                      </div>
                    </td>
                    <td class="mid-col">
                      <div style="display: flex; justify-content: space-between; align-items: center;">
                      {!! Form::checkbox("special_machine_check", 1, old('special_machine')[$key] == 1 ? true :
                      false,['class' => 'special_machine_check']) !!}
                      {!! Form::hidden("special_machine[$key]", old('special_machine')[$key],['class' =>
                      'special_machine']) !!}
                      {!! Form::select("machine_type_id[$key]", $machine_types, old('machine_type_id')[$key] ?? null,
                      ['class' => 'form-control form-control-sm c-select', 'required', 'id' => 'machineTypeId', 'placeholder' => 'Select
                      a Machine Type', 'style' => $errors->has("machine_type_id.$key") ? 'border: 1px solid red;' : ''])
                      !!}
                      </div>
                    </td>
                    <td>
                      {!! Form::select("operator_skill_id[$key]", $operator_skills ?? [], old('operator_skill_id')[$key]
                      ?? null, ['class' => 'form-control form-control-sm c-select', 'required', 'id' => 'operatorSkillId', 'placeholder'
                      => 'Select a Skill', 'style' => $errors->has("operator_skill_id.$key") ? 'border: 1px solid red;'
                      : '']) !!}
                    </td>
                    <td>
                      {!! Form::select("guide_or_folder_id[$key]", $guide_or_folders ?? [],
                      old('guide_or_folder_id')[$key] ?? null, ['class' => 'form-control form-control-sm c-select ', 'id' =>
                      'guideOrFolderId', 'placeholder' => 'Select a folder', 'style' =>
                      $errors->has("guide_or_folder_id") ? 'border: 1px solid red;' : '']) !!}
                    </td>
                    <td class="small-col">
                      {!! Form::number("work_station[$key]", old('work_station')[$key] ?? null, ['class' =>
                      'form-control form-control-sm work-station form-width-custom small-col-input', 'required', 'id' => 'workStation',
                      'placeholder' => 'Work station', 'style' => $errors->has("work_station.$key") ? 'border: 1px solid
                      red;' : '']) !!}
                    </td>
                    <td class="small-col">
                      {!! Form::number("time[$key]", old('time')[$key] ?? null, ['class' => 'form-control form-control-sm time
                      form-width-custom small-col-input', 'required', 'id' => 'time', 'placeholder' => 'Time', 'style'
                      => $errors->has("time.$key") ? 'border: 1px solid red;' : '']) !!}
                    </td>
                    <td class="small-col">
                      {!! Form::text("idle_time[$key]", old('idle_time')[$key] ?? null, ['class' => 'form-control form-control-sm
                      idle-time form-width-custom', 'id' => '', 'placeholder' => 'idle time', 'required', 'readonly', 'style' =>
                      $errors->has("idle_time.$key") ? 'border: 1px solid red;' : '']) !!}
                    </td>
                    <td class="small-col">
                      {!! Form::text("new_work_station[$key]", old('new_work_station')[$key] ?? null, ['class' =>
                      'form-control form-control-sm new-work-station form-width-custom small-col-input', 'required', 'id' =>
                      'newWorkStation', 'placeholder' => 'New Work Station', 'style' =>
                      $errors->has("new_work_station.$key") ? 'border: 1px solid red;' : '']) !!}
                    </td>
                    <td class="small-col">
                      {!! Form::text("new_time[$key]", old('new_time')[$key] ?? null, ['class' => 'form-control form-control-sm new-time
                      form-width-custom small-col-input', 'id' => 'newTime', 'placeholder' => 'New Time', 'required', 'readonly', 'style' =>
                      $errors->has("new_time.$key") ? 'border: 1px solid red;' : '']) !!}
                    </td>
                    <td class="small-col">
                      {!! Form::text("new_idle_time[$key]", old('new_idle_time')[$key] ?? null, ['class' =>
                      'form-control form-control-sm new-idle-time form-width-custom small-col-input', 'id' => 'newIdleTime',
                      'placeholder' => 'New Idle Time', 'required', 'readonly', 'style' => $errors->has("new_idle_time.$key") ? 'border: 1px
                      solid red;' : '']) !!}
                    </td>
                    <td class="semi-small-col">
                      {!! Form::text("hourly_target[$key]", old('hourly_target')[$key] ?? null, ['class' =>
                      'form-control form-control-sm task-hourly-target form-width-custom semi-small-col-input', 'id' => 'hourly_target',
                      'placeholder' => 'Target/Hr.','readonly']) !!}
                    </td>
                    <td class="large-col">
                      {!! Form::text("remarks[$key]", old('remarks')[$key] ?? null, ['class' => 'form-control form-control-sm
                      remarks-input', 'id' => 'gsm', 'placeholder' => 'Remarks']) !!}
                    </td>
                    <td>
                      <button type="button" class="btn btn-xs bg-danger remove-bulletin-duplicate">
                        <i class="glyphicon glyphicon-remove"></i>
                      </button>
                    </td>
                    <td>
                      <button type="button" class="btn btn-xs bg-success bulletin-duplicate">
                        <i class="glyphicon glyphicon-plus"></i>
                      </button>
                    </td>
                  </tr>
                  @endforeach
                  @else
                  <tr class="bulletine-duplicate-me" row-number="0">
                    <td class="sl">1</td>
                    <td class="headcol large-col">
                      <div style="display: flex; justify-content: space-between; align-items: center;">
                      {!! Form::checkbox('special_task_check','1',false,['class' => 'special_task_check']) !!}
                      {!! Form::hidden('special_task[]',0,['class' => 'special_task']) !!}
                      {!! Form::select("task_id[]", $tasks, null, ['class' => 'form-control form-control-sm c-select combobox', 'id' =>
                      'task_id', 'placeholder' => 'Select a Task', 'required', 'style' => $errors->has("task_id") ?
                      'border: 1px solid red;' : '']) !!}
                      </div>
                    </td>
                    <td class="mid-col">
                      <div style="display: flex; justify-content: space-between; align-items: center;">
                        {!! Form::checkbox("special_machine_check", 1, false,['class' => 'special_machine_check']) !!}
                        {!! Form::hidden('special_machine[]',0,['class' => 'special_machine']) !!}
                        {!! Form::select("machine_type_id[]", $machine_types, null, ['class' => 'form-control form-control-sm c-select',
                        'id' => 'machineTypeId', 'required', 'placeholder' => 'Select a Machine Type', 'style' =>
                        $errors->has("machine_type_id") ? 'border: 1px solid red;' : '']) !!}
                      </div>
                      
                    </td>
                    <td>
                      {!! Form::select("operator_skill_id[]", $operator_skills ?? [], null, ['class' => 'form-control form-control-sm
                      c-select ', 'id' => 'operatorSkillId', 'required', 'placeholder' => 'Select a Skill', 'style' =>
                      $errors->has("operator_skill_id") ? 'border: 1px solid red;' : '']) !!}
                    </td>
                    <td>
                      {!! Form::select("guide_or_folder_id[]", $guide_or_folders ?? [], null, ['class' => 'form-control form-control-sm
                      c-select ', 'id' => 'guideOrFolderId', 'placeholder' => 'Select a folder', 'style' =>
                      $errors->has("guide_or_folder_id") ? 'border: 1px solid red;' : '']) !!}
                    </td>
                    <td class="small-col">
                      {!! Form::number("work_station[]", null, ['class' => 'form-control form-control-sm work-station form-width-custom
                      small-col-input' , 'id' => 'workStation', 'placeholder' => 'Work station', 'required']) !!}
                    </td>
                    <td class="small-col">
                      {!! Form::text("time[]", null, ['class' => 'form-control form-control-sm time form-width-custom small-col-input',
                      'id' => 'time', 'placeholder' => 'Time', 'required']) !!}
                    </td>
                    <td class="small-col">
                      {!! Form::text("idle_time[]", null, ['class' => 'form-control form-control-sm idle-time form-width-custom
                      small-col-input', 'id' => '', 'placeholder' => 'idle time', 'readonly', 'required']) !!}
                    </td>
                    <td class="small-col">
                      {!! Form::text("new_work_station[]", null, ['class' => 'form-control form-control-sm new-work-station
                      form-width-custom small-col-input', 'id' => 'newWorkStation', 'required', 'placeholder' => 'New
                      Work sStation']) !!}
                    </td>
                    <td class="small-col">
                      {!! Form::text("new_time[]", null, ['class' => 'form-control form-control-sm new-time form-width-custom
                      small-col-input', 'id' => 'newTime', 'placeholder' => 'New Time', 'required', 'readonly']) !!}
                    </td>
                    <td class="small-col">
                      {!! Form::text("new_idle_time[]", null, ['class' => 'form-control form-control-sm new-idle-time form-width-custom
                      small-col-input', 'id' => 'newIdleTime', 'placeholder' => 'New Idle Time', 'required',
                      'readonly']) !!}
                    </td>
                    <td class="semi-small-col">
                      {!! Form::text("hourly_target[]", null, ['class' => 'form-control form-control-sm task-hourly-target
                      form-width-custom semi-small-col-input', 'id' => 'hourly_target', 'placeholder' =>
                      'Target/Hr.','readonly']) !!}
                    </td>
                    <td width="10%" class="large-col">
                      {!! Form::text("remarks[]", null, ['class' => 'form-control form-control-sm remarks-input', 'id' => 'gsm',
                      'placeholder' => 'Remarks']) !!}
                    </td>
                    <td>
                      <button type="button" class="btn btn-xs bg-danger remove-bulletin-duplicate">
                        <i class="glyphicon glyphicon-remove"></i>
                      </button>
                    </td>
                    <td>
                      <button type="button" class="btn btn-xs bg-success bulletin-duplicate">
                        <i class="glyphicon glyphicon-plus"></i>
                      </button>
                    </td>
                  </tr>
                  @endif
                </tbody>
              </table>
            </div>
          </div>
          <div class="row form-group">
            <div class="col-sm-12 text-center">
              <button type="button" class="btn btn-info" id="calculate-btn">Calculate</button>
              <button type="submit" class="{{ $operation_bulletin ? 'btn btn-primary' : 'btn btn-success' }}">{{ $operation_bulletin ? 'Update' : 'Create' }}</button>
              <a class="btn btn-danger" href="{{ url('operation-bulletins') }}">Cancel</a>
            </div>
          </div>
          {!! Form::close() !!}
        </div>
      </div>
    </div>
  </div>
  @endsection

  @section('scripts')
  <link rel="stylesheet" href="{{ asset('modules/skeleton/flatkit/assets/selectize/selectize.bootstrap3.css') }}">
  <script src="{{ asset('modules/skeleton/flatkit/assets/selectize/selectize.min.js') }}"></script>
  <script src="{{ asset('protracker/custom.js') }}"></script>
  <script src="{{ asset('protracker/operation_bulletin.js') }}"></script>
  <script>
    $(document).on('change', '.special_task_check', function () {
          if (this.checked == true) {
            $(this).parents('tr').find('.special_task').val(1);
          } else {
            $(this).parents('tr').find('.special_task').val(0);
          }
        });
        $(document).on('change', '.special_machine_check', function () {
          if (this.checked == true) {
            $(this).parents('tr').find('.special_machine').val(1);
          } else {
            $(this).parents('tr').find('.special_machine').val(0);
          }
        });
  </script>
  @endsection
