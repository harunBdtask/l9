<?php

namespace SkylarkSoft\GoRMG\Merchandising\Rules;

use Illuminate\Contracts\Validation\Rule;

class UniqueFabricationInMasterOrder implements Rule
{
    public function __construct()
    {
        //
    }

    public function passes($attribute, $value)
    {
        $composition_fabric = request()->get('composition_fabric');
        if (count($composition_fabric) != count(array_unique($composition_fabric))) {
            return false;
        }

        return true;
    }

    public function message()
    {
        return 'Same Fabrication Is Exists';
    }
}
