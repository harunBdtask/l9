<?php

namespace SkylarkSoft\GoRMG\Inventory\Services\States\TrimsItemState;

class ItemFormatterState
{
    const AS_PER_GARMENTS_SENSITIVITY = 1;
    const CONTRAST_COLOR_SENSITIVITY = 2;
    const SIZE_SENSITIVITY = 3;
    const COLOR_SIZE_SENSITIVITY = 4;

    public function setState($type)
    {
        $types = [
            self::AS_PER_GARMENTS_SENSITIVITY => new AsPerGarmentsFormatter(),
            self::CONTRAST_COLOR_SENSITIVITY => new ContrastColorFormatter(),
            self::SIZE_SENSITIVITY => new ColorAndSizeFormatter(),
            self::COLOR_SIZE_SENSITIVITY => new ColorAndSizeFormatter(),
        ];

        if (! $type) {
            return new NoSensitivityFormatter();
        }

        return collect($types)->get($type);
    }
}
