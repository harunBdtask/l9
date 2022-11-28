<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\SystemSettings\Models\HiddenFields;
use SkylarkSoft\GoRMG\SystemSettings\Services\PageService;

class HiddenFieldsController extends Controller
{
    public function pages(): JsonResponse
    {
        return response()->json(PageService::pages());
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'page'   => 'required',
            'fields' => 'nullable|array',
        ]);

        $page = $request->input('page');
        $fields = $request->input('fields');
        $factoryId = factoryId();

        $hiddenFieldsPage = $this->getPage($page, $factoryId);

        if ( !$hiddenFieldsPage ) {
            (new HiddenFields(['page' => $page, 'fields' => $fields, 'factory_id' => $factoryId]))->save();
            return response()->json(['message' => 'Successfully Saved']);
        }


        $hiddenFieldsPage->update(['fields' => $fields]);
        return response()->json(['message' => 'Successfully Updated!']);
    }

    public function getHiddenFields(): JsonResponse
    {
        $page = request('page');

        if ( !$page ) {
            return response()->json([]);
        }

        $hiddenFieldsPage = $this->getPage($page, factoryId());

        if ( !$hiddenFieldsPage ) {
            return response()->json([]);
        }

        return response()->json($hiddenFieldsPage->fields);
    }

    public function show(): JsonResponse
    {
        $page = request('page');

        $data = HiddenFields::query()
            ->where('page', $page)
            ->where('factory_id', factoryId())
            ->firstOrFail();

        return response()->json($data);
    }

    private function getPage($page, $factoryId = null)
    {
        $factoryId = $factoryId ?? factoryId();

        return HiddenFields::query()
            ->where('page', $page)
            ->where('factory_id', $factoryId)
            ->first();
    }
}