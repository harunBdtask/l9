@extends('dyeing::layout')
@section("title","Dyeing Goods Delivery")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Dyeing Goods Delivery</h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        @include('partials.response-message')
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 m-b">
                        <a href="{{ url('dyeing/dyeing-goods-delivery/create') }}"
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
                                <th>Delivery Date</th>
                                <th>Factory</th>
                                <th>Buyer</th>
                                <th>Order No</th>
                                <th>Batch No</th>
                                <th>Fabric Color</th>
                                <th>Delivery Qty</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                             {!! Form::open(['url'=>'/dyeing/dyeing-goods-delivery', 'method'=>'GET']) !!}
                            <tr>
                                <td>*</td>
                                <td>
                                    {!! Form::date('delivery_date', request('delivery_date') ?? null, [
                                       'class'=>'text-center form-control form-control-sm'
                                   ]) !!}

                                </td>
                                <td>
                                    {!! Form::select('factory_id', $factories ?? [], request('factory_id') ?? null, [
                                        'class'=>'text-center select2-input'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::select('buyer_id', $buyers ?? [], request('buyer_id') ?? null, [
                                        'class'=>'text-center select2-input'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::text('dyeing_order_no', request('dyeing_order_no') ?? null, [
                                        'class'=>'text-center form-control form-control-sm'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::text('dyeing_batch_no', request('dyeing_batch_no') ?? null, [
                                        'class'=>'text-center form-control form-control-sm'
                                    ]) !!}
                                </td>
                                <td>

                                </td>
                                <td>

                                </td>
                                <td>
                                    <button class="btn btn-xs white" type="submit">
                                        <em class="fa fa-search"></em>
                                    </button>
                                </td>
                            </tr>
                            {!! Form::close() !!}
                             <tr>
                                <td colspan="10">&nbsp;</td>
                            </tr>
                               @forelse ($dyeingGoodsDelivery as $goodsDelivery)
                               @php
                                    $fabricColor = collect($goodsDelivery->dyeingGoodsDeliveryDetails)
                                                    ->pluck('color')->implode('name',', ');
                                @endphp
                               <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$goodsDelivery->delivery_date}}</td>
                                    <td>{{$goodsDelivery->factory->factory_name}}</td>
                                    <td>{{$goodsDelivery->buyer->name}}</td>
                                    <td>{{$goodsDelivery->textile_order_no}}</td>
                                    <td>{{$goodsDelivery->dyeing_batch_no}}</td>
                                    <td>{{$fabricColor}}</td>
                                    <td>{{$goodsDelivery->total_delivery_qty}}</td>
                                    <td>
                                        <a class="btn btn-xs btn-info" type="button"
                                            href="{{ url('dyeing/dyeing-goods-delivery/create?id=' . $goodsDelivery->id) }}">
                                            <em class="fa fa-pencil"></em>
                                        </a>
                                        <a class="btn btn-success btn-xs" type="button"
                                            href="{{ url('dyeing/dyeing-goods-delivery/view/' . $goodsDelivery->id) }}">
                                            <em class="fa fa-eye"></em>
                                        </a>
                                        <button style="margin-left: 2px;" type="button"
                                                class="btn btn-xs btn-danger show-modal"
                                                title="Delete Stentering"
                                                data-toggle="modal"
                                                data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                ui-target="#animate"
                                                data-url="{{ url('dyeing/dyeing-goods-delivery/' . $goodsDelivery->id) }}">
                                            <em class="fa fa-trash"></em>
                                        </button>
                                    </td>
                                </tr>
                               @empty
                               <tr>
                                    <td colspan="11" align="center">No Data</td>
                                </tr>
                               @endforelse
                            </tbody>
                        </table>
                        {{-- <div class="text-center">
                            {{ $stenterings->appends(request()->except('page'))->links() }}
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->


    <style>
        /*.custom-field {*/
        /*    */
        /*    */
        /*}*/
    </style>
@endsection
@section('scripts')
    <script>

    </script>
@endsection
