@extends('finance::layout')

@section('title', ($actualDepartment ? 'Update Department' : 'New Department'))
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="box">
                    <div class="box-header">
                        <h2>{{ $actualDepartment ? 'Update Department' : 'New Department' }}</h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body">
                        <div class="flash-message">
                            @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                                @if(Session::has('alert-' . $msg))
                                    <div class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</div>
                                @endif
                            @endforeach
                        </div>
                        <div class="row">
                            <div class="col-md-12">

                                {!! Form::model($actualDepartment, ['url' => $actualDepartment ? 'finance/ac-departments/'.$actualDepartment->id : 'finance/ac-departments', 'method' => $actualDepartment ? 'PUT' : 'POST', 'files' => true]) !!}
                                <div class="form-group">
                                    <label for="ac_company_id">Company</label>
                                    {!! Form::select('ac_company_id', $companies, null, ['class' => 'form-control form-control-sm select2-input', 'id' => 'ac_company_id']) !!}

                                    @if($errors->has('ac_company_id'))
                                        <span class="text-danger">{{ $errors->first('ac_company_id') }}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="ac_unit_id">Project</label>
                                    {!! Form::select('ac_unit_id', $units, null, ['class' => 'form-control form-control-sm select2-input', 'id' => 'ac_unit_id']) !!}

                                    @if($errors->has('ac_unit_id'))
                                        <span class="text-danger">{{ $errors->first('ac_unit_id') }}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="ac_cost_center_id">Cost Center</label>
                                    {!! Form::select('ac_cost_center_id', $departments, null, ['class' => 'form-control form-control-sm select2-input', 'id' => 'ac_cost_center_id']) !!}

                                    @if($errors->has('ac_cost_center_id'))
                                        <span class="text-danger">{{ $errors->first('ac_cost_center_id') }}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="name">Department</label>
                                    {!! Form::text('name', null, ['class' => 'form-control form-control-sm', 'id' => 'name', 'placeholder' => 'Write sub cost center name here']) !!}

                                    @if($errors->has('name'))
                                        <span class="text-danger">{{ $errors->first('name') }}</span>
                                    @endif
                                </div>
                                <div class="form-group m-t-md">
                                    <button type="submit" class="btn white">{{ $actualDepartment ? 'Update' : 'Create' }}</button>
                                    <a class="btn white" href="{{ url('finance/ac-departments') }}">Cancel</a>
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
