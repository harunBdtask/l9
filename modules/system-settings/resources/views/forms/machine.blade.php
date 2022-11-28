@extends('skeleton::layout')
@section('title', 'Machine')
@section('content')
    <!-- ############ PAGE START-->
    <div class="padding">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h2>{{ $machine ? 'Update Machine' : 'New Machine' }}</h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body">
                        <div class="flash-message">
                            @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                                @if(Session::has('alert-' . $msg))
                                    <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</p>
                                @endif
                            @endforeach
                        </div>

                        {!! Form::model($machine, ['url' => $machine ? 'machines/'.$machine->id : 'machines', 'method' => $machine ? 'PUT' : 'POST']) !!}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="knitting_floor_id">Knitting Floor Type</label>
                                    {!! Form::select('knitting_floor_id', $knitting_floor, null, ['class' => 'form-control form-control-sm', 'id' => 'knitting_floor_id']) !!}

                                    @if($errors->has('knitting_floor_id'))
                                        <span class="text-danger">{{ $errors->first('knitting_floor_id') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="machine_type_info">Machine Type</label>
                                    {!! Form::select('machine_type_info', $machineType, null, ['class' => 'form-control form-control-sm', 'id' => 'machine_type_info']) !!}

                                    @if($errors->has('machine_type_info'))
                                        <span class="text-danger">{{ $errors->first('machine_type_info') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="machine_name">Machine Name</label>
                                    {!! Form::text('machine_name', null, ['class' => 'form-control form-control-sm', 'id' => 'machine_name', 'placeholder' => 'Write machine\'s name here']) !!}

                                    @if($errors->has('machine_name'))
                                        <span class="text-danger">{{ $errors->first('machine_name') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="machine_no">Machine No</label>
                                    {!! Form::text('machine_no', null, ['class' => 'form-control form-control-sm', 'id' => 'machine_no', 'placeholder' => 'Write machine\'s no here']) !!}

                                    @if($errors->has('machine_no'))
                                        <span class="text-danger">{{ $errors->first('machine_no') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="machine_type">Process Type</label>
                                    {!! Form::select('machine_type', [1 => 'Dying Machine',2 => 'Knitting Machine'], null, ['class' => 'form-control form-control-sm c-select', 'id' => 'machine_type', 'placeholder' => 'Select a machine type']) !!}

                                    @if($errors->has('machine_type'))
                                        <span class="text-danger">{{ $errors->first('machine_type') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="machine_rpm">Machine RPM</label>
                                    {!! Form::text('machine_rpm', null, ['class' => 'form-control form-control-sm', 'id' => 'machine_rpm', 'placeholder' => 'Write machine\'s rpm here']) !!}

                                    @if($errors->has('machine_rpm'))
                                        <span class="text-danger">{{ $errors->first('machine_rpm') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="machine_dia">Machine Dia</label>
                                    {!! Form::text('machine_dia', null, ['class' => 'form-control form-control-sm', 'id' => 'machine_dia', 'placeholder' => 'Write machine\'s dia here']) !!}

                                    @if($errors->has('machine_dia'))
                                        <span class="text-danger">{{ $errors->first('machine_dia') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="machine_gg">Machine GG</label>
                                    {!! Form::text('machine_gg', null, ['class' => 'form-control form-control-sm', 'id' => 'machine_gg', 'placeholder' => 'Write machine\'s gg here']) !!}

                                    @if($errors->has('machine_gg'))
                                        <span class="text-danger">{{ $errors->first('machine_gg') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="machine_capacity">Machine Capacity</label>
                                    {!! Form::text('machine_capacity', null, ['class' => 'form-control form-control-sm', 'id' => 'machine_capacity', 'placeholder' => 'Write machine\'s capacity here']) !!}

                                    @if($errors->has('machine_capacity'))
                                        <span class="text-danger">{{ $errors->first('machine_capacity') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    {!! Form::select('status', ['' => 'Select', 1 => 'Running', 2 => 'Blank', 3 => 'Stop'], null, ['class' => 'form-control form-control-sm c-select', 'id' => 'status']) !!}

                                    @if($errors->has('status'))
                                        <span class="text-danger">{{ $errors->first('status') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-sm white"><i class="fa fa-save"></i>
                                        &nbsp; {{ $machine ? 'Update' : 'Create' }}</button>
                                    <a class="btn btn-sm white" href="{{ url('machines') }}"> <i
                                                class="fa fa-times"></i> &nbsp; Cancel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
