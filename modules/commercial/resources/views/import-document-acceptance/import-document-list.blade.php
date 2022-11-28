@extends('skeleton::layout')
@section('title','Import Document List')
@section('content')
    <div class="padding">
        <div class="box" >
            <div class="box-header">
                <h2>
                    Import Document Acceptance List
                </h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-6">
                        <a href="{{ url('commercial/import-document-acceptance/create') }}" class="btn btn-sm btn-info m-b"><i
                                class="fa fa-plus"></i> New Import Document Acceptance</a>
                    </div>
{{--                    <div class="col-sm-4 col-sm-offset-2">--}}
{{--                        <form action="{{ url('commercial/export-invoice/') }}" method="GET">--}}
{{--                            <div class="input-group">--}}
{{--                                <input type="text" class="form-control form-control-sm" name="search"--}}
{{--                                       value="{{ $search ?? '' }}" placeholder="Search">--}}
{{--                                <span class="input-group-btn">--}}
{{--                                            <button class="btn btn-sm white m-b" type="submit">Search</button>--}}
{{--                                        </span>--}}
{{--                            </div>--}}
{{--                        </form>--}}
{{--                    </div>--}}
                </div>
                @include('partials.response-message')
                <div class="row m-t">
                    <div class="col-sm-12">
                        <table class="reportTable">
                            <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Supplier</th>
                                <th>Invoice Number</th>
                                <th>Invoice Date</th>
                                <th>Currency</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($imporDocuments as $importdocument)
                                <tr>
                                    <th>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</th>
                                    <th>{{$importdocument->supplier->name}}</th>
                                    <th>{{$importdocument->invoice_number}}</th>
                                    <th>{{$importdocument->invoice_date}}</th>
                                    <th>{{$importdocument->currency->currency_name}}</th>

                                    <td style="padding: 2px">
                                        <a href="{{ url('/commercial/import-document-acceptance/'.$importdocument->id. '/edit') }}"
                                           class="btn btn-xs btn-warning">
                                            <i class="fa fa-edit"></i>
                                        </a>

                                        <a target="_blank" class="btn btn-xs btn-info"
                                           href="{{ url('/commercial/import-document-acceptance/'. $importdocument->id . '/view') }}">
                                            <i class="fa fa-eye"></i>
                                        </a>

                                        <button style="margin-left: 2px;" type="button"
                                                class="btn btn-xs btn-danger show-modal"
                                                title="Delete Budget"
                                                data-toggle="modal"
                                                data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                ui-target="#animate"
                                                data-url="{{ url('/commercial/import-document-acceptance/'.$importdocument->id) }}">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
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
                        {{ $imporDocuments->appends(request()->query())->links()  }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
