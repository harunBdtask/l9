@extends('dyeing::layout')
@section("title","Dryer")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Dyeing Dryer</h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        @include('partials.response-message')
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 m-b">
                        <a href="{{ url('dyeing/dryer/create') }}"
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
                                <th>Production Date</th>
                                <th>Factory</th>
                                <th>Booking Type</th>
                                <th>Buyer</th>
                                <th>Order No</th>
                                <th>Batch No</th>
                                <th>Fabric Color</th>
                                <th>Machine Name</th>
                                <th>Finish Qty</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                             {!! Form::open(['url'=>'/dyeing/dryer', 'method'=>'GET']) !!}
                            <tr>
                                <td>*</td>
                                <td>
                                    {!! Form::date('production_date', request('production_date') ?? null, [
                                       'class'=>'text-center form-control form-control-sm'
                                   ]) !!}

                                </td>
                                <td>
                                    {!! Form::select('factory_id', $factories ?? [], request('factory_id') ?? null, [
                                        'class'=>'text-center select2-input'
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
                            @forelse ($dryers as $dryer)
                            @php
                                $fabricColor = collect($dryer->dryerDetails)->pluck('color')->implode('name',', ');
                            @endphp
                                <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$dryer->production_date}}</td>
                                <td>{{$dryer->factory->factory_name}}</td>
                                <td style="text-transform: capitalize">{{ $dryer->dyeingBatch->fabricSalesOrder->booking_type??null  }}</td>
                                <td>{{$dryer->buyer->name}}</td>
                                <td>{{$dryer->textile_order_no}}</td>
                                <td>{{$dryer->dyeing_batch_no}}</td>
                                <td>{{$fabricColor}}</td>
                                <td>{{$dryer->machine->name}}</td>
                                <td>{{$dryer->total_finish}}</td>
                                <td>
                                    <a class="btn btn-xs btn-info" type="button"
                                        href="{{ url('dyeing/dryer/create?id=' . $dryer->id) }}">
                                        <em class="fa fa-pencil"></em>
                                    </a>
                                    <a class="btn btn-success btn-xs" type="button"
                                        href="{{ url('dyeing/dryer/view/' . $dryer->id) }}">
                                        <em class="fa fa-eye"></em>
                                    </a>
                                    <button style="margin-left: 2px;" type="button"
                                            class="btn btn-xs btn-danger show-modal"
                                            title="Delete Order"
                                            data-toggle="modal"
                                            data-target="#confirmationModal" ui-toggle-class="flip-x"
                                            ui-target="#animate"
                                            data-url="{{ url('dyeing/dryer/' . $dryer->id) }}">
                                        <em class="fa fa-trash"></em>
                                    </button>
                                </td>
                                 </tr>
                               @empty
                               <tr>
                                    <td colspan="12" align="center">No Data</td>
                                </tr>
                               @endforelse
                            </tbody>
                        </table>
                        <div class="text-center">
                            {{ $dryers->appends(request()->except('page'))->links() }}
                        </div>
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