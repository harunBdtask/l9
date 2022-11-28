@extends('skeleton::layout')
@section('title', 'Module')

@push('style')
    <style>
        .search-div {
            margin-top: -30px;
        }

        .table-div {
            margin-top: 30px;
        }
    </style>
@endpush

@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Module List</h2>
            </div>

            <div class="row padding">
                <div class="col-m-12 col-md-3">
                    <div class="box">
                        <div class="box-body form-colors">
                            @if(Session::has('permission_of_modules_add') || getRole() == 'super-admin' || getRole() == 'admin')

                                {!! Form::model($module, ['url' => 'modules-data', 'method' => 'POST', 'id'=>'form']) !!}
                                <div class="form-group">
                                    <label for="module_name"><b>Module Name</b></label>
                                    {!! Form::text('module_name', null, ['class' => 'form-control form-control-sm', 'id' => 'module_name', 'placeholder' => 'Write module\'s name here']) !!}

                                    @if($errors->has('module_name'))
                                        <span class="text-danger">{{ $errors->first('module_name') }}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <button type="submit" id="submit" class="btn btn-sm btn-success">Create</button>
                                    <a class="btn btn-sm btn-dark warning" onclick="cancel()" href="javascript:void(0)"><i
                                            class="fa fa-remove"></i> Cancel</a>
                                </div>
                                {!! Form::close() !!}
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-m-12 col-md-9">
                    <div class="box-body b-t search-div">
                        <div class="col-md-6 pull-right">
                            <form action="{{ url('/modules-data') }}" method="GET">
                                <div class="input-group">
                                    <input id="searchInput" type="text" class="form-control form-control-sm" name="q"
                                           value="{{ request('q') ?? '' }}"
                                           placeholder="Search here">
                                    <span class="input-group-btn">
                                        <button class="btn btn-sm white m-b" type="submit">Search</button>
                                    </span>
                                </div>
                            </form>
                        </div>
                        <br>
                        <div class="col-md-12 flash-message p-t-2">
                            @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                                @if(Session::has('alert-' . $msg))
                                    <p class="alert alert-{{ $msg }} text-center">{{ Session::get('alert-' . $msg) }}</p>
                                @endif
                            @endforeach
                        </div>
                        <div class="table-responsive table-div">
                            <table class="reportTable">
                                <thead>
                                <tr>
                                    <th width="20%">SL</th>
                                    <th width="40%">Module Name</th>
                                    <th width="40%">Actions</th>
                                </tr>
                                </thead>
                                <tbody id="table">
                                @if(!$modules->getCollection()->isEmpty())
                                    @foreach($modules->getCollection() as $module)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $module->module_name }}</td>
                                            <td>
                                                @if(Session::has('permission_of_modules_edit') || getRole() == 'super-admin')
                                                    <a href="javascript:void(0)" data-id="{{ $module->id }}"
                                                       class="btn btn-sm btn-success edit"><i
                                                            class="fa fa-edit"></i></a>
                                                @endif
                                                @if(Session::has('permission_of_modules_delete') || getRole() == 'super-admin')
                                                    <button type="button" class="btn btn-sm btn-danger show-modal"
                                                            data-toggle="modal"
                                                            data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                            ui-target="#animate"
                                                            data-url="{{ url('modules-data/'.$module->id) }}">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="3" align="center">
                                            No Modules
                                        <td>
                                    </tr>
                                @endif
                                </tbody>
                                <tfoot>
                                @if($modules->total() > 10)
                                    <tr>
                                        <td colspan="3" align="center">
                                            {{ $modules->appends(request()->except('page'))->links() }}
                                        </td>
                                    </tr>
                                @endif
                                </tfoot>
                            </table>
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
                url: '{{ url('modules-data') }}/' + id + '/edit',
                success: function (result) {
                    $('#form').attr('action', `modules-data/${result.id}`).append(`<input type="hidden" id="_method" name="_method" value="PUT"/>`);
                    $('#module_name').val(result.module_name);
                    $('#submit').html(`<i class="fa fa-save"></i> Update`);
                },
                error: function (xhr) {
                    console.log(xhr)
                }
            })
        })

        function cancel() {
            $('#module_name').val('');
            $('#form').attr('action', '/companies').append(`<input type="hidden" id="_method" name="_method" value="POST"/>`);
            $('#submit').html(`<i class="fa fa-save"></i> Create`);
            $('.text-danger').hide();
        }

        // $('#searchInput').on("keyup", function () {
        //     var value = $(this).val().toLowerCase();
        //     $('#table tr').filter(function () {
        //         $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        //     });
        // });

    </script>
@endpush
