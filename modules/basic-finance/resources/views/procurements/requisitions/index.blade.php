@extends('basic-finance::layout')
@section("title","Requisitions")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Procurement Requisitions</h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        @include('partials.response-message')
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 m-b">
                        <a href="{{ url('basic-finance/procurement/requisitions/create') }}"
                           class="btn btn-sm white m-b">
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
                                <th>Date</th>
                                <th>Factory</th>
                                <th>Project</th>
                                <th>Department</th>
                                <th>Unit</th>
                                <th>Created By</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($procurementRequisitions as $key => $requisition)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $requisition->date }}</td>
                                    <td>{{ $requisition->factory->factory_name }}</td>
                                    <td>{{ $requisition->project->project }}</td>
                                    <td>{{ $requisition->department->department }}</td>
                                    <td>{{ $requisition->unit->unit }}</td>
                                    <td>{{ $requisition->createdBy->screen_name }}</td>
                                    <td>
                                        <a class="btn btn-xs btn-info" type="button"
                                           href="{{ url('basic-finance/procurement/requisitions/create?id='. $requisition->id) }}">
                                            <em class="fa fa-pencil"></em>
                                        </a>
                                        <a class="btn btn-success btn-xs" type="button"
                                           href="{{ url('basic-finance/procurement/requisitions/view/'. $requisition->id) }}">
                                            <em class="fa fa-eye"></em>
                                        </a>
                                        <button style="margin-left: 2px;" type="button"
                                                class="btn btn-xs btn-danger show-modal"
                                                title="Delete Order"
                                                data-toggle="modal"
                                                data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                ui-target="#animate"
                                                data-url="{{ url('basic-finance/procurement/requisitions/'. $requisition->id) }}">
                                            <em class="fa fa-trash"></em>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr class="tr-height">
                                    <td colspan="8" class="text-center text-danger">No Account Found</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                        <div class="text-center">
                            {{ $procurementRequisitions->appends(request()->except('page'))->links() }}
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

@endsection
