@extends('skeleton::layout')
@section('title','Knit Card List')
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>
                    Knit Card List
                </h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-md-4">
                        @permission('permission_of_knit_card_add')
                        <a href="{{ url('/knitting/knit-card/create') }}" class="btn btn-sm btn-info m-b">
                            <i class="fa fa-plus"></i> New Knit Card
                        </a>
                        @endpermission
                    </div>
                    <div class="col-sm-8">
                        <form action="{{ url('/knitting/knit-card') }}" class="form-inline pull-right" method="GET">
                            <div class="form-group">
                                <div class="input-group">
                                    <select name="type" class="form-control form-control">
                                        <option value="">Select Booking Type</option>
                                        <option value="main" @if(request('type') == 'main') selected @endif>
                                            Main
                                        </option>
                                        <option value="short" @if(request('type') == 'short') selected @endif>
                                            Short
                                        </option>
                                        <option value="sample" @if(request('type') == 'sample') selected @endif>
                                            Sample
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="text"
                                        class="form-control form-control-sm"
                                        name="search"
                                        value="{{ request('search')?request('search'): '' }}" placeholder="Search">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <button class="btn btn-sm btn-info" type="submit">Search</button>
                                    <a href="{{ url('/knitting/knit-card') }}" class="btn btn-sm btn-danger">Clear</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                @include('partials.response-message')
                @include('skeleton::partials.dashboard', ['dashboardOverview'=>$dashboardOverview])
                @include('skeleton::partials.table-export')

                <div class="row m-t" style="padding-top:20px">
                    <div class="col-md-12 m-t-1 table-responsive">
                        <table class="reportTable-zero-padding">
                            <thead>
                            <tr style="background: #0ab4e6;">
                                <th nowrap style="width: 2%;">SL</th>
                                <th nowrap style="width: 8%;">Knit Card No</th>
                                <th nowrap style="width: 8%;">Program No</th>
                                <th nowrap style="width: 12%;">Company</th>
                                <th nowrap style="width: 10%;">Buyer</th>
                                <th nowrap style="width: 10%;">Style</th>
                                <th nowrap style="width: 5%;">Booking Type</th>
                                <th nowrap style="width: 10%;">Color</th>
                                <th nowrap style="width: 5%;">Qty</th>
                                <th nowrap style="width: 10%;">Created By</th>
                                <th nowrap style="width: 10%;">Created At</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($data as $key => $value)
                                <tr class="tooltip-data row-options-parent">
                                    <td>{{ str_pad($loop->iteration + $data->firstItem() - 1, 2, '0', STR_PAD_LEFT) }}</td>
                                    <td class="" nowrap>{{ $value->knit_card_no }}
                                        <br>
                                        <div class="row-options" style="display:none ">
                                            @permission('permission_of_knit_card_add_roll')
                                            <a href="{{ url("/knitting/knitting-production/add-roll/{$value->id}") }}"
                                               title="Create Roll Here"
                                               class="text-primary">
                                                <em class="fa fa-plus"></em>
                                            </a>
                                            @endpermission
                                            <span>|</span>
                                            @permission('permission_of_knit_card_view')
                                            <a class="text-info"
                                               href="{{ url("/knitting/knit-card/{$value->id}/view") }}"
                                               target="_blank"
                                               title="View Details">
                                                <em class="fa fa-eye"></em>
                                            </a>
                                            <a style="color: teal;"
                                               href="{{ url("/knitting/knit-card/{$value->id}/view-2") }}"
                                               target="_blank"
                                               title="View Details">
                                                <em class="fa fa-eye"></em>
                                            </a>
                                            @endpermission
                                            <span>|</span>
                                            @permission('permission_of_knit_card_edit')
                                            <a class="text-success"
                                               href="{{ url("/knitting/knit-card/{$value->id}/edit") }}"
                                               title="Edit">
                                                <em class="fa fa-edit"></em>
                                            </a>
                                            @endpermission
                                            <span>|</span>

                                            @permission('permission_of_knit_card_delete')
                                            <a href="{{ url("/knitting/knit-card/{$value->id}/delete") }}"
                                               data-toggle="modal"
                                               ui-target="#animate"
                                               ui-toggle-class="flip-x"
                                               title="Delete"
                                               data-target="#confirmationModal"
                                               class="text-danger show-modal"
                                               data-url="{{ url("/knitting/knit-card/{$value->id}/delete") }}">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                            @endpermission
                                        </div>
                                    </td>
                                    <td>{{ $value->program->program_no ?? '' }}</td>
                                    <td>{{ $value->factory->factory_name ?? '' }}</td>
                                    <td>{{ $value->buyer->name ?? '' }}</td>
                                    <td>{{ $value->planInfo->style_name ?? '' }}</td>
                                    <td style="text-transform: capitalize">{{ $value->planInfo->booking_type ?? '' }}</td>
                                    <td>{{ $value->color }}</td>
                                    <td>{{ $value->assign_qty }}</td>
                                    <td>{{ $value->user->full_name ?? '' }}</td>
                                    <td>{{ $value->created_at->format('d-m-Y H:i A') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="20" class="text-center">No data available</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-12">
                        {{ $data->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
