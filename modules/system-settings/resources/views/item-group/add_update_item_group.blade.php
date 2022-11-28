@extends('skeleton::layout')
@section("title","Items Group")
@push('style')
    <style>

    </style>
@endpush
@section('content')
    <div class="padding">
        @if(Session::has('permission_of_item_add') || Session::has('permission_of_item_group_add') || Session::get('user_role') == 'super-admin' || Session::get('user_role') == 'admin')
            <div class="col-md-8 col-md-offset-2">
                <div class="box">
                    <div class="box-header">
                        <h2>{{ $item_group ? 'Update Item Group' : 'New Item Group' }}</h2>
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

                        {!! Form::model($item_group, ['url' => $item_group ? 'item-group/'.$item_group->id.'/update' : 'item-group/item-group-store', 'method' => $item_group ? 'PUT' : 'POST']) !!}
                        <div class="row">
                            <div class="col-sm-12 col-md-4">
                                @if(getRole() == 'super-admin')
                                    <div class="form-group">
                                        <label for="factory_id">Company Name</label>
                                        {!! Form::select('factory_id', $factories, null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm c-select','id' => 'factory_id','required'=>'required']) !!}

                                        @if($errors->has('factory_id'))
                                            <span class="text-danger">{{ $errors->first('factory_id') }}</span>
                                        @endif
                                    </div>
                                @else
                                    {!! Form::hidden('factory_id', $factory_id, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'factory_id']) !!}
                                @endif
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label for="item_id">Item</label>
                                    {!! Form::select('item_id', $items, null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm c-select','id' => 'item_id', 'placeholder' => 'Select Item']) !!}

                                    @if($errors->has('item_id'))
                                        <span class="text-danger">{{ $errors->first('item_id') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label for="item_subgroup_id">Item Subgroup</label>
                                    {!! Form::select('item_subgroup_id', $item_subgroups, null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm c-select','id' => 'item_subgroup_id', 'placeholder' => 'Select Item Subgroup']) !!}

                                    @if($errors->has('item_subgroup_id'))
                                        <span class="text-danger">{{ $errors->first('item_subgroup_id') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label for="group_code">Group Code</label>
                                    {!! Form::text('group_code', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'group_code', 'placeholder' => 'Group Code']) !!}

                                    @if($errors->has('group_code'))
                                        <span class="text-danger">{{ $errors->first('group_code') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label for="item_group">Item Group </label>
                                    {!! Form::text('item_group', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'item_group', 'placeholder' => 'Item Group']) !!}

                                    @if($errors->has('item_group'))
                                        <span class="text-danger">{{ $errors->first('item_group') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label for="trims_type">Trims Type</label>
                                    {!! Form::select('trims_type', ['Sewing Trims' => 'Sewing Trims', 'Finishing Trims' => 'Finishing Trims'], null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm c-select','id' => 'trims_type', 'placeholder' => 'Select Trims']) !!}

                                    @if($errors->has('trims_type'))
                                        <span class="text-danger">{{ $errors->first('trims_type') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label for="order_uom">Order UOM</label>
                                    {!! Form::select('order_uom', $uom, null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm c-select','id' => 'order_uom', 'placeholder' => 'Select Order UOM']) !!}

                                    @if($errors->has('order_uom'))
                                        <span class="text-danger">{{ $errors->first('order_uom') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label for="cons_uom">Cons. UOM</label>
                                    {!! Form::select('cons_uom', $uom, null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm c-select','id' => 'cons_uom', 'placeholder' => 'Select Cons. UOM']) !!}

                                    @if($errors->has('cons_uom'))
                                        <span class="text-danger">{{ $errors->first('cons_uom') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label for="conv_factor">Conv. Factor</label>
                                    {!! Form::text('conv_factor', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'conv_factor', 'placeholder' => 'Conv. Factor']) !!}

                                    @if($errors->has('conv_factor'))
                                        <span class="text-danger">{{ $errors->first('conv_factor') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label for="fancy_item">Fancy Item</label>
                                    {!! Form::text('fancy_item', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'fancy_item', 'placeholder' => 'Fancy Item']) !!}

                                    @if($errors->has('fancy_item'))
                                        <span class="text-danger">{{ $errors->first('fancy_item') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label for="group_id">Group</label>
                                    {!! Form::select('group_id', $groups, null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm c-select', 'id' => 'group_id']) !!}

                                    @if($errors->has('group_id'))
                                        <span class="text-danger">{{ $errors->first('group_id') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label for="control_ledger">Control Ledger</label>
                                    {!! Form::select('control_ledger', $control_ledgers, null, ['class' => 'form-control select2-input form-control-sm form-control form-control-sm-sm c-select', 'id' => 'control_ledger']) !!}

                                    @if($errors->has('control_ledger'))
                                        <span class="text-danger">{{ $errors->first('control_ledger') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label for="ledger_ac">Ledger A/C</label>
                                    {!! Form::select('ledger_ac', $ledger_accounts??[], $item_group->ledger_ac??null, ['class' => 'form-control form-control-sm select2-input form-control form-control-sm-sm c-select', 'id' => 'ledger_ac']) !!}

                                    @if($errors->has('ledger_ac'))
                                        <span class="text-danger">{{ $errors->first('ledger_ac') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-sm white"><i
                                            class="fa fa-save"></i> {{ $item_group ? 'Update' : 'Create' }}</button>
                                    <a class="btn btn-sm btn-dark" href="{{ url('item-group') }}"><i
                                            class="fa fa-remove"></i> Cancel</a>
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
@push('script-head')
    <script>
        $(document).on('change', '#control_ledger', function () {
            let control_ledger = $(this).val();
            let urlLink = "/finance/api/v1/get-ledger-account-list-by-control-ac/"+control_ledger;
            $.ajax({
                method: 'get',
                url: urlLink ,
                dataType:'json',
                success: function (result) {
                    $('#ledger_ac').html('');
                    if(result.length > 0){

                        $('#ledger_ac').html('');
                        var newOption = new Option('Select', '', false, false);
                        $('#ledger_ac').append(newOption).trigger('change');

                        result.forEach(element => {
                            var newOption = new Option(element.text, element.id, false, false);
                            $('#ledger_ac').append(newOption).trigger('change');
                        });
                    }

                },
                error: function (xhr) {
                    console.log(xhr)
                }
            })
        })
    </script>
@endpush

