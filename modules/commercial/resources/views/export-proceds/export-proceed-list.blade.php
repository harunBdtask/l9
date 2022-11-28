@extends('skeleton::layout')
@section('title','Exprot Proceed Realization')
@section('content')
    <div class="padding">
        <div class="box" >
            <div class="box-header">
                <h2>
                    Export Proceed List
                </h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-6">
                        <a href="{{ url('/commercial/export-proceed-realizations/create') }}" class="btn btn-sm white m-b"><i
                                class="fa fa-plus"></i>
                            Export Proceed</a>
                    </div>
<!--                    <div class="col-sm-4 col-sm-offset-2">
                        <form action="{{ url('/yarn-purchase/requisition/') }}" method="GET">
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm" name="search"
                                       value="{{ $search ?? '' }}" placeholder="Search">
                                <span class="input-group-btn">
                                            <button class="btn btn-sm white m-b" type="submit">Search</button>
                                        </span>
                            </div>
                        </form>
                    </div>-->
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
                                <th>Beneficiary</th>
                                <th>Buyer</th>
                                <th>Bill Invoice Amount</th>
                                <th>Lc Ac No</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($datas as $d)
                                <tr>
                                    <th>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</th>
                                    <td>{{ $d->beneficiary->factory_name }}</td>
                                    <td>{{ $d->buyer->name }}</td>
                                    <td>{{ $d->bill_invoice_amount }}</td>
                                    <td>{{ $d->lc_sc_no }}</td>

                                    <td style="padding: 2px">
                                        <a href="{{ url('/commercial/export-proceed-realizations/' . $d->id.'/edit') }}"
                                           class="btn btn-xs btn-warning">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="{{ url('/commercial/export-proceed-realizations/' . $d->id.'/view') }}"
                                           class="btn btn-xs btn-info">
                                            <i class="fa fa-eye"></i>
                                        </a>

                                        <button style="margin-left: 2px;" type="button"
                                                class="btn btn-xs btn-danger show-modal"
                                                title="Delete Realization"
                                                data-toggle="modal"
                                                data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                ui-target="#animate"
                                                data-url="{{ url('/commercial-api/v1/export-proceed-realizations/'.$d->id) }}">
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
                        {{ $datas->appends(request()->query())->links()  }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
