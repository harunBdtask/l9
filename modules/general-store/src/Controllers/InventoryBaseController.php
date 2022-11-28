<?php


namespace SkylarkSoft\GoRMG\GeneralStore\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

class InventoryBaseController extends Controller
{

    public function alert($type, $message = 'Something Went Wrong!')
    {
        $type = 'alert-' . $type;
        session()->flash($type, $message);
    }

    public function redirectBackWith($validator)
    {
        return \Redirect::back()
            ->with($validator)
            ->withInput();
    }

    protected function redirectBack()
    {
        return redirect()->back();
    }

    public function jsonResponse($data, $status = 200, array $headers = [], $options = 0)
    {
        return response()->json($data, $status, $headers, $options);
    }

    public function redirect($to = null, $type = 'u')
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
