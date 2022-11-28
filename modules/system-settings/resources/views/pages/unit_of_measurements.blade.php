@extends('skeleton::layout')
@section("title","Unit of measurements")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>UOM List</h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-3 col-sm-offset-9">
                        <form action="{{ url('/unit-of-measurements-search') }}" method="GET">
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
                    @if(Session::has('permission_of_unit_of_measurement_add') || getRole() == 'super-admin' || getRole() == 'admin')


                        <div class="col-sm-12 col-md-5">
                            <div class="box form-colors">
                                <div class="box-header">
                                    <form action="{{ url('/unit-of-measurements') }}" method="post" id="form">
                                        @csrf
                                        <div class="form-group">
                                            <label for="unit_of_measurement">Name</label>
                                            <input type="text" id="unit_of_measurement" name="unit_of_measurement"
                                                   class="form-control form-control-sm"
                                                   value="{{ old('unit_of_measurement') }}"
                                                   placeholder="Unit Of Measurement">
                                            @error('unit_of_measurement')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="status">Status</label>
                                            <select name="status" id="status"
                                                    class="form-control form-control-sm c-select">
                                                <option value="Active">Active</option>
                                                <option value="In Active">In Active</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            @if(getRole() === 'super-admin')
                                                <button type="submit" id="submit" class="btn btn-sm btn-success"><i
                                                        class="fa fa-save"></i> Save
                                                </button>
                                            @endif
                                            <a href="{{ url('/unit-of-measurements') }}" class="btn btn-sm btn-warning"><i
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
                                <th>UOM Name</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($unitOfMeasurements as $unitOfMeasurement)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $unitOfMeasurement->unit_of_measurement }}</td>
                                    <td>{{$unitOfMeasurement->status}}</td>
                                    <td>
                                        @if(Session::has('permission_of_unit_of_measurement_edit') || getRole() === 'admin' || getRole() ===
                                        'super-admin')
                                            <a href="javascript:void(0)" data-id="{{ $unitOfMeasurement->id }}"
                                               class="btn btn-xs btn-warning edit"><i class="fa fa-edit"></i>
                                            </a>

                                        @endif
                                        @if(Session::has('permission_of_unit_of_measurement_delete') || getRole() === 'admin' || getRole() ===
                                        'super-admin')
                                            <button type="button" class="btn btn-xs danger show-modal"
                                                    data-toggle="modal"
                                                    data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                    ui-target="#animate"
                                                    data-url="{{ url('/unit-of-measurements/'.$unitOfMeasurement->id) }}">
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
                            {{ $unitOfMeasurements->appends(request()->except('page'))->links() }}
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
                url: '{{ url('/unit-of-measurements') }}/' + id,
                success: function (result) {
                    $('#form').attr('action', `/unit-of-measurements/${result.id}`).append(`<input type="hidden" name="_method" value="PUT"/>`);
                    $('#unit_of_measurement').val(result.unit_of_measurement);
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
