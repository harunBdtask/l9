<?php


namespace SkylarkSoft\GoRMG\Merchandising\Services;

use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Merchandising\Models\CostingDetails;
use SkylarkSoft\GoRMG\Merchandising\Models\PriceQuotation;
use SkylarkSoft\GoRMG\SystemSettings\Models\CostingTemplate;

class PriceQuotationService
{
    public function types(): array
    {
        return [
            'Rubber',
            'Glitter',
            'Flock',
            'Puff',
            'High Density',
            'Foil',
            'Rubber+Foil',
            'Rubber+Silver',
            'Pigment',
            'Rubber+Pearl',
            'Rubber+Sugar',
            'Transfer / Sel',
            'Crack',
            'Photo',
            'Foil+Photo',
            'Pigment+Stud',
            'Rubber+Stud',
            'Rubber+Glitter',
            'Photo+Silicon',
            'Rubber+Silicon',
            'Rubber+Stud/Stone',
            'Photo+Stud/Stone',
            'Rubber+Flock',
            'Photo+Flock',
            'Discharge',
            'Discharge+ Flock',
            'Discharge + Pigment',
            'Pigment + Glitter',
            'Pigment + Foil',
            'Pigment+ Plastisol',
            'Plastisol',
            'Flou color',
            'Fluo +Pigment',
            'Photo + Pigment',
            'Reverse',
            'Reverse + Pigment',
            'Aop',
            'Burnout',
            'Sublimation',
            'Heat Press',
            'Pigment + Rubber',
            'Emboss',
            'Leaser Print',
            'Glow In Dark',
            'Metallic',
            'Pad Printing',
            'Pigment/Rubber',
            'Regular+Puff+Silver Foil',
            'Foil + Glitter',
        ];
    }

    public function save(Request $request)
    {
        $data['details'] = $request->get('details');
        $data['calculation'] = $request->get('dataSummery');
        $details = $data;
        $item['details'] = $details;
        $item['type'] = $request->get('type');
        $item['price_quotation_id'] = $request->get('price_quotation_id');
        $quotation = CostingDetails::where('price_quotation_id', $item['price_quotation_id'])->where('type', $item['type'])->first();

        if ($quotation) {
            CostingDetails::find($quotation->id)->update($item);
        } else {
            CostingDetails::create($item);
        }

        if ($request->get('is_template')) {
            $item['factory_id'] = $request->get('factory_id');
            $item['buyer_id'] = $request->get('buyer_id');
            $item['template_name'] = $request->get('template_name');
            CostingTemplate::create($item);
        }

        return 'Data Created Successfully';
    }

    public function findOldData($pqId, $type)
    {
        return CostingDetails::where('price_quotation_id', $pqId)->where('type', $type)->first();
    }

    public static function generateUniqueId(): string
    {
        $prefix = PriceQuotation::withTrashed()->max('id') + 1;
        $generate = str_pad($prefix, 5, "0", STR_PAD_LEFT);
        $generated_id = getPrefix() . 'PQ-' . date('y') . '-' . $generate;
        $prev_data = PriceQuotation::where('quotation_id', $generated_id)->first();
        if (!$prev_data) {
            return $generated_id;
        }
    }
}
