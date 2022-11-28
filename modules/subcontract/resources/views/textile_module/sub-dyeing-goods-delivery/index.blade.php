@extends('subcontract::layout')
@section("title","Sub Dyeing Goods Delivery")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Sub Dyeing Goods Delivery</h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        @include('partials.response-message')
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 m-b">
                        <a href="{{ url('subcontract/sub-dyeing-goods-delivery/create') }}"
                           class="btn btn-sm btn-info m-b">
                            <em class="fa fa-plus"></em>&nbsp;Create
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="reportTable">
                            <thead>
                            <tr>
                                <th>SL</th>
                                <th>Company</th>
                                <th>Party</th>
                                <th>Entry Basis</th>
                                <th>Goods Delivery UID</th>
                                <th>Batch/Order No</th>
                                <th>Color</th>
                                <th>Roll Qty</th>
                                <th>Del. Qty</th>
                                <th>Dyeing Unit</th>
                                <th>Del. Date</th>
                                <th>Shift</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            {!! Form::open(['url'=>'/subcontract/sub-dyeing-goods-delivery', 'method'=>'GET']) !!}
                            <tr>
                                <td>*</td>
                                <td>
                                    {!! Form::select('factory_id', $factories ?? [], request('factory_id') ?? null, [
                                        'class'=>'text-center select2-input'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::select('party_id', $suppliers ?? [], request('party_id'),[
                                      'class'=>'text-center form-control form-control-sm select2-input', 'id'=>'party_id'
                                  ]) !!}

                                </td>
                                <td>
                                    {!! Form::select('entry_basis', ['0'=>'Select','1'=>'BATCH', '2'=>'ORDER'], request('entry_basis'),[
                                      'class'=>'text-center form-control form-control-sm select2-input', 'id'=>'entry_basis'
                                  ]) !!}
                                </td>
                                <td>
                                    {!! Form::select('goods_delivery_uid', $goodsDeliveryUID ?? [], request('goods_delivery_uid'),[
                                      'class'=>'text-center form-control form-control-sm select2-input', 'id'=>'party_id'
                                  ]) !!}
                                </td>
                                <td>
                                    {!! Form::text('order_batch_no', request('order_batch_no') ?? null, [
                                        'class'=>'text-center form-control form-control-sm'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::text('color', request('color') ?? null, [
                                        'class'=>'text-center form-control form-control-sm'
                                    ]) !!}
                                </td>
                                <td></td>
                                <td></td>
                                <td>
                                    {!! Form::select('dyeing_unit', $dyeingUnits ?? [], request('dyeing_unit'),[
                                      'class'=>'text-center form-control form-control-sm select2-input', 'id'=>'dyeing_unit'
                                  ]) !!}
                                </td>
                                <td>
                                    {!! Form::date('delivery_date', request('delivery_date') ?? null, [
                                        'class'=>'text-center form-control form-control-sm'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::select('shift',$shifts ?? [], request('shift') ?? null, [
                                        'class'=>'text-center form-control form-control-sm select2-input'
                                    ]) !!}
                                </td>
                                <td>
                                    <button class="btn btn-xs white" type="submit">
                                        <em class="fa fa-search"></em>
                                    </button>
                                </td>
                            </tr>
                            {!! Form::close() !!}
                            <tr>
                                <td colspan="12">&nbsp;</td>
                            </tr>
                            @foreach ($subDyeingGoodsDeliveries as $data)
                                @php
                                    $batchNos = collect($data->batch_no)->implode(', ');
                                    $orderNos = collect($data->order_no)->implode(', ');
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $data->factory->factory_name }}</td>
                                    <td>{{ $data->supplier->name  }}</td>
                                    <td>{{ $data->entry_basis_value }}</td>
                                    <td>{{ $data->goods_delivery_uid }}</td>
                                    <td>{{ $data->entry_basis == 1 ? $batchNos : $orderNos }}</td>
                                    <td>
                                        {{
                                            $data->subDyeingGoodsDeliveryDetails
                                                ->pluck('color.name')->unique()->implode(', ')
                                        }}
                                    </td>
                                    <td>{{ $data->subDyeingGoodsDeliveryDetails->sum('total_roll') }}</td>
                                    <td>{{ $data->subDyeingGoodsDeliveryDetails->sum('delivery_qty') }}</td>
                                    <td>{{ $data->subDyeingUnit->name }}</td>
                                    <td>{{ $data->delivery_date }}</td>
                                    <td>{{ $data->shift->shift_name }}</td>
                                    <td>

                                        @permission('permission_of_sub_goods_delivery_edit')
                                        <a class="btn btn-xs btn-info" type="button"
                                           href="{{ url('subcontract/sub-dyeing-goods-delivery/create?id=' . $data->id) }}">
                                            <em class="fa fa-pencil"></em>
                                        </a>
                                        @endpermission

                                        @permission('permission_of_sub_goods_delivery_view')
                                        <a class="btn btn-success btn-xs" title="GATE PASS" type="button"
                                           href="{{ url('subcontract/sub-dyeing-goods-delivery/gate-pass-view/'.$data->id) }}">
                                            <em class="fa fa-eye"></em>
                                        </a>
                                        @endpermission

                                        @permission('permission_of_sub_goods_delivery_view')
                                        <a class="btn btn-warning btn-xs" title="CHALLAN AND GATE PASS" type="button"
                                           href="{{ url('subcontract/sub-dyeing-goods-delivery/challan-and-gate-pass-view/'.$data->id) }}">
                                            <em class="fa fa-eye"></em>
                                        </a>
                                        @endpermission

                                        <a class="btn btn-secondary btn-xs" title="BILL" type="button"
                                           href="{{ url('subcontract/sub-dyeing-goods-delivery/bill-view/'.$data->id) }}">
                                            <em class="fa fa-money"></em>
                                        </a>

                                        @permission('permission_of_sub_goods_delivery_delete')
                                        <button style="margin-left: 2px;" type="button"
                                                class="btn btn-xs btn-danger show-modal"
                                                title="Delete Order"
                                                data-toggle="modal"
                                                data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                ui-target="#animate"
                                                data-url="{{ url('subcontract/sub-dyeing-goods-delivery/' . $data->id) }}">
                                            <em class="fa fa-trash"></em>
                                        </button>
                                        @endpermission

                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                        <div class="text-center">
                            {{ $subDyeingGoodsDeliveries->appends(request()->except('page'))->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function () {
            $('#party_id').select2({
                ajax: {
                    url: `/subcontract/api/v1/textile-parties/select-search`,
                    dataType: 'json',
                    data: function (params) {
                        return {
                            search: params.term
                        };
                    },
                    processResults: function (response) {
                        return {
                            results: response.data,
                            pagination: {
                                more: false
                            }
                        };
                    },
                    cache: true,
                    delay: 150,
                },
                placeholder: 'Search',
                allowClear: true,
            });

        });

    </script>
@endsection
