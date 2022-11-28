<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Controllers\API;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use SkylarkSoft\GoRMG\BasicFinance\Models\Account;
use Symfony\Component\HttpFoundation\Response;

class AccountsAPIController extends Controller
{
    public function fetchData(Request $request): JsonResponse
    {
        try {
            $data = $this->getAccountTypes();
            $status = Response::HTTP_OK;
        } catch (Exception $e) {
            $status = Response::HTTP_INTERNAL_SERVER_ERROR;
            $error = $e->getMessage();
        }

        return response()->json([
            'data' => $data ?? [],
            'error' => $error ?? null,
        ], $status);
    }

    private function getAccountTypeWiseData($type_id, $id = null): Collection
    {
        return Account::query()
            ->with('parentAc')
            ->where('type_id', $type_id)
            ->when($id, function ($query) use ($id) {
                $query->where('parent_ac', $id);
            })
            ->when(!$id, function ($query) {
                $query->whereNull('parent_ac');
            })
            ->get()
            ->map(function ($account, $key) use ($type_id, $id) {
                return [
                    'id' => $account->id,
                    'level' => $account->name,
                    'type' => Account::$types[$account->type_id],
                    'name' => $account->name,
                    'code' => $account->code,
                    'particulars' => $account->particulars,
                    'type_id' => $account->type_id,
                    'parent_ac' => $account->parent_ac,
                    'parent_ac_value' => optional($account->parentAc)->name,
                    'is_editable' => $account->is_editable,
                    'is_transactional' => $account->is_transactional,
                    'is_active' => $account->is_active,
                    'created_by' => $account->created_by,
                    'updated_by' => $account->updated_by,
                    'factory_id' => $account->factory_id,
                    'created_at' => $account->created_at,
                    'updated_at' => $account->updated_at,
                    'children' => $account->id && $account->type_id ? $this->getAccountTypeWiseData($account->type_id, $account->id) : [],
                    'has_children' => $account->childAcs->count()
                ];
            });
    }

    private function getAccountTypes(): Collection
    {
        return collect(Account::$types)
            ->map(function ($level, $type_id) {
                $has_children = count($this->getAccountTypeWiseData($type_id));
                return [
                    'id' => null,
                    'level' => $level,
                    'type' => $level,
                    'name' => null,
                    'code' => null,
                    'particulars' => null,
                    'type_id' => $type_id,
                    'parent_ac' => null,
                    'parent_ac_value' => null,
                    'is_editable' => 0,
                    'is_transactional' => 0,
                    'is_active' => 1,
                    'created_by' => null,
                    'updated_by' => null,
                    'created_at' => null,
                    'updated_at' => null,
                    'factory_id' => factoryId(),
                    'children' => $this->getAccountTypeWiseData($type_id),
                    'has_children' => $has_children > 0 ? $has_children : 1
                ];
            })->values();
    }
}
