<?php

namespace SkylarkSoft\GoRMG\Knitting\Services\Formatter;

class RequisitionFormatAdapter
{
    private $bindings = [
        'main' => MainFabricBookingFormatter::class,
        'short' => ShortFabricBookingFormatter::class,
    ];

    private $salesOrder;

    private function __construct($value)
    {
        $this->salesOrder = $value;
    }

    public static function for($value): RequisitionFormatAdapter
    {
        return new static($value);
    }

    public function getBookingId()
    {
        return $this->salesOrder->booking_id;
    }

    public function format()
    {
        if (!isset($this->salesOrder->booking_type)) {
            return (new DefaultRequisitionFormatter)->doFormat($this);
        }
        return (new $this->bindings[$this->salesOrder->booking_type])->doFormat($this);
    }
}
