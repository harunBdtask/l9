<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Services;

use Exception;
use SkylarkSoft\GoRMG\BasicFinance\Models\Account;
use Symfony\Component\HttpFoundation\Response;

class FetchLeafNodesService
{
    protected $data = [];

    protected $account_name, $except_account_name;

    public function __construct($account_name, $except_account_name = null)
    {
        $this->account_name = $account_name;
        $this->except_account_name = $except_account_name;
    }

    public function handle()
    {
        try {
            $account = Account::query()->with('childAcs')
                ->where('name', $this->account_name)
                ->first();
            if ($account && $account->childAcs) {
                $this->fetchLeafNodes($account->childAcs);
            }
            $status = Response::HTTP_OK;
            $message = \SUCCESS_MSG;
        } catch (Exception $e) {
            $status = Response::HTTP_INTERNAL_SERVER_ERROR;
            $message = \SOMETHING_WENT_WRONG;
            $error = $e->getMessage();
        }

        return [
            'data' => $this->data ?? null,
            'status' => $status ?? null,
            'message' => $message ?? null,
            'error' => $error ?? null,
        ];
    }

    private function fetchLeafNodes($childAcs)
    {
        foreach($childAcs as $account) {
            if ($this->except_account_name && !is_array($this->except_account_name) && $account && $account->name == $this->except_account_name) {
                continue;
            }
            if ($this->except_account_name && is_array($this->except_account_name) && $account && in_array($account->name , $this->except_account_name)) {
                continue;
            }

            if ($account && $account->childAcs) {
                $this->fetchLeafNodes($account->childAcs);
            }
            
            if ($account && $account->is_transactional && $account->is_active) {

                $this->data[] = [
                    'id' => $account->id,
                    'text' => $account->name,
                    'name' => $account->name,
                    'code' => $account->code,
                    'bf_account' => $account
                ];
            }
        }
    }
}