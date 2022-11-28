@extends(\SkylarkSoft\GoRMG\BasicFinance\PackageConst::VIEW_NAMESPACE."::layout")
@section('title', 'Budget List')
@section('styles')
    <style type="text/css">
        .addon-btn-primary {
            padding: 0;
            margin: 0px;
            background: #0275d8;
        }

        .addon-btn-primary:hover {
            background: #025aa5;
        }

        .reportTable th.text-left, .reportTable td.text-left {
            text-align: left;
            padding-left: 5px;
        }

        .reportTable th.text-right, .reportTable td.text-right {
            text-align: right;
            padding-right: 5px;
        }

        .reportTable th.text-center, .reportTable td.text-center {
            text-align: center;
        }
    </style>
@endsection

@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Budget</h2>
            </div>
            <div class="box-body b-t">
                <a class="btn btn-sm white m-b" href="{{ url('basic-finance/budgets/create') }}">
                    <i class="glyphicon glyphicon-plus"></i> New Budget
                </a>
                <div class="pull-right m-b-1">
                    <form action="{{ url('/budgets') }}" method="GET">
                        <div class="pull-left" style="margin-right: 10px;">
                            <input type="text" class="form-control form-control-sm" name="q"
                                   value="{{ request('q') ?? '' }}"
                                   placeholder="Enter search key">
                        </div>
                        <div class="pull-right">
                            <input type="submit" class="btn btn-sm white" value="Search">
                        </div>
                    </form>
                </div>

                @if(Session::has('success'))
                    <div class="col-md-6 col-md-offset-3 alert alert-success alert-dismissible text-center">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <small>{{ Session::get('success') }}</small>
                    </div>
                @elseif(Session::has('failure'))
                    <div class="col-md-6 col-md-offset-3 alert alert-danger alert-dismissible text-center">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <small>{{ Session::get('failure') }}</small>
                    </div>
                @endif

                <table class="reportTable">
                    <thead class="thead-light" style="background-color: azure;">
                    <tr>
                        <th class="text-center">Budget ID</th>
                        <th class="text-center">Budget Date</th>
                        <th class="text-center">Total Amount</th>
                        <th class="text-center">Created Date / By</th>
                        <th class="text-center">Updated Date / By</th>
                        <th class="text-center">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if (isset($budgets))
                        @foreach($budgets as $budget)
                            <tr>
                                <td>{{ $budget->code }}</td>
                                <td>{{ Carbon\Carbon::parse($budget->date)->toFormattedDateString() }}</td>
                                <td>{{ $budget->total_amount }}</td>
                                <td>{{ $budget->created_at->toFormattedDateString() . ' / ' . $budget->createdUser->screen_name }}</td>
                                <td>{{ $budget->updated_at->toFormattedDateString() . ' / ' . $budget->updatedUser->screen_name }}</td>
                                <td>
                                    @if (!count($budget->approvals))
                                        <a class="btn btn-xs btn-info"
                                           href="{{ url('/basic-finance/budgets/'.$budget->id.'/edit') }}">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                    @endif
                                    <a class="btn btn-xs btn-success"
                                       href="{{ url('/basic-finance/budgets/'.$budget->id.'/view') }}">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    @if (!count($budget->approvals))
                                        <button style="margin-left: 2px;" type="button"
                                                class="btn btn-xs btn-danger show-modal" title="Delete Budget"
                                                data-toggle="modal" data-target="#confirmationModal"
                                                ui-toggle-class="flip-x"
                                                ui-target="#animate"
                                                data-url="{{ url('/basic-finance/budgets/'.$budget->id.'/delete') }}">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="text-danger">No Data Found</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
