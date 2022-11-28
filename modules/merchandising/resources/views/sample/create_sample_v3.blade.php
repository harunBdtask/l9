@extends('skeleton::layout')
@section('title', 'Sample')
@push('style')
    <style>
        .table > thead > tr > th,
        .table > tbody > tr > td,
        .table > tbody > tr > th,
        .table > tfoot > tr > td,
        .table > tfoot > tr > th {
            border: 1px solid #ddd;
            vertical-align: middle;
        }

        table thead tr th {
            white-space: nowrap;
        }

        .custom-select {
            width: 100%;
            height: 35px !important;
            margin: 0px 0px;
            border: 1px solid rgba(120, 130, 140, 0.2);
        }

        .datepicker {
            border-radius: 0px;
        }

        .input-group-addon {
            border-radius: 0px !important;
        }

        .suggetion {
            z-index: 1000;
            position: absolute;
        }

        .append-suggetion {
            padding: 0px;
            margin: 0px;
            width: 340px;
            border: 1px solid #ccc;
            min-height: 50px;
            max-height: 200px;
            overflow-y: scroll;
            background: #fff;
            /*margin-top: 40px;*/
        }

        .append-suggetion li {
            list-style: none;
            display: block;
            cursor: pointer;
            height: 40px;
            padding-left: 15px;
            line-height: 40px;
        }

        .append-suggetion li:hover {
            background: lightgoldenrodyellow;
        }

        .add-btn {
            padding: 3px;
            border-radius: 2px;
        }
    </style>
@endpush
@section('content')

    {{--@if ($errors->any())--}}
    {{--@foreach ($errors->all() as $error)--}}
    {{--<div>{{$error}}</div>--}}
    {{--@endforeach--}}
    {{--@endif--}}

    <div class="padding">
        @if(Session::has('permission_of_sample_add') || Session::get('user_role') == 'super-admin' || Session::get('user_role') == 'admin')
            <div class="box knit-card">
                <div class="box-header">
                    <h2>{{ $sample_development? 'Update ':'Add ' }} Sample</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="box-body b-t">
                    <div class="flash-message">
                        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                            @if(Session::has('alert-' . $msg))
                                <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</p>
                            @endif
                        @endforeach
                    </div>
                    {!! Form::model($sample_development, ['url' => $sample_development? 'sample/'.$sample_development->id.'/update' : 'sample/store', 'method' => $sample_development? 'PUT' : 'POST','files' => true]) !!}
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-4">
                                <label for="buyer_id" class=""> Buyer <span class="text-danger req">*</span></label>
                                {!! Form::select('buyer_id', $buyer,null, ['class' => 'form-control form-control-sm custom-select select2-input', 'id' => 'buyer_id', 'placeholder' => 'Select Buyer']) !!}
                                @if($errors->has('buyer_id'))
                                    <span class="text-danger">{{ $errors->first('buyer_id') }}</span>
                                @endif
                            </div>
                            <div class="col-md-4">
                                <label for="agent_id" class=""> Buying Agent <span
                                        class="text-danger req">*</span></label>
                                {!! Form::select('agent_id', $agent,null, ['class' => 'form-control form-control-sm custom-select select2-input', 'id' => 'agent_id', 'placeholder' => 'Select Agent']) !!}
                                @if($errors->has('agent_id'))
                                    <span class="text-danger">{{ $errors->first('agent_id') }}</span>
                                @endif
                            </div>
                            <div class="col-md-4">
                                <label for="sample_ref_no" class=""> Sample Ref. No <span
                                        class="text-danger req">*</span></label>
                                {!! Form::text('sample_ref_no',null, ['class' => 'form-control form-control-sm custom-select', 'id' => 'sample_ref_no', 'placeholder' => 'Sample Ref. No']) !!}
                                @if($errors->has('sample_ref_no'))
                                    <span class="text-danger">{{ $errors->first('sample_ref_no') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-4">
                                <label for="team_leader" class="">Team Leader <span
                                        class="text-danger req">*</span></label>
                                {!! Form::select('team_leader',$team,$user_team ?? null, ['class' => 'form-control form-control-sm custom-select select2-input', 'id' => 'team_leader','onchange'=>'get_dealing_merchant()', 'placeholder' => 'Select Team']) !!}
                                @if($errors->has('team_leader'))
                                    <span class="text-danger">{{ $errors->first('team_leader') }}</span>
                                @endif
                            </div>
                            <div class="col-md-4">
                                <label for="dealing_merchant" class="">Dealing Merchant</label>
                                {!! Form::select('dealing_merchant',$merchant,$user ?? null, ['class' => 'form-control form-control-sm custom-select select2-input', 'id' => 'dealing_merchant', 'placeholder' => 'Dealing Merchant']) !!}
                                @if($errors->has('dealing_merchant'))
                                    <span class="text-danger">{{ $errors->first('dealing_merchant') }}</span>
                                @endif
                            </div>
                            <div class="col-md-4">
                                <label for="recv_date" class="">Sample Receive Date / Order Confirmation Date <span
                                        class="text-danger req">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    {!! Form::date('receive_date',$sample_development->receive_date ?? null, ['class' => 'form-control form-control-sm', 'id' => 'recv_date', 'style' => 'line-height:1.25rem','autocomplete'=>'off']) !!}
                                </div>
                                @if($errors->has('receive_date'))
                                    <span class="text-danger">{{ $errors->first('receive_date') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-4">
                                <label for="team_leader" class="">Season <span class="text-danger req">*</span></label>
                                {!! Form::text('season',null, ['class' => 'form-control form-control-sm', 'id' => 'season', 'placeholder' => 'Season']) !!}
                                @if($errors->has('season'))
                                    <span class="text-danger">{{ $errors->first('season') }}</span>
                                @endif
                            </div>
                            <div class="col-md-4">
                                <label for="team_leader" class="">Currency <span
                                        class="text-danger req">*</span></label>
                                {!! Form::select('currency',$currency,null, ['class' => 'form-control form-control-sm ', 'id' => 'currency', 'placeholder' => 'Currency']) !!}
                                @if($errors->has('currency'))
                                    <span class="text-danger">{{ $errors->first('currency') }}</span>
                                @endif
                            </div>
                            <div class="col-md-4">
                                <label for="sample_files" class="">Sample file upload</label>
                                <br>
                                <div class="form-control form-control-sm">
                                    {!! Form::file('sample_files', [],null, ['class' => 'form-control form-control-sm', 'id' => 'sample_files']) !!}
                                </div>
                                @if($errors->has('sample_files'))
                                    <span class="text-danger">{{ $errors->first('sample_files') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <table class="table table-bordered artwork-form">
                            <thead>
                            <tr>
                                <th>Item <span class="text-danger req">*</span></th>
                                <th>Item Description <span class="text-danger req">*</span></th>
                                <th>Fabric Description <span class="text-danger req">*</span></th>
                                <th>Fabrication / Composition <span class="text-danger req">*</span></th>
                                <th>GSM <span class="text-danger req">*</span></th>
                                <th>Price <span class="text-danger req">*</span></th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(old('item_id'))
                                @foreach(old('item_id') as $keys => $old)
                                    <tr>
                                        <td> {!! Form::select('item_id[]',$items,null, ['class' => 'form-control form-control-sm item_id ', 'id' => 'item_id', 'placeholder' => 'Select Item','style' => $errors->has("item_id.$keys") ? 'border: 1px solid red;' : '']) !!}</td>
                                        <td> {!! Form::text('item_description[]',null, ['class' => 'form-control form-control-sm item_description', 'id' => 'item_description', 'placeholder' => 'Item Description','style' => $errors->has("item_description.$keys") ? 'border: 1px solid red;' : '']) !!}</td>
                                        <td> {!! Form::text('fabric_description[]',null, ['class' => 'form-control form-control-sm select2-input', 'id' => 'fabric_description', 'placeholder' => 'Fabric Description','style' => $errors->has("fabric_description.$keys") ? 'border: 1px solid red;' : '']) !!}</td>
                                        <td>{!! Form::select("composition_fabric_id[]",$fabrication_list, null, ['class' => 'form-control form-control-sm composition_fabric_id', 'id' => 'composition_fabric_id', 'style' => $errors->has("composition_fabric_id.$keys") ? 'border: 1px solid red;' : '','placeholder'=>'Fabrication']) !!}</td>
                                        <td> {!! Form::text('gsm[]',null, ['class' => 'custom-select gsm', 'id' => 'gsm', 'placeholder' => 'GSM','style' => $errors->has("gsm.$keys") ? 'border: 1px solid red;' : '']) !!}</td>
                                        <td> {!! Form::number('unit_price[]',null, ['class' => 'custom-select unit_price','step'=>'.01', 'id' => 'unit_price', 'placeholder' => 'Unit Price','style' => $errors->has("unit_price.$keys") ? 'border: 1px solid red;' : '']) !!}</td>
                                        <td class="action">
                                            <span class=" add-btn btn-success add"><i class="fa fa-plus"></i> </span>
                                        </td>
                                    </tr>
                                @endforeach
                            @elseif(isset($sample_development_details))
                                @foreach($sample_development_details as $key => $detail)
                                    <tr>
                                        <td>{!! Form::select('item_id[]',$items,isset($detail->item_id)? $detail->item_id : null, ['class' => 'form-control form-control-sm item_id','id'=>'item_id','style' => $errors->has("item_id") ? 'border: 1px solid red;' : '', 'placeholder' => 'Select Item']) !!} </td>
                                        <td> {!! Form::text('item_description[]',isset($detail->item_description)? $detail->item_description : null, ['class' => 'form-control form-control-sm item_description', 'id' => 'item_description', 'placeholder' => 'Item Description']) !!}</td>
                                        <td> {!! Form::text('fabric_description[]',isset($detail->fabric_description)? $detail->fabric_description : null, ['class' => 'form-control form-control-sm select2-input', 'id' => 'fabric_description', 'placeholder' => 'Fabric Description']) !!}</td>
                                        <td>{!! Form::select("composition_fabric_id[]",$fabrication_list, isset($detail->composition_fabric_id)? $detail->composition_fabric_id : null, ['class' => 'form-control form-control-sm composition', 'id' => 'composition', 'style' => $errors->has("fabrication") ? 'border: 1px solid red;' : '','placeholder'=>'Fabrication']) !!}</td>
                                        <td> {!! Form::text('gsm[]',isset($detail->gsm)? $detail->gsm : null, ['class' => 'custom-select gsm','id'=>'gsm', 'style' => $errors->has("gsm") ? 'border: 1px solid red;' : '', 'placeholder' => 'GSM']) !!}</td>
                                        <td> {!! Form::number('unit_price[]',isset($detail->unit_price)? $detail->unit_price : null, ['class' => 'custom-select unit_price','step'=>'.01', 'id' => 'unit_price', 'placeholder' => 'Unit Price','style' => $errors->has("unit_price") ? 'border: 1px solid red;' : '']) !!}</td>
                                        <td class="action">
                                            <span><i class="fa fa-add"></i> </span>
                                            <span class=" add-btn btn-success add"><i class="fa fa-plus"></i> </span>
                                            @if($key !=0)
                                                <span class=" add-btn btn-danger remove"><i
                                                        class="fa fa-remove"></i> </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td> {!! Form::select('item_id[]',$items,null, ['class' => 'form-control form-control-sm item_id', 'id' => 'item_id', 'placeholder' => 'Select Item']) !!}</td>
                                    <td> {!! Form::text('item_description[]',null, ['class' => 'form-control form-control-sm item_description', 'id' => 'item_description', 'placeholder' => 'Item Description']) !!}</td>
                                    <td> {!! Form::text('fabric_description[]',null, ['class' => 'form-control form-control-sm select2-input', 'id' => 'fabric_description', 'placeholder' => 'Fabric Description']) !!}</td>
                                    <td>{!! Form::select("composition_fabric_id[]",$fabrication_list, null, ['class' => 'form-control form-control-sm composition_fabric_id', 'id' => 'composition_fabric_id','placeholder'=>'Fabrication']) !!}</td>
                                    <td> {!! Form::text('gsm[]',null, ['class' => 'custom-select gsm', 'id' => 'gsm', 'placeholder' => 'GSM']) !!}</td>
                                    <td> {!! Form::number('unit_price[]', null, ['class' => 'custom-select unit_price','step'=>'.01', 'id' => 'unit_price', 'placeholder' => 'Unit Price']) !!}</td>
                                    <td class="action">
                                        <span class=" add-btn btn-success add"><i class="fa fa-plus"></i> </span>
                                    </td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-12">
                                <label for="agent" class=""> Remarks</label>
                                {!! Form::textarea('update_remark',$sample_development->remarks ?? null, ['class' => 'form-control form-control-sm', 'id' => '', 'placeholder' => '','rows'=>'2']) !!}
                                @if($errors->has('agent'))
                                    <span class="text-danger">{{ $errors->first('agent') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group m-t-md">
                            <div class="col-md-12 col-sm-12">
                                <button type="submit" class="btn btn-sm white"><i
                                        class="fa fa-save"></i> {{ $sample_development? 'Update' : 'Create' }}</button>
                                <a class="btn btn-sm white" href="{{ url('sample') }}"><i class="fa fa-remove"></i> Cancel</a>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        @endif
    </div>
@endsection

@push('script-head')
    <script>
        function get_dealing_merchant() {
            var team_leader_id = $('#team_leader').val();
            $.ajax({
                url: '{{url('get-dealing-merchant')}}',
                type: 'GET',
                data: {team_leader_id: team_leader_id},
                success: function (data) {
                    $('#dealing_merchant').html(data);
                }
            });
        }

        $(function () {
            $('select').select2();
            $('#create_type').on('change', function () {
                var create_type = $(this).val();
                if (create_type == 2) {
                    $('.unit_price').removeAttr("readonly");
                } else {
                    $('.unit_price').attr('readonly', true);
                }
            });

            $('body').on('click', '.add', function () {
                $(this).parents('tr').find('select').each(function (index) {
                    if ($(this).data('select2')) {
                        $(this).select2('destroy');
                    }
                });
                var Last_row_data = $(this).parents('tr').clone(true)
                var has_class = Last_row_data.find('.add-btn').hasClass('remove');

                var selected_item_val = $(this).parents('tr').find('.item_id').val();
                Last_row_data.find('.item_id').val(selected_item_val);


                if (has_class) {
                    $('.artwork-form').find('tbody:last').append(Last_row_data);
                } else {
                    Last_row_data.find('.action').append('<span class=" add-btn btn-danger remove"><i class="fa fa-remove"></i> </span>');
                    $('.artwork-form').find('tbody:last').append(Last_row_data);
                }
                $('select').select2();
            });
            $('body').on('click', '.remove', function () {
                $(this).closest('tr').remove();
            });

            // composition dropdown
            $('body').on('keyup', '.composition', function () {
                var composition_value = $(this).val();
                $(this).parents('tr td').find('.composition_id').val('');
                $.ajax({
                    url: '{{url('get-composition-list')}}',
                    type: 'GET',
                    context: this,
                    data: {_token: '<?php echo csrf_token()?>', composition_value: composition_value},
                    success: function (data) {
                        $(this).parent().find('.suggetion').html(data);
                    }
                });
            });

            $('body').on('click', '.composition-list', function () {
                var composition_list_val = $(this).text();
                var composition_id = $(this).attr('id');
                $(this).parents('tr td').find('.composition').val(composition_list_val);
                $(this).parents('tr td').find('.composition_id').val(composition_id);
                $(this).parents('tr td').find('.append-suggetion').hide();
            });

        });
    </script>
@endpush
