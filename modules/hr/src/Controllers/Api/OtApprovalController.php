<?php

namespace SkylarkSoft\GoRMG\HR\Controllers\Api;

use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\HR\ApiResponse;
use SkylarkSoft\GoRMG\HR\Repositories\OtApprovalRepository;
use SkylarkSoft\GoRMG\HR\Requests\OtApprovalRequest;
use SkylarkSoft\GoRMG\HR\Resources\OtApprovalResource;

class OtApprovalController
{
    public function index()
    {
        $otApprovalRepository = new OtApprovalRepository();
        $apiResponse = new ApiResponse($otApprovalRepository->paginate(), OtApprovalResource::class);
        return $apiResponse->getResponse();
    }

    public function store(OtApprovalRequest $request)
    {
        $otApprovalRepository = new OtApprovalRepository();
        $apiResponse = new ApiResponse($otApprovalRepository->store($request), OtApprovalResource::class);
        return $apiResponse->getResponse();
    }
}
