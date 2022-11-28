@extends('subcontract::layout')
@section("title", "Trims Store Receive Return")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Trims Store Receive Return</h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        @include('partials.response-message')
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 m-b">
                        <a href="{{ url('trims-store/receive-return/create') }}"
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
                                <th>Return Basis</th>
                                <th>Return Type</th>
                                <th>Return Date</th>
                                <th>Gate Pass No</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            {!! Form::open(['url'=>'/trims-store/receive-return', 'method'=>'GET']) !!}
                            <tr>
                                <td>*</td>
                                <td>
                                    {!! Form::text('unique_id', request('unique_id') ?? null, [
                                        'class'=>'text-center form-control form-control-sm',
                                        'placeholder' => 'Search'
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
                                    {!! Form::select('return_basis_id', $receiveReturnBasis ?? [], request('return_basis_id'), [
                                        'class'=>'text-center select2-input', 'id'=>'receive_basis_id'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::select('return_type_id', $receiveReturnTypes ?? [], request('return_type_id'), [
                                        'class'=>'text-center select2-input', 'id'=>'return_type_id'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::date('return_date', request('return_date') ?? null, [
                                        'class'=>'text-center form-control form-control-sm'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::text('gate_pass_no', request('gate_pass_no') ?? null, [
                                        'class'=>'text-center form-control form-control-sm',
                                        'placeholder' => 'Search'
                                    ]) !!}
                                </td>
                                <td>
                                    <button class="btn btn-xs btn-info" type="submit" title="Search">
                                        <em class="fa fa-search"></em>
                                    </button>
                                    <a href="{{ url('trims-store/receive-return') }}"
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
                            @forelse ($receiveReturns as $receiveReturn)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $receiveReturn->unique_id }}</td>
                                    <td>{{ $receiveReturn->factory->factory_name }}</td>
                                    <td>{{ $receiveReturn->source }}</td>
                                    <td>{{ $receiveReturn->store->name }}</td>
                                    <td>{{ $receiveReturn->return_basis }}</td>
                                    <td>{{ $receiveReturn->return_type }}</td>
                                    <td>{{ $receiveReturn->return_date }}</td>
                                    <td>{{ $receiveReturn->gate_pass_no }}</td>
                                    <td>
                                        <a class="btn btn-xs btn-info" type="button"
                                           title="Edit"
                                           href="{{ url('trims-store/receive-return/create?id='.$receiveReturn->id) }}">
                                            <em class="fa fa-pencil"></em>
                                        </a>
                                        <a class="btn btn-success btn-xs"
                                           title="View"
                                           href="{{ url("trims-store/receive-return/$receiveReturn->id/view/") }}">
                                            <em class="fa fa-eye"></em>
                                        </a>
                                        <button style="margin-left: 2px;" type="button"
                                                class="btn btn-xs btn-danger show-modal"
                                                title="Delete"
                                                data-toggle="modal"
                                                data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                ui-target="#animate"
                                                data-url="{{ url('trims-store/receive-return/delete/'.$receiveReturn->id) }}">
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
                            {{ $receiveReturns->appends(request()->except('page'))->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
