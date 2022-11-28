@extends('skeleton::layout')
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
                        @includeIf('partials.response-message')
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-5">
                        <a href="{{ url('procurement/requisitions/create') }}"
                           class="btn btn-sm white m-b">
                            <em class="fa fa-plus"></em>&nbsp;Create
                        </a>
                    </div>
                    <form action="{{ url('/procurement/requisitions') }}" method="GET">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="date" class="form-control form-control-sm" name="start_date" value="{{ request()->get('end_date') ?? '' }}"
                                        placeholder="Start Date">
                                    <span class="input-group-addon">To</span>
                                    <input type="date" class="form-control form-control-sm" name="end_date" value="{{ request()->get('start_date') ?? '' }}"
                                       placeholder="End Date">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm" name="search" value="{{ request()->get('search') ?? '' }}"
                                       placeholder="Search">
                                <span class="input-group-btn">
                                  <button class="btn btn-sm white" type="submit"> Search</button>
                                </span>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <table class="reportTable">
                            <thead>
                            <tr>
                                <th>SL</th>
                                <th>Req. Date</th>
                                <th>Req. ID</th>
                                <th>Req. Raised By</th>
                                <th>Department</th>
                                <th>Department Head</th>
                                <th>Required Date</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($procurementRequisitions as $key => $requisition)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $requisition->date ? date('d M, Y', strtotime($requisition->date)):'' }}</td>
                                    <td>{{ $requisition->requisition_uid??null }}</td>
                                    <td>{{ $requisition->createdBy->screen_name??null }}</td>
                                    <td>{{ $requisition->department->department_name??null }}</td>
                                    <td>{{ $requisition->approvalBy->screen_name??null }}</td>
                                    <td>{{ $requisition->required_date ? date('d M, Y', strtotime($requisition->required_date)):'' }}</td>
                                    <td>{{ $requisition->priority_value??null }}</td>
                                    <td>{{ $requisition->status_value }}</td>
                                    <td>
                                        <a class="btn btn-xs btn-info" type="button"
                                           href="{{ url('/procurement/requisitions/create?id='. $requisition->id) }}">
                                            <em class="fa fa-pencil"></em>
                                        </a>
                                        <a class="btn btn-success btn-xs" type="button"
                                           href="{{ url('/procurement/requisitions/view/'. $requisition->id) }}">
                                            <em class="fa fa-eye"></em>
                                        </a>
                                        <button style="margin-left: 2px;" type="button"
                                                class="btn btn-xs btn-danger show-modal"
                                                title="Delete Order"
                                                data-toggle="modal"
                                                data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                ui-target="#animate"
                                                data-url="{{ url('/procurement/requisitions/'. $requisition->id) }}">
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

@endsection
@section('scripts')

@endsection
