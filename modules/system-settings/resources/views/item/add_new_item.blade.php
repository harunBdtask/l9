@extends('skeleton::layout')
@section('title', 'Item')
@push('style')
    <style>

    </style>
@endpush
@section('content')

    <div class="padding">
        @if(Session::has('permission_of_item_add') || Session::get('user_role') == 'super-admin'|| Session::get('user_role') == 'admin')
            <div class="col-md-6 col-md-offset-3">
                <div class="box" >
                    <div class="box-header">
                        <h2>{{ $items ? 'Update Item' : 'New Item' }}</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="box-body b-t">
                        <div class="flash-message">
                            @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                                @if(Session::has('alert-' . $msg))
                                    <p class="alert alert-{{ $msg }} text-center">{{ Session::get('alert-' . $msg) }}</p>
                                @endif
                            @endforeach
                        </div>
                        {!! Form::model($items, ['url' => $items ? 'item/'.$items->id.'/update' : 'item/items-store', 'method' => $items ? 'PUT' : 'POST']) !!}
                        <div class="form-group">
                            <label for="item_name" class="col-sm-2 form-control form-control-sm-label">Item Name</label>
                            <div class="col-sm-10">
                                {!! Form::text('item_name', null, ['class' => 'form-control form-control-sm', 'id' => 'item_name', 'placeholder' => 'Write Items\'s name here']) !!}

                                @if($errors->has('item_name'))
                                    <span class="text-danger">{{ $errors->first('item_name') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="item_manufacturer" class="col-sm-2 form-control form-control-sm-label">Item Manufacturer </label>
                            <div class="col-sm-10">
                                {!! Form::text('item_manufacturer', null, ['class' => 'form-control form-control-sm', 'id' => 'item_manufacturer', 'placeholder' => 'Write Item manufacturer\'s name here']) !!}

                                @if($errors->has('item_manufacturer'))
                                    <span class="text-danger">{{ $errors->first('item_manufacturer') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="item_desc" class="col-sm-2 form-control form-control-sm-label">Item Description </label>
                            <div class="col-sm-10">
                                {!! Form::textarea('item_desc', null, ['class' => 'form-control form-control-sm','rows'=>'3','id' => 'item_desc', 'placeholder' => 'Write Item Description Here']) !!}

                                @if($errors->has('item_desc'))
                                    <span class="text-danger">{{ $errors->first('item_desc') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="uom_id" class="col-sm-2 form-control form-control-sm-label">UOM</label>
                            <div class="col-sm-10">
                                {!! Form::select('uom_id', $uom ?? [], null, ['class' => 'form-control form-control-sm c-select','id' => 'uom_id','required'=>'required']) !!}

                                @if($errors->has('uom_id'))
                                    <span class="text-danger">{{ $errors->first('uom_id') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="status" class="col-sm-2 form-control form-control-sm-label">Status</label>
                            <div class="col-sm-10">
                                {!! Form::select('status', IS_ACTIVE_STATUS, null, ['class' => 'form-control form-control-sm c-select','id' => 'status','required'=>'required']) !!}

                                @if($errors->has('status'))
                                    <span class="text-danger">{{ $errors->first('status') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="text-right">
                                <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-save"></i> {{ $items ? 'Update' : 'Create' }}</button>
                                <a class="btn btn-sm btn-danger" href="{{ url('item') }}"><i class="fa fa-remove"></i> Cancel</a>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
