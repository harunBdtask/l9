@extends('skeleton::layout')
@section('title','Yarn Issue')
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>
                    Yarn Issue Return List
                </h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-6">
                        <a
                            href="{{ url('/inventory/yarn-issue-return/create') }}"
                            class="btn btn-sm btn-info m-b">
                            <i class="fa fa-plus"></i> New Yarn Issue Return
                        </a>
                    </div>
                </div>
                @include('inventory::partials.flash')
                <div class="row m-t">
                    <div class="col-sm-12">
                        <form action="/inventory/yarn-issue-return">
                            <table class="reportTable">
                                <thead>
                                <tr style="background: #0ab4e6;">
                                    <th>SL</th>
                                    <th>Company Name</th>
                                    <th>Issue Return No</th>
                                    <th>Return Challan</th>
                                    <th>Issue Return Date</th>
                                    <th>Action</th>
                                </tr>
                                <tr>
                                    <th>#</th>
                                    <th style="width: 16%">
                                        <select name="company_id"
                                                class="form-control form-control-sm search-field text-center c-select select2-input">
                                            <option value="">Select</option>
                                            @foreach($company as $value)
                                                <option
                                                    @if(request()->get('company_id') == $value->id) selected @endif
                                                value="{{ $value->id }}">
                                                    {{ $value->factory_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </th>
                                    <th>
                                        <input name="issue_return_no"
                                               value="{{ request()->get('issue_return_no') }}"
                                               class="form-control form-control-sm search-field text-center"
                                               placeholder="Search">
                                    </th>
                                    <th>
                                        <input name="return_challan"
                                               value="{{ request()->get('return_challan') }}"
                                               class="form-control form-control-sm search-field text-center"
                                               placeholder="Search">
                                    </th>
                                    <th>
                                        <input name="return_date" type="date"
                                               value="{{ request()->get('return_date') }}"
                                               class="form-control form-control-sm search-field text-center"
                                               placeholder="Search">
                                    </th>
                                    <th>
                                        <button class="btn btn-xs btn-primary">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($issueReturns as $key => $value)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $value->factory->factory_name }}</td>
                                        <td>{{ $value->issue_return_no  }}</td>
                                        <td>{{ $value->return_challan }}</td>
                                        <td>{{date("d-m-Y", strtotime($value->return_date))}}</td>
                                        <td>
                                            <a href="/inventory/yarn-issue-return/{{ $value->id }}/view-details"
                                               class="btn btn-xs btn-primary">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <a href="/inventory/yarn-issue-return/{{ $value->id }}/view"
                                               class="btn btn-xs btn-default">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            @if (count($value->details) === 0)
                                                <a onclick="return confirm('Are you sure?')"
                                                   href="/inventory/yarn-issue-return/{{ $value->id }}/delete"
                                                   class="btn btn-xs btn-danger">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <th class="text-center" colspan="6">No Data Found</th>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </form>
                    </div>
                </div>
                <div class="row m-t">
                    <div class="col-sm-12">
                        {{ $issueReturns->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

