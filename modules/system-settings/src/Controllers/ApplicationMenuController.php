<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use SkylarkSoft\GoRMG\SystemSettings\Models\ApplicationMenuActiveData;
use SkylarkSoft\GoRMG\SystemSettings\Services\ApplicationMenuFormatService;
use Symfony\Component\HttpFoundation\Response;

class ApplicationMenuController extends Controller
{
    public function index(): View
    {
        return view('system-settings::application_menu_activities.index');
    }

    public function fetchData(): JsonResponse
    {
        try {
            $data = ApplicationMenuActiveData::first() ?? null;
            $session_menus = [];
            collect(\session()->get('menu'))
            ->sortBy('priority')
            ->each(function($item, $key) use(&$session_menus) {
                \array_push($session_menus, $item);
            });
            $inactive_menus = ($data && $data->inactive_menus) ? $data->inactive_menus : null;
            (new ApplicationMenuFormatService)->format($session_menus, $inactive_menus);
            $id = $data ? $data->id : null;
            $inactive_urls = $data ? $data->inactive_urls : [];
            $message = \SUCCESS_MSG;
            $status = Response::HTTP_OK;
            $html = view('system-settings::application_menu_activities.form', [
               'menus' => $session_menus 
            ])->render();
        } catch (Exception $e) {
            $error = $e->getMessage();
            $message = \SOMETHING_WENT_WRONG;
            $status = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return \response()->json([
            'html' => $html ?? null,
            'id' => $id ?? null,
            'session_menus' => $session_menus,
            'inactive_urls' => $inactive_urls ?? [],
            'message' => $message ?? null,
            'status' => $status ?? null,
            'error' => $error ?? null,
        ], $status);
    }

    public function store(Request $request)
    {
        $request->validate([
            'inactive_urls' => 'nullable|array',
            'inactive_menus' => 'required|array'
        ]);
        try {
            $data = ApplicationMenuActiveData::findOrNew($request->id ?? null);
            $data->inactive_urls = $request->inactive_urls;
            $data->inactive_menus = $request->inactive_menus;
            $data->save();
            $message = \SUCCESS_MSG;
            $status = Response::HTTP_OK;
            
        } catch (Exception $e) {
            $error = $e->getMessage();
            $message = \SOMETHING_WENT_WRONG;
            $status = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return \response()->json([
            'data' => $data ?? null,
            'message' => $message ?? null,
            'status' => $status ?? null,
            'error' => $error ?? null,
        ], $status);
    }
}