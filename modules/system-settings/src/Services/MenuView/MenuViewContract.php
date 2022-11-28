<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Services\MenuView;

interface MenuViewContract
{
    public function willRender(array $variables): array;
}
