@extends('sample::layout')
@section('title','Sample TNA List')
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
            <h2 style="font-weight: 400;">Sample TNA List</h2>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-6">
                    @permission('permission_of_sample_list_add')
                    <a href="{{ url('/sample-management/sample-tna/form') }}" class="btn btn-sm btn-info m-b">
                        <i class="fa fa-plus"></i>
                        Sample TNA Entry
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
                                <th>TNA Unique ID</th>
                                <th>Requisition ID</th>
                                <th>Buyer Name</th>
                                <th>Style Name</th>
                                <th>Booking NO</th>
                                <th>Control / Ref. NO</th>
                                <th>Total Lead Time</th>
                                <th>Company Name</th>
                                <th>Details</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if($values && count($values))
                            @foreach($values as $value)
                            <tr class="tooltip-data row-options-parent">
                                <td>{{ $value->unique_id ?? null }}</td>
                                <td>{{ $value->requisition_id ?? null }}</td>
                                <td>{{ $value->buyer->name }}</td>
                                <td>{{ $value->style_name ?? null }}</td>
                                <td>{{ $value->booking_no ?? null }}</td>
                                <td>{{ $value->control_ref_no ?? null }}</td>
                                <td>{{ $value->total_lead_time ?? null }}</td>
                                <td>{{ $value->factory->factory_name ?? null }}</td>
                                <td>
                                    <button data-id="{{$value->id}}" class="poInfo btn btn-info btn-xs">Browse</button>
                                </td>
                                <td>
                                    @if(Session::has('permission_of_sample_tna_view') || Session::get('user_role') == 'super-admin' || Session::get('user_role') == 'admin')
                                        <a class="btn btn-xs btn-info" href="{{ url('/sample-management/sample-tna/view/' . $value->id) }}"><i class="fa fa-eye"></i> </a>
                                    @endif
                                    @if(Session::has('permission_of_sample_tna_edit') || Session::get('user_role') == 'super-admin' ||Session::get('user_role') == 'admin')
                                        <a class="btn btn-xs btn-success" href="{{ url('/sample-management/sample-tna/form/' . $value->id) }}"><i class="fa fa-edit"></i> </a>
                                    @endif
                                    @if(Session::has('permission_of_sample_tna_delete') || Session::get('user_role') == 'super-admin' ||Session::get('user_role') == 'admin')
                                        <button type="button" class="btn btn-xs danger show-modal" data-toggle="modal" data-target="#confirmationModal" ui-toggle-class="flip-x" ui-target="#animate" data-url="{{ url('sample-management/sample-tna/delete/' . $value->id) }}">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    @endif
                                </td>

                            </tr>
                            @endforeach
                        @else
                        <tr>
                            <td colspan="20" class="text-center">No Data Found</td>
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

<!-- modal button -->
<div class="modal fade" id="po-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="poModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form>
                @csrf
                <div class="modal-body" id="poDetails">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('scripts')
<script>
    const poDetails = $('#poDetails');
    //poInfo
    $('body').on('click', '.poInfo', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        $('#poModalLabel').text("Details");
        poDetails.html('');
        $('#po-modal').modal('show');
        $.ajax({
            url: "/sample-management/sample-tna/show/"+id,
            type: "get",
            dataType: "html",
            beforeSend() {
                $('html,body').css('cursor', 'wait');
                $("html").css({'background-color': 'black', 'opacity': '0.5'});
                $(".loader").show();
            },
            complete() {
                $('html,body').css('cursor', 'default');
                $("html").css({'background-color': '', 'opacity': ''});
                $(".loader").hide();
            },
            success(data) {
                poDetails.html(data);
            },
            error(errors) {
                alert("Something Went Wrong");
            }
        })
    });
</script>
@endsection
