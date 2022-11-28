<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Services\Reports\TrialBalance;

interface TrialBalanceReportInterface
{
    public function formatView();

    public function reportData();

    public function openingBalanceCalculate(array $data): array;

    public function transactionBalanceCalculate(array $data): array;
}
