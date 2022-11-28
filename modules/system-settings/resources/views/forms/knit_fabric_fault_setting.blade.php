@extends('skeleton::layout')
@section('title', 'Knit Fabric Fault')
@section('content')
    <!-- ############ PAGE START-->
    <div class="padding">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="box">
                    <div class="box-header">
                        <h2>{{ $knit_fabric_fault ? 'Update Knit Fabric Fault' : 'New Knit Fabric Fault' }}</h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body b-t">
                        <div class="flash-message">
                            @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                                @if(Session::has('alert-' . $msg))
                                    <div class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</div>
                                @endif
                            @endforeach
                        </div>
                        {!! Form::model($knit_fabric_fault, ['url' => $knit_fabric_fault ? 'knit_fabric_fault_settings/'.$knit_fabric_fault->id : 'knit_fabric_fault_settings', 'method' => $knit_fabric_fault ? 'PUT' : 'POST']) !!}
                        <div class="form-group">
                            <label for="from">Sequence</label>
                            {!! Form::text('sequence', null, ['class' => 'form-control form-control-sm', 'id' => 'sequence', 'placeholder' => 'Sequence']) !!}

                            @if($errors->has('sequence'))
                                <span class="text-danger">{{ $errors->first('sequence') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="to">Fault Name</label>
                            {!! Form::text('name', null, ['class' => 'form-control form-control-sm', 'id' => 'name', 'placeholder' => 'Name']) !!}

                            @if($errors->has('name'))
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="to">Status</label>
                            {!! Form::select('status', [1 => 'Active', 2 => 'Inactive'], 1, ['class' => 'form-control form-control-sm', 'id' => 'status']) !!}

                            @if($errors->has('status'))
                                <span class="text-danger">{{ $errors->first('status') }}</span>
                            @endif
                        </div>
                        <div class="form-group m-t-md">
                            <button type="submit" class="btn btn-sm white"> <i class="fa fa-save"></i> &nbsp; {{ $knit_fabric_fault ? 'Update' : 'Create' }}</button>
                            <a class="btn btn-sm btn-dark" href="{{ url('knit_fabric_fault_settings') }}"> <i class="fa fa-times"></i> &nbsp; Cancel</a>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ############ PAGE END-->
@endsection



