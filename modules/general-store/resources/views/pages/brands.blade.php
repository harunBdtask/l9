@extends('general-store::layout')
@section('title', 'Brand')
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>BRAND</h2>
            </div>
            <div class="box-body table-responsive b-t">
                <div class="row">
                    <div class="col-sm-9">
                        <a class="btn btn-sm btn-info" href="{{ url('/general-store/brands/create') }}">
                            <i class="glyphicon glyphicon-plus"></i> New Brand
                        </a>
                    </div>
                    <div class="col-sm-3">
                        <form action="{{ url('/general-store/brands') }}" method="GET">
                            <div class="input-group">
                                <input id="searchInput" type="text" class="form-control" name="search" value="{{ request()->query('search') ?? '' }}"
                                       placeholder="Search here">
                                <span class="input-group-btn">
                                            <button class="btn btn-info" type="submit">Search</button>
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
                                <th> Brand Name</th>
                                <th> Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($brands as $brand)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $brand->name }}</td>
                                    <td>
                                        <a href="{{url('/general-store/brands/'.$brand->id.'/edit')}}" class="btn btn-xs btn-success"
                                           data-toggle="tooltip" data-placement="top" title="Edit"><i
                                                class="fa fa-edit"></i></a>
                                        <button type="button" class="btn btn-xs btn-danger show-modal" data-toggle="modal"
                                                data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                ui-target="#animate"
                                                data-url="{{ url('/general-store/brands/'.$brand->id) }}">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" align="center">No Data</td>
                                </tr>
                            @endforelse
                            </tbody>
                            <tfoot>
                            @if($brands->total() > 15)
                                <tr>
                                    <td colspan="3"
                                        align="center">{{ $suppliers->appends(request()->except('page'))->links() }}</td>
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
