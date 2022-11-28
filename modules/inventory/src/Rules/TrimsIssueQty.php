<?php


namespace SkylarkSoft\GoRMG\Inventory\Rules;


use SkylarkSoft\GoRMG\Inventory\Models\TrimsIssueDetail;

class TrimsIssueQty extends StockQtyRule
{

    public function passes($attribute, $value): bool
    {
        $this->setValues($attribute, $value);
        $passed = true;

        $balance = $this->summary->balance;
        $totalIssueQty = $this->summary->issue_qty;
        $totalIssueReturnQty = $this->summary->issue_return_qty;


        /* issue qty edit */
        if ( !$this->id ) {
            $this->setMessage('Insufficient balance!');
            return $value <= $balance;
        }

        /* Issue QTY update */
        $issue = $this->getItemIssueForStyle();

        /* Issue Qty Increasing */
        if ( $value > $issue->issue_qty ) {
            $this->message = 'Insufficient Balance!';
            $passed = $value - $issue->issue_qty <= $balance;
        }

        /* Issue Qty Decreasing */
        if ( $value < $issue->issue_qty ) {
            $passed = $totalIssueQty - ($issue->issue_qty - $value) >= $totalIssueReturnQty;
        }

        return $passed;
    }

    private function getItemIssueForStyle()
    {
        return TrimsIssueDetail::find($this->id);
    }

    public function message()
    {
        return $this->message;
    }
}
