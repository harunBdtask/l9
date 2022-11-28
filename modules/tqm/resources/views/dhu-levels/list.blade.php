@extends('tqm::layout')
@section('title', 'DHU Levels')
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>DHU levels List</h2>
            </div>
            <div class="box-body b-t">
                @if(Session::has('permission_of_defects_add') || getRole() == 'super-admin' || getRole() == 'admin')
                    <a class="btn btn-sm btn-info m-b" href="{{ url('tqm-dhu-levels/create') }}">
                        <i class="glyphicon glyphicon-plus"></i> New DHU Level
                    </a>
                @endif
                <div class="pull-right">
                    <form action="{{ url('/tqm-dhu-levels') }}" method="GET">
                        <div class="pull-left" style="margin-right: 10px;">
                            <input type="text" class="form-control form-control-sm" name="search"
                                   value="{{ request()->get('search') ?? '' }}">
                        </div>
                        <div class="pull-right">
                            <input type="submit" class="btn btn-sm btn-info" value="Search">
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
                <table class="reportTable" aria-describedby="DHU Levels List">
                    <thead>
                    <tr>
                        <th>SL</th>
                        <th>Section</th>
                        <th>Factory</th>
                        <th>Comparison Status</th>
                        <th>Level</th>
                        <th>Color</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(!$dhuLevels->getCollection()->isEmpty())
                        @foreach($dhuLevels->getCollection() as $dhuLevel)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $dhuLevel->section_name }}</td>
                                <td>{{ $dhuLevel->factory->factory_name ?? '' }}</td>
                                <td>{{ $dhuLevel->comparison_status_value }}</td>
                                <td>{{ $dhuLevel->level }}</td>
                                <td style="padding: 5px">
                                    <div style="background-color: {{ $dhuLevel->color }}">&nbsp;</div>
                                </td>
                                <td>
                                    <a class="btn btn-sm white"
                                       href="{{ url('tqm-dhu-levels/'.$dhuLevel->id.'/edit') }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm white show-modal" data-toggle="modal"
                                            data-target="#confirmationModal" ui-toggle-class="flip-x"
                                            ui-target="#animate" data-url="{{ url('tqm-dhu-levels/'.$dhuLevel->id) }}">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7">No Data Found</td>
                        </tr>
                    @endif
                    </tbody>
                    <tfoot>
                    @if($dhuLevels->total() > 15)
                        <tr>
                            <td colspan="5">{{ $dhuLevels->appends(request()->except('page'))->links() }}</td>
                        </tr>
                    @endif
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection
