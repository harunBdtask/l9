<div class="col-sm-12 m-t">
    <table>
        <thead>
        <tr>
            <th colspan="14" class="text-center text-uppercase">Accessories DETAILS</th>
        </tr>
        <tr>
            <th>ITEM NAME</th>
            <th>DESCRIPTION</th>
            <th>N. Supplier</th>
            <th>GMTS COLORS</th>
            <th>With Color</th>
            <th>SIZE</th>
            <th>REQ QTY</th>
            <th>UOM</th>
            <th>Rate</th>
            <th>TOTAL AMOUNT</th>
            <th>Delivery TO</th>
            <th>Delivery Date</th>
            <th>Remarks</th>
            <th>IMAGE</th>
        </tr>
        </thead>
        @if ($sampleOrderRequisition->accessories)
        @foreach ($sampleOrderRequisition->accessories as $value)
        @php
            $base64 = '';
            if ($value->details['image_path'] && File::exists('storage/'.$value->details['image_path'])) {
                $path = 'storage/'. $value->details['image_path'];
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            }
        @endphp
        <tr>
            <td>{{ $value->itemGroup->item_group ?? null }}</td>
            <td>{{ $value->details['description'] ?? null }}</td>
            <td>{{ $value->supplier->name ?? null }}</td>
            <td>{{ $value->color->name ?? null }}</td>
            <td>{{ $value->details['color'] ?? null }}</td>
            <td>{{ $value->size->name ?? null }}</td>
            <td>{{ $value->calculations['req_qty'] ? number_format($value->calculations['req_qty'], 2) : null }}</td>
            <td>{{ $value->unitOfMeasurement->unit_of_measurement ?? null }}</td>
            <td>{{ $value->calculations['rate'] ? number_format($value->calculations['rate'], 2) : null }}</td>
            <td>{{ $value->calculations['total_amount'] ? number_format($value->calculations['total_amount'], 2) : null }}</td>
            <td>{{ $value->factory->factory_name ?? null }}</td>
            <td>
                {{ $value->details['delivery_date'] ? \Carbon\Carbon::make($value->details['delivery_date'])->toFormattedDateString() : null  }}
            </td>
            <td>{{ $value->details['remarks'] ?? null }}</td>
            <td class="text-center">
                @if ($sampleOrderRequisition->viewType != 'excel')
                    @if($base64)
                        <img
                            src="{{ $base64 }}"
                            alt="style image"
                            width="250">
                    @else
                        <img src="{{ asset('images/no_image.jpg') }}" height="50" width="50" alt="no image">
                    @endif
                    <img src="" alt="">
                @endif
            </td>
        </tr>
        @endforeach
        <tr>
            <th colspan="9" class="text-right">Total</th>
            <td>{{ $sampleOrderRequisition->accessories_details_cal['in_total'] ? number_format($sampleOrderRequisition->accessories_details_cal['in_total'], 2) : null }}</td>
            <td colspan="9"></td>
        </tr>
        @else
        <tr>
            <td colspan="14" class="text-center">No Data Found</td>
        </tr>
        @endif
        <tbody>
        </tbody>
    </table>
</div>

