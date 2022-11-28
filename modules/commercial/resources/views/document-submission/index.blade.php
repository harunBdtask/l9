@extends('skeleton::layout')
@section('title','Document Submission List')
@section('content')
    <div class="padding">
        <div class="box" >
            <div class="box-header">
                <h2>
                    Document Submission List
                </h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-6">
                        <a href="{{ url('commercial/document-submission/create') }}" class="btn btn-sm btn-info m-b"><i
                                class="fa fa-plus"></i> New Document Submission</a>
                    </div>
                    <div class="col-sm-4 col-sm-offset-2">
                        <form action="{{ url('commercial/document-submission/') }}" method="GET">
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
                @include('partials.response-message')
                <div class="row m-t">
                    <div class="col-sm-12">
                        <table class="reportTable">
                            <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Factory</th>
                                <th>Buyer</th>
                                <th>Bank Ref</th>
                                <th>Submission Date</th>
                                <th>Submission Type</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($document_submissions as $document_submission)
                                <tr>
                                    <th>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</th>
                                    <td>{{ $document_submission->factory->factory_name }}</td>
                                    <td>{{ $document_submission->buyer->name }}</td>
                                    <td>{{ $document_submission->bank_ref_bill }}</td>
                                    <td>{{ $document_submission->submission_date ? date_format(date_create($document_submission->submission_date), 'd-M-Y') : 'N/A' }}</td>
                                    <td>{{ $document_submission->submission_type==1 ? 'Collection' : 'Negotiable'  }}</td>
                                    <td style="padding: 2px">
                                        <a href="{{ url('/commercial/document-submission/create?document_submission_id='.$document_submission->id) }}"
                                           class="btn btn-xs btn-warning">
                                            <i class="fa fa-edit"></i>
                                        </a>

                                        <a target="_blank" class="btn btn-xs btn-info"
                                           href="{{ url('/commercial/document-submission/'. $document_submission->id . '/view') }}">
                                            <i class="fa fa-eye"></i>
                                        </a>

                                        <button style="margin-left: 2px;" type="button"
                                                class="btn btn-xs btn-danger show-modal"
                                                title="Delete document submission"
                                                data-toggle="modal"
                                                data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                ui-target="#animate"
                                                data-url="{{ url('/commercial-api/v1/document-submissions/'.$document_submission->id) }}">
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
                        {{ $document_submissions->appends(request()->query())->links()  }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
