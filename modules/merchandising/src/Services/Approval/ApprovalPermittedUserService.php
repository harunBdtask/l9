<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services\Approval;

use Illuminate\Support\Collection;
use SkylarkSoft\GoRMG\Approval\Models\Approval;
use SkylarkSoft\GoRMG\Merchandising\Models\Budget;
use SkylarkSoft\GoRMG\SystemSettings\Models\MerchandisingVariableSettings;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;

class ApprovalPermittedUserService
{
    private $for;
    private $step;
    private $buyer_id;
    private $approvalType;
    private $allBuyerType;

    private function __construct($for)
    {
        $this->for = $for;
    }

    public static function for($for): ApprovalPermittedUserService
    {
        return new static($for);
    }

    public function setBuyer($id): ApprovalPermittedUserService
    {
        $this->buyer_id = $id;
        return $this;
    }

    public function getBuyer()
    {
        return $this->buyer_id;
    }

    public function setApprovalType($approvalType): ApprovalPermittedUserService
    {
        $this->approvalType = $approvalType;
        return $this;
    }

    public function getApprovalType()
    {
        return $this->approvalType;
    }

    public function setStep($step): ApprovalPermittedUserService
    {
        $this->step = $step;
        return $this;
    }

    public function getStep()
    {
        return $this->step;
    }

    public function get(): Collection
    {
        $approval = Approval::query()->with('user')
            ->where('page_name', $this->for)
            ->orderBy('priority', 'asc')
            ->get();

        $usersId = $approval->filter(function ($data, $key) {
            if (!$this->getBuyer()) {
                return $key == ($this->getApprovalType() == 'approve' ? $this->getStep() : $this->getStep() - 1);
            } else {
                $buyers = explode(',', $data->buyer_ids);
                return in_array($this->getBuyer(), $buyers) && $key == ($this->getApprovalType() == 'approve' ? $this->getStep() : $this->getStep() - 1);
            }
        });

        $user = $usersId->pluck('user_id');
        $alterUser = $usersId->pluck('alternative_user_id')
            ->merge($user)
            ->unique()
            ->toArray();

        return User::query()->whereIn('id', $alterUser)->get();
    }
}
