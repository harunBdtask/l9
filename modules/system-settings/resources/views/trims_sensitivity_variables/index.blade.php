@extends('skeleton::layout')
@section("title", "Trims Sensitivity Variable")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Trims Sensitivity Variable</h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-3 col-sm-offset-9">
                        <form action="{{ url('/trims-sensitivity-variables') }}" method="GET">
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm" name="search"
                                       value="{{ request()->query('search') ?? '' }}" placeholder="Search">
                                <span class="input-group-btn">
                                            <button class="btn btn-sm white m-b" type="submit">Search</button>
                                        </span>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="flash-message">
                            @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                                @if(Session::has('alert-' . $msg))
                                    <p class="alert alert-{{ $msg }} text-center">{{ Session::get('alert-' . $msg) }}</p>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="row m-t">
                    <div class="col-sm-12 col-md-5">
                        <div class="box form-colors">
                            <div class="box-header">
                                <form action="{{ url('/trims-sensitivity-variables') }}" method="post" id="form">
                                    @csrf
                                    <div class="form-group">
                                        <label for="buyer_id">Factory</label>
                                        {!! Form::select('factory_id', $factories ?? [], null, [
                                            'class' => 'form-control form-control-sm select-option',
                                            'id' => 'factory_id',
                                            'placeholder' => 'Select',
                                        ]) !!}
                                        @error('factory_id')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="buyer_id">Sensitivity Validation</label>
                                        {!! Form::select('sensitivity_variable', $variables ?? [], null, [
                                            'class' => 'form-control form-control-sm select-option',
                                            'id' => 'sensitivity_variable',
                                            'placeholder' => 'Select',
                                        ]) !!}
                                        @error('sensitivity_variable')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" id="submit" class="btn btn-sm btn-success"><i
                                                class="fa fa-save"></i> Save
                                        </button>
                                        <a href="{{ url('/trims-sensitivity-variables') }}"
                                           class="btn btn-sm btn-warning"><i
                                                class="fa fa-refresh"></i> Refresh</a>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-7">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Sl No</th>
                                <th>Factory</th>
                                <th>Issue</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($trimsVariables as $variable)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $variable->factory->factory_name ?? '' }}</td>
                                    <td>{{ $variable->sensitivity_variable_value ?? '' }}</td>
                                    <td style="display: inline-flex">
                                        <a href="javascript:void(0)" data-id="{{ $variable->id }}"
                                           class="btn btn-sm white edit"><i
                                                class="fa fa-edit"></i></a>
                                        <button type="button" class="btn btn-xs btn-danger show-modal"
                                                data-toggle="modal"
                                                data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                ui-target="#animate"
                                                data-url="{{ url('trims-sensitivity-variables/'.$variable->id) }}">
                                            <i class="fa fa-times"></i>
                                        </button>
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
                            {{ $trimsVariables->appends(request()->except('page'))->links() }}
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
                url: '{{ url('trims-sensitivity-variables') }}/' + id + '/edit',
                success: function (result) {
                    $('#form').attr('action', `trims-sensitivity-variables/${result.id}`).append(`<input type="hidden" id="_method" name="_method" value="PUT"/>`);
                    $('#factory_id').val(result.factory_id);
                    $('#sensitivity_variable').val(result.sensitivity_variable);
                    $('#submit').html(`<i class="fa fa-save"></i> Update`);
                },
                error: function (xhr) {
                    console.log(xhr)
                }
            })
        })

        function cancel() {
            $('#company_name').val('');
            $('#form').attr('action', '/companies').append(`<input type="hidden" id="_method" name="_method" value="POST"/>`);
            $('#submit').html(`<i class="fa fa-save"></i> Create`);
            $('.text-danger').hide();
        }
    </script>
@endpush
