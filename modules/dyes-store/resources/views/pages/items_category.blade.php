@extends('dyes-store::layout')
@section('title', 'Item Category')
@section('content')

    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>ITEM CATEGORY </h2>
            </div>
            <div class="box-body table-responsive b-t">
                <div class="row">
                    <div class="col-sm-9">
                        <a href="{{ url('/dyes-store/items-category/create') }}" class="btn btn-primary btn-sm">
                            <i class="fa fa-plus"></i> Create Items Category
                        </a>
                    </div>
                    <div class="col-sm-3">
                        <form action="{{ url('/dyes-store/items-category/') }}" method="GET">
                            <div class="input-group">
                                <input id="searchInput" type="text" class="form-control form-control-sm" name="search" value="{{ request()->query("search") ?? '' }}"
                                       placeholder="Search here">
                                <span class="input-group-btn">
                                            <button class="btn btn-sm btn-primary" type="submit">Search</button>
                                 </span>
                            </div>
                        </form>
                    </div>
                </div>
                <br>
                <div class="flash-message">
                    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                        @if(Session::has('alert-' . $msg))
                            <div class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</div>
                        @endif
                    @endforeach
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <table class="reportTable">
                            <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Name</th>
                                <th>Code</th>
                                <th>Parent</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            @forelse($items_category as $key=>$category)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$category->name}}</td>
                                    <td>{{$category->code}}</td>
                                    <td>{{$category->parent->name ?? ''}}</td>
                                    <td>
                                        <a href="{{url('/dyes-store/items-category/'.$category->id.'/edit')}}" class="btn btn-xs btn-success"
                                           data-toggle="tooltip" data-placement="top" title="Edit"><i
                                                class="fa fa-edit"></i></a>
                                        <button type="button" class="btn btn-xs btn-danger show-modal" data-toggle="modal"
                                                data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                ui-target="#animate"
                                                data-url="{{ url('/dyes-store/items-category/'.$category->id) }}">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">No Data Found</td>
                                </tr>
                            @endforelse
                            </tbody>
                            <tfoot>
                            @if($items_category->total() > 15)
                                <tr>
                                    <td colspan="5"
                                        align="center">{{ $items_category->appends(request()->except('page'))->links() }}</td>
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
