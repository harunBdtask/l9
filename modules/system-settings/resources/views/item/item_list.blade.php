@extends('skeleton::layout')
@section("title","Items")
@section('content')
    <div class="padding">
        @if(Session::has('permission_of_item_view') || getRole() == 'super-admin' || getRole() == 'admin')
            <div class="box" >
                <div class="box-header">
                    <h2>Items List</h2>
                </div>
                <div class="box-body b-t">
                    <div class="row">
                        <div class="col-sm-3 col-sm-offset-9">
                            <form action="{{ url('items-search') }}" method="GET">
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
                        @if(Session::has('permission_of_item_add') || getRole() == 'super-admin' || getRole() == 'admin')
                            <div class="col-sm-12 col-md-5">
                                <div class="box form-colors">
                                    <div class="box-header">
                                        <form action="{{ url('/items') }}" method="post" id="form">
                                            @csrf
                                            <div class="row">
                                                <div class="col-sm-12 col-md-6">
                                                    <div class="form-group">
                                                        <label for="item_name">Item Name</label>
                                                        <input type="text" name="item_name"  id="item_name" class="form-control form-control-sm"
                                                               value="{{ old('item_name') }}" placeholder="Item Name">
                                                        @error('item_name')
                                                        <div class="alert alert-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-md-6">
                                                    <div class="form-group">
                                                        <label for="status">Status</label>
                                                        <select name="status" id="status" class="form-control form-control-sm c-select">
                                                            <option value="Active">Active</option>
                                                            <option value="In Active">In Active</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <button type="submit" id="submit" class="btn btn-sm btn-success"><i
                                                                class="fa fa-save"></i> Create
                                                        </button>
                                                        <a href="{{ url('items') }}" class="btn btn-sm btn-warning"><i
                                                                class="fa fa-refresh"></i> Refresh</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                            </div>
                        @endif
                        <div class="col-sm-12 col-md-7">
                            <table class="reportTable display compact cell-border" id="item_list_table">
                                <thead>
                                <tr>
                                    <th>Sl.</th>
                                    <th>Item Name</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($items as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td class="text-left" style="padding-left: 10px">{{ $item->item_name ?? 'N/A' }}</td>
                                        <td>{{ $item->status }}</td>
                                        <td>
                                            @if(Session::has('permission_of_item_edit') || getRole() == 'super-admin' || getRole() == 'admin')
                                                <a class="btn btn-xs btn-success edit" data-id="{{ $item->id }}"
                                                   href="javascript:void(0)"><i class="fa fa-edit"></i></a>
                                            @endif
                                            @if(Session::has('permission_of_item_delete') || getRole() == 'super-admin' || getRole() == 'admin')
{{--                                                <button type="button" class="btn btn-xs btn-danger show-modal"--}}
{{--                                                        data-toggle="modal" data-target="#confirmationModal"--}}
{{--                                                        ui-toggle-class="flip-x" ui-target="#animate"--}}
{{--                                                        data-url="{{ url('items/'.$item->id.'/delete') }}">--}}
{{--                                                    <i class="fa fa-trash"></i>--}}
{{--                                                </button>--}}
                                            @endif
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
                                {{ $items->appends(request()->except('page'))->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
@push('script-head')
    <script>
        $(document).on('click', '.edit', function () {
            let id = $(this).data('id');
            $.ajax({
                method: 'get',
                url: '{{ url('items') }}/' + id,
                success: function (result) {
                    $('#form').attr('action', `/items/${result.id}`).append(`<input type="hidden" name="_method" value="PUT"/>`);
                    $('#item_name').val(result.item_name);
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
