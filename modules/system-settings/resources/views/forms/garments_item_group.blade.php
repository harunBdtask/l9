@extends('skeleton::layout')
@section("title","Garments Item Group")
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="box" >
                    <div class="box-header">
                        <h2>{{ $garmentsItemGroup ? 'Update Garments Item Group' : 'New Garments Item Group' }}</h2>
                    </div>
                    <div class="box-body">
                        {!! Form::model($garmentsItemGroup, ['url' => $garmentsItemGroup ? 'garments-item-group/'.$garmentsItemGroup->id : 'garments-item-group', 'method' => $garmentsItemGroup ? 'PUT' : 'POST']) !!}

                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="form-group">
                                    <label for="name">Garments Item Group</label>
                                    {!! Form::text('name', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'name', 'placeholder' => 'Garments Item Group']) !!}
                                    @if($errors->has('name'))
                                        <span class="text-danger">{{ $errors->first('name') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-sm white"><i class="fa fa-save"></i> {{ $garmentsItemGroup ? 'Update' : 'Create' }}</button>
                                    <a class="btn btn-sm btn-dark" href="{{ url('garments-item-group') }}"><i class="fa fa-remove"></i> Cancel</a>
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
