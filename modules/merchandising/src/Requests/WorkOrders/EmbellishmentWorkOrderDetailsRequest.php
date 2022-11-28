<?php


namespace SkylarkSoft\GoRMG\Merchandising\Requests\WorkOrders;

use App\Constants\ApplicationConstant;
use Illuminate\Foundation\Http\FormRequest;
use SkylarkSoft\GoRMG\Merchandising\Models\BudgetCostingDetails;
use SkylarkSoft\GoRMG\SystemSettings\Models\EmbellishmentItem;

class EmbellishmentWorkOrderDetailsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $emblCosting = $this->getEmbellishmentCosting();
        $itemWiseDetails = $emblCosting ? collect($emblCosting->details['details'])->where('type_id', request()->get('itemId'))->first() : null;
        //$totalAmountSum = ($itemWiseDetails['breakdown']['total_qty_sum'] ?? 0) * ($itemWiseDetails['breakdown']['rate_avg'] ?? 0);
        $totalAmountSum = $itemWiseDetails['breakdown']['total_amount_sum'] ?? 0;
        
        $cost = $emblCosting ? collect($emblCosting->details['details'])->pluck('breakdown')->sum('total_amount_sum') : null;
        if (!$cost || !$totalAmountSum) {
            return [
                'amount_sum' => [function ($attribute, $value, $fail) {
                    $message = "Please Check Budget(Costing Not Found)";
                    $fail($message);
                }],
            ];
        }

        return [
            'amount_sum' => [function ($attribute, $value, $fail) use ($totalAmountSum) {
                if ($value > $totalAmountSum) {
                    $exceeds = $value - $totalAmountSum;
                    $message = "Exceeds: ($exceeds). Total Embellishment Cost in Budget is $totalAmountSum";
                    $fail($message);
                }
            }],
        ];
    }

    public function getEmbellishmentCosting()
    {
        $getWorkOrderTag = EmbellishmentItem::query()
            ->where('id', $this->input('itemId'))
            ->first();
        return BudgetCostingDetails::query()
            ->where('budget_id', $this->input('budget_id'))
            ->where('type', $getWorkOrderTag->tag)
            ->first();
    }
}
