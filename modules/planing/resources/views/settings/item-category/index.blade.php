@extends('skeleton::layout')
@section('title', 'Item Category List')
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Item Category List</h2>
            </div>
            <div class="box-body b-t">
                <a class="btn btn-sm white m-b" href="{{ route('planning.settings.item-categories.create') }}">
                    <i class="glyphicon glyphicon-plus"></i> New Item Category
                </a>
                <div class="pull-right">
                    <form action="{{ url('/search-parts') }}" method="GET">
                        <div class="pull-left" style="margin-right: 10px;">
                            <input type="text" class="form-control form-control-sm" name="q" value="{{ $q ?? '' }}">
                        </div>
                        <div class="pull-right">
                            <input type="submit" class="btn btn-sm white" value="Search">
                        </div>
                    </form>
                </div>
                <hr>
                <div class="flash-message print-delete">
                    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                        @if(Session::has('alert-' . $msg))
                            <p class="text-center alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</p>
                        @endif
                    @endforeach
                </div>
                <table class="reportTable">
                    <thead>
                    <tr>
                        <th>SL</th>
                        <th>Name</th>
                        <th>SMV From - To</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($itemCategories as $itemCategory)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $itemCategory->name }}</td>
                            <td>{{ $itemCategory->smv_from }} - {{ $itemCategory->smv_to }}</td>
                            <td>
                                <a class="btn btn-sm white"
                                   href="{{ route('planning.settings.item-categories.edit',['id' => $itemCategory->id ]) }}"><i
                                        class="fa fa-edit"></i></a>

                                <button type="button" class="btn btn-sm white show-modal" data-toggle="modal"
                                        data-target="#confirmationModal" ui-toggle-class="flip-x"
                                        ui-target="#animate"
                                        data-url="{{ route('planning.settings.item-categories.delete',['id' => $itemCategory->id ]) }}">
                                    <i class="fa fa-times"></i>
                                </button>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">No Data Found</td>
                        </tr>
                    @endforelse
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="4"
                            align="center">{{ $itemCategories->appends(request()->except('page'))->links() }}</td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection
