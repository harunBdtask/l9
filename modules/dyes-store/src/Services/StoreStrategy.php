<?php

namespace SkylarkSoft\GoRMG\DyesStore\Services;

class StoreStrategy
{
    public static function getStrategy($store, $type): Store
    {
        return new General($store, $type);
    }
}
