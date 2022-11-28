@extends('printembrdroplets::layout')
@section('title', 'Print/Embr Factory Receive Challan')
@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
            <h2>Create Print/Embr Factory Receive Challan</h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body">

            @include('partials.response-message')
            <form method="POST" action="{{ url('/create-factory-receive-challan-post')}}" accept-charset="UTF-8"
                  onsubmit="submit.disabled = true; return true;">
              @csrf
              <input type="hidden" name="challan_no" value="{{ $challan_no }}">
              <div class="form-group">
                <div class="col-sm-4 col-sm-offset-4">
                  {!! Form::select('operation_name', OPERATION, null, ['class' => 'form-control form-control-sm c-select', 'id' => 'operation_name', 'placeholder' => 'Select a operation']) !!}

                  @if($errors->has('operation_name'))
                    <span class="text-danger">{{ $errors->first('operation_name') }}</span>
                  @endif
                </div>
              </div>

              <div class="form-group">
                <div class="col-sm-4 col-sm-offset-4">
                    <?php
                    $tableIdFormAttrs = [
                        'class' => 'form-control form-control-sm c-select select2-input',
                        'id' => 'table_id',
                        'placeholder' => 'Select a table'
                    ]
                    ?>
                  {!! Form::select('table_id', $tables, null, $tableIdFormAttrs)!!}

                  @if($errors->has('table_id'))
                    <span class="text-danger">{{ $errors->first('table_id') }}</span>
                  @endif
                </div>
              </div>

              <div class="form-group m-t-md">
                <div class="text-center">
                  <button name="submit" type="submit" class="btn btn-sm btn-success">Continue</button>
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
  <script>
    window.history.forward();

    function noBack() {
      window.history.forward();
    }
  </script>
@endsection
