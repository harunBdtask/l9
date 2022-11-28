@extends('skeleton::layout')
@section("title","Items Group")
@section('content')
    <div class="padding">
        @if(Session::has('permission_of_item_group_view') || Session::get('user_role') == 'super-admin' || Session::get('user_role') == 'admin')
            <div class="box knit-card">
                <div class="box-header">
                    <h2>Items Group List</h2>
                </div>
                <div class="box-body b-t">
                    <div class="col-md-6">
                        @if(Session::has('permission_of_item_group_add') || Session::get('user_role') == 'super-admin' || Session::get('user_role') == 'admin')
                            <a href="{{url('item-group/add-item-group')}}"
                               class="btn btn-sm white m-b add-new-btn btn-sm">
                                <i class="glyphicon glyphicon-plus"></i> Add Item Group
                            </a>
                        @endif
                    </div>
                    <div class="col-md-3"></div>
                    <div class="col-md-3">
                        {!! Form::open(['url' => 'item-group', 'method' => 'GET']) !!}
                        <div class="input-group">
                            <input type="text" class="form-control form-control-sm" name="q"
                                   value="{{ request('q') ?? '' }}" placeholder="Search">
                            <span class="input-group-btn">
                            <button class="btn btn-sm white m-b" type="submit">Search</button>
                        </span>
                        </div>
                        {!! Form::close() !!}
                    </div>
                    <br>
                    <div class="flash-message" style="margin-top: 20px;">
                        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                            @if(Session::has('alert-' . $msg))
                                <p class="alert alert-{{ $msg }} text-center">{{ Session::get('alert-' . $msg) }}</p>
                            @endif
                        @endforeach
                    </div>
                    <hr>
                    <div class="table-responsive">
                        <table class="table table-bordered display compact cell-border" id="item_group_list">
                            <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Product Id</th>
                                <th>Item Category</th>
                                <th>Group Code</th>
                                <th>Item Subgroup Name</th>
                                <th>Item Group</th>
                                <th>Trims Type</th>
                                <th>Order UOM</th>
                                <th>Cons UOM</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($itemGroups as $key => $itemGroup)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $itemGroup->product_id }}</td>
                                    <td>{{ optional($itemGroup->item)->item_name}}</td>
                                    <td>{{ $itemGroup->group_code }}</td>
                                    <td>{{ $itemGroup->itemSubgroup->name ?? 'N/A' }}</td>
                                    <td>{{ $itemGroup->item_group }}</td>
                                    <td>{{ $itemGroup->trims_type }}</td>
                                    <td>{{ $itemGroup->orderUOM ? $itemGroup->orderUOM->unit_of_measurement : 'N/A' }}</td>
                                    <td>{{ $itemGroup->consUOM ? $itemGroup->consUOM->unit_of_measurement  : 'N/A' }}</td>
                                    <td>
                                        @if(Session::has('permission_of_item_group_edit') || getRole() == 'super-admin' || getRole() == 'admin')
                                            <a class="btn btn-sm white"
                                               href="{{ url('item-group/'.$itemGroup->id.'/edit') }}"><i
                                                    class="fa fa-edit"></i></a>
                                        @endif
                                        @if(Session::has('permission_of_item_group_delete') || getRole() == 'super-admin' || getRole() == 'admin')
                                            <button type="button" class="btn btn-sm white show-modal"
                                                    data-toggle="modal" data-target="#confirmationModal"
                                                    ui-toggle-class="flip-x" ui-target="#animate"
                                                    data-url="{{ url('item-group/'.$itemGroup->id.'/delete') }}">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" align="center">No Data Found</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div colspan="4" align="center">{{ $itemGroups->appends(request()->except('page'))->links() }}</div>
                </div>
            </div>
        @endif
    </div>
@endsection
