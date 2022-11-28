<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\SystemSettings\Models\Fabric_composition;

class UniqueFabricComposition implements Rule
{
    public function passes($attribute, $value): bool
    {
        $composition_fabric = request()->get('composition_fabric');
        $composition_fabric_id = request()->get('composition_fabric_id');

        if (is_array($composition_fabric)) {
            $has_error = 0;
            foreach ($composition_fabric as $key => $value) {
                if ($composition_fabric[$key] && ! $composition_fabric_id[$key]) {
                    $query = Fabric_composition::where(['yarn_composition' => strtoupper($composition_fabric[$key])]);
                    if (request()->route('id')) {
                        $query = $query->where('id', '!=', request()->route('id'));
                    }
                    $sample = $query->first();
                    $sample ? $has_error++ : '';
                }
            }

            return $has_error > 0 ? false : true;
        } else {
            $query = Fabric_composition::where(['yarn_composition' => strtoupper(request()->yarn_composition)]);
            if (request()->route('id')) {
                $query = $query->where('id', '!=', request()->route('id'));
            }
            $sample = $query->first();

            return $sample ? false : true;
        }
    }

    public function message(): string
    {
        return 'This composition already exists!!';
    }
}
