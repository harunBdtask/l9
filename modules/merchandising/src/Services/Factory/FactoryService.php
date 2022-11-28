<?php


namespace SkylarkSoft\GoRMG\Merchandising\Services\Factory;

use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class FactoryService
{
    public static function getAllFactories()
    {
        return Factory::query()->userWiseFactories()->get();
    }
}
