<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\BasicFinance\Models\AccountType;
use SkylarkSoft\GoRMG\BasicFinance\Requests\AccountTypeFormRequest;

class AccountTypeController extends Controller
{

    public function store(AccountTypeFormRequest $request, AccountType $accountType): JsonResponse
    {
        try {
            $accountType->fill($request->all())->save();

            return response()->json([
                'message' => 'Account Type created successfully',
                'data' => [
                    'id' => $accountType->id,
                    'text' => $accountType->account_type,
                    'short_form' => $accountType->short_form,
                ],
                'status' => Response::HTTP_CREATED,
            ], Response::HTTP_CREATED);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
