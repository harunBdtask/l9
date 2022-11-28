<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Controllers\API;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use SkylarkSoft\GoRMG\BasicFinance\Models\Account;
use Symfony\Component\HttpFoundation\Response;

class AccountsAPIControllerV2 extends Controller
{
    public function fetchData()
    {
        try {
            $data = collect($this->getAccounts())->groupBy('type');
            $status = Response::HTTP_OK;
        } catch (Exception $e) {
            $status = Response::HTTP_INTERNAL_SERVER_ERROR;
            $error = $e->getMessage();
        }
        return $data;
//        return response()->json([
//            'data' => $data ?? [],
//            'error' => $error ?? null,
//        ], $status);
    }

    private function getDataWithSpaceLevel()
    {
        $data = $this->fetchData();

    }

    private function getParentAccountData($type_id, $id = null): Collection
    {
        return Account::query()
            ->with('parentAc')
            ->where('type_id', $type_id)
            ->when($id, function ($query) use ($id) {
                $query->where('id', $id);
            })
            ->get()
            ->map(function ($account, $key) use ($type_id) {
                return [
                    'id' => $account->id,
                    'type' => Account::$types[$account->type_id],
                    'name' => $account->name,
                    'code' => $account->code,
                    'particulars' => $account->particulars,
                    'type_id' => $account->type_id,
                    'parent_ac' => $account->parent_ac,
                    'is_editable' => $account->is_editable,
                    'is_transactional' => $account->is_transactional,
                    'is_active' => $account->is_active,
                    'created_by' => $account->created_by,
                    'updated_by' => $account->updated_by,
                    'factory_id' => $account->factory_id,
                    'created_at' => $account->created_at,
                    'updated_at' => $account->updated_at,
                    'parent' => $account->parent_ac && $account->type_id ? $this->getParentAccountData($account->type_id, $account->parent_ac) : [],
                ];
            });
    }

    private function getAccounts(): Collection
    {
        return Account::query()->where('parent_ac', '!=', null)
            ->where('code', 'LIKE', '%000')
            ->where('is_active', 1)
            ->get()->map(function ($account) {
                return [
                    'id' => $account->id,
                    'type' => Account::$types[$account->type_id],
                    'name' => $account->name,
                    'code' => $account->code,
                    'particulars' => $account->particulars,
                    'type_id' => $account->type_id,
                    'parent_ac' => $account->parent_ac,
                    'is_editable' => $account->is_editable,
                    'is_transactional' => $account->is_transactional,
                    'is_active' => $account->is_active,
                    'created_by' => $account->created_by,
                    'updated_by' => $account->updated_by,
                    'factory_id' => $account->factory_id,
                    'created_at' => $account->created_at,
                    'updated_at' => $account->updated_at,
                    'parent' => $account->parent_ac && $account->type_id ? $this->getParentAccountData($account->type_id, $account->parent_ac) : [],
                ];
            });

    }
}
