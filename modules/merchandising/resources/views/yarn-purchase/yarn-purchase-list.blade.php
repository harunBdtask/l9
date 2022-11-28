@extends('skeleton::layout')
@section('title','Yarn Purchase Requisition')
@section('content')
    <div class="padding">
        <div class="box" >
            <div class="box-header">
                <h2>
                    Yarn Purchase Requisition List
                </h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-6">
                        <a href="{{ url('/yarn-purchase/requisition/create') }}" class="btn btn-sm btn-info m-b"><i
                                class="fa fa-plus"></i>
                            New Yarn Purchase Requisition</a>
                    </div>
                    <div class="col-sm-4 col-sm-offset-2">
                        <form action="{{ url('/yarn-purchase/requisition/') }}" method="GET">
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm" name="search"
                                       value="{{ $search ?? '' }}" placeholder="Search">
                                <span class="input-group-btn">
                                            <button class="btn btn-sm btn-info m-b" type="submit">Search</button>
                                        </span>
                            </div>
                        </form>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="flash-message">
                            @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                                @if(Session::has('alert-' . $msg))
                                    <p class="alert alert-{{ $msg }} text-center">{{ Session::get('alert-' . $msg) }}</p>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                @include('skeleton::partials.dashboard', ['dashboardOverview'=>$dashboardOverview])

                @include('skeleton::partials.table-export')

                <div class="row m-t">
                    <div class="col-sm-12" style="overflow-x: scroll;">
                        <table class="reportTable">
                            <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Company Name</th>
                                <th>Requisition No</th>
                                <th>Required Date</th>
                                <th>Requisition Date</th>
                                <th>Pay Mode</th>
                                <th>Source</th>
                                <th>Currency</th>
                                <th>Dealing Merchant</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($yarnRequisitions as $requisition)
                                <tr  class="tooltip-data row-options-parent">
                                    <td>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</td>
                                    <td>{{ $requisition->factory->factory_name }}</td>
                                    <td>{{ $requisition->requisition_no }}
                                        <div class="row-options" style="display:none ">

                                            <a href="{{ url('/yarn-purchase/requisition/create?yarn_requisition_id=') . $requisition->id }}"
                                            class="text-warning">
                                                <i class="fa fa-edit" style="color:#f0ad4e"></i>
                                            </a>
                                            <span>|</span>
                                            <a href="{{ url('/yarn-purchase/requisition/' . $requisition->id.'/view') }}"
                                            class="text-info">
                                                <i class="fa fa-eye" style="color:#269abc"></i>
                                            </a>
                                            <span>|</span>
                                            <a href="{{ url('/yarn-purchase/requisition/'.$requisition->id) }}" style="margin-left: 2px;" type="button"
                                                    class="text-danger show-modal"
                                                    title="Delete Budget"
                                                    data-toggle="modal"
                                                    data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                    ui-target="#animate"
                                                    data-url="{{ url('/yarn-purchase/requisition/'.$requisition->id) }}">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                    </td>
                                    <td>{{ $requisition->required_date }}</td>
                                    <td>{{ $requisition->requisition_date }}</td>
                                    <td>{{ $requisition->pay_mode_value }}</td>
                                    <td>{{ $requisition->source_value }}</td>
                                    <td>{{ $requisition->currency }}</td>
                                    <td class="text-left">{{ $requisition->merchant->screen_name }}</td>
                                    
                                </tr>
                            @empty
                                <tr>
                                    <th colspan="10">No Data Found</th>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row m-t">
                    <div class="col-sm-12">
                        {{ $yarnRequisitions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
