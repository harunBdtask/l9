<?php

namespace SkylarkSoft\GoRMG\Merchandising\Actions;

use Illuminate\Http\Request;

class StyleEntryAction
{
    public function execute($model, $relativeKey, Request $request)
    {
        $model->styleEntry()->updateOrCreate([
            $relativeKey => $model->id
        ], [
            'pcs_per_carton' => $request->input('pcs_per_carton'),
            'cbm_per_carton' => $request->input('cbm_per_carton')
        ]);
    }
}
