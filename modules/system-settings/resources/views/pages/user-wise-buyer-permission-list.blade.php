@extends('skeleton::layout')
@section("title","User Wise Buyer Permission List")
@section('content')
    <div class="padding">
        <div class="box" >
            <div class="box-header">
                <h2>User Wise Buyer Permission List</h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-3">
                        <a class="btn btn-sm white m-b b-t m-b-1" href="{{ url('/user-wise-buyer-permission') }}">
                            <i class="glyphicon glyphicon-plus"></i> New User Wise Buyer Permission
                        </a>
                    </div>

                    <div class="col-sm-5  col-sm-offset-4 pull-right">
                        <form action="" method="GET">
                            <div class="col-md-8">
                                <input class="form-control form-control-sm" type="text" name="search" value="{{ request()->get('search') ?? '' }}"  id="search" placeholder="Search">
                            </div>

                            <div class="input-group-btn">
                                <div class="col-md-2" style="margin-left: -10px;">
                                    <button type="submit" class="btn btn-sm">Search</button>
                                </div>

                                <div class="col-md-2" style="margin-left: 45px;">
                                    <a href="{{ url('/user-wise-buyer-permission-list') }}" class="btn btn-sm btn-primary">Clear</a>
                                </div>
                            </div>

                        </form>

                    </div>

                </div>
                <div class="row">
                    <div class="col-sm-12">
                        @include('partials.response-message')
                    </div>
                </div>
                <div class="row m-t">
                    <div class="col-sm-12 col-md-12">
                        <table class="reportTable">
                            <thead>
                            <tr>
                                <th>SL</th>
                                <th>Factory</th>
                                <th>User</th>
                                <th>Buyer</th>
                                <th>Permission Type</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($permissions->groupBy('user_id') as $permissionGrp)
                                @foreach($permissionGrp as $permission)
                                    <tr>
                                        @if($loop->first)
                                            <td rowspan="{{ $permissionGrp->count() }}">{{ $loop->parent->iteration }}</td>
                                            <td rowspan="{{ $permissionGrp->count() }}">{{ $permission->factory->factory_name }}</td>
                                            <td rowspan="{{ $permissionGrp->count() }}">
                                                {{ $permission->user->screen_name }}
                                            </td>
                                        @endif
                                        <td>
                                            @if($permission->permission_type == 1)
                                                {{ $permission->buyer->name }}
                                            @else
                                                {{ $permission->viewBuyer->name }}
                                            @endif
                                        </td>
                                        <td>{{ $permission->permission_type == 1 ? 'All Permission' : 'View' }}</td>
                                        <td style="padding: 0.2%;">
                                            {{--                                        @if(Session::has('permission_of_buying_agent_edit') || getRole() == 'super-admin' || getRole() == 'admin')--}}
                                            {{--                                            <a href="javascript:void(0)" data-id="{{ $buyingAgent->id }}"--}}
                                            {{--                                               class="btn btn-xs btn-warning edit"><i class="fa fa-edit"></i></a>--}}
                                            {{--                                        @endif--}}
                                            <button type="button" class="btn btn-xs danger show-modal"
                                                    data-toggle="modal" data-target="#confirmationModal"
                                                    ui-toggle-class="flip-x" ui-target="#animate"
                                                    data-url="{{ url('user-wise-buyer-permission-list/'.$permission->id) }}">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @empty
                                <tr>
                                    <td colspan="5" style="text-align: center;">No Data Found</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                        <div class="text-center">
                            {{ $permissions->appends(request()->except('page'))->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-head')
    <script>

    </script>
@endpush
