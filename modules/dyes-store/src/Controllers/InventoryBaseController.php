<?php


namespace SkylarkSoft\GoRMG\DyesStore\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

class InventoryBaseController extends Controller
{

    public function alert($type, $message = 'Something Went Wrong!')
    {
        $type = 'alert-' . $type;
        session()->flash($type, $message);
    }

    public function redirectBackWith($validator): RedirectResponse
    {
        return \Redirect::back()
            ->with($validator)
            ->withInput();
    }

    protected function redirectBack(): RedirectResponse
    {
        return redirect()->back();
    }

    public function jsonResponse($data, $status = 200, array $headers = [], $options = 0): JsonResponse
    {
        return response()->json($data, $status, $headers, $options);
    }

    public function redirect($to = null, $type = 'u'): RedirectResponse
    {
        if ($type == 'u') {
            return Redirect::to($to);
        }
        if ($type == 'r') {
            return Redirect::route($to);
        }
        return Redirect::back();
    }
}
