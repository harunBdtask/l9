@extends('sample::layout')
@section('title','Sample Trims Receive List')
@push('style')
    <style>
        .v-align-top td, .v-algin-top th {
            vertical-align: top;
        }
        .text-uppercase {
            text-transform: uppercase;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .text-left {
            text-align: left;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        table.borderless {
            border: none;
        }
        .modal-dialog {
            width: 1200px;
        }
        #mainTable{
            display: none;
        }
        #detailsTable th, #detailsTable td{
            padding: 3px;
            font-size: 12px;
            text-align: center;
        }
    </style>
@endpush
@section('content')
<div class="padding">
    <div class="box">
        <div class="box-header">
            <h2 style="font-weight: 400;">Sample Trims Receive List</h2>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-6">
                    @permission('permission_of_sample_list_add')
                    <a href="{{ url('/sample-management/trims-receive/form') }}" class="btn btn-sm btn-info m-b">
                        <i class="fa fa-plus"></i>
                        Sample Trims Receive Entry
                    </a>
                    @endpermission
                </div>
                <div class="col-sm-4 col-sm-offset-2">
                    <form action="" method="GET">
                        <div class="input-group">
                            <input type="text" class="form-control form-control-sm"
                                name="search" value="{{ request('search')?request('search'): '' }}"
                                placeholder="Search">
                            <span class="input-group-btn">
                                <button class="btn btn-sm btn-info" type="submit"> Search</button>
                                <a href="{{ url('/sample-management/trims-receive/list') }}" class="btn btn-sm btn-danger">Cancel</a>
                            </span>
                        </div>
                    </form>
                </div>
            </div>
            @include('partials.response-message')
            <div class="row m-t">
                <div class="col-sm-12">
                    <table class="reportTable">
                        <thead>
                            <tr class="table-header" style="background-color: rgb(148, 218, 251);">
                                <th>Unique ID</th>
                                <th>Issue Challan No</th>
                                <th>Company Name</th>
                                <th>Trims Issue Unique ID</th>
                                <th>Receive Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if($values && count($values))
                            @foreach($values as $value)
                            <tr class="tooltip-data row-options-parent">
                                <td>{{ $value->unique_id ?? null }}</td>
                                <td>{{ $value->issue_challan_no ?? null }}</td>
                                <td>{{ $value->factory->factory_name ?? null }}</td>
                                <td>{{ $value->trims_issue_unique_id ?? null }}</td>
                                <td>{{ $value->receive_date ?? null }}</td>
                                <td>
                                    @if(Session::has('permission_of_sample_trims_receive_view') || Session::get('user_role') == 'super-admin' || Session::get('user_role') == 'admin')
                                        <a class="btn btn-xs btn-info" href="{{ url('/sample-management/trims-receive/view/' . $value->id) }}"><i class="fa fa-eye"></i> </a>
                                    @endif
                                    @if(Session::has('permission_of_sample_trims_receive_edit') || Session::get('user_role') == 'super-admin' ||Session::get('user_role') == 'admin')
                                        <a class="btn btn-xs btn-success" href="{{ url('/sample-management/trims-receive/form/' . $value->id) }}"><i class="fa fa-edit"></i> </a>
                                    @endif
                                    @if(Session::has('permission_of_sample_trims_receive_delete') || Session::get('user_role') == 'super-admin' ||Session::get('user_role') == 'admin')
                                        <button type="button" class="btn btn-xs danger show-modal" data-toggle="modal" data-target="#confirmationModal" ui-toggle-class="flip-x" ui-target="#animate" data-url="{{ url('sample-management/trims-receive/delete/' . $value->id) }}">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    @endif
                                </td>

                            </tr>
                            @endforeach
                        @else
                        <tr>
                            <td colspan="8" class="text-center">No Data Found</td>
                        </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row m-t">
                <div class="col-sm-12">
                {{ $values->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

