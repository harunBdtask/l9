<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\SystemSettings\Models\UserWiseBuyerPermission;
use Symfony\Component\HttpFoundation\Response;

class UserWiseBuyerApiController extends Controller
{
    public function __invoke($userId): JsonResponse
    {
        try {
            $userWiseBuyers = UserWiseBuyerPermission::query()
                ->whereHas('buyer')
                ->with('buyer')
                ->where('user_id', $userId)
                ->get()
                ->map(function ($collection) {
                    return [
                        'id' => $collection->buyer->id,
                        'text' => $collection->buyer->name,
                        'user_id' => $collection->user_id
                    ];
                });
            return response()->json($userWiseBuyers, Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
