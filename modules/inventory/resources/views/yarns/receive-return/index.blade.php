@extends('skeleton::layout')
@section('title','Yarn Receive Return')
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>
                    Yarn Receive Return List
                </h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-6">
                        <a
                            href="{{ url('/inventory/yarn-receive-return/create') }}"
                            class="btn btn-sm btn-info m-b">
                            <i class="fa fa-plus"></i> New Yarn Receive Return
                        </a>
                    </div>
                </div>
                @include('inventory::partials.flash')
                <div class="row m-t">
                    <form class="col-sm-12" action="{{ url('/inventory/yarn-receive-return') }}">
                        <table class="reportTable">
                            <thead>
                            <tr style="background: #0ab4e6;">
                                <th>SL</th>
                                <th>Return ID</th>
                                <th>Year</th>
                                <th>Company Name</th>
                                <th>Returned To</th>
                                <th>Return Date</th>
                                <th>Return Qty</th>
                                <th>Receive ID</th>
                                <th>Action</th>
                            </tr>
                            <tr>
                                <th>#</th>
                                <th>
                                    <input name="receive_return_no"
                                           value="{{ request()->get('receive_return_no') }}"
                                           class="form-control form-control-sm search-field text-center"
                                           placeholder="Search">
                                </th>
                                <th>
                                    <input name="year" value="{{ request()->get('year') }}"
                                           class="form-control form-control-sm search-field text-center"
                                           placeholder="Search">
                                </th>
                                <th style="width: 14%">
                                    <select name="factory_id"
                                            class="form-control form-control-sm search-field text-center c-select select2-input">
                                        <option value="">Select</option>
                                        @foreach($companies as $value)
                                            <option
                                                @if(request()->get('factory_id') == $value->id) selected @endif
                                            value="{{ $value->id }}">
                                                {{ $value->factory_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </th>
                                <th style="width: 14%">
                                    <select name="supplier_id"
                                            class="form-control form-control-sm search-field text-center c-select select2-input">
                                        <option value="">Select</option>
                                        @foreach($suppliers as $value)
                                            <option
                                                @if(request()->get('supplier_id') == $value->id) selected @endif
                                            value="{{ $value->id }}">
                                                {{ $value->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </th>
                                <th>
                                    <input name="return_date" type="date"
                                           value="{{ request()->get('return_date') }}"
                                           class="form-control form-control-sm search-field text-center"
                                           placeholder="Search">
                                </th>
                                <th>
                                    <input name="return_qty" value="{{ request()->get('return_qty') }}"
                                           class="form-control form-control-sm search-field text-center"
                                           placeholder="Search">
                                </th>
                                <th>
                                    <input name="receive_id" value="{{ request()->get('receive_id') }}"
                                           class="form-control form-control-sm search-field text-center"
                                           placeholder="Search">
                                </th>
                                <th>
                                    <button class="btn btn-xs white">
                                        <i class="fa fa-search"></i>
                                    </button>
                                    <a href="{{ url('/inventory/yarn-receive-return') }}"
                                       class="btn btn-xs btn-warning">
                                        <i class="fa fa-refresh"></i>
                                    </a>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($data as $key => $value)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $value->receive_return_no }}</td>
                                    <td>{{ \Carbon\Carbon::parse($value->return_date)->year }}</td>
                                    <td>{{ $value->company->factory_name ?? '' }}</td>
                                    <td>{{ $value->supplier->name ?? '' }}</td>
                                    <td>{{date("d-m-Y", strtotime($value->return_date))}}</td>
                                    <td>{{ $value->details->sum('return_qty') }}</td>
                                    <td>{{ $value->yarn_receive->receive_no ?? '' }}</td>
                                    <td>
                                        <div style="display: flex; align-items: center; margin:5px">
                                            <a href="/inventory/yarn-receive-return/{{ $value->id }}/view-details"
                                               class="btn btn-xs btn-primary">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <a href="/inventory/yarn-receive-return/{{ $value->id }}"
                                               class="btn btn-xs btn-success"
                                               title="View Details">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            @if (count($value->details) == 0)
                                                <a onclick="return confirm('Are you sure?')"
                                                   href="/inventory/yarn-receive-return/{{ $value->id }}/delete"
                                                   class="btn btn-xs btn-danger"
                                                   style="margin-left: 4px"
                                                   title="Delete">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <th class="text-center" colspan="14">No Data Found</th>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </form>
                </div>

                <div class="row">
                    <div class="col-md-12 text-center">
                        @if(count($data))
                            {{ $data->render() }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
