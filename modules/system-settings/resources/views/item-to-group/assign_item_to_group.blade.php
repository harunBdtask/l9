@extends('skeleton::layout')

@push('style')
    <style>

    </style>
@endpush
@section('content')

    <div class="padding">
        @if(Session::has('permission_of_item_to_group_add') || Session::has('permission_of_item_to_group_edit') || Session::get('user_role') == 'super-admin' || Session::get('user_role') == 'admin')
            <div class="col-md-8 col-md-offset-2">
                <div class="box item-group-assign">
                    <div class="box-header">
                        <h2>{{$item_group_assign ? "Items to Group Assign Update Form" : "Items to Group Assign Add Form"}}</h2>
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
                        {!! Form::model($item_group_assign, ['url' => $item_group_assign ? 'item-to-group/'.$item_group_assign->id.'/update' : 'item-to-group/item-group-assign', 'method' => $item_group_assign ? 'PUT' : 'POST']) !!}
                        @if(getRole() == "super-admin")
                            <div class="form-group">
                                <label for="select_factory_id" class="col-sm-2 form-control form-control-sm-label">Company </label>
                                <div class="col-sm-10">
                                    {!! Form::select('factory_id', $factories,null, ['class' => 'form-control form-control-sm select-2-plugin', 'id' => 'select_factory_id_s', 'placeholder' => 'Select Company']) !!}
                                    @if($errors->has('factory_id'))
                                        <span class="text-danger">{{ $errors->first('factory_id') }}</span>
                                    @endif
                                </div>
                            </div>
                        @else
                            {!! Form::hidden('factory_id', $factory_id, ['class' => 'form-control form-control-sm', 'id' => 'item_group_name', 'placeholder' => 'Write Item Group name here']) !!}
                        @endif
                        <div class="form-group">
                            <label for="brand_type" class="col-sm-2 form-control form-control-sm-label">Item Group </label>
                            <div class="col-sm-10">
                                {!! Form::select('item_group_id',$groups, null, ['class' => 'form-control form-control-sm  select-2-plugin','rows'=>'3','id' => 'item_group_id', 'placeholder' => 'Select Item Group']) !!}
                                @if($errors->has('item_group_id'))
                                    <span class="text-danger">{{ $errors->first('item_group_id') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="brand_type" class="col-sm-2 form-control form-control-sm-label">Item </label>
                            <div class="col-sm-10">
                                {!! Form::select('item_id', $items,null, ['class' => 'form-control form-control-sm select-2-plugin', 'id' => 'item_id', 'placeholder' => 'Select Item here']) !!}
                                @if($errors->has('item_id'))
                                    <span class="text-danger">{{ $errors->first('item_id') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="status" class="col-sm-2 form-control form-control-sm-label">Status</label>
                            <div class="col-sm-10">
                                {!! Form::select('status', IS_ACTIVE_STATUS, null, ['class' => 'form-control form-control-sm select-2-plugin','id' => 'status','required'=>'required']) !!}
                                @if($errors->has('status'))
                                    <span class="text-danger">{{ $errors->first('status') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="text-right">
                                <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> {{ $item_group_assign ? 'Update' : 'Assign' }}</button>
                                <a class="btn btn-danger" href="{{ url('item-to-group') }}"><i class="fa fa-remove"></i> Cancel</a>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
