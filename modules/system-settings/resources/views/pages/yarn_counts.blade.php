@extends('skeleton::layout')
@section("title","Yarn Counts")
@section('content')
    <div class="padding">
        <div class="box" >
            <div class="box-header">
                <h2>Yarn Count List</h2>
            </div>

            <div class="box-body">
                <div class="row" >
                    <div class="col-sm-3 col-sm-offset-9">
                        <form action="{{ url('yarn-counts-search') }}" method="GET">
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
                    @if(Session::has('permission_of_yarn_count_add') || getRole() == 'super-admin' || getRole() == 'admin')
                        <div class="col-sm-12 col-md-5 form-colors">
                            <form action="{{ url('yarn-counts') }}" method="post" id="form">
                                @csrf
                                <div class="form-group">
                                    <label for="yarn_count">Yarn Count</label>
                                    <input type="text" id="yarn_count" name="yarn_count" class="form-control form-control-sm" value="{{ old('yarn_count') }}" placeholder="Yarn Compositions">
                                    @error('yarn_count')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <button type="submit" id="submit" class="btn btn-sm btn-success"><i class="fa fa-save"></i> Save</button>
                                    <a href="{{ url('yarn-counts') }}" class="btn btn-sm btn-warning"><i class="fa fa-refresh"></i> Refresh</a>
                                </div>
                            </form>
                        </div>
                    @endif
                    <div class="col-sm-12 col-md-7">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>SL</th>
                                <th>Yarn Count</th>
                                <th>Company</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($yarnCounts as $yarnCount)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $yarnCount->yarn_count }}</td>
                                    <td>{{$yarnCount->factory->factory_name}}</td>
                                    <td>
                                        @if(Session::has('permission_of_yarn_count_edit') || getRole() == 'super-admin' || getRole() == 'admin')
                                            <a href="javascript:void(0)" data-id="{{ $yarnCount->id }}" class="btn btn-xs btn-warning edit"><i class="fa fa-edit"></i></a>
                                        @endif
                                        @if(Session::has('permission_of_yarn_count_delete') || getRole() == 'super-admin' || getRole() == 'admin')
                                            <button type="button" class="btn btn-xs danger show-modal"
                                                    data-toggle="modal" data-target="#confirmationModal"
                                                    ui-toggle-class="flip-x" ui-target="#animate"
                                                    data-url="{{ url('yarn-counts/'.$yarnCount->id) }}">
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
                            {{ $yarnCounts->appends(request()->except('page'))->links() }}
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
                url: '{{ url('yarn-counts') }}/' + id,
                success: function (result) {
                    $('#form').attr('action', `yarn-counts/${result.id}`).append(`<input type="hidden" name="_method" value="PUT"/>`);
                    $('#yarn_count').val(result.yarn_count);
                    $('#submit').html(`<i class="fa fa-save"></i> Update`);
                },
                error: function (xhr) {
                    console.log(xhr)
                }
            })
        })
    </script>
@endpush
