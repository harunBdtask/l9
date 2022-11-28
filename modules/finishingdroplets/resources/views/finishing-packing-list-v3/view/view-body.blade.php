@php
    use SkylarkSoft\GoRMG\Finishingdroplets\Models\GarmentPackingProduction;
@endphp
<div class="row p-x-1">
    <div class="col-md-12">

        <div class="row m-t">
            <div class="col-md-12">
                <table class="table">
                    <tbody>
                    <tr>
                        <td class="text-right">
                            <strong>Buyer :</strong>
                        </td>
                        <td class="text-left"> {{ $garmentPackingProduction->buyer->name }} </td>
                        <td class="text-right">
                            <strong>Style :</strong>
                        </td>
                        <td class="text-left"> {{ $garmentPackingProduction->order->style_name }} </td>
                    </tr>
                    <tr>
                        <td class="text-right">
                            <strong>PO No : </strong>
                        </td>
                        <td class="text-left">{{ $garmentPackingProduction->purchaseOrder->po_no }}</td>
                        <td class="text-right">
                            <strong>Delivery Country : </strong>
                        </td>
                        <td class="text-left">{{ $garmentPackingProduction->purchaseOrder->country->name }}</td>
                    </tr>
                    <tr>
                        <td class="text-right">
                            <strong>Assortment : </strong>
                        </td>
                        <td class="text-left">{{ Str::headline($garmentPackingProduction->packing_ratio) }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        @includeWhen($garmentPackingProduction->packing_ratio === GarmentPackingProduction::SOLID_COLOR_SOLID_SIZE,
            'finishingdroplets::finishing-packing-list-v3.view.solid-color-solid-size')
        @includeWhen($garmentPackingProduction->packing_ratio === GarmentPackingProduction::SOLID_COLOR_ASSORT_SIZE,
            'finishingdroplets::finishing-packing-list-v3.view.solid-color-assort-size')
        @includeWhen($garmentPackingProduction->packing_ratio === GarmentPackingProduction::ASSORT_COLOR_SOLID_SIZE,
            'finishingdroplets::finishing-packing-list-v3.view.assort-color-solid-size')
        @includeWhen($garmentPackingProduction->packing_ratio === GarmentPackingProduction::ASSORT_COLOR_ASSORT_SIZE,
            'finishingdroplets::finishing-packing-list-v3.view.assort-color-assort-size')

    </div>
</div>
