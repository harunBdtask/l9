@extends('skeleton::layout')
@section('title', 'Sections List')

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

    .change-color{
        background: #00a65a;
    }



</style>
@endpush
@section('content')
<div class="padding">
    @if(Session::has('permission_of_section_view') || Session::get('user_role') == 'super-admin' || Session::get('user_role') == 'admin')
    <div class="box knit-card">
        <div class="box-header">
            <h2>Sections List</h2>
            <div class="clearfix"></div>
        </div>

        <div class="row padding">
            <div class="col-sm-12 col-md-3">
                @if(Session::has('permission_of_section_add') || Session::get('user_role') == 'super-admin' || Session::get('user_role') == 'admin')
                    <div class="box" >

                        <div class="box-body b-t form-colors">

                            {{ Form::open(array('id'=> 'form', 'url' => 'section/sections-store', 'method' => 'POST')) }}
                            <div class="form-group">
                                <label for="name" ><b>Section Name </b></label>
                                {!! Form::text('name', null, ['class' => 'form-control form-control-sm', 'id' => 'name', 'placeholder' => 'Write Sections\'s name here']) !!}

                                @if($errors->has('name'))
                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="description" ><b>Section Description</b> </label>
                                {!! Form::text('description', null, ['class' => 'form-control form-control-sm', 'id' => 'description', 'placeholder' => 'description']) !!}

                                @if($errors->has('description'))
                                    <span class="text-danger">{{ $errors->first('description') }}</span>
                                @endif
                            </div>


                            <div class="form-group">
                                <button type="submit" id="submit" class="btn btn-sm btn-success"><i class="fa fa-save"></i> Create </button>
                                <a href="javascript:void(0)" onclick="cancel()" class="btn btn-sm btn-warning"><i class="fa fa-remove"></i> Cancel</a>
                            </div>

                            {{ Form::close() }}

                        </div>
                    </div>

                @endif
            </div>
            <div class="col-sm-12 col-md-9">
                <div class="box-body b-t">
                    <div class="col-sm-6">
                    </div>
                    <div class="col-sm-6">

                        {!! Form::open(['url' => 'section/search', 'method' => 'GET']) !!}
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm" name="q"
                                       value="{{ $q ?? '' }}" placeholder="Search">
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
                                <th>Section Name</th>
                                <th> Description</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody id="table">
                            @forelse($sections as $section)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $section->name }}</td>
                                    <td>{{ $section->description }}</td>

                                    <td>
                                        @if(Session::has('permission_of_section_edit') || getRole() == 'super-admin' || getRole() == 'admin')
{{--                                            <a class="btn btn-xs btn-success" href="{{ url('section/'.$section->id.'/edit') }}"><i class="fa fa-edit"></i></a>--}}
                                            <a href="javascript:void(0)" data-id="{{ $section->id }}" class="btn btn-xs btn-success edit"><i class="fa fa-edit"></i></a>
                                        @endif
                                        @if(Session::has('permission_of_section_delete') || getRole() == 'super-admin' || getRole() == 'admin')
                                            <button type="button" class="btn btn-xs btn-danger show-modal" data-toggle="modal" data-target="#confirmationModal" ui-toggle-class="flip-x" ui-target="#animate" data-url="{{ url('section/'.$section->id.'/delete') }}">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">No Data Found</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                        <div class="text-center">
                            {{ $sections->appends(request()->except('page'))->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

{{--  previous code ------------------------------}}

{{--        <div class="box-body b-t">--}}
{{--            <div class="col-sm-6">--}}
{{--                @if(Session::has('permission_of_section_add') || Session::get('user_role') == 'super-admin' || Session::get('user_role') == 'admin')--}}
{{--                    <a href="{{url('section/add-section')}}" class="btn btn-sm white m-b add-new-btn btn-sm">--}}
{{--                        <i class="glyphicon glyphicon-plus"></i> Add New Section--}}
{{--                    </a>--}}
{{--                @endif--}}
{{--            </div>--}}
{{--            <div class="col-sm-6">--}}
{{--                {!! Form::open(['url' => 'section/search', 'method' => 'GET']) !!}--}}
{{--                    <div class="form-group">--}}
{{--                        <div class="row m-b">--}}
{{--                            <div class="col-sm-offset-4 col-sm-5">--}}
{{--                                {!! Form::text('q', $q ?? null, ['class' => 'form-control form-control-sm', 'placeholder' => 'Section']) !!}--}}
{{--                            </div>--}}
{{--                            <div class="col-sm-3">--}}
{{--                                <button type="submit" class="btn btn-sm white m-b button-class"--}}
{{--                                        style="border-radius: 0px">--}}
{{--                                    Search--}}
{{--                                </button>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                {!! Form::close() !!}--}}
{{--            </div>--}}

{{--            <br>--}}
{{--            <div class="flash-message" style="margin-top: 20px;">--}}
{{--                @foreach (['danger', 'warning', 'success', 'info'] as $msg)--}}
{{--                @if(Session::has('alert-' . $msg))--}}
{{--                <p class="alert alert-{{ $msg }} text-center">{{ Session::get('alert-' . $msg) }}</p>--}}
{{--                @endif--}}
{{--                @endforeach--}}
{{--            </div>--}}
{{--            <hr>--}}
{{--            <div class="table-responsive">--}}
{{--                <table class="reportTable display compact cell-border" id="section_list_table">--}}
{{--                    <thead>--}}
{{--                        <tr>--}}
{{--                            <th>Sl.</th>--}}
{{--                            <th>Section Name</th>--}}
{{--                            <th> Description</th>--}}
{{--                            <th>Action</th>--}}
{{--                        </tr>--}}
{{--                    </thead>--}}
{{--                    <tbody>--}}
{{--                    @forelse($sections as $section)--}}
{{--                        <tr>--}}
{{--                            <td>{{ $loop->iteration }}</td>--}}
{{--                            <td>{{ $section->name }}</td>--}}
{{--                            <td>{{ $section->description }}</td>--}}

{{--                            <td>--}}
{{--                                @if(Session::has('permission_of_section_edit') || getRole() == 'super-admin' || getRole() == 'admin')--}}
{{--                                    <a class="btn btn-xs btn-success" href="{{ url('section/'.$section->id.'/edit') }}"><i class="fa fa-edit"></i></a>--}}
{{--                                @endif--}}
{{--                                @if(Session::has('permission_of_section_delete') || getRole() == 'super-admin' || getRole() == 'admin')--}}
{{--                                    <button type="button" class="btn btn-xs btn-danger show-modal" data-toggle="modal" data-target="#confirmationModal" ui-toggle-class="flip-x" ui-target="#animate" data-url="{{ url('section/'.$section->id.'/delete') }}">--}}
{{--                                        <i class="fa fa-trash"></i>--}}
{{--                                    </button>--}}
{{--                                @endif--}}
{{--                            </td>--}}
{{--                        </tr>--}}
{{--                    @empty--}}
{{--                        <tr>--}}
{{--                            <td colspan="4">No Data Found</td>--}}
{{--                        </tr>--}}
{{--                    @endforelse--}}
{{--                    </tbody>--}}
{{--                </table>--}}
{{--                <div class="text-center">--}}
{{--                    {{ $sections->appends(request()->except('page'))->links() }}--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
    </div>
    @endif
</div>


@endsection

@push('script-head')


    <script>
        $(document).on('click', '.edit', function () {
            let id = $(this).data('id');
            $.ajax({
                method: 'get',
                url: '{{ url('section') }}/' + id + '/edit',
                success: function (result) {
                    $('#box-header').addClass('change-color')
                    $('#box-header h2').html('Update Section Info')
                    $('#form').attr('action', `section/${result.id}/update`).append(`<input type="hidden" id="_method" name="_method" value="PUT"/>`);
                    $('#name').val(result.name);
                    $('#description').val(result.description);
                    $('#submit').html(`<i class="fa fa-save"></i> Update`);
                },
                error: function (xhr) {
                    console.log(xhr)
                }
            })
        })

        // $('#searchInput').on("keyup", function () {
        //     var value = $(this).val().toLowerCase();
        //     $('#table tr').filter(function () {
        //         $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        //     });
        // });

        function cancel(){
            $('#box-header').removeClass('change-color')
            $('#box-header h2').html('New Section')
            $('#name').val('');
            $('#description').val('');
            $('#form').attr('action', 'section/sections-store').append(`<input type="hidden" id="_method" name="_method" value="POST"/>`);;
            $('#submit').html(`<i class="fa fa-save"></i> Create`);
            $('.text-danger').hide();

        }
    </script>
@endpush
