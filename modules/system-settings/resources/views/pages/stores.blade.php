@extends('skeleton::layout')
@section("title","Store")
@section('content')
    <div class="padding">
        <div class="box" >
            <div class="box-header">
                <h2>Stores List</h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-3 col-sm-offset-9">
                        <form action="{{ url('/stores-search') }}" method="GET">
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
                    @if(Session::has('permission_of_store_add') || getRole() == 'super-admin' || getRole() == 'admin')
                        <div class="col-sm-12 col-md-4">
                            <div class="box form-colors" >
                                <div class="box-header">
                                    <form action="{{ url('/stores') }}" method="post" id="form">
                                        @csrf
                                        <div class="form-group">
                                            <label for="name">Store Name</label>
                                            <input type="text" id="name" name="name" class="form-control form-control-sm"
                                                   value="{{ old('name') }}" placeholder="Store Name">
                                            @error('name')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="company">Select Company</label>
                                            {!! Form::select('factory_id', $factories, null, ['class' => 'form-control form-control-sm', 'id'=>'factory_id', 'placeholder' => 'Select a Company']) !!}
                                            <span class="text-danger factory_id"></span>
                                            @if($errors->has('factory_id'))
                                                <span class="text-danger">{{ $errors->first('factory_id') }}</span>
                                            @endif

                                        </div>
                                        <div class="form-group">
                                            <label for="name">Location</label>
                                            <input type="text" id="location" name="location" class="form-control form-control-sm"
                                                   value="{{ old('location') }}" placeholder="location">
                                            @error('location')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="item_category">Item Category</label>
                                            {!! Form::select('item_category_id', $items, null, ['class' => 'form-control form-control-sm', 'id'=>'item_category_id', 'placeholder' => 'Select a Item']) !!}
                                            <span class="text-danger item_category_id"></span>
                                            @if($errors->has('item_category'))
                                                <span class="text-danger">{{ $errors->first('item_category_id') }}</span>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="status">Status</label>
                                            <select name="status" id="status" class="form-control form-control-sm form-control form-control-sm-sm">
                                                <option value="1">Active</option>
                                                <option value="0">In Active</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <button type="submit" id="submit" class="btn btn-sm btn-success"><i
                                                    class="fa fa-save"></i> Save
                                            </button>
                                            <a href="{{ url('/stores') }}" class="btn btn-sm btn-warning"><i
                                                    class="fa fa-refresh"></i> Refresh</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="col-sm-12 col-md-8">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>SL</th>
                                <th>Store Name</th>
                                <th>Company Name</th>
                                <th>Location</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($stores as $store)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $store->name }}</td>
                                    <td>{{ $store->factory->factory_name }}</td>
                                    <td>{{ $store->location }}</td>
                                    <td>
                                        @if(Session::has('permission_of_store_edit') || getRole() == 'super-admin' || getRole() == 'admin')
                                            <a href="javascript:void(0)" data-id="{{ $store->id }}"
                                               class="btn btn-xs btn-warning edit"><i class="fa fa-edit"></i></a>
                                        @endif
                                        @if(Session::has('permission_of_store_delete') || getRole() == 'super-admin' || getRole() == 'admin')
                                            <button type="button" class="btn btn-xs danger show-modal"
                                                    data-toggle="modal" data-target="#confirmationModal"
                                                    ui-toggle-class="flip-x" ui-target="#animate"
                                                    data-url="{{ url('/stores/'.$store->id) }}">
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
                            {{ $stores->appends(request()->except('page'))->links() }}
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
                url: '{{ url('/stores') }}/' + id,
                success: function (result) {
                    $('#form').attr('action', `/stores/${result.id}`).append(`<input type="hidden" name="_method" value="PUT"/>`);
                    $('#name').val(result.name);
                    $('#factory_id').val(result.factory_id);
                    $('#status').val(result.status);
                    $('#location').val(result.location);
                    $('#item_category_id').val(result.item_category_id);
                    $('#submit').html(`<i class="fa fa-save"></i> Update`);
                },
                error: function (xhr) {
                    console.log(xhr)
                }
            })
        });

        $(document).on('change', 'select[name="factory_id"]', function () {
            let factory_id = $(this).val();
            if (factory_id) {
                $.ajax({
                    'type': 'GET',
                    'url': '/fetch-factory-address',
                    'data': {'factory_id': factory_id}
                }).done(function (response) {

                    console.log(response);
                    $('input[name="location"]').val(response?.text);
                }).fail(function (response) {
                    console.log("Something went wrong!!");
                });
            }
        });
    </script>
@endpush
