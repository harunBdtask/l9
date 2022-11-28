@extends('skeleton::layout')
@section("title","PO File Issue Settings")
@section('content')
    <div class="padding">
        <div class="box" >
            <div class="box-header">
                <h2>PO File Issue Settings</h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-3 col-sm-offset-9">
                        <form action="{{ url('/po_file_issue_settings') }}" method="GET">
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm" name="search"
                                       value="{{ request()->query('search') ?? '' }}" placeholder="Search">
                                <span class="input-group-btn">
                                            <button class="btn btn-sm white m-b" type="submit">Search</button>
                                        </span>
                            </div>
                        </form>
                    </div>
                </div>
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
                <div class="row m-t">
                    <div class="col-sm-12 col-md-5">
                        <div class="box form-colors" >
                            <div class="box-header">
                                <form action="{{ url('/po_file_issue_settings') }}" method="post" id="form">
                                    @csrf
                                    <div class="form-group">
                                        <label for="buyer_id">Buyer</label>
                                        <select class="form-control form-control-sm select2-input" name="buyer_id" id="buyer_id">
                                            <option selected disabled>Select</option>
                                            @foreach($buyers as $buyer)
                                                <option
                                                    data-pdf_code="{{$buyer->pdf_conversion_key}}"
                                                    value="{{ $buyer->id }}">{{ $buyer->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('buyer_id')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="po_no">PDF Issue</label>
                                        <input type="text" name="issue" class="form-control form-control-sm"
                                               placeholder="Issue">
                                    </div>



                                    <div class="form-group">
                                        <button type="submit" id="submit" class="btn btn-sm btn-success"><i
                                                class="fa fa-save"></i> Save
                                        </button>
                                        <a href="{{ url('/po_file_issue_settings') }}" class="btn btn-sm btn-warning"><i
                                                class="fa fa-refresh"></i> Refresh</a>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-7">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Sl No</th>
                                <th>Buyer</th>
                                <th>Issue</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($po_file_issues as $po_file_issue)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{ $po_file_issue->buyer->name ?? '' }}</td>
                                    <td>{{ $po_file_issue->issue }}</td>
                                    <td style="display: inline-flex">

                                        <button type="button" class="btn btn-xs btn-danger show-modal"
                                                data-toggle="modal"
                                                data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                ui-target="#animate"
                                                data-url="{{ url('po_file_issue_settings/'.$po_file_issue->id) }}">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" align="center">No Data Found</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                        <div class="text-center">
                            {{ $po_file_issues->appends(request()->except('page'))->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-head')

@endpush
