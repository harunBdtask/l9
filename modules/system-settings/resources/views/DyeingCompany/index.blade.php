@extends('skeleton::layout')
@section("title","Dyeing Company")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Dyeing Company</h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-3 col-sm-offset-9">
                        <form action="{{ url('dyeing-company-search') }}" method="GET">
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
             
                        <div class="col-sm-12 col-md-5">
                            <div class="box form-colors">
                                <div class="box-header">
                                    <form action="{{ url('dyeing-company') }}" method="post" id="form">
                                        @csrf

                                        <div class="form-group">
                                            <label for="name">Name</label>
                                            <input type="text" id="name" name="name"
                                                   class="form-control form-control-sm"
                                                   value="{{ old('name') }}"
                                                   placeholder="Name">
                                        @if($errors->has('name'))
                                                <span class="text-danger">{{ $errors->first('name') }}</span>
                                        @endif
                                         
                                        </div>
                                       
                                        <div class="form-group">
                                            <button type="submit" id="submit" class="btn btn-sm btn-success"><i
                                                    class="fa fa-save"></i> Save
                                            </button>
                                            <a href="" class="btn btn-sm btn-warning"><i
                                                    class="fa fa-refresh"></i> Refresh</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    <div class="col-sm-12 col-md-7">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>SL</th>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                                @forelse ($dyeingCompanys as $company)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$company->name}}</td>
                                    <td>
                                       
                                        <a href="javascript:void(0)" data-id="{{ $company->id }}"
                                            class="btn btn-xs btn-warning edit"><i class="fa fa-edit"></i></a>
                                    
                                        <button type="button" class="btn btn-xs danger show-modal"
                                                data-toggle="modal" data-target="#confirmationModal"
                                                ui-toggle-class="flip-x" ui-target="#animate"
                                                data-url="{{ url('dyeing-company/'.$company->id) }}">
                                            <i class="fa fa-trash"></i>
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
                            {{ $dyeingCompanys->appends(request()->except('page'))->links() }}
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
                url: '{{ url('dyeing-company') }}/' + id,
                success: function (result) {
                    $('#form').attr('action', `dyeing-company/${result.id}`).append(`<input type="hidden" name="_method" value="PUT"/>`);
                    $('#name').val(result.name);
                    $('#submit').html(`<i class="fa fa-save"></i> Update`);
                },
                error: function (xhr) {
                    console.log(xhr)
                }
            })
        })
    </script>
@endpush