@extends('skeleton::layout')
@section("title","Items")
@section('content')
    <div class="padding">
        @if(getRole() == 'super-admin')
            <div class="box" >
                <div class="box-header">
                    <h2>Logs</h2>
                </div>
                <div class="box-body b-t">
                    <div class="row">
                        <div class="col-sm-3 col-sm-offset-9 text-right">
                            <button type="button" class="btn btn-xs btn-danger show-modal"
                                    data-toggle="modal" data-target="#confirmationModal"
                                    ui-toggle-class="flip-x" ui-target="#animate"
                                    data-url="{{ url('logs/all-delete') }}">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            @include('partials.response-message')
                        </div>
                    </div>
                    <div class="row m-t">
                        <div class="col-sm-12">
                            <table class="reportTable display compact cell-border" id="item_list_table">
                                <thead>
                                <tr>
                                    <th>Sl.</th>
                                    <th style="width: 50%;">Message</th>
                                    <th>Code</th>
                                    <th>Url</th>
                                    <th>Method</th>
                                    <th>Logged</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($logs as $log)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $log->message }}</td>
                                        <td>{{ $log->code}}</td>
                                        <td>{{ $log->url}}</td>
                                        <td>{{ $log->method}}</td>
                                        <td>{{ $log->created_at->format("Y-m-d") }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7">No Data Found</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                            <div class="text-center">
                                {{ $logs->appends(request()->except('page'))->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
