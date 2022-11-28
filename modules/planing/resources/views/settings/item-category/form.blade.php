@extends('skeleton::layout')
@section('title', 'Item Category')
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="box form-colors">
                    <div class="box-header">
                        <h2>{{ $itemCategory ? 'Update Item Category' : 'New Item Category' }}</h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body">
                        {!! Form::model($itemCategory, [
                            'url' => $itemCategory ? '/planning/settings/item-categories/'.$itemCategory->id : '/planning/settings/item-categories',
                            'method' => $itemCategory ? 'PUT' : 'POST'])
                        !!}
                        <div class="form-group">
                            <label for="name">Name</label>
                            {!! Form::text('name', null, ['class' => 'form-control form-control-sm', 'id' => 'name', 'placeholder' => 'Write name here']) !!}

                            @if($errors->has('name'))
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="name">SMV Range Start From</label>
                            {!! Form::text('smv_from', null, ['class' => 'form-control form-control-sm', 'id' => 'name']) !!}

                            @if($errors->has('smv_from'))
                                <span class="text-danger">{{ $errors->first('smv_from') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="name">SMV Range End At</label>
                            {!! Form::text('smv_to', null, ['class' => 'form-control form-control-sm', 'id' => 'name']) !!}

                            @if($errors->has('smv_to'))
                                <span class="text-danger">{{ $errors->first('smv_to') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-sm btn-success"><i
                                    class="fa fa-save"></i> {{ $itemCategory ? 'Update' : 'Create' }}</button>
                            <a class="btn btn-sm btn-warning"
                               href="{{ route('planning.settings.item-categories.index') }}"><i
                                    class="fa fa-remove"></i>
                                Cancel</a>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
