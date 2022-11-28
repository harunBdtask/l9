@extends('skeleton::layout')
@section('title', 'Guide or Folders')

@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="box">
                    <div class="box-header">
                        <h2>{{ 'Guide or folder' }}</h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body form-colors">
                        {!! Form::model($guide_or_folder, ['url' => $guide_or_folder ? 'guide-or-folders/'.$guide_or_folder->id : 'guide-or-folders', 'method' => $guide_or_folder ? 'PUT' : 'POST']) !!}

                        <div class="form-group">
                            <label for="name">Name</label>
                            {!! Form::text('name', null, ['class' => 'form-control form-control-sm', 'id' => 'name', 'placeholder' => 'Write guide or folder\'s name here']) !!}

                            @if($errors->has('name'))
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                        <div class="form-group m-t-md">
                            <button type="submit"
                                    class="btn btn-success">{{ $guide_or_folder ? 'Update' : 'Create' }}</button>
                            <a class="btn btn-warning" href="{{ url('guide-or-folders') }}">Cancel</a>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
