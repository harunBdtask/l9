@extends('skeleton::layout')
@section("title","Body Parts")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Body Part List</h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-3 col-sm-offset-9">
                        <form action="{{ url('body-parts-search') }}" method="GET">
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
                    @if(Session::has('permission_of_body_part_add') || getRole() == 'super-admin' || getRole() == 'admin')
                        <div class="col-sm-12 col-md-4">
                            <div class="box form-colors">
                                <div class="box-header">
                                    <form action="{{ url('body-parts') }}" method="post" id="form">
                                        @csrf
                                        <div class="row">
                                            <div class="col-sm-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="name">Name</label>
                                                    <input type="text" id="name" name="name"
                                                           class="form-control form-control-sm" value="{{ old('name') }}"
                                                           placeholder="Body Part">
                                                    @error('name')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="short_name">Short Name</label>
                                                    <input type="text" id="short_name" name="short_name"
                                                           class="form-control form-control-sm" value="{{ old('short_name') }}"
                                                           placeholder="Short Name">
                                                    @error('short_name')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="entry_page">Entry Page</label>
                                                    <select name="entry_page[]" id="entry_page"
                                                            class="form-control form-control-sm select2-input c-select form-control form-control-sm-sm select2-hidden-accessible"
                                                            multiple required>
                                                        @foreach($entryPages as $entryPage)
                                                            <option value="{{ $entryPage }}">{{ $entryPage }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="type">Body Part Type</label>
                                                    <select name="type" id="type" class="form-control form-control-sm c-select"
                                                            required>
                                                        <option value="">Select Type</option>
                                                        @foreach($types as $type)
                                                            <option value="{{ $type }}">{{ $type }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="status">Status</label>
                                                    <select name="status" id="status"
                                                            class="form-control form-control-sm c-select">
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
                                                            class="fa fa-save"></i> Save
                                                    </button>
                                                    <a href="{{ url('body-parts') }}" class="btn btn-sm btn-warning"><i
                                                            class="fa fa-refresh"></i> Refresh</a>
                                                </div>
                                            </div>
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
                                <th>Full Name</th>
                                <th>Short Name</th>
                                <th>Entry Page</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th style="width: 92px">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($bodyParts as $bodyPart)
                                <tr style="font-size: 12px">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $bodyPart->name }}</td>
                                    <td>{{ $bodyPart->short_name }}</td>
                                    <td>
                                        @foreach(explode(',', $bodyPart->entry_page) as $item)
                                            <code style="color: black">{{ $item }}</code>
                                        @endforeach
                                    </td>
                                    <td>{{ $bodyPart->type }}</td>
                                    <td>{{ $bodyPart->status }}</td>
                                    <td>
                                        @if(Session::has('permission_of_body_part_edit') || getRole() == 'super-admin' || getRole() == 'admin')
                                            <a href="javascript:void(0)" data-id="{{ $bodyPart->id }}"
                                               class="btn btn-xs btn-warning edit"><i class="fa fa-edit"></i></a>
                                        @endif
                                        @if(Session::has('permission_of_body_part_delete') || getRole() == 'super-admin' || getRole() == 'admin')
                                            <button type="button" class="btn btn-xs danger show-modal"
                                                    data-toggle="modal" data-target="#confirmationModal"
                                                    ui-toggle-class="flip-x" ui-target="#animate"
                                                    data-url="{{ url('body-parts/'.$bodyPart->id) }}">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" align="center">No Data Found</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                        <div class="text-center">
                            {{ $bodyParts->appends(request()->except('page'))->links() }}
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
                url: '{{ url('body-parts') }}/' + id,
                success: function (result) {
                    $('#form').attr('action', `body-parts/${result.id}`).append(`<input type="hidden" name="_method" value="PUT"/>`);
                    $('#name').val(result.name);
                    $('#short_name').val(result.short_name);
                    $('#entry_page').val(result.entry_page.split(',')).trigger('change');
                    $('#type').val(result.type);
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
