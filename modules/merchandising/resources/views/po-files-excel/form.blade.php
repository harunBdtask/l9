@extends('skeleton::layout')
@section("title","PO Wise Color & Size Breakdown File")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>PO Wise Color & Size Breakdown File</h2>
            </div>

            <div class="box-body">
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
                <form action="{{ url('/po-files-excel/' . $pOFileModel->id) }}" method="post" id="form">
                    @csrf
                    @method('PUT')
                    <div class="row m-t-1">
                        <div class="col-md-12">
                            <table class="reportTable">
                                <thead>
                                <tr>
                                    <th>Po No.</th>
                                    <th>Po Receive Date</th>
                                    <th>Product</th>
                                    <th>Particular</th>
                                    <th>Style</th>
                                    <th>Color</th>
                                    <th>Size</th>
                                    <th>Quantity</th>
                                    <th>FOB Price</th>
                                    <th>Ex Factory Date</th>
                                    <th>Remarks</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($quantityMatrix as $breakdown)
                                    <tr>
                                        <td>
                                            <input type="hidden" class="form-control" name="league[]"
                                                   value="{{ $breakdown['league'] }}"/>
                                            <input type="hidden" class="form-control" name="customer[]"
                                                   value="{{ $breakdown['customer'] }}"/>
                                            <input type="hidden" class="form-control" name="country_id[]"
                                                   value="{{ $breakdown['country_id'] ?? '' }}"/>
                                            <input type="hidden" class="form-control" name="country_code[]"
                                                   value="{{ $breakdown['country_code'] ?? '' }}"/>
                                            <input type="text" class="form-control" name="po_no[]"
                                                   value="{{ $breakdown['po_no'] }}"/>
                                        </td>
                                        <td>
                                            <input type="date" class="form-control" name="po_received_date[]"
                                                   value="{{ $breakdown['po_received_date'] }}"/>
                                        </td>
                                        <td>
                                            <input type="hidden" class="form-control" name="item_id[]"
                                                   value="{{ $breakdown['item_id'] }}"/>
                                            <input type="text" class="form-control" name="item[]"
                                                   value="{{ $breakdown['item'] }}"/>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="particulars[]"
                                                   value="{{ $breakdown['particulars'] }}"/>
                                        </td>
                                        <td>{{ $pOFileModel->style }}</td>
                                        <td>
                                            <input type="hidden" class="form-control" name="color_id[]"
                                                   value="{{ $breakdown['color_id'] }}"/>
                                            <input type="text" class="form-control" name="color[]"
                                                   value="{{ $breakdown['color'] }}"/>
                                        </td>
                                        <td>
                                            <input type="hidden" class="form-control" name="size_id[]"
                                                   value="{{ $breakdown['size_id'] }}"/>
                                            <input type="text" class="form-control" name="size[]"
                                                   value="{{ $breakdown['size'] }}"/>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="value[]"
                                                   value="{{ $breakdown['value'] }}"/>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="fob_price[]"
                                                   value="{{ $breakdown['fob_price'] }}"/>
                                        </td>
                                        <td>
                                            <input type="date" class="form-control" name="x_factory_date[]"
                                                   value="{{ $breakdown['x_factory_date'] }}"/>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="remarks[]"
                                                   value="{{ $breakdown['remarks'] }}"/>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <div class="text-center">
                                <button type="submit" id="submit" class="btn btn-sm btn-success"><i
                                        class="fa fa-save"></i> Save
                                </button>
                                <a href="{{ url('/po-files-excel') }}" class="btn btn-sm btn-danger"><i
                                        class="fa fa-backward"></i> Cancel</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection
