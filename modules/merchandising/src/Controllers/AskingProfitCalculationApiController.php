<?php


namespace SkylarkSoft\GoRMG\Merchandising\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use SkylarkSoft\GoRMG\Merchandising\Models\PriceQuotation;
use SkylarkSoft\GoRMG\SystemSettings\Models\FinancialParameterSetup;
use SkylarkSoft\GoRMG\SystemSettings\Models\MerchandisingVariableSettings;

class AskingProfitCalculationApiController
{
    public function __invoke(Request $request)
    {
        $price_quotation = PriceQuotation::where("quotation_id", $request->quotation_id)->first();
        $asking_profit_calculation = MerchandisingVariableSettings::where("factory_id", $price_quotation->factory_id)->first();
        $response = [];
        if (isset($asking_profit_calculation['variables_details']['asking_profit_calculation']) && $asking_profit_calculation['variables_details']['asking_profit_calculation'] == 1) {
            // from Financial Parameter Setup
            $asking_profit = FinancialParameterSetup::where("factory_id", $price_quotation->factory_id)
                ->where("date_from", "<=", $price_quotation->created_at->format('Y-m-d'))
                ->where("date_to", ">=", $price_quotation->created_at->format('Y-m-d'))
                ->first();
            $response["type"] = 1;
            $response["asking_profit"] = $asking_profit['asking_profit'];
            $response["is_financial"] = true;
        } else {
            $response["type"] = 2;
            $response["asking_profit"] = false;
            $response["is_financial"] = false;
        }

        return response()->json($response, Response::HTTP_OK);
    }
}
