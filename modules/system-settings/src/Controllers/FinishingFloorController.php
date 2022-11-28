<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\FinishingFloor;
use SkylarkSoft\GoRMG\SystemSettings\Models\FinishingTable;

class FinishingFloorController extends Controller
{
    public function index(Request $request)
    {
        $factories = Factory::query()->pluck('factory_name', 'id');
        $finishingFloors = FinishingFloor::query()
            ->with([
                'tables', 'factory'
            ])
            ->search($request->get('search'))
            ->orderBy('id', 'DESC')
            ->paginate();
        return view('system-settings::finishing.finishing_floor_list', compact('factories', 'finishingFloors'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'factory_id' => 'required',
            'name' => "required|not_regex:/([^\w\d\s&'.\-\_\)\(\/])+/i|unique:finishing_floors,name,". $request->get('id') . ',id',
        ]);
        (new FinishingFloor())->fill($request->all())->save();
        Session::flash('alert-success', 'Successfully Stored');
        return redirect()->back();
    }

    /**
     * @param FinishingFloor $finishingFloor
     * @return JsonResponse
     */
    public function edit(FinishingFloor $finishingFloor): JsonResponse
    {
        return response()->json($finishingFloor);
    }

    /**
     * @param Request $request
     * @param FinishingFloor $finishingFloor
     * @return RedirectResponse
     */
    public function update(Request $request, FinishingFloor $finishingFloor): RedirectResponse
    {
        $finishingFloor->fill($request->all())->save();
        Session::flash('alert-success', 'Successfully Updated');
        return redirect()->back();
    }

    /**
     * @param FinishingFloor $finishingFloor
     * @return RedirectResponse
     */
    public function destroy(FinishingFloor $finishingFloor): RedirectResponse
    {
        $finishingFloor->delete();
        Session::flash('alert-danger', 'Successfully Deleted');
        return redirect()->back();
    }

    /**
     * @return JsonResponse
     */
    public function getFinishingFloor(): JsonResponse
    {
        $floors = FinishingFloor::query()
            ->where('factory_id', factoryId())
            ->select('id', 'name')
            ->get()
            ->map(function($floor) {
                return [
                    'id' => $floor->id,
                    'name' => $floor->name,
                    'text' => $floor->name,
                ];
            });
        return response()->json($floors);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function getFinishingFactoryFloor($id): JsonResponse
    {
        $floors = FinishingFloor::query()
            ->where('factory_id', $id)
            ->select('id', 'name')
            ->get();
        return response()->json($floors);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function getFinishingFloorTable($id): JsonResponse
    {
        $tables = FinishingTable::query()
            ->where('factory_id', factoryId())
            ->where('floor_id', $id)
            ->select('id', 'name')
            ->get();
        return response()->json($tables);
    }
}
