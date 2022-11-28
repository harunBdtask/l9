@extends('skeleton::layout')
@section('title','Yarn Receive Return')
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-body">
                <div class="row text-center">
                    <a class="btn pull-right" href="{{ url('knitting/yarn-allocation/'.$data->id.'/pdf') }}" title="PDF"><i class="fa fa-file-pdf-o"></i></a>
                    <div class="col-md-6 col-md-offset-3" style="display: flex; flex-direction: column;">
                        <span style="font-size: 16px; font-weight: bold">{{ factoryName() }}</span>
                        <span>{{ factoryAddress() }}</span>
                    </div>
                    <div class="col-md-8 col-md-offset-2 m-t-1">
                        <u style="font-size: 15px; font-weight: bold">Yarn Allocation</u>
                        <div style="display: flex; justify-content: space-between; margin-top: 10px;">
                            <div>
                                <strong>Company: </strong>
                                <span>{{ $data->factory->factory_name }}</span>
                                <div class="text-left">
                                    <strong>Uniq Id: </strong>
                                    <span>{{ $data->uniq_id }}</span>
                                </div>
                            </div>
                            <div>
                                <strong>Buyer: </strong>
                                <span>{{ $data->buyer->name ?? '' }}</span>
                                <div class="text-left">
                                    <strong>Allocation Date: </strong>
                                    <span>{{ $data->allocation_date }}</span>
                                </div>
                            </div>
                            <div>
                                <strong>Order Number: </strong>
                                <span>{{ $data->order_number }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row m-t-3">
                    <table class="reportTable">
                        <thead>
                        <tr style="background-color: #eee">
                            <th>SL</th>
                            <th>Supplier</th>
                            <th>Lot No</th>
                            <th>Allocated Quantity</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data->details as $key => $value)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $value->supplier->name ?? '' }}</td>
                                <td>{{ $value->yarn_lot }}</td>
                                <td>{{ $value->allocated_qty }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="3" class="text-right"><strong style="margin-right: 5px">Total: </strong></td>
                            <td><strong>{{ $data->details->sum('allocated_qty') }}</strong></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="row m-t-1">
                    <div class="col-md-12">
                        @php
                            $digit = new NumberFormatter("en", NumberFormatter::SPELLOUT);
                        @endphp
                        <strong>Total Allocated Quantity: </strong>{{ $data->details->sum('allocated_qty') }} <br>
                        <strong>In word: </strong>{{ ucwords($digit->format($data->details->sum('allocated_qty'))) }} Kg Zero Grams
                    </div>
                </div>
                <div class="row m-t-1">
                    <div class="col-md-6 text-center">
                        <u>Check By</u>
                    </div>
                    <div class="col-md-6 text-center">
                        <u>Approve By </u>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
