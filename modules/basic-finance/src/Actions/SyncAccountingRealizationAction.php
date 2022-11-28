<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Actions;

use SkylarkSoft\GoRMG\BasicFinance\Models\AccountRealization;

class SyncAccountingRealizationAction
{

    public function syncRealization($accountRealizationId)
    {
        $accountRealization = AccountRealization::query()->findOrFail($accountRealizationId);
        $accountRealization->update(['approve_status' => 1]);
    }

}
