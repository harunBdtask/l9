@extends('skeleton::layout')
@section("title", "Garments Sample")
@section('content')
    <div class="padding">
        <div class="box" >
            <div class="box-header">
                <h2>Garments Sample</h2>
            </div>

            <div class="box-body">

                <a class="btn btn-sm white m-b b-t m-b-1" href="{{ url('garments-sample/create') }}">
                    <i class="glyphicon glyphicon-plus"></i> New Garments Sample
                </a>
{{--                <div class="row">--}}
{{--                    <div class="col-sm-3 col-sm-offset-9">--}}
{{--                        <form action="{{ url('incoterms-search') }}" method="GET">--}}
{{--                            <div class="input-group">--}}
{{--                                <input type="text" class="form-control form-control-sm" name="search"--}}
{{--                                       value="{{ $search ?? '' }}" placeholder="Search">--}}
{{--                                <span class="input-group-btn">--}}
{{--                                            <button class="btn btn-sm white m-b" type="submit">Search</button>--}}
{{--                                        </span>--}}
{{--                            </div>--}}
{{--                        </form>--}}
{{--                    </div>--}}
{{--                </div>--}}
                <div class="row">
                    <div class="col-sm-12">
                        @include('partials.response-message')
                    </div>
                </div>
                <div class="row m-t">

                    <div class="col-sm-12 col-md-12">
                        <table class="reportTable table-bordered">
                            <thead>
                            <tr>
                                <th>SL</th>
                                <th>Company</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($samples as $sample)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $sample->factory->factory_name }}</td>
                                    <td>{{ $sample->name }}</td>
                                    <td>{{ \SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsSample::TYPES[$sample->type] }}</td>
                                    <td>{{ \SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsSample::STATUSES[$sample->status] }}</td>

                                    <td>
                                        <a class="btn btn-xs btn-success"
                                           href="{{ url('garments-sample/' . $sample->id . '/edit') }}">
                                            <i class="fa fa-fw fa-edit"></i>
                                        </a>
{{--                                        <a class="btn btn-xs btn-danger" href="http://gears.test/factories/delete/3"><i class="fa fa-fw fa-trash-o del-confirm"></i></a>--}}
                                    </td>

{{--                                    <td>--}}
{{--                                        --}}
{{--                                        @if(Session::has('permission_of_incoterms_edit') || getRole() == 'super-admin' || getRole() == 'admin')--}}
{{--                                            <a href="javascript:void(0)" data-id="{{ $incoterm->id }}" class="btn btn-xs btn-warning edit"><i class="fa fa-edit"></i></a>--}}
{{--                                        @endif--}}
{{--                                        @if(Session::has('permission_of_incoterms_delete') || getRole() == 'super-admin' || getRole() == 'admin')--}}
{{--                                            <button type="button" class="btn btn-xs danger show-modal"--}}
{{--                                                    data-toggle="modal" data-target="#confirmationModal"--}}
{{--                                                    ui-toggle-class="flip-x" ui-target="#animate"--}}
{{--                                                    data-url="{{ url('incoterms/'.$incoterm->id) }}">--}}
{{--                                                <i class="fa fa-trash"></i>--}}
{{--                                            </button>--}}
{{--                                        @endif--}}
{{--                                    </td>--}}
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" align="center">No Data Found</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                        <div class="text-center">
                            {{ $samples->render() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-head')
{{--    <script>--}}
{{--        $(document).on('click', '.edit', function () {--}}
{{--            let id = $(this).data('id');--}}
{{--            $.ajax({--}}
{{--                method: 'get',--}}
{{--                url: '{{ url('incoterms') }}/' + id,--}}
{{--                success: function (result) {--}}
{{--                    $('#form').attr('action', `incoterms/${result.id}`).append(`<input type="hidden" name="_method" value="PUT"/>`);--}}
{{--                    $('#incoterm').val(result.incoterm);--}}
{{--                    $('#submit').html(`<i class="fa fa-save"></i> Update`);--}}
{{--                },--}}
{{--                error: function (xhr) {--}}
{{--                    console.log(xhr)--}}
{{--                }--}}
{{--            })--}}
{{--        })--}}
{{--    </script>--}}
@endpush
