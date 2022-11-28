<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\SystemSettings\Models\HiddenFields;
use SkylarkSoft\GoRMG\SystemSettings\Services\PageService;

class HideFieldsVariableController extends Controller
{
    public function menus(): JsonResponse
    {
        return response()->json(PageService::pages());
    }


    public function store(Request $request)
    {
        $request->validate([
            'page' => 'required',
            'fields' => 'required|array|min:1',
        ]);

        $page = $request->input('page');
        $fields = $request->input('fields');
        $factoryId = factoryId();

        $hiddenField = HiddenFields::query()
            ->where('page', $page)
            ->where('factory_id', factoryId());

    }
}