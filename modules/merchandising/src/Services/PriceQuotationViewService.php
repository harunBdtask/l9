<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services;

use Illuminate\Support\Carbon;
use SkylarkSoft\GoRMG\Merchandising\Models\CostingDetails;
use SkylarkSoft\GoRMG\Merchandising\Models\FabricCostDetails;
use SkylarkSoft\GoRMG\Merchandising\Models\PqCommissionDetail;
use SkylarkSoft\GoRMG\Merchandising\Models\PriceQuotation;
use SkylarkSoft\GoRMG\Merchandising\Services\FabricDescriptionService;
use SkylarkSoft\GoRMG\SystemSettings\Models\FinancialParameterSetup;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsItem;
use SkylarkSoft\GoRMG\SystemSettings\Models\ItemGroup;
use SkylarkSoft\GoRMG\SystemSettings\Models\UnitOfMeasurement;
use SkylarkSoft\GoRMG\SystemSettings\Services\ConsumptionBasisService;
use SkylarkSoft\GoRMG\SystemSettings\Services\DiaTypesService;
use SkylarkSoft\GoRMG\SystemSettings\Services\FabricSourceService;
use function PHPUnit\Framework\isNull;

class PriceQuotationViewService
{
    public static function data($id)
    {
        $price_quotation = PriceQuotation::with([
            "buyer:id,name",
            "season:id,season_name"
        ])->where('quotation_id', $id)->first();

        $totalSmv = collect($price_quotation->item_details)->last()['total_smv'] ?? '0.00';
        $particulars_data = [
            [
                "cost" => $price_quotation->fab_cost,
                "percent_order_value" => $price_quotation->fab_cost_prcnt
            ],
            [
                "cost" => $price_quotation->trims_cost,
                "percent_order_value" => $price_quotation->trims_cost_prcnt
            ],
            [
                "cost" => $price_quotation->embl_cost,
                "percent_order_value" => $price_quotation->embl_cost_prcnt
            ],
            [
                "cost" => $price_quotation->comml_cost,
                "percent_order_value" => $price_quotation->comml_cost_prcnt
            ],
            [
                "cost" => $price_quotation->gmt_wash,
                "percent_order_value" => $price_quotation->gmt_wash_prcnt
            ],
            [
                "cost" => $price_quotation->lab_cost,
                "percent_order_value" => $price_quotation->lab_cost_prcnt
            ],
            [
                "cost" => $price_quotation->inspect_cost,
                "percent_order_value" => $price_quotation->inspect_cost_prcnt
            ],
            [
                "cost" => $price_quotation->cm_cost,
                "percent_order_value" => $price_quotation->cm_cost_prcnt
            ],
            [
                "cost" => $price_quotation->freight_cost,
                "percent_order_value" => $price_quotation->freight_cost_prcnt
            ],
            [
                "cost" => $price_quotation->currier_cost,
                "percent_order_value" => $price_quotation->currier_cost_prcnt
            ],
            [
                "cost" => $price_quotation->certif_cost,
                "percent_order_value" => $price_quotation->certif_cost_prcnt
            ],

        ];
        $others_component_data = [
            [
                "cost" => $price_quotation->gmt_wash ?? 0.00,
            ],
            [
                "cost" => $price_quotation->lab_cost ?? 0.00,
            ],
            [
                "cost" => $price_quotation->inspect_cost ?? 0.00,
            ],
            [
                "cost" => $price_quotation->cm_cost ?? 0.00,
            ],
            [
                "cost" => $price_quotation->freight_cost ?? 0.00,
            ],
            [
                "cost" => $price_quotation->currier_cost ?? 0.00,
            ],
            [
                "cost" => $price_quotation->certif_cost ?? 0.00,
            ],
            [
                "cost" => $price_quotation->design_cost ?? 0.00,
            ],
            [
                "cost" => $price_quotation->studio_cost ?? 0.00,
            ],
        ];
        $costingDetails = CostingDetails::where('price_quotation_id', $price_quotation->id)->get()->map(function ($costing) {
            return [
                'type' => $costing->type,
                'data' => $costing->details
            ];
        });
        $yarnCosting = collect($costingDetails)->where("type", "fabric_costing")->pluck("data.details.yarnCostForm")->first();
        $avg_yarn_val = !empty($yarnCosting) ? collect($yarnCosting)->sum('amount') : 0;;
        $conversionCosting = collect($costingDetails)->where("type", "fabric_costing")->pluck("data.details.conversionCostForm")->first();
        $totalFabricCost = collect($costingDetails)->where("type", "fabric_costing")->pluck("data.calculation.total")->first();
        $embellishmentCosting = collect($costingDetails)->where("type", "embellishment_cost")->pluck("data.details")->first();
        $commercialCosting = collect($costingDetails)->where("type", "commercial_cost")->pluck("data.details")->first();
        $commissionCosting = PqCommissionDetail::where("quotation_id", $id)->get();
        $fabricCost = FabricCostDetails::where('quotation_id', $id);
        $fabricCostDetails = collect($costingDetails)->where("type", "fabric_costing")->pluck("data.details")->first();
        $gsm = !empty($fabricCostDetails) ? collect($fabricCostDetails['fabricForm'])->pluck('gsm')->implode(', ') : '';
        $totalKnit = !empty($fabricCostDetails) ? collect($fabricCostDetails['fabricForm'])->where('fabric_nature_id', 1)->sum('fabric_cons') : 0;
        $totalWoven = !empty($fabricCostDetails) ? collect($fabricCostDetails['fabricForm'])->where('fabric_nature_id', 2)->sum('fabric_cons') : 0;

        $createdDate = date_format(date_create($price_quotation->created_at), 'Y-m-d');
        $financialParameter = FinancialParameterSetup::where('date_from', '<', $createdDate)->where('date_to', '>', $createdDate)->first();
        $priceQuotationItemsId = collect($price_quotation->item_details)->pluck(("garment_item_id"))->filter(function ($data) {
            return $data !== null;
        });

        $priceQuotationItems = implode(",", GarmentsItem::whereIn("id", $priceQuotationItemsId)->get()->pluck("name")->toArray());
        $priceQuotationGsm = FabricCostDetails::where('quotation_id', $id)->get()->pluck('gsm');
        $priceQuotationGsmData = implode(",", $priceQuotationGsm->toArray());
        $trimCosting = collect($costingDetails)->where("type", "trims_costing")->pluck("data.details")->first();
        $response = [
            "id" => $id,
            "price_quotation" => $price_quotation,
            "particulars_sum" => number_format(collect($particulars_data)->sum('cost'), 2),
            "particulars_percentage_sum" => number_format(collect($particulars_data)->sum('percent_order_value'), 2),
            'costingDetails' => $costingDetails,
            "items" => $priceQuotationItems,
            'fabricCostDetails' => collect($fabricCostDetails),
            "gsm_data" => $priceQuotationGsmData,
            "yarn_costing" => $yarnCosting ?? [],
            "conversion_costing" => $conversionCosting ?? [],
            "total_fabric_cost" => $totalFabricCost,
            "embellishment_costing" => $embellishmentCosting ?? [],
            "commercial_costing" => $commercialCosting ?? [],
            "commission_costing" => $commissionCosting ?? [],
            'trim_costing' => $trimCosting ?? [],
            "others_component_data" => $others_component_data,
            'financialParameter' => $financialParameter,
            'total_smv' => $totalSmv,
            'avg_yarn_val' => $avg_yarn_val,
            'gsm' => $gsm,
            'totalKnit' => $totalKnit,
            'totalWoven' => $totalWoven,
        ];
        return $response;
    }

    public static function costingData($id): array
    {
        $price_quotation = PriceQuotation::with([
            "buyer:id,name",
            "season:id,season_name",
            "colorRange:id,name"
        ])->where('quotation_id', $id)->first();

        $gmtsItemIds = collect($price_quotation['item_details'])->pluck('garment_item_id')->filter(function ($val) {
            return $val != null;
        });
        $items = GarmentsItem::query()->whereIn('id', $gmtsItemIds)->get()->pluck('name');
        $price_quotation['item_names'] = $items->implode(', ');
        $price_quotation['items'] = $items;
        $costingDetails = CostingDetails::where('price_quotation_id', $price_quotation->id)->get();
        $data['wash_cost'] = $costingDetails->where('type', 'wash_cost')->first()->details ?? [];
        $data['commercial_cost'] = $costingDetails->where('type', 'commercial_cost')->first()->details ?? [];
        $data['embellishment_cost'] = $costingDetails->where('type', 'embellishment_cost')->first()->details ?? [];
        $data['commission_cost'] = $costingDetails->where('type', 'commission_cost')->first()->details ?? [];
        $data['trims_costing'] = collect($costingDetails->where('type', 'trims_costing')->first()->details['details'] ?? [])->map(function ($trim) {
            return [
                'gmts_item_id' => $trim['gmts_item_id'] ?? '',
                'gmts_item_name' => $trim['gmts_item_name'] ?? '',
                'group_id' => $trim['group_id'] ?? '',
                'type' => ItemGroup::find($trim['group_id'])->trims_type,
                'group_name' => $trim['group_name'] ?? '',
                'item_description' => $trim['item_description'] ?? '',
                'cons_uom_id' => $trim['cons_uom_id'] ?? '',
                'cons_uom_value' => $trim['cons_uom_value'] ?? '',
                'cons_gmts' => $trim['cons_gmts'] ?? '',
                'rate' => $trim['rate'] ?? 0,
                'amount' => $trim['amount'] ?? 0,
                'nominated_supplier_id' => $trim['nominated_supplier_id'] ?? '',
                'nominated_supplier_value' => $trim['nominated_supplier_value'] ?? '',
                'approval_req' => $trim['approval_req'] ?? '',
                'status' => $trim['status'] == 1 ? 'Active' : 'In Active',
            ];
        });
        $data['fabric_costing'] = $costingDetails->where('type', 'fabric_costing')->first()->details['details'] ?? [];
        $data['fabricUom'] = [1 => 'Kg', 2 => 'Yards', 3 => 'Meter', 4 => 'Pcs'];
        $data['size_range'] = $data['fabric_costing'] ?
            collect($data['fabric_costing']['fabricForm'])->pluck('fabricConsumptionForm')->flatten(1)->pluck('gmts_size')->unique()->implode(', ')
            : ' ';
        $data['priceQuotation'] = $price_quotation;
        return $data;
    }


    public static function formatAnwarViewData($id)
    {
        $price_quotation = PriceQuotation::with([
            "buyer:id,name", 'costingDetails'
        ])->where('quotation_id', $id)->first();
        $data['buyer'] = $price_quotation->buyer->name ?? '';
        $data['quotation_id'] = $price_quotation->quotation_id ?? '';
        $data['id'] = $price_quotation->quotation_id ?? '';
        $fabric_costing = collect($price_quotation->costingDetails)->where('type', 'fabric_costing');
        $fabric_costing = count($fabric_costing) > 0 ? $fabric_costing->pluck('details.details.fabricForm') : [];
        $data['fabric_costing'] = count(collect($fabric_costing)->whereNotNull()) > 0 ? $fabric_costing->collapse() : [];
        $data['additional'] = $price_quotation->additional_costing;
        return $data;

    }
}
