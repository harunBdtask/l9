<?php

namespace SkylarkSoft\GoRMG\DyesStore\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\DyesStore\Models\DyesChemicalTransaction;

class DsReceiveReturnQtyRule implements Rule
{
    private const IN = 'in';
    private const OUT = 'out';
    private const RECEIVE_RETURN = 'receive_return';
    private const ISSUE_RETURN = 'issue_return';

    private $balanceQty;

    public function passes($attribute, $value): bool
    {
        $explodeAttribute = explode('.', $attribute);
        $details = request()->input('details');
        $itemId = $details[$explodeAttribute[1]]['item_id'];
        $receiveQty = $details[$explodeAttribute[1]]['receive_qty'];


        $totalReceiveQty = DyesChemicalTransaction::query()
            ->where('item_id', $itemId)
            ->where('trn_type', self::IN)
            ->sum('qty');

        $totalIssueQty = DyesChemicalTransaction::query()
            ->where('item_id', $itemId)
            ->where('trn_type', self::OUT)
            ->sum('qty');

        $totalReceiveReturnQty = DyesChemicalTransaction::query()
            ->where('item_id', $itemId)
            ->where('trn_type', self::RECEIVE_RETURN)
            ->sum('qty');

        $totalIssueReturnQty = DyesChemicalTransaction::query()
            ->where('item_id', $itemId)
            ->where('trn_type', self::ISSUE_RETURN)
            ->sum('qty');

        $actualReceiveQty = format($totalReceiveQty, 4) - format($totalReceiveReturnQty, 4);
        $actualIssueQty = format($totalIssueQty, 4) - format($totalIssueReturnQty, 4);

        $this->balanceQty = format($actualReceiveQty - $actualIssueQty, 4);

        if ($this->balanceQty > $receiveQty) {
            $this->balanceQty = format($receiveQty, 4);
        }

        return $this->balanceQty >= format($value, 4);
    }

    public function message(): string
    {
        return "Return Qty Not Greater Then {$this->balanceQty}";
    }
}
