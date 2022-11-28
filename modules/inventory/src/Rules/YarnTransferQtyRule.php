<?php

namespace SkylarkSoft\GoRMG\Inventory\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use SkylarkSoft\GoRMG\Inventory\Models\YarnTransferDetail;
use SkylarkSoft\GoRMG\Inventory\Services\YarnStockSummaryService;

class YarnTransferQtyRule implements Rule
{
    /**
     * @var YarnStockSummaryService
     */
    private $stockSummaryService;
    private $message;

    /**
     */
    public function __construct()
    {
        $this->stockSummaryService = new YarnStockSummaryService();
        $this->message = '';
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $yarn_count_id = request('yarn_count_id');
        $yarn_composition_id = request('yarn_composition_id');
        $yarn_type_id = request('yarn_type_id');
        $yarn_lot = request('yarn_lot');
        $uom_id = request('uom_id');
        $yarn_color = request('yarn_color');
        $store_id = request('store_id');
        $id = request('id');
        $transfer_qty = request('transfer_qty');

        $summary = $this->stockSummaryService->summary(
            request()->toArray()
        );

        try {

            if ( !$id && ($summary->balance >= $transfer_qty) ) {
                return true;
            }

            $yarnTransfer = YarnTransferDetail::query()
                ->where('yarn_count_id', $yarn_count_id)
                ->where('yarn_composition_id', $yarn_composition_id)
                ->where('yarn_type_id', $yarn_type_id)
                ->where('yarn_lot', $yarn_lot)
                ->where('uom_id', $uom_id)
                ->where('yarn_color', $yarn_color)
                ->where('store_id', $store_id)
                ->first();

            $previousTransferQty = $yarnTransfer->transfer_qty;

            if ( $previousTransferQty < $transfer_qty ) {
                return true;
            }

            if ( $previousTransferQty > $transfer_qty ) {
                $increasedQty = $transfer_qty - $previousTransferQty;
                if ( $increasedQty >= $summary->balance ) {
                    return true;
                }
            }

        } catch (ModelNotFoundException $e) {
            $this->message = 'Stock Not Found!';
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string|array
     */
    public function message(): string
    {
        return $this->message;
    }
}