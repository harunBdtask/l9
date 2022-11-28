<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use SkylarkSoft\GoRMG\BasicFinance\Models\Account;

class AccountCodeApiController extends Controller
{

    /**
     * @param Request $request
     * @param $typeId
     * @return string|null
     */
    public function __invoke(Request $request, $typeId): ?string
    {
        $parentAccountId = $request->get('parent_account_id') ?? null;

        $parentAccountCode = $this->parentAccount($parentAccountId)
            ? rtrim($this->parentAccount($parentAccountId)->code,0)
            : null;
        $codeLength = Account::ACCOUNT_CODE_SEPARATOR[strlen($parentAccountCode)] ?? null;
        $parentAccountCode = str_pad($parentAccountCode, $codeLength, 0, STR_PAD_RIGHT);
        $totalAccounts = $this->previousAccounts($typeId, $parentAccountId) + 1;

        if ($parentAccountCode) {
            if (strlen($parentAccountCode) < 4) {
                $totalAccounts = str_pad($totalAccounts, 2, 0, STR_PAD_LEFT);
            } else {
                $totalAccounts = str_pad($totalAccounts, 3, 0, STR_PAD_LEFT);
            }

            $code = $parentAccountCode . $totalAccounts;
        } else {
            $code = $typeId . $totalAccounts;
        }

        $lastAccountCode = rtrim($this->previousLatestAccount($typeId, $parentAccountId), 0);

        if ($code <= $lastAccountCode) {
            $code = ++$lastAccountCode;
        }

        return str_pad($code, 13, 0, STR_PAD_RIGHT);
    }

    /**
     * @param $parentAccountId
     * @return Builder|Model|object|null
     */
    public function parentAccount($parentAccountId)
    {
        return Account::query()->where('id', $parentAccountId)->first();
    }

    /**
     * @param $typeId
     * @param $parentAccountId
     * @return int
     */
    public function previousAccounts($typeId, $parentAccountId): int
    {
        return Account::query()->where('type_id', $typeId)
            ->when($parentAccountId, function (Builder $builder) use ($parentAccountId) {
                $builder->where('parent_ac', $parentAccountId);
            })->when($parentAccountId == null, function (Builder $builder) {
                $builder->whereNull('parent_ac');
            })->latest()->count();
    }

    public function previousLatestAccount($typeId, $parentAccountId)
    {
        return Account::query()->where('type_id', $typeId)
            ->when($parentAccountId, function (Builder $builder) use ($parentAccountId) {
                $builder->where('parent_ac', $parentAccountId);
            })->when($parentAccountId == null, function (Builder $builder) {
                $builder->whereNull('parent_ac');
            })->orderByDesc('code')->first()['code'] ?? 0;
    }
}
