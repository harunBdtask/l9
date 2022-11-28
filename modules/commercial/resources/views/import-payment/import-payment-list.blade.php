@extends('skeleton::layout')
@section('title','Import Payments')
@section('content')
    <div class="padding">
        <div class="box" >
            <div class="box-header">
                <h2>
                    Import Payment List
                </h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-6">
                        <a href="{{ url('/commercial/import-payment/create') }}" class="btn btn-sm btn-info m-b">
                            <i class="fa fa-plus"></i>New Import Payment
                        </a>
                    </div>
                    <div class="col-sm-4 col-sm-offset-2">
                        <form action="{{ url('/commercial/import-payment/') }}" method="GET">
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
                <div class="row m-t">
                    <div class="col-sm-12">
                        <table class="reportTable">
                            <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Import Doc. Acc.</th>
                                <th>Payment Date</th>
                                <th>Payment Head</th>
                                <th>Adj. Source Id</th>
                                <th>Conversion Rate</th>
                                <th>Accepted Amount</th>
                                <th>Currency</th>
                                <th>Factory</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($importPayments as $importPayment)
                                <tr>
                                    <th>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</th>
                                    <td>{{ $importPayment->import_document_acceptance_id }}</td>
                                    <td>{{ $importPayment->payment_date }}</td>
                                    <td>{{ $importPayment->payment_head }}</td>
                                    <td>{{ $importPayment->adj_source }}</td>
                                    <td>{{ $importPayment->conversion_rate }}</td>
                                    <td>{{ $importPayment->accepted_amount }}</td>
                                    <td>{{ $importPayment->currency->currency_name ?? '--' }}</td>
                                    <td>{{ $importPayment->factory->factory_name ?? '--' }}</td>
                                    <td style="padding: 2px">
                                        <a href="{{ url('/commercial/import-payment/create?id=' . $importPayment->id) }}"
                                           class="btn btn-xs btn-warning">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="{{ url('/commercial/import-payment/' . $importPayment->id.'/view') }}"
                                           class="btn btn-xs btn-info">
                                            <i class="fa fa-eye"></i>
                                        </a>

                                        <button style="margin-left: 2px;" type="button"
                                                class="btn btn-xs btn-danger show-modal"
                                                title="Delete Realization"
                                                data-toggle="modal"
                                                data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                ui-target="#animate"
                                                data-url="{{ url('/commercial/import-payment/'.$importPayment->id) }}">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty

                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row m-t">
                    <div class="col-sm-12">
                        {{ $importPayments->appends(request()->query())->links()  }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
