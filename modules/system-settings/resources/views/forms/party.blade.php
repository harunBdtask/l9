@extends('skeleton::layout')
@section("title","Party")
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="box">
                    <div class="box-header">
                        <h2>{{ $parties ? 'Update Party' : 'New Party'}}</h2>
                    </div>{{-- .box-header --}}
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body b-t form-colors">
                        {!! Form::model($parties, ['url' => $parties ? 'parties/'.$parties->id : 'parties', 'method' => $parties ? 'PUT' : 'POST']) !!}
                        <div class="form-group">
                            <label for="party_name">Party Name</label>
                            {!! Form::text('party_name', null, ['class' => 'form-control form-control-sm', 'id' => 'party_name', 'placeholder' => 'Write Party Name here']) !!}

                            @if($errors->has('party_name'))
                                <span class="text-danger">{{ $errors->first('party_name') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="party_type">Party Type</label>
                            {!! Form::select('party_type_id', $party_types, null, ['class' => 'form-control form-control-sm c-select', 'id' => 'party_type', 'placeholder' => 'Select a party type']) !!}

                            @if($errors->has('party_type_id'))
                                <span class="text-danger">{{ $errors->first('party_type_id') }}</span>
                            @endif
                        </div>
                        <div class="form-group m-t-md">
                            <button type="submit" class="btn btn-sm btn-success">{{ $parties ? 'Update' : 'Create' }}</button>
                            <a class="btn btn-sm btn-warning" href="{{ url('parties') }}">Cancel</a>
                        </div>
                        {!! Form::close() !!}

                        {{--<form action="{{URL::to('save-party-action')}}" method="post">--}}
                        {{--<input type="hidden" party_name="_token" value="{{csrf_token()}}">--}}
                        {{--<div class="row">--}}
                        {{--<div class="col-sm-6">--}}
                        {{--<div class="form-group">--}}
                        {{--<label for="party_name" class="control-label"> Party Name* </label>--}}
                        {{--<input type="text" class="form-control form-control-sm party-party_name" party_name="party_name" value="{{old('party_name')}}" placeholder="Party Name" required>--}}
                        {{--</div>--}}{{-- .form-group --}}
                        {{--<span class="validator_output">{{ $errors->first('party_name') }}</span>--}}
                        {{--</div>--}}{{-- .col-sm-6 --}}
                        {{--<div class="col-sm-6">--}}
                        {{--<div class="form-group">--}}
                        {{--<label for="party_type_id" class="control-label"> Party Types* </label>--}}
                        {{--<select class="form-control form-control-sm" party_name="party_type_id" required>--}}
                        {{--<option value="">Select Party Type</option>--}}
                        {{--@foreach($party_types as $party)--}}
                        {{--<option value="{{$party->id}}" {{old('party_type_id') == $party->id ? 'selected' : ''}}>{{$party->party_type}}</option>--}}
                        {{--@endforeach--}}
                        {{--</select>--}}
                        {{--</div>--}}{{-- .form-group --}}
                        {{--<span class="validator_output">{{ $errors->first('party_type_id') }}</span>--}}
                        {{--</div>--}}{{-- .col-sm-6 --}}
                        {{--</div>--}}{{-- .row --}}
                        {{--<div class="row">--}}
                        {{--<div class="col-sm-12">--}}
                        {{--<input type="submit" value="Submit" class="btn btn-success btn-sm pull-right">--}}
                        {{--</div>--}}{{-- .col-sm-12 --}}
                        {{--</div>--}}{{-- .row --}}
                        {{--</form>--}}
                    </div>{{-- .box-body --}}
                </div>{{-- .box --}}
            </div>
        </div>
    </div>{{-- .padding --}}
@endsection
