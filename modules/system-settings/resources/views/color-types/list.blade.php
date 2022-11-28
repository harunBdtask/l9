@extends('skeleton::layout')
@section("title","Color Types")
@section('content')
    <div class="padding">
        <div class="box" >
            <div class="box-header">
                <h2>Color Type List</h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-3 col-sm-offset-9">
                        <form action="{{ url('color-types-search') }}" method="GET">
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
                    @if(Session::has('permission_of_color_type_add') || getRole() == 'super-admin' || getRole() == 'admin')
                        <div class="col-sm-12 col-md-4">
                            <div class="box form-colors">
                                <div class="box-header">
                                    <form action="{{ url('color-types') }}" method="post" id="form">
                                        @csrf
                                        <div class="form-group">
                                            <label for="color_types">Color Type</label>
                                            <input type="text" id="color_types" name="color_types" class="form-control form-control-sm"
                                                   value="{{ old('color_types') }}" placeholder="Color Type">
                                            @error('color_types')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="status">Status</label>
                                            <select name="status" id="status" class="form-control form-control-sm c-select">
                                                <option value="0">Select</option>
                                                <option value="1">Increment</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="is_stripe">Is Stripe</label>
                                            <select name="is_stripe" id="is_stripe" class="form-control form-control-sm c-select">
                                                <option value="0">No</option>
                                                <option value="1">Yes</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <a href="{{ url('color-types') }}" class="btn btn-sm btn-warning"><i
                                                    class="fa fa-refresh"></i> Refresh</a>
                                            <button type="submit" id="submit" class="btn btn-sm btn-success"><i
                                                    class="fa fa-save"></i> Save
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="col-sm-12 col-md-8">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>SL</th>
                                <th>Color Type</th>
                                <th>Status</th>
                                <th>Is Stripe</th>
                                <th>Company</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($colorTypes as $colorType)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $colorType->color_types }}</td>
                                    <td>{{ $colorType->status == 1 ? 'Increment' : '---'}}</td>
                                    <td>{{ $colorType->is_stripe == 1 ? 'Yes' : 'No'}}</td>
                                    <td>{{$colorType->factory->factory_name}}</td>
                                    <td>
                                        @if(Session::has('permission_of_color_type_edit') || getRole() == 'super-admin' || getRole() == 'admin')
                                            <a href="javascript:void(0)" data-id="{{ $colorType->id }}"
                                               class="btn btn-xs btn-warning edit"><i class="fa fa-edit"></i></a>
                                        @endif
                                        @if(Session::has('permission_of_color_type_delete') || getRole() == 'super-admin' || getRole() == 'admin')
                                            <button type="button" class="btn btn-xs danger show-modal"
                                                    data-toggle="modal" data-target="#confirmationModal"
                                                    ui-toggle-class="flip-x" ui-target="#animate"
                                                    data-url="{{ url('color-types/'.$colorType->id) }}">
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
                            {{ $colorTypes->appends(request()->except('page'))->links() }}
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
                url: '{{ url('color-types') }}/' + id,
                success: function (result) {
                    $('#form').attr('action', `color-types/${result.id}`).append(`<input type="hidden" name="_method" value="PUT"/>`);
                    $('#color_types').val(result.color_types);
                    $('#status').val(result.status);
                    $('#is_stripe').val(result.is_stripe);
                    $('#submit').html(`<i class="fa fa-save"></i> Update`);
                },
                error: function (xhr) {
                    console.log(xhr)
                }
            })
        })
    </script>
@endpush
