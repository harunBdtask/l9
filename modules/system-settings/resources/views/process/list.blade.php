@extends('skeleton::layout')
@section("title","Process")
@section('content')
    <div class="padding">
        <div class="box" >
            <div class="box-header">
                <h2>Process List</h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-3 col-sm-offset-9">
                        <form action="{{ url('processes-search') }}" method="GET">
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
                    @if(getRole() == 'super-admin' || getRole() == 'admin' || Session::has('permission_of_process_add'))
                        <div class="col-sm-12 col-md-5">

                            <div class="box form-colors" >
                                <div class="box-header">
                                    <form action="{{ url('processes') }}" method="post" id="form">
                                        @csrf
                                        <div class="form-group">
                                            <label for="process_name">Process</label>
                                            <input type="text" id="process_name" name="process_name" class="form-control form-control-sm" value="{{ old('process_name') }}" placeholder="Process">
                                            @error('process_name')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="color_wise_charge_unit">
                                                <input id="color_wise_charge_unit" name="color_wise_charge_unit" type="checkbox" value="1">
                                                Color Wise Charge Unit</label>
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" id="submit" class="btn btn-sm btn-success"><i class="fa fa-save"></i> Save</button>
                                            <a href="{{ url('processes') }}" class="btn btn-sm btn-warning"><i class="fa fa-refresh"></i> Refresh</a>
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
                                <th>Name</th>
                                <th>Charge Unit</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($processes as $process)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $process->process_name }}</td>
                                    <td>{{ $process->color_wise_charge_unit ? 'Increment' : 'N/A' }}</td>
                                    <td>
                                        @if(getRole() == 'super-admin' || getRole() == 'admin'  || Session::has('permission_of_process_edit'))
                                            <a href="javascript:void(0)" data-id="{{ $process->id }}" class="btn btn-xs btn-warning edit"><i class="fa fa-edit"></i></a>
                                        @endif
                                        @if(getRole() == 'super-admin' || getRole() == 'admin' || Session::has('permission_of_process_delete'))
                                            <button type="button" class="btn btn-xs danger show-modal"
                                                    data-toggle="modal" data-target="#confirmationModal"
                                                    ui-toggle-class="flip-x" ui-target="#animate"
                                                    data-url="{{ url('processes/'.$process->id) }}">
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
                            {{ $processes->appends(request()->except('page'))->links() }}
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
                url: '{{ url('processes') }}/' + id,
                success: function (result) {
                    $('#form').attr('action', `processes/${result.id}`).append(`<input type="hidden" name="_method" value="PUT"/>`);
                    $('#process_name').val(result.process_name);
                    if(parseInt(result.color_wise_charge_unit) === 1){
                        $('#color_wise_charge_unit').prop('checked', true);
                    } else {
                        $('#color_wise_charge_unit').prop('checked', false);
                    }
                    $('#submit').html(`<i class="fa fa-save"></i> Update`);
                },
                error: function (xhr) {
                    console.log(xhr)
                }
            })
        })
    </script>
@endpush
