@extends('skeleton::layout')
@section('title', 'Care Instructions')
@section('content')
    <div class="padding">
        <div class="box" >
            <div class="box-header">
                <h2>Care Instructions List</h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        @include('partials.response-message')
                    </div>
                </div>
                <div class="row m-t">
                    <div class="col-sm-12 col-md-5">
                        <div class="box form-colors" >
                            <div class="box-header">
                                <form action="{{ url('care-instructions') }}" method="post" id="form">
                                    @csrf
                                    <div class="form-group">
                                        <label for="instruction">Instruction</label>
                                        <input type="text" id="instruction" name="instruction" class="form-control form-control-sm" value="{{ old('instruction') }}" placeholder="Instruction">
                                        @error('instruction')
                                            <p class="alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" id="submit" class="btn btn-sm btn-success"><i class="fa fa-save"></i> Save</button>
                                        <a href="{{ url('care-instructions') }}" class="btn btn-sm btn-warning"><i class="fa fa-refresh"></i> Refresh</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-7">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>SL</th>
                                <th>Instruction</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($careInstructions as $instruction)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $instruction->instruction }}</td>
                                    <td>
                                        <a href="javascript:void(0)" data-id="{{ $instruction->id }}" class="btn btn-xs btn-warning edit"><i class="fa fa-edit"></i></a>
                                        <button type="button" class="btn btn-xs danger show-modal"
                                                data-toggle="modal" data-target="#confirmationModal"
                                                ui-toggle-class="flip-x" ui-target="#animate"
                                                data-url="{{ url('care-instructions/'.$instruction->id) }}">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" align="center">No Data Found</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                        <div class="text-center">
                            {{ $careInstructions->appends(request()->except('page'))->links() }}
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
                url: '{{ url("care-instructions") }}/' + id,
                success: function (result) {
                    $('#form').attr('action', `care-instructions/${result.id}`).append(`<input type="hidden" name="_method" value="PUT"/>`);
                    $('#instruction').val(result.instruction);
                    $('#submit').html(`<i class="fa fa-save"></i> Update`);
                },
                error: function (xhr) {
                    console.log(xhr)
                }
            })
        })
    </script>
@endpush
