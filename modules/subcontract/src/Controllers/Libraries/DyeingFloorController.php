<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\Libraries;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Subcontract\Models\Libraries\DyeingFloor;
use SkylarkSoft\GoRMG\Subcontract\PackageConst;
use SkylarkSoft\GoRMG\Subcontract\Requests\Libraries\DyeingFloorFormRequest;

class DyeingFloorController extends Controller
{
    protected function dyeingFloors(Request $request): LengthAwarePaginator
    {
        return DyeingFloor::query()
            ->when($request->get('floor_type_filter'), function ($query) use ($request) {
                $query->where('type', "{$request->get('floor_type_filter')}");
            })
            ->when($request->get('name_filter'), function ($query) use ($request) {
                $query->where('name', 'LIKE', "%{$request->get('name_filter')}%");
            })
            ->when($request->get('attention_filter'), function ($query) use ($request) {
                $query->where('attention', 'LIKE', "%{$request->get('attention_filter')}%");
            })->paginate();
    }

    public function index(Request $request)
    {
        $dyeingFloors = $this->dyeingFloors($request);
        $floorTypes = collect(DyeingFloor::FLOOR_TYPES)->prepend('Select', 0);

        return view(PackageConst::VIEW_PATH.'libraries.dyeing_floors', [
            'dyeingFloor' => null,
            'dyeingFloors' => $dyeingFloors,
            'floorTypes' => $floorTypes,
        ]);
    }

    public function store(DyeingFloorFormRequest $request, DyeingFloor $dyeingFloor): RedirectResponse
    {
        try {
            $dyeingFloor->fill($request->all())->save();
        } catch (Exception $exception) {
            Session::flash('alert-danger', $exception->getMessage());
        } finally {
            return redirect()->route('dyeing-floor.index');
        }
    }

    public function edit(Request $request, DyeingFloor $dyeingFloor)
    {
        try {
            $dyeingFloors = $this->dyeingFloors($request);
            $floorTypes = collect(DyeingFloor::FLOOR_TYPES)->prepend('Select', 0);

            return view(PackageConst::VIEW_PATH.'libraries.dyeing_floors', [
                'dyeingFloor' => $dyeingFloor,
                'dyeingFloors' => $dyeingFloors,
                'floorTypes' => $floorTypes,
            ]);
        } catch (Exception $exception) {
            Session::flash('alert-danger', $exception->getMessage());

            return back();
        }
    }

    public function update(DyeingFloorFormRequest $request, DyeingFloor $dyeingFloor): RedirectResponse
    {
        try {
            $dyeingFloor->fill($request->all())->save();
        } catch (Exception $exception) {
            Session::flash('alert-danger', $exception->getMessage());
        } finally {
            return redirect()->route('dyeing-floor.index');
        }
    }

    public function destroy(DyeingFloor $dyeingFloor): RedirectResponse
    {
        try {
            $dyeingFloor->delete();
        } catch (Exception $exception) {
            Session::flash('alert-danger', $exception->getMessage());
        } finally {
            return back();
        }
    }
}
