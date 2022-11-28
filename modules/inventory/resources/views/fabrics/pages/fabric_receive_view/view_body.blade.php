<div class="body-section" style="margin-top: 0px;">
    <table class="reportTable">
        <tbody>
        <tr>
            <th>Receive Date:</th>
            <td>{{ \Carbon\Carbon::make($receive->receive_date)->format('d-m-Y') }}</td>
            <th>To:</th>
            <td>{{ $receive->store->name }}</td>
        </tr>
        <tr>
            <th>Challan No:</th>
            <td>{{ $receive->receive_challan }}</td>
            <th>LC/AC No:</th>
            <td>{{ $receive->lc_sc_no }}</td>
        </tr>
        <tr>
            <th>Grey Issue Challan:</th>
            <td>{{ $receive->grey_issue_challan }}</td>
            <td></td>
            <td></td>
        </tr>
        </tbody>
    </table>
    <br>
    <table class="reportTable">
        <thead>
        <tr>
            <th>Buyer</th>
            <th>Style</th>
            <th>PO</th>
            <th>Fab. Color</th>
            <th>Body Part</th>
            <th>Fab. Description</th>
            <th>Batch</th>
            <th>Roll No.</th>
            <th>Rcv. Qty</th>
            <th>Reject Qty</th>
            <th>Balance Qty</th>
            <th>Rate</th>
            <th>Amount</th>
            <th>UOM</th>
            <th>Fabric Shade</th>
            <th>Floor</th>
            <th>Room</th>
            <th>Rack</th>
            <th>Shelf</th>
            <th>Remarks</th>
            @if (isset($variableSettings) && $variableSettings->barcode == 1 && $receive->status == 1)
                <th>Action</th>
            @endif
        </tr>
        </thead>
        <tbody>
        @if($receive->details->count())
            @foreach($receive->details as $key => $detail)
                @php
                    $receiveQty = $detail->receive_qty;
                    $returnQty = $detail->receiveReturnDetails->sum('return_qty');
                    $balanceQty = $receiveQty - $returnQty;
                @endphp
                <tr>
                    <td>{{ $detail->buyer->name }}</td>
                    <td>{{ $detail->style_name }}</td>
                    <td>{{ $detail->po_no }}</td>
                    <td>{{ $detail->color->name }}</td>
                    <td>{{ $detail->body->short_name ?? $detail->body->name }}</td>
                    <td>{{ $detail->fabric_description }} {{ $detail->ac_dia ? ", Dia -".$detail->ac_dia : '' }}</td>
                    <td>{{ $detail->batch_no }}</td>
                    <td class="text-right">{{ $detail->no_of_roll }}</td>
                    <td class="text-right">{{ number_format($detail->receive_qty, 4) }}</td>
                    <td class="text-right">{{ number_format($detail->reject_qty, 4) }}</td>
                    <td class="text-right">{{ number_format($balanceQty, 4) }}</td>
                    <td class="text-right">{{ number_format($detail->rate, 2) }}</td>
                    <td class="text-right">{{ number_format($detail->amount, 4) }}</td>
                    @if($receive->receive_basis === 'independent')
                        <td>{{ $detail->uom->unit_of_measurement  }}</td>
                    @else
                        <td>{{ $uomService[$detail->uom_id] ?? 'kg'  }}</td>
                    @endif
                    <td>{{ $detail->fabric_shade }}</td>
                    <td>{{ $detail->floor->name }}</td>
                    <td>{{ $detail->room->name }}</td>
                    <td>{{ $detail->rack->name }}</td>
                    <td>{{ $detail->shelf->name }}</td>
                    <td>{{ $detail->remarks }}</td>
                    @if (isset($variableSettings) && $variableSettings->barcode == 1 && $receive->status == 1)
                        <td>
                            @if (!count($detail->barcodeDetails))
                                <button type="button" id="barcode" class="btn btn-sm success" data-toggle="modal"
                                        data-target="#barcode-modal" data="{{ $detail }}">
                                    <i class="fa fa-barcode"></i>
                                </button>
                            @else
                                <button style="margin-left: 2px;" type="button"
                                        class="btn btn-xs btn-danger show-modal" title="Delete Barcodes"
                                        data-toggle="modal" data-target="#confirmationModal"
                                        ui-toggle-class="flip-x"
                                        ui-target="#animate"
                                        data-url="{{ url('/inventory-api/v1/fabric-barcode-details/'.$detail->id. '/delete') }}">
                                    <i class="fa fa-trash"></i>
                                </button>
                            @endif
                        </td>
                    @endif
                </tr>
            @endforeach
            <tr>
                <td colspan="7"><b>Total</b></td>
                <td class="text-right"><b>{{ number_format($receive->details->sum('no_of_roll'), 2) }}</b></td>
                <td class="text-right"><b>{{ number_format($receive->details->sum('receive_qty'), 4) }}</b></td>
                <td><b>{{ number_format($receive->details->sum('reject_qty'), 4) }}</b></td>
                <td></td>
                <td></td>
                <td class="text-right"><b>{{ number_format($receive->details->sum('amount'), 4) }}</b></td>
                <td class="text-right"></td>
                <td colspan="10"></td>
            </tr>
        @else
            <tr>
                <td colspan="25">No Data Available</td>
            </tr>
        @endif
        </tbody>
        <br>

    </table>
</div>

<div class="signature">
    <table class="borderless" style="margin-top: 4%;">
        <tbody>
        <tr>
            <td colspan="5" class="text-center"><u> <b>Prepared By</b> </u></td>
            <td colspan="5" class='text-center'><u> <b>Received Sign</b> </u></td>
            <td colspan="5" class="text-center"><u> <b>Store Officer</b> </u></td>
            <td colspan="5" class="text-center"><u> <b>Authorized Sign</b> </u></td>
        </tr>
        </tbody>
    </table>
</div>

{{--<div id="barcode-modal" class="modal fade" style="display: none;">--}}
{{--    <div class="modal-dialog">--}}
{{--        <div class="modal-content">--}}
{{--            <div id="error-message"></div>--}}
{{--            <div class="modal-header">--}}
{{--                <h5 class="modal-title">Barcode</h5>--}}
{{--            </div>--}}
{{--            <div class="modal-body" id="modal-body">--}}
{{--            </div>--}}
{{--            <div class="modal-footer">--}}
{{--                <button type="button" class="btn danger p-x-md" data-dismiss="modal">Cancel</button>--}}
{{--                <button type="button" id="submit" class="btn success p-x-md">Submit</button>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            let detail = null;

            $(document).on('click', '#barcode', function () {
                detail = JSON.parse($(this).attr('data'));
                let fields = '';
                for (let i = 0; i < detail.no_of_roll; i++) {
                    fields += `<div class="form-group">
                                    <input class="form-control" type="text" name="qty[]" placeholder="Enter qty...">
                               </div>`;
                }
                $('#modal-body').html(fields);
            })

            $(document).on('click', '#submit', function () {
                let qty = $('input[name="qty[]"]').map(function () {
                    return $(this).val();
                }).get();

                let form = {
                    detail: detail,
                    qty: qty,
                };

                axios.post(`/inventory-api/v1/fabric-barcode-details`, form)
                    .then((response) => {
                        if (response.status === 201) {
                            location.reload();
                        }
                    })
                    .catch((error) => {
                        if (error.response.status === 500) {
                            let message = `<div class="col-md-12 alert alert-danger alert-dismissible text-center">
                                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                                <small>${error.response.data.message}</small>
                                            </div>`;
                            $('#error-message').html(message);
                        }

                        if (error.response.status === 422) {
                            let message = `<div class="col-md-12 alert alert-danger alert-dismissible text-center">
                                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                                <small>Field is required !</small>
                                            </div>`;
                            $('#error-message').html(message);
                        }
                    })
            })
        })
    </script>
@endsection
