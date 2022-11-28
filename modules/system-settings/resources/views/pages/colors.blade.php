@extends('skeleton::layout')
@section("title","Colors")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2 class="pull-left">Color List</h2>
                <a href="{{url('colors/pdf')}}" class="btn btn-xs btn-secondary pull-right"><i
                        class="fa fa-file-pdf-o"></i> Pdf</a>
                <div class="clearfix"></div>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-3 col-sm-offset-9">
                        <form action="{{ url('colors') }}" method="GET">
                            <div class="input-group">
                                <input
                                    type="text"
                                    name="search"
                                    placeholder="Search"
                                    value="{{ $search ?? '' }}"
                                    class="form-control form-control-sm">
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
                    @if(Session::has('permission_of_colors_add') || getRole() == 'super-admin' || getRole() == 'admin')
                        <div class="col-sm-12 col-md-5">

                            <div class="box form-colors">
                                <div class="box-header">
                                    <form action="{{ url('colors') }}" method="post" id="form">
                                        @csrf
                                        <div class="form-group">
                                            <label for="name">Name</label>
                                            <input type="text" id="name" name="name"
                                                   class="form-control form-control-sm"
                                                   value="{{ old('name') }}">
                                            @error('name')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            @error('status')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="status_section">
                                            <div class="form-group">
                                                <input type="checkbox" class="color color_1" name="status[]" value="1">
                                                <label for="status">Garments Color</label>
                                            </div>
                                            <div class="form-group">
                                                <input type="checkbox" class="color color_2" name="status[]" value="2">
                                                <label for="status">Team</label>
                                            </div>
                                            <div class="form-group">
                                                <input type="checkbox" class="color color_3" name="status[]" value="3">
                                                <label for="status">Fabric Color</label>
                                            </div>
                                            <div class="form-group">
                                                <input type="checkbox" class="color color_4" name="status[]" value="4">
                                                <label for="status">Stripe Color</label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" id="submit" class="btn btn-sm btn-success"><i
                                                    class="fa fa-save"></i> Save
                                            </button>
                                            <a href="{{ url('colors') }}" class="btn btn-sm btn-warning"><i
                                                    class="fa fa-refresh"></i> Refresh</a>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        </div>
                    @endif
                    <div class="col-sm-12 col-md-7">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>SL</th>
                                <th>Color Name</th>
                                <th>Company</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($colors as $color)
                                <tr class="{{$color->deleted_at?'text-danger':''}}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $color->name }}</td>
                                    <td>{{$color->factory->factory_name}}</td>
                                    <td>
                                        @if($color->status == 1)
                                            Garments Color
                                        @elseif($color->status == 2)
                                            Team
                                        @elseif($color->status == 3)
                                            Fabric Color
                                        @else
                                            Stripe Color
                                        @endif
                                    </td>
                                    <td>
                                        @if(Session::has('permission_of_colors_edit') || getRole() == 'super-admin' || getRole() == 'admin')
                                            <a href="javascript:void(0)" data-name="{{ $color->name }}"
                                               class="btn btn-xs btn-warning edit"><i class="fa fa-edit"></i></a>
                                        @endif
                                        @if(Session::has('permission_of_colors_delete') || getRole() == 'super-admin' || getRole() == 'admin')
                                            <button
                                                type="button"
                                                ui-toggle-class="flip-x" ui-target="#animate"
                                                title="{{$color->deleted_at?'Restore':'Delete'}}"
                                                data-toggle="modal" data-target="#confirmationModal"
                                                class="btn btn-xs btn-{{$color->deleted_at?'primary':'danger'}} show-modal"
                                                data-url="{{ url('colors/'.$color->id).($color->deleted_at?'?restore=true':'') }}">
                                                @if($color->deleted_at)
                                                    <i class="fa fa-reply"></i>
                                                @else
                                                    <i class="fa fa-trash"></i>
                                                @endif
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
                            {{ $colors->appends(request()->except('page'))->links() }}
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
            jQuery('.color').prop('checked', false)
            let name = $(this).data('name');
            $.ajax({
                method: 'get',
                url: '{{ url('colors') }}/' + name,
                success: function (result) {
                    $('#form').attr('action', `colors/${result.name}`).append(`<input type="hidden" name="_method" value="PUT"/>`);
                    $('#name').val(result.name);
                    $.each(result.status, function (key, status) {
                        jQuery('.color_' + status).prop('checked', true)
                    })
                    $('#submit').html(`<i class="fa fa-save"></i> Update`);
                },
                error: function (xhr) {
                    console.log(xhr)
                }
            })
        })
    </script>
@endpush
