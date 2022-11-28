@extends('skeleton::layout')
@section('title','Yarn Allocation')
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>
                    Yarn Allocation List
                </h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-6">
                        <a
                            href="{{ url('/knitting/yarn-allocation/create') }}"
                            class="btn btn-sm white m-b">
                            <i class="fa fa-plus"></i> New Yarn Allocation
                        </a>
                    </div>
                </div>
                @include('inventory::partials.flash')

                <div class="row m-t">
                    <div class="col-sm-12">
                        <table class="reportTable">
                            <thead>
                            <tr style="background: #0ab4e6;">
                                <th>SL</th>
                                <th>Factory</th>
                                <th>Buyer</th>
                                <th>Order Number</th>
                                <th>Total Allocated Qty</th>
                                <th>Allocation Date</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($allocations as $key => $allocation)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $allocation->factory->factory_name ?? '' }}</td>
                                    <td>{{ $allocation->buyer->name ?? '' }}</td>
                                    <td>{{ $allocation->order_number }}</td>
                                    <td>{{ $allocation->details->sum('allocated_qty') }}</td>
                                    <td>{{ $allocation->allocation_date }}</td>
                                    <td style="padding: 2px;">
                                        <a href="/knitting/yarn-allocation/{{ $allocation->id }}"
                                           class="btn btn-success btn-xs"
                                           target="_blank"
                                           title="View Details">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <a href="{{ url('/knitting/yarn-allocation/'.$allocation->id.'/edit') }}"
                                           class="btn btn-xs btn-primary">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <button
                                            type="button"
                                            data-toggle="modal"
                                            ui-target="#animate"
                                            ui-toggle-class="flip-x"
                                            title="Delete Yarn Allocation"
                                            data-target="#confirmationModal"
                                            data-url="{{ url('/knitting/yarn-allocation/'.$allocation->id.'/delete') }}"
                                            class="btn btn-xs btn-danger show-modal">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

