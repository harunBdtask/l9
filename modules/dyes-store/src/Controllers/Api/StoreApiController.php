<?php

namespace SkylarkSoft\GoRMG\DyesStore\Controllers\Api;

use SkylarkSoft\GoRMG\DyesStore\Controllers\InventoryBaseController;
use SkylarkSoft\GoRMG\DyesStore\Models\DsStoreModel;
use Symfony\Component\HttpFoundation\Response;

class StoreApiController extends InventoryBaseController
{
    public function __invoke(): \Illuminate\Http\JsonResponse
    {
        try {
            $stores = DsStoreModel::all();

            return $this->jsonResponse($stores, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return $this->jsonResponse($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
