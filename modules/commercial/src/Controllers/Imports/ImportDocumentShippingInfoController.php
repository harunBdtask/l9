<?php


namespace SkylarkSoft\GoRMG\Commercial\Controllers\Imports;

use App\Constants\ApplicationConstant;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Commercial\Models\Imports\ImportDocumentAcceptance;
use SkylarkSoft\GoRMG\Commercial\Models\Imports\ImportDocumentShippingInfo;
use Symfony\Component\HttpFoundation\Response;

class ImportDocumentShippingInfoController extends Controller
{
    public $response = [];
    public $status = 200;

    public function store(ImportDocumentAcceptance $importDocument, Request $request): JsonResponse
    {
        try {
            if ($importDocument->shippingInfo()->exists()) {
                $importDocument->shippingInfo()->update($request->all());
                $this->response['message'] = ApplicationConstant::S_UPDATED;
            } else {
                $importDocument->shippingInfo()->create($request->all());
                $this->response['message'] = ApplicationConstant::S_STORED;
                $this->status = Response::HTTP_CREATED;
            }
        } catch (\Exception $e) {
            $this->response['message'] = ApplicationConstant::SOMETHING_WENT_WRONG;
            $this->status = Response::HTTP_BAD_REQUEST;
        }

        return response()->json($this->response, $this->status);
    }

    public function show(ImportDocumentShippingInfo $documentShippingInfo): JsonResponse
    {
        $this->response = $documentShippingInfo;

        return response()->json($this->response);
    }

    public function delete(ImportDocumentShippingInfo $documentShippingInfo): JsonResponse
    {
        try {
            $documentShippingInfo->delete();
            $this->response['message'] = ApplicationConstant::S_DELETED;
        } catch (\Exception $e) {
            $this->response['message'] = ApplicationConstant::SOMETHING_WENT_WRONG;
            $this->status = Response::HTTP_BAD_REQUEST;
        }

        return response()->json($this->response, $this->status);
    }
}
