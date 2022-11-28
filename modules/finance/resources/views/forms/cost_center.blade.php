@extends('finance::layout')

@section('title', ($costCenter ? 'Update Cost Center' : 'New Cost Center'))
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="box">
                    <div class="box-header">
                        <h2>{{ $costCenter ? 'Update Cost Center' : 'New Cost Center' }}</h2>
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

                                {!! Form::model($costCenter, ['url' => $costCenter ? 'finance/cost-centers/'.$costCenter->id : 'finance/cost-centers', 'method' => $costCenter ? 'PUT' : 'POST', 'files' => true]) !!}
                                <div class="form-group">
                                    <label for="cost_center">Cost Center *</label>
                                    {!! Form::text('cost_center', null, ['class' => 'form-control form-control-sm', 'id' => 'cost_center', 'placeholder' => 'Write cost center name here']) !!}

                                    @if($errors->has('cost_center'))
                                        <span class="text-danger">{{ $errors->first('cost_center') }}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="cost_center_details">Cost Center Details</label>
                                    {!! Form::text('cost_center_details', null, ['class' => 'form-control form-control-sm', 'id' => 'cost_center_details', 'placeholder' => 'Write cost center details here']) !!}

                                    @if($errors->has('cost_center_details'))
                                        <span class="text-danger">{{ $errors->first('cost_center_details') }}</span>
                                    @endif
                                </div>
                                <div class="form-group m-t-md">
                                    <button type="submit" class="btn btn-success">{{ $costCenter ? 'Update' : 'Create' }}</button>
                                    <a class="btn btn-danger" href="{{ url('finance/cost-centers') }}">Cancel</a>
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
