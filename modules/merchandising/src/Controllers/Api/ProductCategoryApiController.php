<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers\Api;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\SystemSettings\Models\ProductCateory;

class ProductCategoryApiController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $productCategories = ProductCateory::query()->get()->map(function ($category) {
           return [
               'id' => $category->id,
               'text' => $category->category_name,
           ];
        });

        return response()->json($productCategories, Response::HTTP_OK);
    }
}
