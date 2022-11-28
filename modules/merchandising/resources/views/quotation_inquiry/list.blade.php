@extends('skeleton::layout')
@section("title","Quotation inquiries")

@section('styles')
<style>
    /*.table-header {*/
    /*    background: #93dcf9;*/
    /*}*/
</style>
@endsection


@section('content')
<div class="padding">
    <div class="box">
        <div class="box-header">
            <div class="row">
                <div class="col-md-6">
                    <h2>Quotation Inquiry List</h2>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="box-body b-t ">
            <div class="col-md-6">
                @permission('permission_of_quotation_inquiries_add')
                <a href="{{url('quotation-inquiries/create')}}" class="btn btn-sm btn-info m-b add-new-btn btn-sm">
                    <i class="glyphicon glyphicon-plus"></i> New Entry
                </a>
                @endpermission
            </div>
            <div class="col-md-6 pull-right" style="padding-right: 0px!important;">
                {!! Form::open(['url' => 'quotation-inquiries', 'method' => 'GET']) !!}
                <div class="form-group">
                    <div class="row m-b">
                        <div class="col-sm-4">
                            {!! Form::select('search_column', $search_columns ?? [], request()->get('search_column') ?? null, ['class' => 'select2-input form-control form-control-sm', 'placeholder' => 'Search Column']) !!}
                        </div>
                        <div class="col-sm-5">
                            {!! Form::text('q', request()->get('q') ?? null, ['class' => 'form-control form-control-sm']) !!}
                        </div>
                        <div class="col-sm-3">
                            <button type="submit" class="btn btn-sm btn-info m-b button-class" style="border-radius: 0px">
                                Search
                            </button>
                        </div>
                    </div>
                </div>

                {!! Form::close() !!}
            </div>
            <br class="print-delete">
            <br class="print-delete">
            <!-- <div class="flash-message">
                @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                @if(Session::has('alert-' . $msg))
                <p class="alert alert-{{ $msg }} text-center">{{ Session::get('alert-' . $msg) }}</p>
                @endif
                @endforeach
            </div> -->

            <div class="table-responsive" style="min-height: 300px" id="tableOrder">
                <table class="reportTable reportTableCustom">
                    <thead>
                        <tr class="table-header">
                            <th>
                                @php
                                $sort = request('sort') == 'asc' ? 'desc' : 'asc';
                                $search_column = request('search_column') ?? null;
                                $q = request('q') ?? null;
                                $extended = isset($search_column) ? '&search_column='.$search_column . '&q='. $q : null;
                                $url = 'quotation-inquiries?sort=' . $sort . isset($extended) ? $extended : null;
                                @endphp
                                <a class="btn btn-sm btn-light" href="{{  url('quotation-inquiries?sort=' . $sort . $extended)}}">
                                    <i class="fa {{ $sort == 'asc' ? 'fa-angle-down' : 'fa-angle-up' }}">SL</i>
                                </a>
                            </th>
                            <th>Factory</th>
                            <th>Buyer</th>
                            <th>Inquiry Id</th>
                            <th>Style</th>
                            <th>Season</th>
                            <th>Inquiry Date</th>
                            <th>Year</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="company-list">
                        @if(!$quotation_inquiries->getCollection()->isEmpty())
                        @foreach($quotation_inquiries->getCollection() as $quotation_inquiry)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $quotation_inquiry->factory->factory_short_name ?? ''}}</td>
                            <td>{{ $quotation_inquiry->buyer->name }}</td>
                            <td>{{ $quotation_inquiry->quotation_id }}</td>
                            <td>{{ $quotation_inquiry->style_name }}</td>
                            <td>{{ $quotation_inquiry->season->season_name }}</td>
                            <td>{{ $quotation_inquiry->inquiry_date }}</td>
                            <td>{{ date('Y', strtotime($quotation_inquiry->created_at)) }}</td>
                            <td>
                                @buyerPermission($quotation_inquiry->buyer->id,'permission_of_quotation_inquiries_edit')
                                <a href="{{url('quotation-inquiries/'.$quotation_inquiry->id.'/edit')}}" class="btn btn-xs btn-success"><i class="fa fa-edit"></i></a>
                                @endbuyerPermission
                                @buyerPermission($quotation_inquiry->buyer->id,'permission_of_quotation_inquiries_delete')
                                <button type="button" class="btn btn-xs btn-danger show-modal" data-toggle="modal" data-target="#confirmationModal" ui-toggle-class="flip-x" ui-target="#animate" data-url="{{ url('quotation-inquiries/'.$quotation_inquiry->id) }}">
                                    <i class="fa fa-times"></i>
                                </button>
                                @endbuyerPermission
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="9" align="center">No Data</td>
                        </tr>
                        @endif
                    </tbody>
                    <tfoot>
                        @if($quotation_inquiries->total() > 15)
                        <tr>
                            <td colspan="9" align="center">{{ $quotation_inquiries->appends(request()->except('page'))->links() }}</td>
                        </tr>
                        @endif
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection