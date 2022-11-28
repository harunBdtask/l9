@extends('skeleton::layout')
@section('title','Export Invoice List')
@section('content')
    <div class="padding">
        <div class="box" >
            <div class="box-header">
                <h2>
                    Export Invoice List
                </h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-6">
                        <a href="{{ url('commercial/export-invoice/create') }}" class="btn btn-sm btn-info m-b"><i
                                class="fa fa-plus"></i> New Export Invoice</a>
                    </div>
                    <div class="col-sm-4 col-sm-offset-2">
                        <form action="{{ url('commercial/export-invoice/') }}" method="GET">
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
                                <th>Unique ID</th>
                                <th>Lc / Sc No</th>
                                <th>Invoice No</th>
                                <th>Invoice Date</th>
                                <th>Buyer</th>
                                <th>Beneficiary</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($invoices as $invoice)
                                <tr>
                                    <th>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</th>
                                    <td>{{ $invoice->uniq_id ?? '--' }}</td>
                                    <td>{{ $invoice->lc_sc_no ?? '--' }}</td>
                                    <td>{{ $invoice->invoice_no ?? '--' }}</td>
                                    <td>{{ $invoice->invoice_date ? date_format(date_create($invoice->invoice_date), 'd-M-Y') : 'N/A' }}</td>
                                    <td>{{ $invoice->buyer->name ?? '--' }}</td>
                                    <td>{{ $invoice->beneficiary->factory_name ?? '--' }}</td>
                                    <td style="padding: 2px">
                                        <a href="{{ url('/commercial/export-invoice/create?export_invoice_id=') . $invoice->id }}"
                                           class="btn btn-xs btn-warning">
                                            <i class="fa fa-edit"></i>
                                        </a>

                                        <a target="_blank" class="btn btn-xs btn-info"
                                           href="{{ url('/commercial/export-invoice/'. $invoice->id . '/view') }}">
                                            <i class="fa fa-eye"></i>
                                        </a>

                                        <button style="margin-left: 2px;" type="button"
                                                class="btn btn-xs btn-danger show-modal"
                                                title="Delete Budget"
                                                data-toggle="modal"
                                                data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                ui-target="#animate"
                                                data-url="{{ url('/commercial/export-invoice/'.$invoice->id) }}">
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
                        {{ $invoices->appends(request()->query())->links()  }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
