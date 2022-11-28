<div class="box allContent">
    <div class="box-header noprint flex flex-row justify-content-between align-items-center">
        <h2>{{ $detail->fabric_description }}, Total Roll: {{ $detail->total_roll }}, Receive
            Qty: {{ $detail->receive_qty }}</h2>
        <button class="pull-right btn btn-xs btn-primary print-btn" type="button" data-key="{{ $detailKey }}">
            <i class="fa fa-print"></i>
        </button>
    </div>
    <div class="box-body">
        @if($detail && count($detail->barcodes))
            @foreach (collect($detail->barcodes)->chunk(2)->values() as $barcodes)
                <div class="row">
                    @foreach (collect($barcodes)->values() as $key => $barcodeData)
                        @php
                            $barcodeDetail = $barcodeData->subDyeingOrderDetail;
                        @endphp
                        <div
                            class="col-md-4 {{ $key == 0 ? 'col-md-offset-1' : ''}} b-dashed barcode-container">
                            <div class="text-center">
                                <p>
                                    <span class="font-weight-bold">{{ sessionFactoryName() }}</span>
                                </p>
                            </div>
                            <div class="flex flex-row justify-content-between align-items-center">
                                <p>
                                    <span class="font-weight-bold">ITEM:</span> <span
                                        class="font-600">{{ $barcodeDetail->fabric_description ?? '' }}</span>
                                </p>
                            </div>

                            <div class="flex flex-row justify-content-between align-items-center">
                                <p>
                                    <span class="font-weight-bold">BUYER:</span>
                                    <span class="font-600">{{ $barcodeDetail->supplier->name ?? '' }}</span>
                                </p>
                            </div>

                            <div class="flex flex-row justify-content-between align-items-center">
                                <p>
                                    <span class="font-weight-bold">REF NO:</span>
                                    <span class="font-600">{{ $receive->challan_no ?? '' }}</span>
                                </p>
                            </div>

                            <div class="flex flex-row justify-content-between align-items-center">
                                <p>
                                    <span class="font-weight-bold">Roll NO:</span>
                                    <span class="font-600">{{ $barcodeData->roll_id ?? '' }}</span>
                                </p>
                            </div>

                            <div class="flex flex-row justify-content-between align-items-center">
                                <p>
                                    <span class="font-weight-bold">Total Qty: </span>
                                    <span
                                        class="font-600">{{ $barcodeData->barcode_qty ?? '' }} {{ $barcodeDetail->unitOfMeasurement->unit_of_measurement }}</span>
                                </p>
                            </div>

                            <div class="flex flex-row justify-content-between align-items-center">
                                <p>
                                    <span>
                                        <?php echo DNS1D::getBarcodeSVG((str_pad($barcodeData->id, 10, 0, STR_PAD_LEFT) ?? '1234'), "C128A", 2.98, 58, '', true); ?>
                                    </span>
                                </p>
                            </div>
                        </div>

                        <div class="col-md-1"></div>
                    @endforeach
                </div>

                <br>
            @endforeach
        @else
            <h5 class="text-danger text-center">No Barcodes Generated Yet</h5>
        @endif
    </div>
</div>

<iframe id="printable-{{ $detailKey }}" name="printf"
        src="{{ url('subcontract/material-fabric-receive/barcode/print/' . $detail->id) }}"
        width="100%"
        style="visibility: hidden;"></iframe>

@section('scripts')
    <script type="text/javascript">
        $(document).on('click', '.print-btn', function (event) {
            let detailKey = $(this).attr('data-key');
            $(`#printable-${detailKey}`).get(0).contentWindow.print();
        });

        // For browser print restriction
        if ('matchMedia' in window) {
            // Chrome, Firefox, and IE 10 support mediaMatch listeners
            window.matchMedia('print').addListener(function (media) {
                if (media.matches) {
                    beforePrint();
                } else {
                    // Fires immediately, so wait for the first mouse movement
                    $(document).one('mouseover', afterPrint);
                }
            });
        } else {
            // IE and Firefox fire before/after events
            $(window).on('beforeprint', beforePrint);
            $(window).on('afterprint', afterPrint);
        }

        function beforePrint() {
            $(".allContent").hide();
            $(".print-permit-message").show();
        }

        function afterPrint() {
            $(".allContent").show();
            $(".print-permit-message").hide();
        }
    </script>
@endsection
