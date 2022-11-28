<?php

namespace SkylarkSoft\GoRMG\Approval\Services;

use SkylarkSoft\GoRMG\Approval\Models\Approval;

abstract class PriorityService
{
    private $pageName;
    private $buyers;
    private $request;

    protected const UNAPPROVED = 1;
    protected const APPROVED = 2;
    protected const NO = 0;
    protected const YES = 1;

    private function __construct($pageName)
    {
        $this->pageName = $pageName;
    }

    public static function for($pageName): PriorityService
    {
        return new static($pageName);
    }

    public function setRequest($request): PriorityService
    {
        $this->request = $request;
        return $this;
    }

    public function setBuyer($buyers): PriorityService
    {
        $this->buyers = $buyers;
        return $this;
    }

    public function getBuyerList()
    {
        $priority = $this->getLastPriority() ?? null;
        return $priority ? explode(',', $priority['buyer_ids']) : [];
    }

    public function getBuyer()
    {
        return $this->buyers;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function getPriority()
    {
        $buyers = $this->buyers;

        return Approval::query()
            ->where([
                'factory_id' => factoryId(),
                'user_id' => userId(),
                'page_name' => $this->pageName])
            ->when($buyers, function ($query) use ($buyers) {
                return $query->whereRaw('FIND_IN_SET(?,buyer_ids)', [$buyers]);
            })
            ->get();
    }

    public function getLastPriority()
    {
        return $this->getPriority()->last();
    }

    public function getStep(): int
    {
        return $this->getLastPriority()->priority ?? 0;
    }

    public function sortedPriority(): array
    {
        $buyerIDs = $this->getBuyerList();

        $priority = Approval::query()
            ->where([
                'factory_id' => factoryId(),
                'page_name' => $this->pageName])
            ->when(count($buyerIDs) > 0, function ($query) use($buyerIDs){
                return $query->whereRaw('FIND_IN_SET(?,buyer_ids)', [$buyerIDs]);
            })
            ->get();

        $priorityList = collect($priority)->pluck('priority')->unique()->toArray();
        sort($priorityList);
        return $priorityList;
    }

    public function getPreviousStep()
    {
        $sortedPriority = $this->sortedPriority();
        $stepIndex = array_search($this->getStep(), $sortedPriority);
        return array_key_exists($stepIndex - 1, $sortedPriority) ? $sortedPriority[$stepIndex - 1] : 0;
    }

    public function lastStep()
    {
        $sortedPriority = $this->sortedPriority();

        if (count($sortedPriority) > 0) {
            $lastPriorityIndex = array_key_last($sortedPriority);
            return $sortedPriority[$lastPriorityIndex];
        }

        return [];
    }

    abstract public function response();

    abstract public function store();

    abstract public function storeDetail($data);

    abstract public function getUnapprovedData();
}
