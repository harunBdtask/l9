<?php

namespace SkylarkSoft\GoRMG\Subcontract\Services\SubTextileServices\States\BatchDetailsStates;

use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Subcontract\Models\Libraries\SubcontractVariableSetting;

class BatchDetailsState
{
    public function setState(Request $request): BatchDetailsContract
    {
        if ($this->variableCheck($request->input('factory_id'))) {
            return new ReceiveBasis();
        }

        return new IssueBasis();
    }

    public function variableCheck($factoryId): bool
    {
        $setting = SubcontractVariableSetting::query()
            ->where('factory_id', $factoryId)
            ->first();

        if (isset($setting) &&
            $setting->variable_details['batch_creation'] === SubcontractVariableSetting::RECEIVE_BASIS) {
            return true;
        }

        return false;
    }
}
