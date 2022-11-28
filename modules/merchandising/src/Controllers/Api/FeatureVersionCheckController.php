<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers\Api;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use SkylarkSoft\GoRMG\Merchandising\Models\FeatureVersion;
use Symfony\Component\HttpFoundation\Response;

class FeatureVersionCheckController extends Controller
{

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $checkBy = $request->input('check_by');
            $checkFor = $request->input('check_for');
            $checkById = $request->input('check_by_id');
            $checkForId = $request->input('check_for_id');

            $checkByVersion = FeatureVersion::query()->where([
                'feature_name' => $checkBy,
                'feature_id' => $checkById
            ])->firstOr(function () {
                return ['version' => 0];
            });

            $checkForVersion = FeatureVersion::query()->where([
                'feature_name' => $checkFor,
                'feature_id' => $checkForId
            ])->firstOr(function () {
                return ['version' => 0];
            });

            $checkByMsg = Str::ucfirst(Str::replace('_', ' ', $checkBy));
            $checkForMsg = Str::ucfirst(Str::replace('_', ' ', $checkFor));
            $response = [
                'message' => "{$checkByMsg} version is {$checkByVersion['version']} and {$checkForMsg} version is {$checkForVersion['version']}",
                'version_equality' => $checkByVersion['version'] > $checkForVersion['version']
            ];
            return response()->json($response, Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
