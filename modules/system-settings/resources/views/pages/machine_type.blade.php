@extends('skeleton::layout')
@section('title', 'Machine Type')
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Machine Type</h2>
            </div>
            <div class="box-body b-t">
                <div class="col-md-6">
                    <a href="{{ url('knit-machine-types/create') }}" class="btn btn-sm white m-b btn-sm">
                        <i class="glyphicon glyphicon-plus"></i> New Machine Type
                    </a>
                </div>
                <div class="col-md-6 pull-right">
                    <div class="form-group">
                        <div class="row m-b">
                            <form action="{{ url('/machine-types') }}" method="GET">
                                <div class="col-sm-offset-5 col-sm-5">
                                    <input type="text" class="form-control form-control-sm" name="q"
                                           value="{{ request()->get('q') }}"
                                           placeholder="Search...">
                                </div>
                                <div class="col-sm-2">
                                    <input type="submit" class="btn btn-sm white" value="Search">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 flash-message p-t-1">
                    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                        @if(Session::has('alert-' . $msg))
                            <div class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</div>
                        @endif
                    @endforeach
                </div>
                <table class="reportTable">
                    <thead>
                    <tr>
                        <th> SL</th>
                        <th> Machine Type Name</th>
                        <th> Company</th>
                        <th> Action</th>
                    </tr>
                    </thead>
                    <tbody class="knittingFloor-list">

                    @forelse($machineType  as $type)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $type->name }}</td>
                            <td>{{ $type->sequence }}</td>
                            <td>
                                <a href="{{ url('knit-machine-types/'.$type->id.'/edit')}}"
                                   class="btn btn-sm white"><i
                                            class="fa fa-edit"></i></a>
                                <button type="button" class="btn btn-sm white show-modal" data-toggle="modal"
                                        data-target="#confirmationModal" ui-toggle-class="flip-x"
                                        ui-target="#animate" data-url="{{ url('knit-machine-types/'.$type->id) }}">
                                    <i class="fa fa-times"></i>
                                </button>
                            </td>
                    @empty
                        <tr>
                            <td colspan="6" align="center">No Data
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                    <tfoot>
                    @if($machineType ->total() > 15)
                        <tr>
                            <td colspan="6"
                                align="center">{{ $machineType ->appends(request()->except('page'))->links() }}</td>
                        </tr>
                    @endif
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection
