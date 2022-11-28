@extends('skeleton::layout')
@section("title","Knitting Floor")
@section('content')
    <!-- ############ PAGE START-->
    <div class="padding">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="box">
                    <div class="box-header">
                        <h2>{{ $knittingFloor ? 'Update Knitting Floor' : 'New Knitting Floor' }}</h2>
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
                        {!! Form::model($knittingFloor, ['url' => $knittingFloor ? 'knitting-floor/'.$knittingFloor->id : 'knitting-floor', 'method' => $knittingFloor ? 'PUT' : 'POST']) !!}
                        <div class="form-group">
                            <label for="name">Knitting Floor Name</label>
                            {!! Form::text('name', null, ['class' => 'form-control form-control-sm', 'id' => 'name', 'placeholder' => 'Write knitting floor\'s name here']) !!}

                            @if($errors->has('name'))
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="sequence">Knitting Floor Sequence</label>
                            {!! Form::text('sequence', null, ['class' => 'form-control form-control-sm', 'id' => 'sequence']) !!}

                            @if($errors->has('sequence'))
                                <span class="text-danger">{{ $errors->first('sequence') }}</span>
                            @endif
                        </div>
                        <div class="form-group m-t-md">
                            <button type="submit" class="btn btn-sm white">{{ $knittingFloor ? 'Update' : 'Create' }}</button>
                            <a class="btn btn-sm btn-dark" href="{{ url('knitting-floor') }}">Cancel</a>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ############ PAGE END-->
@endsection



