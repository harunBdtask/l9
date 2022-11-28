@extends('skeleton::layout')
@section('title','ASI Consumption')
@section('content')
    <div class="padding">
        <div class="box" style="min-height: 610px">
            <div class="box-header btn-info">
                <h2>
                    ASI Consumption List
                </h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-6">
                        <a href="{{ url('/asi-consumption/create') }}" class="btn btn-info"><i class="fa fa-plus"></i>
                            New ASI Consumption</a>
                    </div>
                    <div class="col-sm-4 col-sm-offset-2">
                        <form action="{{ url('/asi-consumption/search') }}" method="GET">
                            <div class="input-group">
                                <input type="text" class="form-control" name="search"
                                       value="{{  '' }}" placeholder="Search">
                                <span class="input-group-btn">
                                            <button class="btn btn-info" type="submit">Search</button>
                                        </span>
                            </div>
                        </form>
                    </div>
                </div>
                @include('partials.response-message')
                <div class="row m-t">
                    <div class="col-sm-12">
                        <table class="reportTable">
                            <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Company Name</th>
                                <th>Buyer Name</th>
                                <th>Season Name</th>
                                <th>Style Name</th>
                                <th>System Id</th>
                                <th>Created Dated</th>
                                <th>Update Date</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            @forelse ($consumptions as $key => $consumption)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $consumption->factory->factory_name }}</td>
                                    <td>{{ $consumption->buyer->name }}</td>
                                    <td>{{ $consumption->season->season_name }}</td>
                                    <td>{{ $consumption->style_name }}</td>
                                    <td>{{ $consumption->unique_id }}</td>
                                    <td>{{ $consumption->created_date }}</td>
                                    <td>{{ $consumption->updated_date }}</td>
                                    <td>
                                        <a href="{{ url('/asi-consumption/' . $consumption->id.'/edit') }}" class="btn btn-xs btn-warning">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <button style="margin-left: 2px;" type="button"
                                                class="btn btn-xs btn-danger show-modal"
                                                title="Delete Budget"
                                                data-toggle="modal"
                                                data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                ui-target="#animate"
                                                data-url="{{ url('asi-consumption/'.$consumption->id.'/delete') }}">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9"> No Data Found!</td>
                                </tr>
                            @endforelse

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row m-t">
                    <div class="col-sm-12">
                        {{ $consumptions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
