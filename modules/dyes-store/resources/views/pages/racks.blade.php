@extends('dyes-store::layout')
@section('title','Racks')
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>RACKS</h2>
            </div>
            <div class="box-body table-responsive b-t">
                <div class="row">
                    <div class="col-sm-9">
                        <a class="btn btn-sm btn-info" href="{{ url('/dyes-store/racks/create') }}">
                            <i class="glyphicon glyphicon-plus"></i> New Rack
                        </a>
                    </div>
                    <div class="col-sm-3">
                        <form action="{{ url('/dyes-store/racks') }}" method="GET">
                            <div class="input-group">
                                <input id="searchInput" type="text" class="form-control form-control-sm" name="search" value="{{ request()->query('search') ?? '' }}"
                                       placeholder="Search here">
                                <span class="input-group-btn">
                                            <button class="btn btn-sm btn-info" type="submit">Search</button>
                                 </span>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="flash-message">
                    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                        @if(Session::has('alert-' . $msg))
                            <div class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</div>
                        @endif
                    @endforeach
                </div>
                <br>
                <div class="row">
                    <div class="col-sm-12">
                        <table class="reportTable">
                            <thead>
                            <tr>
                                <th> SL</th>
                                <th> Rack Name</th>
                                <th> Description</th>
                                <th> Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($racks as $rack)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $rack->name }}</td>
                                    <td>{{ $rack->description }}</td>
                                    <td>
                                        <a href="{{url('/dyes-store/racks/'.$rack->id.'/edit')}}" class="btn btn-xs btn-success"
                                           data-toggle="tooltip" data-placement="top" title="Edit"><i
                                                class="fa fa-edit"></i></a>
                                        <button type="button" class="btn btn-xs btn-danger show-modal" data-toggle="modal"
                                                data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                ui-target="#animate"
                                                data-url="{{ url('/dyes-store/racks/'.$rack->id) }}">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" align="center">No Data</td>
                                </tr>
                            @endforelse
                            </tbody>
                            <tfoot>
                            @if($racks->total() > 15)
                                <tr>
                                    <td colspan="4"
                                        align="center">{{ $racks->appends(request()->except('page'))->links() }}</td>
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
