<?php

namespace SkylarkSoft\GoRMG\Inventory\Rules;


use SkylarkSoft\GoRMG\Inventory\Models\TrimsReceiveDetail;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\TrimsBookingDetails;

class TrimsReceiveQty extends StockQtyRule
{
    public function passes($attribute, $value): bool
    {

        if ($value <= 0) {
            $this->setMessage('Negative Or Zero Value Not Accepted');
            return false;
        }

        $this->setValues($attribute, $value);

        if ( !$this->id ) {
            return $this->validateReceiveQtyAdd();
        }

        return $this->validateReceiveQtyEdit();
    }

    private function validateReceiveQtyAdd(): bool
    {
        $bookingQty = $this->getBookingQty();

        $this->message = 'Exceeds booking qty by ';

        if ( !$this->summary ) {
            $this->message .= ($this->value - $bookingQty);
            return $this->value <= $bookingQty;
        }

        $this->message .= ($this->value + $this->summary->receive_qty) - $bookingQty;
        return ($this->value + $this->summary->receive_qty) <= $bookingQty;
    }

    private function validateReceiveQtyEdit(): bool
    {
        $receive = $this->getReceiveDetail();
        $receiveQtyBeforeEdit = $receive->receive_qty;
        $bookingQty = $this->getBookingQty();
        $summaryReceiveQty = $this->summary->receive_qty;
        $this->message = 'Exceeds booking qty by ';


        /* Same Quantity as Before*/
        if ( $this->value == $receiveQtyBeforeEdit ) {
            return true;
        }

        /* Qty is greater than previous receive qty*/
        if ( $this->value > $receiveQtyBeforeEdit ) {
            $this->message .= ($summaryReceiveQty - $receiveQtyBeforeEdit + $this->value) - $bookingQty;
            return $bookingQty >= ($summaryReceiveQty - $receiveQtyBeforeEdit + $this->value);
        }


        /* Qty is smaller than previous receive qty*/
        $this->setMessage('Negative balance is not accepted!');
        $newBalance = $this->summary->balance - $receiveQtyBeforeEdit + $this->value;
        return $newBalance >= 0;
    }

    private function getBookingQty()
    {
        return TrimsBookingDetails::query()
                ->where([
                    'style_name'  => $this->styleName,
                    'item_id'     => $this->itemId,
                    'cons_uom_id' => $this->uomId
                ])
                ->whereNotNull('details')
                ->pluck('details')
                ->flatten(1)
                ->sum('wo_qty') ?? 0;
    }

    private function getReceiveDetail()
    {
        return TrimsReceiveDetail::query()->find($this->id);
    }

    public function message()
    {
        return $this->message;
    }
}