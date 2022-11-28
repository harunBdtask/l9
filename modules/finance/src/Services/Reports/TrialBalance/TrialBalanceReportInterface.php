<?php

namespace SkylarkSoft\GoRMG\Finance\Services\Reports\TrialBalance;

interface TrialBalanceReportInterface
{
    public function formatView();

    public function reportData();

    public function openingBalanceCalculate(array $data): array;

    public function transactionBalanceCalculate(array $data): array;
}
