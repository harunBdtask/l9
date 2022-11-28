<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Directives;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class BladeDirectiveCriteria
{
    public static function permission($permission): bool
    {
        if (in_array(getRole(), ['super-admin', 'admin']) || session()->has($permission)) {
            return true;
        }
        return false;
    }
}
