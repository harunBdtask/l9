@extends('skeleton::layout')
@section('title', 'Purpose List')

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
        <div class="box knit-card">
            <div class="box-header">
                <h2>Purpose</h2>
                <div class="clearfix"></div>
            </div>

            <div class="row padding">
                <div class="col-sm-12 col-md-3">
                    <div class="box">
                        <div class="box-body b-t">
                            {{ Form::open(array('id'=> 'form', 'url' => '/finance/fund-requisition/purposes', 'method' => 'POST')) }}
                            <div class="form-group">
                                <label for="name"><b>Purpose</b></label>
                                {!! Form::text('purpose', null, ['class' => 'form-control form-control-sm', 'id' => 'purpose', 'placeholder' => 'Write purpose']) !!}

                                @if($errors->has('name'))
                                    <span class="text-danger">{{ $errors->first('purpose') }}</span>
                                @endif
                            </div>

                            <div class="form-group">
                                <button type="submit" id="submit" class="btn btn-sm white"><i
                                        class="fa fa-save"></i> Create
                                </button>
                                <a href="javascript:void(0)" onclick="cancel()" class="btn btn-sm btn-dark"><i
                                        class="fa fa-remove"></i> Cancel</a>
                            </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-9">
                    <div class="box-body b-t">
                        <div class="col-sm-6">
                        </div>
                        <div class="col-sm-6">

                            {!! Form::open(['url' => '/finance/fund-requisition/purposes/search', 'method' => 'GET']) !!}
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
                                    <th>Purposes</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody id="table">
                                @forelse($purposes as $purpose)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $purpose->purpose }}</td>
                                        <td>
                                            <a href="javascript:void(0)" data-id="{{ $purpose->id }}"
                                               class="btn btn-xs btn-success edit"><i class="fa fa-edit"></i></a>
                                            <button type="button" class="btn btn-xs btn-danger show-modal"
                                                    data-toggle="modal" data-target="#confirmationModal"
                                                    ui-toggle-class="flip-x" ui-target="#animate"
                                                    data-url="{{ url('finance/fund-requisition/purposes/'.$purpose->id.'/delete') }}">
                                                <i class="fa fa-trash"></i>
                                            </button>
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
                                {{ $purposes->appends(request()->except('page'))->links() }}
                            </div>
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
                url: '{{ url('finance/fund-requisition/purposes') }}/' + id + '/edit',
                success: function (result) {
                    $('#box-header').addClass('change-color')
                    $('#box-header h2').html('Update Section Info')
                    $('#form').attr('action', `/finance/fund-requisition/purposes/${result.id}`).append(`<input type="hidden" id="_method" name="_method" value="PUT"/>`);
                    $('#purpose').val(result.purpose);
                    $('#submit').html(`<i class="fa fa-save"></i> Update`);
                },
                error: function (xhr) {
                    console.log(xhr)
                }
            })
        })

        function cancel() {
            $('#box-header').removeClass('change-color')
            $('#box-header h2').html('New Section')
            $('#purpose').val('');
            $('#form').attr('action', '/finance/fund-requisition/purposes').append(`<input type="hidden" id="_method" name="_method" value="POST"/>`);

            $('#submit').html(`<i class="fa fa-save"></i> Create`);
            $('.text-danger').hide();

        }
    </script>
@endpush
