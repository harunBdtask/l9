@extends('skeleton::layout')
@section("title","Item Subgroups")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Item Subgroups</h2>
            </div>
            <div class="box-body b-t">
                <div class="row">
                    <div class="col-sm-3 col-sm-offset-9">
                        <form action="{{ url('/item-subgroups') }}" method="GET">
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm" name="search"
                                       value="{{ $search ?? '' }}" placeholder="Search">
                                <span class="input-group-btn">
                                            <button class="btn btn-sm white m-b" type="submit">Search</button>
                                        </span>
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
                    <div class="col-sm-12 col-md-5">
                        <div class="box form-colors">
                            <div class="box-header">
                                <form action="{{ url('/item-subgroups') }}" method="post" id="form">
                                    @csrf
                                    <div class="row">
                                        <div class="col-sm-12 col-md-12">
                                            <div class="form-group">
                                                <label for="item_name">Subgroup Name</label>
                                                <input type="text" name="name" id="name"
                                                       class="form-control form-control-sm"
                                                       value="{{ old('name') }}" placeholder="Item Subgroup Name">
                                                @error('name')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-12">
                                            <div class="form-group">
                                                <label for="status">Status</label>
                                                <select name="status" id="status"
                                                        class="form-control form-control-sm c-select">
                                                    <option value="1">Active</option>
                                                    <option value="2">InActive</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <button type="submit" id="submit" class="btn btn-sm btn-success"><i
                                                        class="fa fa-save"></i> Submit
                                                </button>
                                                <a href="{{ url('/item-subgroups') }}" class="btn btn-sm btn-warning"><i
                                                        class="fa fa-refresh"></i> Refresh</a>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-7">
                        <table class="reportTable display compact cell-border" id="item_list_table">
                            <thead>
                            <tr>
                                <th>Sl.</th>
                                <th>Item Subgroup Name</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($itemSubgroups as $itemSubgroup)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="text-left" style="padding-left: 10px">
                                        {{ $itemSubgroup->name ?? 'N/A' }}
                                    </td>
                                    <td>{{ $itemSubgroup->status == 1 ? 'Active' : 'InActive' }}</td>
                                    <td>
                                        {{--@if(Session::has('permission_of_item_edit') || getRole() == 'super-admin' || getRole() == 'admin')--}}
                                        <a class="btn btn-xs btn-success edit" data-id="{{ $itemSubgroup->id }}"
                                           href="javascript:void(0)"><i class="fa fa-edit"></i></a>
                                        {{--@endif--}}
                                        {{--@if(Session::has('permission_of_item_delete') || getRole() == 'super-admin' || getRole() == 'admin')--}}
                                        <button type="button" class="btn btn-xs btn-danger show-modal"
                                                data-toggle="modal" data-target="#confirmationModal"
                                                ui-toggle-class="flip-x" ui-target="#animate"
                                                data-url="{{ url('/item-subgroups/'.$itemSubgroup->id) }}">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                        {{--@endif--}}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">No Data Found</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                        <div class="text-center">
                            {{ $itemSubgroups->appends(request()->except('page'))->links() }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
@push('script-head')
    <script>
        $(document).on('click', '.edit', function () {
            let id = $(this).data('id');
            $.ajax({
                method: 'get',
                url: '{{ url('/item-subgroups') }}/' + id + '/edit',
                success: function (result) {
                    $('#form').attr('action', `/item-subgroups/${result.id}`).append(`<input type="hidden" name="_method" value="PUT"/>`);
                    $('#name').val(result.name);
                    $('#status').val(result.status);
                    $('#submit').html(`<i class="fa fa-save"></i> Update`);
                },
                error: function (xhr) {
                    console.log(xhr)
                }
            })
        })
    </script>
@endpush
