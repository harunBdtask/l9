<?php

namespace SkylarkSoft\GoRMG\DyesStore\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\DyesStore\Models\DyesChemicalTransaction;

class DsIssueReturnQtyRule implements Rule
{
    private const OUT = 'out';
    private const ISSUE_RETURN = 'issue_return';

    private $balanceQty;

    public function passes($attribute, $value)
    {
        $explodeAttribute = explode('.', $attribute);
        $details = request()->input('details');
        $itemId = $details[$explodeAttribute[1]]['item_id'];
        $deliveryQty = $details[$explodeAttribute[1]]['delivery_qty'];

        $totalIssueInQty = DyesChemicalTransaction::query()
            ->where('item_id', $itemId)
            ->where('trn_type', self::OUT)
            ->sum('qty');

        $totalIssueReturnQty = DyesChemicalTransaction::query()
            ->where('item_id', $itemId)
            ->where('trn_type', self::ISSUE_RETURN)
            ->sum('qty');

        $this->balanceQty = $totalIssueInQty - $totalIssueReturnQty;

        if ($this->balanceQty > $deliveryQty) {
            $this->balanceQty = format($deliveryQty, 4);
        }

        return $this->balanceQty >= format($value, 4);

    }

    public function message(): string
    {
        return "Return Qty Not Greater Then {$this->balanceQty}";
    }
}
