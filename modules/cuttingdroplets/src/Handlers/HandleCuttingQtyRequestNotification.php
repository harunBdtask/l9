<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Handlers;

use SkylarkSoft\GoRMG\Approval\Models\Approval;
use SkylarkSoft\GoRMG\Cuttingdroplets\Notifications\CuttingQtyRequestNotification;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;

class HandleCuttingQtyRequestNotification
{
    private $cuttingQtyRequest;

    private function __construct($cuttingQtyRequest)
    {
        $this->cuttingQtyRequest = $cuttingQtyRequest;
        return $this;
    }

    public static function for($cuttingQtyRequest): self
    {
        return new static($cuttingQtyRequest);
    }

    private function getApprovalUser()
    {
        $approval = Approval::query()
            ->where([
                'page_name' => Approval::CUTTING_QTY_APPROVAL,
            ])
            ->first();
        if (empty($approval)) {
            return User::findOrFail(1);
        }
        return User::findOrFail($approval->user_id);
    }

    public function notify()
    {
        $this->getApprovalUser()
            ->notify(new CuttingQtyRequestNotification($this->cuttingQtyRequest));
    }
}
