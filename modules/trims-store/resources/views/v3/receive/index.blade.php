@extends('subcontract::layout')
@section("title","Trims Store Receive")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Trims Store Receive</h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        @include('partials.response-message')
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 m-b">
                        <a href="{{ url('trims-store/receive/create') }}"
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
                                <th>Unique ID</th>
                                <th>Factory</th>
                                <th>Source</th>
                                <th>Store</th>
                                <th>Receive Basis</th>
                                <th>Receive Challan No</th>
                                <th>Receive Date</th>
                                <th>Pay Mode</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            {!! Form::open(['url'=>'/trims-store/receive', 'method'=>'GET']) !!}
                            <tr>
                                <td>*</td>
                                <td>
                                    {!! Form::text('unique_id', request('unique_id') ?? null, [
                                        'class'=>'text-center form-control form-control-sm'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::select('factory_id', $factories ?? [], request('factory_id') ?? null, [
                                        'class'=>'text-center select2-input'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::select('source_id', $sources ?? [], request('source_id'),[
                                        'class'=>'text-center select2-input', 'id'=>'source_id'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::select('store_id', $stores ?? [], request('store_id'),[
                                        'class'=>'text-center select2-input', 'id'=>'store_id'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::select('receive_basis_id', $receiveBasis ?? [], request('receive_basis_id'), [
                                        'class'=>'text-center select2-input', 'id'=>'receive_basis_id'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::text('challan_no', request('challan_no') ?? null, [
                                        'class'=>'text-center form-control form-control-sm'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::date('receive_date', request('receive_date') ?? null, [
                                        'class'=>'text-center form-control form-control-sm'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::select('pay_mode_id', $payModes ?? [], request('pay_mode_id'),[
                                        'class'=>'text-center select2-input', 'id'=>'pay_mode_id'
                                    ]) !!}
                                </td>
                                <td>
                                    <button class="btn btn-xs btn-info" type="submit" title="Search">
                                        <em class="fa fa-search"></em>
                                    </button>
                                    <a href="{{ url('trims-store/receive') }}"
                                       class="btn btn-xs btn-warning"
                                       title="Reload"
                                    >
                                        <em class="fa fa-refresh"></em>
                                    </a>
                                </td>
                            </tr>
                            {!! Form::close() !!}
                            <tr>
                                <td colspan="10">&nbsp;</td>
                            </tr>
                            @forelse ($receives as $receive)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $receive->unique_id }}</td>
                                    <td>{{ $receive->factory->factory_name }}</td>
                                    <td>{{ $receive->source }}</td>
                                    <td>{{ $receive->store->name }}</td>
                                    <td>{{ $receive->receive_basis }}</td>
                                    <td>{{ $receive->challan_no }}</td>
                                    <td>{{ $receive->receive_date }}</td>
                                    <td>{{ $receive->pay_mode }}</td>
                                    <td>
                                        <a class="btn btn-xs btn-info" type="button"
                                           title="Edit"
                                           href="{{ url('trims-store/receive/create?id='.$receive->id) }}">
                                            <em class="fa fa-pencil"></em>
                                        </a>
                                        <a class="btn btn-success btn-xs"
                                           title="View"
                                           target="_blank"
                                           href="{{ url("trims-store/receive/$receive->id/view/") }}">
                                            <em class="fa fa-eye"></em>
                                        </a>
                                        <button style="margin-left: 2px;" type="button"
                                                class="btn btn-xs btn-danger show-modal"
                                                title="Delete"
                                                data-toggle="modal"
                                                data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                ui-target="#animate"
                                                data-url="{{ url('trims-store/receive/delete/'.$receive->id) }}">
                                            <em class="fa fa-trash"></em>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-danger" colspan="11" align="center">No Data Found</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                        <div class="text-center">
                            {{ $receives->appends(request()->except('page'))->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
