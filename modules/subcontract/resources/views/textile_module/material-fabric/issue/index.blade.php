@extends('subcontract::layout')
@section("title","Sub Grey Store Material Fabric Receive")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Sub Grey Store Material Fabric Issue</h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        @include('partials.response-message')
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 m-b">
                        <a href="/subcontract/material-fabric-issue/create" class="btn btn-sm btn-info m-b"><i
                                class="fa fa-plus"></i>&nbsp;Create</a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="reportTable">
                            <thead>
                            <tr>
                                <th>SL</th>
                                <th>Company</th>
                                <th>Party</th>
                                <th>Issue Purpose</th>
                                <th>Order No</th>
                                <th>Challan No</th>
                                <th>Challan Date</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            {!! Form::open(['url'=>'/subcontract/material-fabric-issue', 'method'=>'GET']) !!}
                            <tr>
                                <td>*</td>
                                <td>
                                    {!! Form::select('factory_id', $factories ?? [], request('factory_id'), [
                                        'class'=>'text-center select2-input'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::select('supplier_id', $supplier ?? [], request('supplier_id'), [
                                        'class'=>'text-center select2-input',
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::select('issue_purpose', $issuePurpose ?? [], request('issue_purpose'), [
                                        'class'=>'text-center select2-input', 'id'=>'issue_purpose'
                                    ]) !!}
                                </td>
                                <td>
                                    <input style="width: 90%;border: 1px solid #cecece;" type="text"
                                           class="text-center" placeholder="Write" name="sub_textile_order_id"
                                           value="{{ request('sub_textile_order_id') }}">
                                </td>
                                <td>
                                    <input style="width: 90%;border: 1px solid #cecece;" type="text"
                                           class="text-center" placeholder="Write" name="challan_no"
                                           value="{{ request('challan_no') }}">
                                </td>
                                <td>
                                    <input style="width: 90%;border: 1px solid #cecece;" type="date"
                                           class="text-center" placeholder="Write" name="challan_date"
                                           value="{{ request('challan_date') }}">
                                </td>
                                <td>
                                    <button class="btn btn-xs white" type="submit">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </td>
                            </tr>
                            {!! Form::close() !!}
                            <tr>
                                <td colspan="9">&nbsp;</td>
                            </tr>
                            @foreach ($subGreyStoreIssues as $issue)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $issue->factory->factory_name }}</td>
                                    <td>{{ $issue->supplier->name }}</td>
                                    <td>{{ \SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubGreyStoreIssue::ISSUE_PURPOSE[$issue->issue_purpose] ?? 'N\A' }}</td>
                                    <td>{{ $issue->textileOrder->order_no }}</td>
                                    <td>{{ $issue->challan_no }}</td>
                                    <td>
                                        {{
                                            $issue->challan_date
                                                ? \Carbon\Carbon::make($issue->challan_date)->toFormattedDateString()
                                                : 'N/A'
                                        }}
                                    </td>
                                    <td>
                                        <a class="btn btn-xs btn-success" type="button"
                                           href="{{ url('subcontract/material-fabric-issue/create?id=' . $issue->id . '&mode=return') }}">
                                            <i class="fa fa-retweet"></i>
                                        </a>
                                        @permission('permission_of_material_issue_edit')
                                        <a class="btn btn-xs btn-info" type="button"
                                           title="Edit"
                                           href="{{ url('subcontract/material-fabric-issue/create?id=' . $issue->id) }}">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                        @endpermission
                                        @permission('permission_of_material_issue_view')
                                        <a class="btn btn-success btn-xs" type="button"
                                           title="View"
                                           href="{{ url('subcontract/material-fabric-issue/view/'.$issue->id) }}">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        @endpermission
                                        @permission('permission_of_material_issue_delete')
                                        <button style="margin-left: 2px;" type="button"
                                                class="btn btn-xs btn-danger show-modal"
                                                title="Delete"
                                                data-toggle="modal"
                                                data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                ui-target="#animate"
                                                data-url="{{ url('subcontract/material-fabric-issue/' . $issue->id) }}">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                        @endpermission
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                        <div class="text-center">
                            {{ $subGreyStoreIssues->appends(request()->except('page'))->links() }}
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
        $(document).ready(function () {
            $('#supplier_id').select2({
                ajax: {
                    url: "/subcontract/api/v1/textile-parties/select-search",
                    dataType: 'json',
                    data: function (params) {
                        return {
                            search: params.term
                        };
                    },
                    processResults: function (response, params) {
                        return {
                            results: response.data,
                            pagination: {
                                more: false
                            }
                        };
                    },
                    cache: true,
                    delay: 150,
                },
                placeholder: 'Search',
                allowClear: true,
            });

        });

    </script>
@endsection
