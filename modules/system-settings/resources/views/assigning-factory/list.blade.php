@extends('skeleton::layout')
@section("title","Assigning Factory")
@section('content')
    <div class="padding">
        <div class="box" style="min-height: 610px">
            <div class="box-header btn-info">
                <h2>Assigning Factory List</h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-3 col-sm-offset-9">
                        <form action="{{ url('assigning-factory-search') }}" method="GET">
                            <div class="input-group">
                                <input type="text" class="form-control" name="search"
                                       value="{{ $search ?? '' }}" placeholder="Search">
                                <span class="input-group-btn">
                                            <button class="btn btn-info" type="submit">Search</button>
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
                    {{--                    @if(Session::has('permission_of_buying_agent_add') || getRole() == 'super-admin' || getRole() == 'admin')--}}
                    <div class="col-sm-12 col-md-5">
                        <form action="{{ url('assigning-factory') }}" method="post" id="form">
                            @csrf
                            <div class="form-group">
                                <label for="name">Assigning Factory</label>
                                <input type="text" id="name" name="name"
                                       class="form-control" value="{{ old('name') }}"
                                       placeholder="Assigning Factory">
                                @error('assigning_factory_name')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="address">Address</label>
                                {!! Form::textarea('address', null, ['class' => 'form-control', 'id' => 'address', 'placeholder' => 'Address', 'rows' => 2]) !!}
                                @if($errors->has('address'))
                                    <span class="text-danger">{{ $errors->first('address') }}</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <div class="text-right">
                                    <a href="{{ url('assigning-factory') }}" class="btn btn-sm btn-warning"><i
                                            class="fa fa-refresh"></i> Refresh</a>
                                    <button type="submit" id="submit" class="btn btn-sm btn-success"><i
                                            class="fa fa-save"></i> Save
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    {{--                    @endif--}}
                    <div class="col-sm-12 col-md-7">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>SL</th>
                                <th>Assigning Factory</th>
                                <th>Address</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($assigningFactories as $assigningFactory)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $assigningFactory->name }}</td>
                                    <td>{{ $assigningFactory->address }}</td>
                                    <td>
                                        {{--                                        @if(Session::has('permission_of_buying_agent_edit') || getRole() == 'super-admin' || getRole() == 'admin')--}}
                                        <a href="javascript:void(0)" data-id="{{ $assigningFactory->id }}"
                                           class="btn btn-xs btn-warning edit"><i class="fa fa-edit"></i></a>
                                        {{--                                        @endif--}}
                                        {{--                                        @if(Session::has('permission_of_buying_agent_delete') || getRole() == 'super-admin' || getRole() == 'admin')--}}
                                        <button type="button" class="btn btn-xs danger show-modal"
                                                data-toggle="modal" data-target="#confirmationModal"
                                                ui-toggle-class="flip-x" ui-target="#animate"
                                                data-url="{{ url('assigning-factory/'.$assigningFactory->id) }}">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                        {{--                                        @endif--}}
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
                            {{ $assigningFactories->appends(request()->except('page'))->links() }}
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
                url: '{{ url('assigning-factory') }}/' + id,
                success: function (result) {
                    $('#form').attr('action', `assigning-factory/${result.id}`).append(`<input type="hidden" name="_method" value="PUT"/>`);
                    $('#name').val(result.name);
                    $('#address').val(result.address);
                    $('#submit').html(`<i class="fa fa-save"></i> Update`);
                },
                error: function (xhr) {
                    console.log(xhr)
                }
            })
        })
    </script>
@endpush
