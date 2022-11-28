@extends('skeleton::layout')
@section("title","Item Creations")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Item Creation List</h2>
            </div>
            <div class="box-body b-t">
                <a class="btn btn-sm white m-b" href="{{ url('item-creations/create') }}">
                    <i class="glyphicon glyphicon-plus"></i> New Item Creation
                </a>
                <div class="pull-right">
                    <form action="{{ url('item-creations') }}" method="GET">
                        <div class="pull-left" style="margin-right: 1px;">
                            <input type="text" class="form-control form-control-sm" name="q"
                                   value="{{ request('q') ?? '' }}" placeholder="Search..">
                        </div>
                        <div class="pull-right">
                            <input type="submit" class="btn btn-sm white" value="Search">
                        </div>
                    </form>
                </div>
            </div>
            @include('partials.response-message')
            <div class="col-md-12">
                <table class="reportTable">
                    <thead>
                    <tr>
                        <th>SL</th>
                        <th>Item Category</th>
                        <th>Item Group</th>
                        <th>Sub Group Name</th>
                        <th>Item Description</th>
                        <th>Item Size</th>
                        <th>Cons. UOM</th>
                        <th>Company</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($itemCreations as $itemCreation)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $itemCreation->itemGroup->item->item_name }}</td>
                            <td>{{ $itemCreation->itemGroup->item_group }}</td>
                            <td>{{ $itemCreation->sub_group_name }}</td>
                            <td>{{ $itemCreation->item_description }}</td>
                            <td>{{ $itemCreation->item_size }}</td>
                            <td>{{ $itemCreation->itemGroup->consUOM->unit_of_measurement }}</td>
                            <td>{{ optional($itemCreation->factory)->factory_name ?? ''}}</td>
                            <td>{{ $itemCreation->status }}</td>
                            <td>
                                @if(Session::has('permission_of_buyers_edit') || getRole() == 'super-admin' || getRole() == 'admin')
                                    <a class="btn btn-sm white"
                                       href="{{ url('item-creations/'.$itemCreation->id.'/edit') }}"><i
                                            class="fa fa-edit"></i></a>
                                @endif
                                @if(getRole() == 'super-admin' || getRole() == 'admin')
                                    <button type="button" class="btn btn-sm white show-modal" data-toggle="modal"
                                            data-target="#confirmationModal" ui-toggle-class="flip-x"
                                            ui-target="#animate"
                                            data-url="{{ url('item-creations/'.$itemCreation->id) }}">
                                        <i class="fa fa-times"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty

                    @endforelse
                    </tbody>
                </table>
                <div class="text-center">
                    {{ $itemCreations->appends(request()->except('page'))->links() }}
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
@endsection
