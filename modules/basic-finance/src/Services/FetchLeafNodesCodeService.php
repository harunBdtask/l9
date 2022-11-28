<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Services;

use Exception;
use SkylarkSoft\GoRMG\BasicFinance\Models\Account;
use Symfony\Component\HttpFoundation\Response;

class FetchLeafNodesCodeService
{
    protected $data = [];

    protected $account_code, $except_account_code;

    public function __construct($account_code, $except_account_code = null)
    {
        $this->account_code = $account_code;
        $this->except_account_code = $except_account_code;
    }

    public function handle()
    {
        try {
            $account = Account::query()->with('childAcs')
                ->where('code', $this->account_code)
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
            if ($this->except_account_code && !is_array($this->except_account_code) && $account && $account->code == $this->except_account_code) {
                continue;
            }
            if ($this->except_account_code && is_array($this->except_account_code) && $account && in_array($account->code , $this->except_account_code)) {
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