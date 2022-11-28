<?php

namespace SkylarkSoft\GoRMG\DyesStore\DTOs;

use Illuminate\Support\Collection;
use SkylarkSoft\GoRMG\DyesStore\Models\DyesChemicalTransaction;

class StockSummaryDTO
{
    private $date;
    private $dyesChemicalTransaction;

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date): self
    {
        $this->date = $date;
        return $this;
    }

    private function getDyesTransactions()
    {
        if (!$this->dyesChemicalTransaction) {
            $this->dyesChemicalTransaction = DyesChemicalTransaction::query()
                ->with('item.uomDetails')
                ->selectRaw('*,SUM(qty) AS total_qty')
                ->groupBy([
                    'trn_date',
                    'item_id',
                    'trn_type'
                ])
                ->get();
        }

        return $this->dyesChemicalTransaction;
    }

    private function getItems(): Collection
    {
        return collect($this->getDyesTransactions())->pluck('item', 'item_id');
    }

    private function getItemReceiveTransactions($item_id): Collection
    {
        return collect($this->getDyesTransactions())->where('item_id', $item_id)->where('trn_type', 'in');
    }

    private function getItemIssueTransactions($item_id): Collection
    {
        return collect($this->getDyesTransactions())->where('item_id', $item_id)->where('trn_type', 'out');
    }

    private function totalReceiveTransactions($item_id)
    {
        return $this->getItemReceiveTransactions($item_id)->sum('qty');
    }

    private function totalIssueTransactions($item_id)
    {
        return $this->getItemIssueTransactions($item_id)->sum('qty');
    }

    private function todayReceiveTransactions($item_id)
    {
        return $this->getItemReceiveTransactions($item_id)->where('trn_date', $this->getDate())->sum('qty');
    }

    private function todayIssueTransactions($item_id)
    {
        return $this->getItemIssueTransactions($item_id)->where('trn_date', $this->getDate())->sum('qty');
    }

    private function prevStock($item_id)
    {
        return (($this->totalReceiveTransactions($item_id) - $this->todayReceiveTransactions($item_id)) + $this->todayReceiveTransactions($item_id))
            - ($this->totalIssueTransactions($item_id) - $this->todayIssueTransactions($item_id));
    }

    public function reportData(): array
    {
        $transactionReport = [];
        foreach ($this->getItems() as $item_id => $item) {
            $transactionReport[] = [
                'item_id' => $item_id,
                'item_name' => $item->name,
                'description' => $item->description,
                'prev_stock' => $this->prevStock($item_id),
                'today_receive' => $this->todayReceiveTransactions($item_id),
                'today_issue' => $this->todayIssueTransactions($item_id),
                'balance' => $this->totalReceiveTransactions($item_id) - $this->totalIssueTransactions($item_id),
                'uom' => $item->uomDetails->name,
            ];
        }

        return $transactionReport;
    }
}
