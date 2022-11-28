<?php


namespace SkylarkSoft\GoRMG\Merchandising\Requests\Bookings;

use App\Constants\ApplicationConstant;
use Illuminate\Foundation\Http\FormRequest;
use SkylarkSoft\GoRMG\Merchandising\Models\BudgetCostingDetails;

class FabricServiceBookingDetailRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        $costing = $this->getFabricCosting();

        $cost = null;

        if ($costing['calculation'] && $costing['calculation']['conversion_costing'] && $costing['calculation']['conversion_costing']['conversion_amount_sum']) {
            $cost = $costing['calculation']['conversion_costing']['conversion_amount_sum'];
        }

        if (! $cost) {
            abort(404);
        }
    }

    public function getFabricCosting()
    {
        $costing = BudgetCostingDetails::query()
            ->where('budget_id', $this->input('budget_id'))
            ->where('type', ApplicationConstant::FABRIC_COST)
            ->firstOrFail();

        return $costing->details;
    }
}
