@extends('skeleton::layout')
@section("title","Incoterm")
@section('content')
    <div class="padding">
        <div class="box" >
            <div class="box-header">
                <h2>Incoterm List</h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-3 col-sm-offset-9">
                        <form action="{{ url('incoterms-search') }}" method="GET">
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
                    @if(Session::has('permission_of_incoterms_add') || getRole() == 'super-admin' || getRole() == 'admin')
                        <div class="col-sm-12 col-md-5">
                            <div class="box form-colors">
                                <div class="box-header">
                                    <form action="{{ url('incoterms') }}" method="post" id="form">
                                        @csrf
                                        <div class="form-group">
                                            <label for="incoterm">Incoterm</label>
                                            <input type="text" id="incoterm" name="incoterm" class="form-control form-control-sm" value="{{ old('incoterm') }}" placeholder="Incoterm">
                                            @error('incoterm')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" id="submit" class="btn btn-sm btn-success"><i class="fa fa-save"></i> Save</button>
                                            <a href="{{ url('incoterms') }}" class="btn btn-sm btn-warning"><i class="fa fa-refresh"></i> Refresh</a>
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
                                <th>Incoterm Name</th>
                                <th>Created By</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($incoterms as $incoterm)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $incoterm->incoterm }}</td>
                                    <td>{{ $incoterm->preparedBy->first_name }} {{ $incoterm->preparedBy->last_name }}</td>
                                    <td>
                                        @if(Session::has('permission_of_incoterms_edit') || getRole() == 'super-admin' || getRole() == 'admin')
                                            <a href="javascript:void(0)" data-id="{{ $incoterm->id }}" class="btn btn-xs btn-warning edit"><i class="fa fa-edit"></i></a>
                                        @endif
                                        @if(Session::has('permission_of_incoterms_delete') || getRole() == 'super-admin' || getRole() == 'admin')
                                            <button type="button" class="btn btn-xs danger show-modal"
                                                    data-toggle="modal" data-target="#confirmationModal"
                                                    ui-toggle-class="flip-x" ui-target="#animate"
                                                    data-url="{{ url('incoterms/'.$incoterm->id) }}">
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
                            {{ $incoterms->appends(request()->except('page'))->links() }}
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
                url: '{{ url('incoterms') }}/' + id,
                success: function (result) {
                    $('#form').attr('action', `incoterms/${result.id}`).append(`<input type="hidden" name="_method" value="PUT"/>`);
                    $('#incoterm').val(result.incoterm);
                    $('#submit').html(`<i class="fa fa-save"></i> Update`);
                },
                error: function (xhr) {
                    console.log(xhr)
                }
            })
        })
    </script>
@endpush
