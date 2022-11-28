@extends('dyeing::layout')
@section("title","Dyeing Batch")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Dyeing Batches</h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        @include('partials.response-message')
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 m-b">
                        <a href="{{ url('dyeing/dyeing-batches/create') }}"
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
                                <th>Batch Unique Id</th>
                                <th>Booking Type</th>
                                <th>Buyer</th>
                                <th>Order No</th>
                                <th>Batch No</th>
                                <th>Fabric Color</th>
                                <th>Batch Weight</th>
                                <th>Batch Date</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            {!! Form::open(['url'=>'/dyeing/dyeing-batches', 'method'=>'GET']) !!}
                            <tr>
                                <td>*</td>
                                <td>
                                    {!! Form::select('factory_id', $factories ?? [], request('factory_id') ?? null, [
                                        'class'=>'text-center select2-input'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::text('unique_id', request('unique_id') ?? null, [
                                        'class'=>'text-center form-control form-control-sm'
                                    ]) !!}
                                </td>
                                <td>
                                    <select
                                        name="type"
                                        class="form-control form-control-sm search-field text-center c-select select2-input">
                                        <option value="">Select</option>
                                        <option @if(request()->get('type') == 'main') selected @endif value="main">Main</option>
                                        <option @if(request()->get('type') == 'short') selected @endif value="short">Short</option>
                                        <option @if(request()->get('type') == 'sample') selected @endif value="sample">Sample</option>
                                    </select>
                                </td>
                                <td>
                                    {!! Form::select('buyer_id', $buyers ?? [], request('buyer_id'),[
                                        'class'=>'text-center select2-input', 'id'=>'buyer_id'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::text('order_no', request('order_no') ?? null, [
                                        'class'=>'text-center form-control form-control-sm'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::text('batch_no', request('batch_no') ?? null, [
                                        'class'=>'text-center form-control form-control-sm'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::select('fabric_color_id', $colors ?? [], request('fabric_color_id'),[
                                        'class'=>'text-center select2-input', 'id'=>'fabric_color_id'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::text('total_batch_weight', request('total_batch_weight') ?? null, [
                                        'class'=>'text-center form-control form-control-sm'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::date('batch_date', request('batch_date') ?? null, [
                                        'class'=>'text-center form-control form-control-sm'
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
                            @foreach ($batches as $batch)
                                @php
                                    $ordersNo = collect($batch->orders_no)->implode(', ');
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $batch->factory->factory_name }}</td>
                                    <td>{{ $batch->unique_id }}</td>
                                    <td style="text-transform: capitalize">{{ $batch->fabricSalesOrder->booking_type??null }}</td>
                                    <td>{{ $batch->buyer->name  }}</td>
                                    <td>{{ $ordersNo }}</td>
                                    <td>{{ $batch->batch_no }}</td>
                                    <td>{{ $batch->fabricColor->name }}</td>
                                    <td>{{ $batch->total_batch_weight }}</td>
                                    <td>{{ $batch->batch_date }}</td>
                                    <td>
                                        <a class="btn btn-xs btn-info" type="button"
                                           href="{{ url('dyeing/dyeing-batches/create?id=' . $batch->id) }}">
                                            <em class="fa fa-pencil"></em>
                                        </a>
                                        <a class="btn btn-success btn-xs" type="button"
                                           href="{{ url('dyeing/dyeing-batches/view/'.$batch->id) }}">
                                            <em class="fa fa-eye"></em>
                                        </a>
                                        <button style="margin-left: 2px;" type="button"
                                                class="btn btn-xs btn-danger show-modal"
                                                title="Delete Order"
                                                data-toggle="modal"
                                                data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                ui-target="#animate"
                                                data-url="{{ url('dyeing/dyeing-batches/' . $batch->id) }}">
                                            <em class="fa fa-trash"></em>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                        <div class="text-center">
                            {{ $batches->appends(request()->except('page'))->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
