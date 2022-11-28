@extends(\SkylarkSoft\GoRMG\BasicFinance\PackageConst::VIEW_NAMESPACE."::layout")
@section('title', 'Approval List')
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
                <h2>Budget Approvals</h2>
            </div>
            <div class="box-body b-t">
                <a class="btn btn-sm white m-b" href="{{ url('basic-finance/budget-approvals/create') }}">
                    <i class="glyphicon glyphicon-plus"></i> New Budget Approval
                </a>
                <div class="pull-right m-b-1">
                    <form action="{{ url('basic-finance/budget-approvals') }}" method="GET">
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
                        <th class="text-center">Budget Code</th>
                        <th class="text-center">Budget Date</th>
                        <th class="text-center">Approval Date</th>
                        <th class="text-center">Approved Amount</th>
                        <th class="text-center">Created Date / By</th>
                        <th class="text-center">Updated Date / By</th>
                        <th class="text-center">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if (isset($budgetApprovals))
                        @foreach($budgetApprovals->groupBy('code') as $approval)
                            <tr>
                                <td>{{ $approval->first()->code ?? null }}</td>
                                <td>{{ Carbon\Carbon::parse($approval->first()->acBudget->date)->toFormattedDateString() }}</td>
                                <td>{{ Carbon\Carbon::parse($approval->first()->date)->toFormattedDateString() }}</td>
                                <td>{{ $approval->sum('apprv_amount') }}</td>
                                <td>{{ $approval->first()->created_at->toFormattedDateString() . ' / ' . $approval->first()->createdUser->screen_name ?? null }}</td>
                                <td>{{ $approval->first()->updated_at->toFormattedDateString() . ' / ' . $approval->first()->updatedUser->screen_name ?? null }}</td>
                                <td>
                                    <a class="btn btn-xs btn-success"
                                       href="{{ url('/basic-finance/budget-approvals/'.$approval->first()->bf_ac_budget_id.'/view?date='.$approval->first()->date) }}">
                                        <i class="fa fa-eye"></i>
                                    </a>
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
