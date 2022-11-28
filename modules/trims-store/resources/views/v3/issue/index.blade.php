@extends('subcontract::layout')
@section("title", "Trims Store Issue")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Trims Store Issue</h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        @include('partials.response-message')
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 m-b">
                        <a href="{{ url('trims-store/issues/create') }}"
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
                                <th>Issue Basis</th>
                                <th>Issue Challan No</th>
                                <th>Issue Date</th>
                                <th>Pay Mode</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            {!! Form::open(['url'=>'/trims-store/issues', 'method'=>'GET']) !!}
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
                                    {!! Form::select('issue_basis_id', $issueBasis ?? [], request('issue_basis_id'), [
                                        'class'=>'text-center select2-input', 'id'=>'issue_basis_id'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::text('challan_no', request('challan_no') ?? null, [
                                        'class'=>'text-center form-control form-control-sm'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::date('issue_date', request('issue_date') ?? null, [
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
                                    <a href="{{ url('trims-store/issues') }}"
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
                            @forelse ($issues as $issue)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $issue->unique_id }}</td>
                                    <td>{{ $issue->factory->factory_name }}</td>
                                    <td>{{ $issue->source }}</td>
                                    <td>{{ $issue->store->name }}</td>
                                    <td>{{ $issue->issue_basis }}</td>
                                    <td>{{ $issue->challan_no }}</td>
                                    <td>{{ $issue->issue_date }}</td>
                                    <td>{{ $issue->pay_mode }}</td>
                                    <td>
                                        <a class="btn btn-xs btn-info" type="button"
                                           title="Edit"
                                           href="{{ url('trims-store/issues/create?id='.$issue->id) }}">
                                            <em class="fa fa-pencil"></em>
                                        </a>
                                        <a class="btn btn-success btn-xs"
                                           title="View"
                                           target="_blank"
                                           href="{{ url("trims-store/issues/$issue->id/view") }}">
                                            <em class="fa fa-eye"></em>
                                        </a>
                                        <button style="margin-left: 2px;" type="button"
                                                class="btn btn-xs btn-danger show-modal"
                                                title="Delete"
                                                data-toggle="modal"
                                                data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                ui-target="#animate"
                                                data-url="{{ url('trims-store/issues/'.$issue->id) }}">
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
                            {{ $issues->appends(request()->except('page'))->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
