@extends('skeleton::layout')
@section('title', 'Care Label Types')

@section('content')
    <!-- ############ PAGE START-->
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Care Label Types</h2>
            </div>
            <div class="row padding">
                <div class="col-sm-12 col-md-4">
                    <div class="box">
                        <div class="box-body form-colors">
                            <div class="row">
                                <div class="col-md-12">
                                    {!! Form::model($careLabelType, ['id'=> 'form','url' =>'care-label-types', 'method' => 'POST', 'files' => true]) !!}

                                    <div class="form-group">
                                        <label for="name"><b>Name</b></label>
                                        {!! Form::text('name', null, ['class' => 'form-control form-control-sm', 'id' => 'name', 'placeholder' => 'Write name\'s name here', 'required']) !!}

                                        @if($errors->has('name'))
                                            <span class="text-danger">{{ $errors->first('name') }}</span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" id="submit" class="btn btn-success btn-sm"><i
                                                class="fa fa-save"></i> {{ $careLabelType ? 'Update' : 'Create' }}
                                        </button>
                                        <a class="btn btn-sm btn-warning" onclick="cancel()"><i
                                                class="fa fa-remove"></i> Cancel</a>
                                    </div>
                                    {!! Form::close() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12 col-md-8">
                    <div class="box-body table-responsive b-t">
                        <div class="flash-message">
                            @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                                @if(Session::has('alert-' . $msg))
                                    <div class="alert alert-{{ $msg }} text-center">
                                        {{ Session::get('alert-' . $msg) }}
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        <table class="reportTable">
                            <thead>
                            <tr>
                                <th>SL</th>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody class="company-list">
                            @if(!$careLabelTypes->getCollection()->isEmpty())
                                @foreach($careLabelTypes as $careLabelType)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $careLabelType->name }}</td>
                                        <td>
                                            <a href="javascript:void(0)" data-id="{{ $careLabelType->id }}"
                                               class="btn btn-sm white edit"><i
                                                    class="fa fa-edit"></i></a>

                                            <button type="button" class="btn btn-sm white show-modal"
                                                    data-toggle="modal"
                                                    data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                    ui-target="#animate"
                                                    data-url="{{ url('care-label-types/'.$careLabelType->id) }}">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="3" align="center" class="text-danger">
                                        No Data Found
                                    </td>
                                </tr>
                            @endif
                            </tbody>
                            <tfoot>
                            @if($careLabelTypes->total() > 15)
                                <tr>
                                    <td colspan="3" align="center">
                                        {{ $careLabelTypes->appends(request()->except('page'))->links() }}
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
@endsection


@push('script-head')
    <script>
        $(document).on('click', '.edit', function () {
            let id = $(this).data('id');
            $.ajax({
                method: 'get',
                url: '{{ url('care-label-types') }}/' + id + '/edit',
                success: function (result) {
                    $('#form').attr('action', `care-label-types/${result.id}`).append(`<input type="hidden" id="_method" name="_method" value="PUT"/>`);
                    $('#name').val(result.name);
                    $('#submit').html(`<i class="fa fa-save"></i> Update`);
                },
                error: function (xhr) {
                    console.log(xhr)
                }
            })
        })

        function cancel() {
            $('#name').val('');
            $('#form').attr('action', '/care-label-types').append(`<input type="hidden" id="_method" name="_method" value="POST"/>`);
            $('#submit').html(`<i class="fa fa-save"></i> Create`);
            $('.text-danger').hide();
        }
    </script>
@endpush
