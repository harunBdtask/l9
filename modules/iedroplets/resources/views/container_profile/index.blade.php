@extends('iedroplets::layout')
@section("title","Container Profiles")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Container Profiles</h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        @include('partials.response-message')
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 m-b">
                        <a href="{{ url('container-profiles/create') }}"
                           class="btn btn-sm btn-info m-b">
                            <em class="fa fa-plus"></em>&nbsp;Create
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="reportTable">
                            <thead>
                            <tr>
                                <th>SL</th>
                                <th>Factory</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($containerProfiles as $containerProfile)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $containerProfile->factory->factory_name }}</td>
                                    <td>{{ \Carbon\Carbon::make($containerProfile->start_date)->toFormattedDateString() }}</td>
                                    <td>{{ \Carbon\Carbon::make($containerProfile->end_date)->toFormattedDateString() }}</td>
                                    <td>
                                        <a class="btn btn-xs btn-info" type="button"
                                           href="{{ url('container-profiles/create?id='. $containerProfile->id) }}">
                                            <em class="fa fa-pencil"></em>
                                        </a>
{{--                                        <a class="btn btn-success btn-xs" type="button"--}}
{{--                                           href="{{ url('container-profiles/view/'. $containerProfile->id) }}">--}}
{{--                                            <em class="fa fa-eye"></em>--}}
{{--                                        </a>--}}
                                        <button style="margin-left: 2px;" type="button"
                                                class="btn btn-xs btn-danger show-modal"
                                                title="Delete Order"
                                                data-toggle="modal"
                                                data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                ui-target="#animate"
                                                data-url="{{ url('container-profiles/'. $containerProfile->id) }}">
                                            <em class="fa fa-trash"></em>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" align="center">No Data</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                        <div class="text-center">
                            {{ $containerProfiles->appends(request()->except('page'))->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /*.custom-field {*/
        /*    */
        /*    */
        /*}*/
    </style>
@endsection
@section('scripts')
    <script></script>
@endsection
