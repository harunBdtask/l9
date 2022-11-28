@extends('printembrdroplets::layout')
@section('title', 'Print Factory Receive Challan')
@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
            <h2>Edit Print Factory Receive Challan</h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body">
            @include('partials.response-message')
            {!! Form::open(['url' => 'receive-challan/' . $challan_no, 'method' => 'post', 'onsubmit' => 'submit.disabled = true; return true;']) !!}

            <div class="form-group">
              <div class="col-md-4 col-md-offset-4">
                {{ Form::text('', $challan_no, ['class' => 'form-control form-control-sm', 'readonly']) }}
              </div>
            </div>
            <div class="form-group">
              <div class="col-md-4 col-md-offset-4">
                {!! Form::select('operation_name', OPERATION, $challan->operation_name, ['class' => 'form-control form-control-sm c-select', 'id' => 'operation_name', 'placeholder' =>
                'Select a operation']) !!}

                @if($errors->has('operation_name'))
                  <span class="text-danger">{{ $errors->first('operation_name') }}</span>
                @endif
              </div>
            </div>
            <div class="form-group">
              <div class="col-md-4 col-md-offset-4">
                @php
                  $tableIdFormAttrs = [
                      'class'       => 'form-control form-control-sm c-select select2-input',
                      'id'          => 'table_id',
                      'placeholder' => 'Select a table'
                  ]
                @endphp
                {!! Form::select('table_id', $tables, $challan->table_id, $tableIdFormAttrs)!!}

                @if($errors->has('table_id'))
                  <span class="text-danger">{{ $errors->first('table_id') }}</span>
                @endif
              </div>
            </div>
            <div class="form-group m-t-md">
              <div class="text-center">
                <button name="submit" type="submit" class="btn btn-success">Submit</button>
              </div>
            </div>
            {!! Form::close() !!}

          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
