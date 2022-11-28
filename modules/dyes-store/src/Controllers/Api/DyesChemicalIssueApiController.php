<?php

namespace SkylarkSoft\GoRMG\DyesStore\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\DyesStore\Models\DyesChemicalsIssue;
use Symfony\Component\HttpFoundation\Response;

class DyesChemicalIssueApiController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $issue = DyesChemicalsIssue::query()->where('readonly',0)->get();
        return response()->json($issue, Response::HTTP_OK);
    }
}
