@foreach($receiveDetails as $key => $detail)
    <div class="card">
        <div class="card-header"
             data-toggle="collapse"
             data-target="#collapse_{{ $key }}"
             data-total-roll="{{ $detail['total_roll'] }}"
             aria-expanded="false"
             aria-controls="collapse_{{ $key }}"
             id="detail">
            {{ $detail['fabric_description'] }}, Total Roll: {{ $detail['total_roll'] }},
            Receive Qty: {{ $detail['receive_qty'] }}
        </div>

        <div id="collapse_{{ $key }}" class="collapse" aria-labelledby="heading_{{ $key }}"
             data-parent="#accordion">
            <form action="/subcontract/material-fabric-receive/barcode/generate-barcodes" id="detail_form">
                <div class="card-body">
                    <input type="hidden" name="factory_id" value="{{ $detail['factory_id'] }}"/>
                    <input type="hidden" name="sub_grey_store_receive_id"
                           value="{{ $detail['sub_grey_store_receive_id'] }}"/>
                    <input type="hidden" name="sub_grey_store_receive_detail_id" value="{{ $detail['id'] }}"/>
                    <input type="hidden" name="sub_grey_store_id" value="{{ $detail['sub_grey_store_id'] }}"/>
                    <input type="hidden" name="supplier_id" value="{{ $detail['supplier_id'] }}"/>
                    <input type="hidden" name="sub_textile_order_id" value="{{ $detail['sub_textile_order_id'] }}"/>
                    <input type="hidden" name="sub_textile_order_detail_id"
                           value="{{ $detail['sub_textile_order_detail_id'] }}"/>
                    <table class="reportTable">
                        <tbody id="detail_table">
                        <tr>
                            <td>
                                <input type="text" class="form-control form-control-sm" disabled>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div class="card-footer">
                    <button class="btn btn-xs btn-success" type="submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
@endforeach
