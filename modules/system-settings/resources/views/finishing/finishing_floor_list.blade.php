@extends('skeleton::layout')
@section('title', 'Finishing Floor')

@push('style')
    <style>
        .table > thead > tr > th,
        .table > tbody > tr > td,
        .table > tbody > tr > th,
        .table > tfoot > tr > td,
        .table > tfoot > tr > th {
            padding: 3px;
            text-align: center;
        }

        .change-color {
            background: #00a65a;
        }


    </style>
@endpush
@section('content')
    <div class="padding">
        {{--    @if(Session::has('permission_of_section_view') || Session::get('user_role') == 'super-admin' || Session::get('user_role') == 'admin')--}}
        <div class="box knit-card">
            <div class="box-header">
                <h2>Finishing Floor List</h2>
                <div class="clearfix"></div>
            </div>

            <div class="row padding">
                <div class="col-sm-12 col-md-3">
                    {{--                @if(Session::has('permission_of_section_add') || Session::get('user_role') == 'super-admin' || Session::get('user_role') == 'admin')--}}
                    <div class="box form-colors">

                        <div class="box-body b-t">

                            {{ Form::open(array('id'=> 'form', 'url' => 'finishing-floor', 'method' => 'POST')) }}
                            <div class="form-group">
                                <label for="factory_id"><b>Factory </b></label>
                                {!! Form::select('factory_id', $factories, null, ['class' => 'form-control form-control-sm select2-input', 'id' => 'factory_id']) !!}

                                @if($errors->has('factory_id'))
                                    <span class="text-danger">{{ $errors->first('factory_id') }}</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="name"><b>Floor Name</b> </label>
                                {!! Form::text('name', null, ['class' => 'form-control form-control-sm', 'id' => 'name', 'placeholder' => 'Floor name']) !!}

                                @if($errors->has('name'))
                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="sorting"><b>Sorting</b> </label>
                                {!! Form::number('sorting', null, ['class' => 'form-control form-control-sm', 'id' => 'sorting', 'placeholder' => 'Sorting']) !!}

                                @if($errors->has('sorting'))
                                    <span class="text-danger">{{ $errors->first('sorting') }}</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="responsible_person"><b>Responsible Person</b> </label>
                                {!! Form::text('responsible_person', null,
                                    ['class' => 'form-control form-control-sm',
                                    'id' => 'responsible_person', 'placeholder' => 'Responsible Person']) !!}

                                @if($errors->has('responsible_person'))
                                    <span class="text-danger">{{ $errors->first('responsible_person') }}</span>
                                @endif
                            </div>

                            <div class="form-group">
                                <button type="submit" id="submit" class="btn btn-sm btn-success"><i class="fa fa-save"></i>
                                    Create
                                </button>
                                <a href="javascript:void(0)" onclick="cancel()" class="btn btn-sm btn-warning"><i
                                        class="fa fa-remove"></i> Cancel</a>
                            </div>

                            {{ Form::close() }}

                        </div>
                    </div>

                    {{--                @endif--}}
                </div>
                <div class="col-sm-12 col-md-9">
                    <div class="box-body b-t">
                        <div class="col-sm-6">
                        </div>
                        <div class="col-sm-6">

                            {!! Form::open(['url' => 'finishing-floor', 'method' => 'GET']) !!}
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm" name="search"
                                       value="{{ request('search') ?? '' }}" placeholder="Search">
                                <span class="input-group-btn">
                                    <button class="btn btn-sm white m-b" type="submit">Search</button>
                                </span>
                            </div>
                            {!! Form::close() !!}
                        </div>

                        <br>
                        <div class="flash-message" style="margin-top: 20px;">
                            @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                                @if(Session::has('alert-' . $msg))
                                    <p class="alert alert-{{ $msg }} text-center">{{ Session::get('alert-' . $msg) }}</p>
                                @endif
                            @endforeach
                        </div>
                        <hr>
                        <div class="table-responsive">
                            <table class="reportTable display compact cell-border" id="section_list_table">
                                <thead>
                                <tr>
                                    <th>Sl.</th>
                                    <th>Factory</th>
                                    <th>Floor</th>
                                    <th>Sorting</th>
                                    <th>Responsible</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody id="table">
                                @forelse($finishingFloors as $floor)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $floor->factory->factory_name }}</td>
                                        <td>{{ $floor->name }}</td>
                                        <td>{{ $floor->sorting }}</td>
                                        <td>{{ $floor->responsible_person }}</td>

                                        <td>
                                            {{--                                            @if(Session::has('permission_of_section_edit') || getRole() == 'super-admin' || getRole() == 'admin')--}}
                                            {{--                                            <a class="btn btn-xs btn-success" href="{{ url('section/'.$section->id.'/edit') }}"><i class="fa fa-edit"></i></a>--}}
                                            <a href="javascript:void(0)" data-id="{{ $floor->id }}"
                                               class="btn btn-xs btn-success edit"><i class="fa fa-edit"></i></a>
                                            {{--                                            @endif--}}
                                            {{--                                            @if(Session::has('permission_of_section_delete') || getRole() == 'super-admin' || getRole() == 'admin')--}}
                                            <button type="button" class="btn btn-xs btn-danger show-modal"
                                                    data-toggle="modal" data-target="#confirmationModal"
                                                    ui-toggle-class="flip-x" ui-target="#animate"
                                                    data-url="{{ url('finishing-floor/'.$floor->id) }}">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                            {{--                                            @endif--}}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6">No Data Found</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                            <div class="text-center">
                                {{ $finishingFloors->appends(request()->except('page'))->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{--    @endif--}}
    </div>


@endsection

@push('script-head')


    <script>
        $(document).on('click', '.edit', function () {
            let id = $(this).data('id');
            $.ajax({
                method: 'get',
                url: '{{ url('finishing-floor') }}/' + id,
                success: function (result) {
                    $('#box-header').addClass('change-color')
                    $('#box-header h2').html('Update Finishing Floor Info')
                    $('#form').attr('action', `finishing-floor/${result.id}`).append(`<input type="hidden" id="_method" name="_method" value="PUT"/>`);
                    $('#factory_id').val(result.factory_id).change();
                    $('#name').val(result.name);
                    $('#sorting').val(result.sorting);
                    $('#responsible_person').val(result.responsible_person);
                    $('#submit').html(`<i class="fa fa-save"></i> Update`);
                },
                error: function (xhr) {
                    console.log(xhr)
                }
            })
        })

        function cancel() {
            $('#box-header').removeClass('change-color')
            $('#box-header h2').html('New Finishing Floor')
            $('#name').val('');
            $('#sorting').val('');
            $('#responsible_person').val('');
            $('#form').attr('action', 'finishing-floor').append(`<input type="hidden" id="_method" name="_method" value="POST"/>`);
            $('#submit').html(`<i class="fa fa-save"></i> Create`);
            $('.text-danger').hide();
        }
    </script>
@endpush
