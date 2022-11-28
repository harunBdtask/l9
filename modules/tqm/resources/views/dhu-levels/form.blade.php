@extends('tqm::layout')
@section('title', $dhuLevel ? 'Update DHU Level' : 'New DHU Level')
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="box form-colors">
                    <div class="box-header">
                        <h2>{{ $dhuLevel ? 'Update DHU Level' : 'New DHU Level' }}</h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-12">
                                @include('partials.response-message')
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                {!! Form::model($dhuLevel, ['url' => $dhuLevel ? 'tqm-dhu-levels/'.$dhuLevel->id : 'tqm-dhu-levels', 'method' => $dhuLevel ? 'PUT' : 'POST', 'onsubmit' => "document.getElementById('submit').disabled=true;"]) !!}
                                <div class="form-group">
                                    <label for="factory_id">Factory</label>
                                    {!! Form::select('factory_id', $factory_options ?? [], null, ['class' => 'form-control form-control-sm', 'id' => 'factory_id']) !!}

                                    @if($errors->has('factory_id'))
                                        <span class="text-danger">{{ $errors->first('factory_id') }}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="section">Section</label>
                                    {!! Form::select('section', $sections ?? [], null, ['class' => 'form-control form-control-sm', 'id' => 'section']) !!}

                                    @if($errors->has('section'))
                                        <span class="text-danger">{{ $errors->first('section') }}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="name">Level</label>
                                    {!! Form::number('level', null, ['class' => 'form-control form-control-sm', 'id' => 'level', 'step' => ".01", 'placeholder' => 'Write level here']) !!}

                                    @if($errors->has('level'))
                                        <span class="text-danger">{{ $errors->first('level') }}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="section">Comparison Status</label>
                                    <select class="form-control form-control-sm" name="comparison_status">
                                        @foreach($comparison_statuses as $key => $status)
                                            <option
                                                {{ $dhuLevel ? $key == $dhuLevel->comparison_status ? 'selected' : '' : null}} value="{{ $key }}">
                                                {{ $status }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @if($errors->has('comparison_status'))
                                        <span class="text-danger">{{ $errors->first('comparison_status') }}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="name">Color</label>
                                    <div style="width: 150px;">
                                        {!! Form::color('color', null, ['class' => 'form-control form-control-sm', 'id' => 'color', 'placeholder' => 'Write color here']) !!}
                                    </div>

                                    @if($errors->has('color'))
                                        <span class="text-danger">{{ $errors->first('color') }}</span>
                                    @endif
                                </div>
                                <div class="form-group" style="margin-top: 30px;">
                                    <button type="submit" id="submit" class="btn btn-sm btn-success"><i
                                            class="fa fa-save"></i> {{ $dhuLevel ? 'Update' : 'Create' }}</button>
                                    <a class="btn btn-sm btn-warning" href="{{ url('tqm-dhu-levels') }}"><i
                                            class="fa fa-remove"></i> Cancel</a>
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
