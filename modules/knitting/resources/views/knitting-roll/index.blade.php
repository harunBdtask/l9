@extends('skeleton::layout')
@section('title','Roll List')
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>
                    Roll List
                </h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-md-4"></div>
                    <div class="col-sm-8">
                        <form action="{{ url('/knitting/knitting-roll') }}" class="form-inline pull-right" method="GET">
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
                                    <a href="{{ url('/knitting/knitting-roll') }}" class="btn btn-sm btn-danger">Clear</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                @include('partials.response-message')
                @include('skeleton::partials.dashboard',['dashboardOverview'=> $dashboardOverview ?? ''])

                @include('skeleton::partials.table-export')

                <div class="row m-t">
                    <div class="col-md-12 table-responsive" style="padding-top:20px">
                        <table class="reportTable-zero-padding">
                            <thead>
                            <tr style="background: #0ab4e6;">
                                <th nowrap class="mx-2">SL</th>
                                <th nowrap class="mx-2">Roll No</th>
                                <th nowrap class="mx-2">Knit card</th>
                                <th nowrap class="mx-2">Booking Type</th>
                                <th nowrap class="mx-2">Buyer</th>
                                <th nowrap class="mx-2">Color</th>
                                <th nowrap class="mx-2">Shift</th>
                                <th nowrap class="mx-2">Operator</th>
                                <th nowrap class="mx-2">Roll weight</th>
                                <th nowrap class="mx-2">Date and Time</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($data as $key => $value)
                                <tr class="tooltip-data row-options-parent">
                                    <td>{{ str_pad($loop->iteration + $data->firstItem() - 1, 2, '0', STR_PAD_LEFT) }}</td>
                                    <td>{{ $value['barcode_no'] }}
                                        <br>
                                        <div class="row-options" style="display:none ">

                                        @permission('permission_of_roll_list_edit')
                                            <a class="text-primary btn-sm"
                                                target="_blank"
                                                href="{{ url('knitting/knitting-production/add-roll/'.$value->knit_card_id) }}"
                                                title="Edit">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            @endpermission
                                            <span>|</span>
                                            @permission('permission_of_roll_list_view')
                                            <a class=" white "
                                            target="_blank"
                                            title="View Details"
                                            href="{{ url("knitting/knitting-roll/$value->id/view") }}">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            @endpermission
                                    </td>
                                    <td>{{ $value->knitCard->knit_card_no ?? '' }}</td>
                                    <td style="text-transform: capitalize">{{ $value->planningInfo->booking_type ?? '' }}</td>
                                    <td>{{ $value->knitCard->buyer->name ?? '' }}</td>
                                    <td>{{ $value->knitCard->color ?? '' }}</td>
                                    <td>{{ $value->shift->shift_name ?? '' }}</td>
                                    <td>{{ $value->operator->operator_name ?? '' }}</td>
                                    <td>{{ $value['roll_weight'] }}</td>
                                    <td>{{ date('d-m-Y h:s a', strtotime($value['production_datetime'])) }}</td>

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
                        {{ $data->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
