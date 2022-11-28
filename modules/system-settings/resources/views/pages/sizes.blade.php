@extends('skeleton::layout')
@section("title","Sizes")
@section('styles')
  <style>
    .selected-tr {
      background-color: aquamarine!important;
    }
  </style>
@endsection
@section('content')
    <div class="padding">
        <div class="box" >
            <div class="box-header">
                <h2>Size List</h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-3 col-sm-offset-9">
                        <form action="{{ url('sizes-search') }}" method="GET">
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
                    @if(Session::has('permission_of_sizes_add') || getRole() == 'super-admin' || getRole() == 'admin')
                        <div class="col-sm-12 col-md-5">
                            <div class="box form-colors" >
                                <div class="box-header">
                                    <form action="{{ url('sizes') }}" method="post" id="form">
                                        @csrf
                                        <div class="form-group">
                                            <label for="name">Name</label>
                                            <input type="text" id="name" name="name" class="form-control form-control-sm"
                                                   value="{{ old('name') }}" placeholder="Size">
                                            @error('name')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="sort">Sort</label>
                                            <input type="text" id="sort" name="sort" class="form-control form-control-sm"
                                                   value="{{ old('sort') }}" placeholder="Sorting No.">
                                            @error('sort')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" id="submit" class="btn btn-sm btn-success"><i
                                                    class="fa fa-save"></i> Save
                                            </button>
                                            <a href="{{ url('sizes') }}" class="btn btn-sm btn-warning"><i
                                                    class="fa fa-refresh"></i> Refresh</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="col-sm-12 col-md-7">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>SL</th>
                                <th>Size Name</th>
                                <th>Sorting No.</th>
                                <th>Company</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($sizes as $size)
                                <tr class="size-data">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $size->name }}</td>
                                    <td>{{ $size->sort }}</td>
                                    <td>{{$size->factory->factory_name}}</td>
                                    <td>
                                        @if(Session::has('permission_of_sizes_edit') || getRole() == 'super-admin' || getRole() == 'admin')
                                            <a href="javascript:void(0)" data-id="{{ $size->id }}"
                                               class="btn btn-xs btn-warning edit"><i class="fa fa-edit"></i></a>
                                        @endif
                                        @if(Session::has('permission_of_sizes_delete') || getRole() == 'super-admin' || getRole() == 'admin')
                                            <button type="button" class="btn btn-xs danger show-modal"
                                                    data-toggle="modal" data-target="#confirmationModal"
                                                    ui-toggle-class="flip-x" ui-target="#animate"
                                                    data-url="{{ url('sizes/'.$size->id) }}">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" align="center">No Data Found</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                        <div class="text-center">
                            {{ $sizes->appends(request()->except('page'))->links() }}
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
            $('tr.selected-tr').removeClass('selected-tr');
            $(this).parents('tr').addClass('selected-tr');
            $.ajax({
                method: 'get',
                url: '/sizes/' + id,
                success: function (result) {
                    $('#form').attr('action', `sizes/${result.id}`).append(`<input type="hidden" name="_method" value="PUT"/>`);
                    $('#name').val(result.name);
                    $('#sort').val(result.sort);
                    $('#submit').html(`<i class="fa fa-save"></i> Update`);
                },
                error: function (xhr) {
                    console.log(xhr)
                }
            })
        })
    </script>
@endpush
