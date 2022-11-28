<?php

namespace SkylarkSoft\GoRMG\Finance\Services\Reports\TrialBalance;

class TrialBalanceReportStrategy
{
    private $type;
    private $data;
    private $groupId;

    private $bindings = [
        'account_type' => AccountWiseTrialBalance::class,
        'parent_account' => ParentAccountWiseTrialBalance::class,
        'group_account' => GroupAccountWiseTrialBalance::class,
        'control_account' => ControlAccountWiseTrialBalance::class,
        'ledger_account' => LedgerAccountWiseTrialBalance::class,
    ];

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type): TrialBalanceReportStrategy
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data): TrialBalanceReportStrategy
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getGroupId()
    {
        return $this->groupId;
    }

    /**
     * @param mixed $groupId
     */
    public function setGroupId($groupId): TrialBalanceReportStrategy
    {
        $this->groupId = $groupId;
        return $this;
    }

    public function generateReport()
    {
        if (!isset($this->bindings[$this->getGroupId()])) {
            return false;
        }
        return (new $this->bindings[$this->getGroupId()]($this->getData()))->formatView();
    }
}
