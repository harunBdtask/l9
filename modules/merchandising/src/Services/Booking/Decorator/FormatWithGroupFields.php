<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services\Booking\Decorator;

use SkylarkSoft\GoRMG\SystemSettings\Services\FieldsService;

class FormatWithGroupFields extends FormatterDecorator
{
    public function decorate(): array
    {
        return collect($this->bookingFormatterComponentInterface->decorate())
            ->map(function ($collection) {
                foreach ($this->groupFields() as $field) {
                    if (!array_key_exists($field, $collection)) {
                        $collection[$field] = null;
                    }
                }
                return $collection;
            })
            ->toArray();
    }

    private function groupFields(): array
    {
        return FieldsService::getKeys();
    }
}
