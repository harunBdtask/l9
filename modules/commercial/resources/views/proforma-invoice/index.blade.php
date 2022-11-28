@extends('skeleton::layout')
@section('title','Proforma Invoice')
@section('styles')
    {{-- <style>
        .table-header {
            background: #93dcf9;
        }
    </style> --}}
@endsection

@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>
                   Proforma Invoice
                </h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-6">
                        @permission('permission_of_pro_forma_invoice_add')
                        <a href="{{ url('/commercial/proforma-invoice/create') }}" class="btn btn-sm btn-info m-b"><i
                                class="fa fa-plus"></i> New
                            Proforma Invoice </a>
                        @endpermission
                    </div>
                    <div class="col-sm-4 col-sm-offset-2">
                        <form action="{{ url('/commercial/pi-list/search') }}" method="GET">
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm" name="search"
                                       value="{{ request('search') ?? '' }}" placeholder="Search">
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
                            <tr class="table-header">
                                <th>Sl</th>
                                <th style="width: 200px">Importer</th>
                                <th>Supplier</th>
                                <th>Item Category</th>
                                <th>PI NO</th>
                                <th>PI Receive Date</th>
                                <th>PI Value</th>
                                <th>PI Date</th>
                                <th>L/C Date</th>
                                <th>H.S Code</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($invoices as $key => $invoice)
                                @php 
                                    $key = $key+1+($invoices->currentPage()-1)*$invoices->perPage() 
                                @endphp
                                <tr>
                                    <td>{{ $key }}</td>
                                    <td>{{ $invoice->importer->factory_name ?? '' }}</td>
                                    <td>{{ $invoice->supplier->name ?? '' }}</td>
                                    <td>{{ $invoice->item->item_name ?? '' }}</td>
                                    <td>{{ $invoice->pi_no ?? ''}}</td>
                                    <td>{{ $invoice->pi_receive_date ?? ''}}</td>
                                    <td>{{ !empty($invoice->details->total)?sprintf("%01.2f", $invoice->details->total):0 }}</td>
                                    <td>{{ $invoice->pi_created_date ?? '' }}</td>
                                    <td>{{ $invoice->lc_receive_date ?? '' }}</td>
                                    <td>{{ $invoice->hs_code ?? '' }}</td>
                                    <td>{{ !empty($invoice->b_to_b_margin_lc_id)?'Close':'Open' }}</td>

                                    <td style="padding: 2px">
                                    @permission('permission_of_pro_forma_invoice_edit')
                                    @if(empty($invoice->b_to_b_margin_lc_id))
                                        <a href="{{ url('/commercial/proforma-invoice/'. $invoice->id.'/edit') }}"
                                           class="btn btn-xs btn-warning">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                    @endif
                                    @endpermission

                                    @permission('permission_of_pro_forma_invoice_view')
                                    <a href="{{ url('/commercial/proforma-invoice/'. $invoice->id.'/view') }}"
                                        target="_blank"
                                        class="btn btn-xs btn-success">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    @endpermission
                                    @permission('permission_of_pro_forma_invoice_view')
                                    @if(!empty($invoice->file_path))

                                    <a href="{{ url('/commercial/proforma-invoice/'. $invoice->id.'/file_view')  }}"
                                        target="_blank"
                                        class="btn btn-xs btn-success">
                                        <i class="fa fa-file"></i>
                                    </a>
                                    @endif
                                    @endpermission

                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <th colspan="11">No Data Found</th>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row m-t">
                    <div class="col-sm-12">
                        {{ $invoices->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
