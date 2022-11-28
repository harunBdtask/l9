@extends('skeleton::layout')
@section('title', 'Company')
@push('style')
    <style>

    </style>
@endpush

@section('content')
    <!-- ############ PAGE START-->
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>GROUP</h2>
            </div>
            <div class="row padding">
                <div class="col-sm-12 col-md-4">
                    @if(getRole() == 'super-admin')
                        <div class="box">
                            <div class="box-body form-colors">
                                <div class="row">
                                    <div class="col-md-12">
                                        {!! Form::model($company, ['id'=> 'form','url' =>'companies', 'method' => 'POST', 'files' => true]) !!}

                                        <div class="form-group">
                                            <label for="company_name"><b>Group Name</b></label>
                                            {!! Form::text('company_name', null, ['class' => 'form-control form-control-sm', 'id' => 'company_name', 'placeholder' => 'Write company\'s name here', 'required']) !!}

                                            @if($errors->has('company_name'))
                                                <span class="text-danger">{{ $errors->first('company_name') }}</span>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label for="company_logo"><b>Group Logo</b></label>
                                            {!! Form::file('company_logo') !!}
                                            @if($errors->has('company_logo'))
                                                <span class="text-danger">{{ $errors->first('company_logo') }}</span>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            {{--                                                <a class="btn btn-danger" href="{{ url('companies') }}"><i class="fa fa-remove"></i> Cancel</a>--}}

                                            <button type="submit" id="submit" class="btn btn-success btn-sm"><i
                                                    class="fa fa-save"></i> {{ $company ? 'Update' : 'Create' }}
                                            </button>
                                            <a class="btn btn-sm btn-warning" onclick="cancel()"><i
                                                    class="fa fa-remove"></i> Cancel</a>
                                        </div>
                                        {!! Form::close() !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="col-sm-12 col-md-8">
                    <div class="box-body table-responsive b-t">
                        <div class="flash-message">
                            @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                                @if(Session::has('alert-' . $msg))
                                    <div
                                        class="alert alert-{{ $msg }} text-center">{{ Session::get('alert-' . $msg) }}</div>
                                @endif
                            @endforeach
                        </div>
                        <table class="reportTable">
                            <thead>
                            <tr>
                                <th> SL</th>
                                <th> Group Name</th>
                                <th> Group Logo</th>
                                <th> Action</th>
                            </tr>
                            </thead>
                            <tbody class="company-list">
                            @if(!$companies->getCollection()->isEmpty())
                                @foreach($companies->getCollection() as $cmp)
                                    @php
                                        $imageHtml = '';
                                        if (Storage::disk('public')->exists('company/'.$cmp->company_logo)){
                                            $imageHtml = asset('storage/company/'.$cmp->company_logo);
                                        }
                                    @endphp
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $cmp->company_name }}</td>
                                        <td>
                                            <img src="{{ $imageHtml }}" alt="" style="height: 40px;">
                                        </td>
                                        <td>
                                            @if(Session::has('permission_of_company_edit') || Session::get('user_role') == 'super-admin')
                                                <a href="javascript:void(0)" data-id="{{ $cmp->id }}"
                                                   class="btn btn-sm white edit"><i
                                                        class="fa fa-edit"></i></a>
                                            @endif
                                            @if(Session::has('permission_of_company_delete') || Session::get('user_role') == 'super-admin')
                                                <button type="button" class="btn btn-sm white show-modal"
                                                        data-toggle="modal"
                                                        data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                        ui-target="#animate"
                                                        data-url="{{ url('companies/'.$cmp->id) }}">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4" align="center">No Data
                                    <td>
                                </tr>
                            @endif
                            </tbody>
                            <tfoot>
                            @if($companies->total() > 15)
                                <tr>
                                    <td colspan="4"
                                        align="center">{{ $companies->appends(request()->except('page'))->links() }}</td>
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
                url: '{{ url('companies') }}/' + id + '/edit',
                success: function (result) {
                    $('#form').attr('action', `companies/${result.id}`).append(`<input type="hidden" id="_method" name="_method" value="PUT"/>`);
                    $('#company_name').val(result.company_name);
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
