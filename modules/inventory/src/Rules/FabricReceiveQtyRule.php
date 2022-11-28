<?php

namespace SkylarkSoft\GoRMG\Inventory\Rules;

use SkylarkSoft\GoRMG\Inventory\Services\FabricStore\MaxPIQty;
use SkylarkSoft\GoRMG\Inventory\Models\FabricStore\FabricReceive;
use SkylarkSoft\GoRMG\Inventory\Models\FabricStore\FabricReceiveDetail;
use SkylarkSoft\GoRMG\Inventory\Services\FabricStore\FabricBookingMaxReceiveQty;
use SkylarkSoft\GoRMG\Inventory\Services\FabricStore\ShortFabricBookingMaxReceiveQty;

class FabricReceiveQtyRule extends FabricStockQtyRule
{
    public function passes($attribute, $value): bool
    {
        if ($value <= 0) {
            $this->message = 'Negative Or Zero Value Not Accepted';

            return false;
        }

        $this->setFabric($attribute, $value);
//
//        if (! $this->fabric['id']) {
//            return $this->validateReceiveQtyAdd();
//        }

        if (isset($this->fabric['id'])) {
            return $this->validateReceiveQtyEdit();
        }

        return true;
    }

    private function validateReceiveQtyAdd(): bool
    {
        $bookingQty = $this->getBookingQty();
        $this->message = 'Exceeds booking qty by ';
        $value = $this->fabric['qty'];

        if (!$this->summary) {
            $this->message .= ($value - $bookingQty);

            return $value <= $bookingQty;
        }

        $this->message .= ($value + $this->summary->receive_qty - $this->summary->receive_return_qty) - $bookingQty;

        return ($value + $this->summary->receive_qty - $this->summary->receive_return_qty) <= $bookingQty;
    }

    private function validateReceiveQtyEdit(): bool
    {
        $oldReceiveQty = $this->oldReceiveQty();
        $newReceiveQty = $this->fabric['qty'];
//        $bookingQty = $this->getBookingQty();
//        $summaryReceiveQty = $this->summary->receive_qty - $this->summary->receive_return_qty;
        $this->message = 'Exceeds booking qty by ';

        /* Same Quantity as Before*/
        if ($newReceiveQty == $oldReceiveQty) {
            return true;
        }

        /* Qty is greater than previous receive qty*/
//        if ($newReceiveQty > $oldReceiveQty) {
//            $this->message .= ($summaryReceiveQty - $oldReceiveQty + $newReceiveQty) - $bookingQty;
//
//            return $bookingQty >= ($summaryReceiveQty - $oldReceiveQty + $newReceiveQty);
//        }

        /* Qty is smaller than previous receive qty*/
        $this->message = 'Negative balance is not accepted!';
        $newBalance = $this->summary->balance - $oldReceiveQty + $newReceiveQty;

        return $newBalance >= 0;
    }

    private function getBookingQty(): float
    {
        $receivableType = request('receivable_type');
        $maxQty = 0;

        if ($receivableType == FabricReceive::FABRIC_BOOKING) {
            $maxQty = $this->maxReceiveQty(new FabricBookingMaxReceiveQty());
        }

        if ($receivableType == FabricReceive::SHORT_BOOKING) {
            $maxQty = $this->maxReceiveQty(new ShortFabricBookingMaxReceiveQty());
        }

        if ($receivableType == FabricReceive::PROFORMA_INVOICE) {
            $maxQty = $this->maxReceiveQty(new MaxPIQty());
        }

        return $maxQty;
    }

    private function oldReceiveQty()
    {
        return FabricReceiveDetail::query()
            ->where('id', $this->fabric['id'])
            ->sum('receive_qty');
    }
}
