<?php

namespace SkylarkSoft\GoRMG\Finance\Services\AccountCode;

class AccountCodeStrategy
{
    const PARENT = 1, GROUP = 2, CONTROL = 3, LEDGER = 4, SUB_LEDGER = 5;

    protected $type, $accountType, $parentId, $groupId, $controlId, $ledgerId;

    protected $implementors = [
        self::PARENT => ParentCodeGenerate::class,
        self::GROUP => GroupCodeGenerate::class,
        self::CONTROL => ControlCodeGenerate::class,
        self::LEDGER => LedgerCodeGenerate::class,
        self::SUB_LEDGER => SubLedgerCodeGenerate::class,
    ];

    public function setStrategy($accountType): AccountCodeStrategy
    {
        $this->accountType = $accountType;
        return $this;
    }

    public function setType($type): AccountCodeStrategy
    {
        $this->type = $type;
        return $this;
    }

    public function setParentId($parentId): AccountCodeStrategy
    {
        $this->parentId = $parentId;
        return $this;
    }

    public function setGroupId($groupId): AccountCodeStrategy
    {
        $this->groupId = $groupId;
        return $this;
    }

    public function setControlId($controlId): AccountCodeStrategy
    {
        $this->controlId = $controlId;
        return $this;
    }

    public function setLedgerId($ledgerId): AccountCodeStrategy
    {
        $this->ledgerId = $ledgerId;
        return $this;
    }

    public function getControlId()
    {
        return $this->controlId;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getParentId()
    {
        return $this->parentId;
    }

    public function getGroupId()
    {
        return $this->groupId;
    }

    public function getLedgerId()
    {
        return $this->ledgerId;
    }

    public function generate()
    {
        if (!isset($this->implementors[$this->accountType])) {
            return false;
        }
        return (new $this->implementors[$this->accountType])->handle($this);
    }
}
