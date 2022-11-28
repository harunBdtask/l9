@extends('skeleton::layout')
@section("title","Terms & Conditions")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Terms And Conditions</h2>
            </div>
            <div class="row m-t">
                <div class="col-sm-12">
                    @include('partials.response-message')
                </div>
            </div>
            <div class="box-body">
                <div class="sr-only">
                    <table>
                        <tbody id="target_data">
                        <tr>
                            <td>*</td>
                            <td>
                                <input type="text" name="terms_name[]"
                                       class="form-control form-control-sm form-control form-control-sm-sm"
                                       placeholder="Page Name">
                            </td>
                            <td>
                                <i style="cursor: pointer"
                                   class="fa fa-plus element_add btn btn-sm btn-primary"></i>
                                <i style="cursor: pointer"
                                   class="fa fa-minus element_remove btn btn-sm btn-warning"></i>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-6 form-colors" id="season_form">
                        <form action="{{ url('/terms-conditions') }}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-sm-12 col-md-7">
                                    <div class="form-group">
                                        <label for="buyer_id">Page Name</label>
                                        <select name="page_name" id="page_name"
                                                class="form-control form-control-sm form-control form-control-sm-sm"
                                                required>
                                            <option value="">Select Page</option>
                                            @foreach($pages  as $key => $page_name)
                                                <option value="{{ $key }}">{{ $page_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <table class="table table-bordered season-form">
                                        <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>Terms</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody id="terms_add">
                                        <tr>
                                            <td>*</td>
                                            <td>
                                                <input type="text" name="terms_name[]"
                                                       class="form-control form-control-sm form-control form-control-sm-sm"
                                                       placeholder="Terms Name">
                                            </td>
                                            <td>
                                                <i style="cursor: pointer"
                                                   class="fa fa-plus element_add btn btn-sm btn-primary"></i>
                                                <i style="cursor: pointer"
                                                   class="fa fa-minus element_remove btn btn-sm btn-warning"></i>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <div class="form-group">
                                        <button type="submit" id="action_button" class="btn btn-sm btn-success"><i
                                                class="fa fa-save"></i>
                                            Save
                                        </button>
                                        <a href="{{ url('/terms-conditions') }}" class="btn btn-sm btn-warning"><i
                                                class="fa fa-refresh"></i> Refresh</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>SL</th>
                                <th>Page Name</th>
                                <th>Terms Name</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($data as $index => $item)
                                <tr>
                                    <td>{{ ++$index }}</td>
                                    <td>{{ $pages[$item['page_name']] ?? null }}</td>
                                    <td>{{ $item['terms_name'] }}</td>
                                    <td align="center">
                                        <button type="button" class="btn btn-xs btn-warning terms_edit"
                                                data-id="{{ $item['id'] }}"><i class="fa fa-edit "></i>
                                        </button>
                                        <button type="button" class="btn btn-xs btn-danger show-modal"
                                                data-toggle="modal" data-target="#confirmationModal"
                                                ui-toggle-class="flip-x" ui-target="#animate"
                                                data-url="{{ url('terms-conditions/'.$item['id']) }}">
                                            <i class="fa fa-close"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td align="center" colspan="4">No Data Found</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                        <div class="text-center">
                            {{ $data->appends(request()->except('page'))->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script-head')
    <script>
        var rowIdx = 0;

        $(document).on('click', '.element_add', function () {
            $('.season-form').find('tbody:last').append($(`#target_data`).html());
        });

        $(document).on('click', '.element_remove', function () {
            let length = $('.season-form tr').length;
            if (length < 3) {
                alert('Last row can`t be deleted');
                return false;
            }
            $(this).closest('tr').remove();
        });

        $(document).on('click', '.terms_edit', function () {
            let id = $(this).data('id');
            $.ajax({
                method: 'get',
                url: '{{ url('terms-conditions') }}/' + id + '/' + 'edit',
                success: function (result) {
                    console.log(result);
                    if (result.status == 'error') {
                        alert(result.message)
                    } else {
                        $('#season_form').html('').html(result);
                    }
                },
                error: function (xhr) {
                    console.log(xhr)
                }
            })
        });
        $(document).on('click', '.terms_delete', function () {
            let id = $(this).data('id');
            $.ajax({
                type: 'DELETE',
                headers: {'Content-Type': 'application/json'},
                url: '{{ url('terms-conditions') }}/' + id,
                success: function (result) {
                    console.log(result);
                },
                error: function (xhr) {
                    console.log(xhr)
                }
            })
        });
    </script>
@endpush
