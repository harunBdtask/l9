<?php

namespace SkylarkSoft\GoRMG\Merchandising\Commands;

use Illuminate\Console\Command;
use SkylarkSoft\GoRMG\Merchandising\Models\CostingDetails;
use SkylarkSoft\GoRMG\Merchandising\Models\FabricCostDetails;
use SkylarkSoft\GoRMG\Merchandising\Models\PriceQuotation;
use SkylarkSoft\GoRMG\Merchandising\Services\FabricDescriptionService;
use SkylarkSoft\GoRMG\SystemSettings\Models\BodyPart;
use SkylarkSoft\GoRMG\SystemSettings\Models\ColorType;
use SkylarkSoft\GoRMG\SystemSettings\Models\FabricNature;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsItem;
use SkylarkSoft\GoRMG\SystemSettings\Models\Supplier;
use SkylarkSoft\GoRMG\SystemSettings\Services\ConsumptionBasisService;
use SkylarkSoft\GoRMG\SystemSettings\Services\DiaTypesService;
use SkylarkSoft\GoRMG\SystemSettings\Services\FabricSourceService;

class FabricCosting extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fabric-costing:replace {quotation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'To Change old Fabric Costing Structure to New Structure';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $quotation = $this->argument('quotation');
            $price_quotation = PriceQuotation::where("quotation_id", $quotation)->first();
            $old_fabric_cost = FabricCostDetails::where("quotation_id", $quotation)->get()->toArray();
            $itemsId = collect($old_fabric_cost)->pluck("garment_item_id")->unique()->toArray();
            $items = GarmentsItem::whereIn("id", $itemsId)->get();
            $fabric_costing = CostingDetails::where("price_quotation_id", $price_quotation->id)
                ->where("type", "fabric_costing")
                ->first();
            $data = collect($old_fabric_cost)->map(function ($value) use ($items) {
                $item_name = collect($items)->where("id", $value['garment_item_id'])->first();
                $body_part = BodyPart::where("id", $value['body_part_id'])->first();
                $fabric_nature = FabricNature::where("id", $value['fabric_nature_id'])->first();
                $color_type = ColorType::where("id", $value['color_type_id'])->first();
                $supplier = Supplier::where("id", $value['supplier_id'])->first();

                return [
                    "quotation_id" => $value['quotation_id'],
                    "costing_multiplier" => $value['costing_multiplier'],
                    "garment_item_id" => $value['garment_item_id'],
                    "garment_item_name" => $item_name['name'] ?? '',
                    "body_part_id" => $value['body_part_id'],
                    "body_part_value" => $body_part->name ?? '',
                    "body_part_type" => null,
                    "fabric_nature_id" => $value['fabric_nature_id'],
                    "fabric_nature_value" => $fabric_nature['name'] ?? '',
                    "color_type_id" => $value['color_type_id'],
                    "color_type_value" => $color_type['color_types'] ?? '',
                    "fabric_composition_id" => $value['fabric_composition_id'],
                    "fabric_composition_value" => FabricDescriptionService::description($value['fabric_composition_id']),
                    "fabric_source" => $value['fabric_source'],
                    "fabric_source_value" => FabricSourceService::get($value['fabric_source'])['name'] ?? '',
                    "supplier_id" => $value['supplier_id'] ?? '',
                    "supplier_value" => $supplier['name'] ?? '',
                    "dia_type" => $value['dia_type'],
                    "dia_type_value" => DiaTypesService::get($value['dia_type'])['name'] ?? '',
                    "gsm" => $value['gsm'],
                    "consumption_basis" => $value['consumption_basis'],
                    "consumption_basis_value" => ConsumptionBasisService::get($value['consumption_basis'])['name'] ?? '',
                    "uom" => $value['uom'],
                    "fabric_cons" => $value['fabric_cons'],
                    "rate" => $value['rate'],
                    "amount" => $value['amount'],
                    "status" => $value['status'],
                    "yarn_cost_amount" => null,
                    "fabricConsumptionForm" => json_decode($value['fabric_consumption_details'])->details,
                    "fabricConsumptionCalculation" => json_decode($value['fabric_consumption_details'])->calculation,
                ];
            });

            $old_details = $fabric_costing['details'];
            $old_details['details']['fabricForm'] = $data;
            $fabric_costing->update([
                "details" => $old_details,
            ]);
            $this->info("Converted Successfully!");

            return true;
        } catch (\Exception $exception) {
            $this->info("Conversion Failed at: {$exception->getMessage()}!");

            return false;
        }
    }
}
