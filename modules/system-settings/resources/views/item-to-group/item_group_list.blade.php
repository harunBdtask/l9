@extends('skeleton::layout')

@push('style')
<style>
    .table > thead > tr > th,
    .table > tbody > tr > td,
    .table > tbody > tr > th,
    .table > tfoot > tr > td,
    .table > tfoot > tr > th {
        padding: 3px;
        /*text-align: center;*/
    }
    .select2-container .select2-selection--single {
        height: 38px;
        border-radius: 0px;
        line-height: 50px;
        border: 1px solid #e7e7e7;
    }

    .reportTable .select2-container .select2-selection--single {
        border: 1px solid #e7e7e7;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 40px;
        width: 100%;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        top: 8px;
    }

    .error + .select2-container .select2-selection--single {
        border: 1px solid red;
    }

    .select2-container--default .select2-selection--multiple {
        min-height: 40px !important;
        border-radius: 0px;
        width: 100%;
    }
</style>
@endpush
@section('content')
<div class="padding">
    @if(Session::has('permission_of_item_to_group_view') || Session::get('user_role') == 'super-admin' || Session::get('user_role') == 'admin')
    <div class="box knit-card">
        <div class="box-header">
            <h2>Group Items List</h2>
        </div>
        <div class="box-body b-t">
            <div class="col-sm-6">
                @if(Session::has('permission_of_item_to_group_add') || Session::get('user_role') == 'super-admin' || Session::get('user_role') == 'admin')
                    <a href="{{url('item-to-group/assign-item-to-group')}}" class="btn btn-sm white m-b add-new-btn btn-sm">
                        <i class="glyphicon glyphicon-plus"></i> Assign Item To Group
                    </a>
                @endif
            </div>
            <div class="col-sm-6 pull-right">
                {!! Form::open(['url' => 'item-to-group/search', 'method' => 'GET']) !!}
                <div class="form-group">
                    <div class="row m-b">
                        <div class="col-sm-4">
                            {!! Form::select('search_column', ['item_group_id' => 'Group', 'item_id' => 'Item'], $search_column ?? null, ['class' => 'form-control form-control-sm select2-input', 'placeholder' => 'Select Search Column']) !!}
                        </div>
                        <div class="col-sm-5">
                            {!! Form::text('q', $q ?? null, ['class' => 'form-control form-control-sm', 'placeholder' => 'Item, Group']) !!}
                        </div>
                        <div class="col-sm-3">
                            <button type="submit" class="btn btn-sm white m-b button-class"
                                    style="border-radius: 0px">
                                Search
                            </button>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>

            <br>
            <div class="flash-message" style="margin-top: 20px;">
                @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                @if(Session::has('alert-' . $msg))
                <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</p>
                @endif
                @endforeach
            </div>
            <div class="table-responsive" style="margin-top: 20px;">
                <table class="reportTable" id="item_group_list_table">
                    <thead>
                        <tr>
                            <th>Sl.</th>
                            <th>Group Name</th>
                            <th>Item Name</th>
                            <th>Company</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if(!$item_details->getCollection()->isEmpty())
                        @foreach($item_details->getCollection() as $item_detail)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item_detail->group->item_group_name ?? '' }}</td>
                                <td>{{ $item_detail->item->item_name }}</td>
                                <td>{{ $item_detail->factory->factory_name ?? '' }}</td>
                                <td>{{ $item_detail->status == 1? "Active" : ($item_detail->status == 2 ? "In Active": "Cancelled") }}</td>
                                <td>
                                    @if(Session::has('permission_of_item_to_group_edit') || getRole() == 'super-admin' || getRole() == 'admin')
                                        <a class="btn btn-sm white" href="{{ url('item-to-group/'.$item_detail->id.'/edit') }}"><i class="fa fa-edit"></i></a>
                                    @endif
                                    @if(Session::has('permission_of_item_to_group_delete') || getRole() == 'super-admin' || getRole() == 'admin')
                                        <button type="button" class="btn btn-sm white show-modal" data-toggle="modal" data-target="#confirmationModal" ui-toggle-class="flip-x" ui-target="#animate" data-url="{{ url('item-to-group/'.$item_detail->id.'/delete') }}">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4" align="center">No Supplier
                            </td>
                        </tr>
                    @endif
                    </tbody>
                    <tfoot>
                    @if($item_details->total() > 15)
                        <tr>
                            <td colspan="4" align="center">{{ $item_details->appends(request()->except('page'))->links() }}</td>
                        </tr>
                    @endif
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
