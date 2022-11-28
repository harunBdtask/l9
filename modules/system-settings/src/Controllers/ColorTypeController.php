<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Merchandising\Models\BudgetCostingDetails;
use SkylarkSoft\GoRMG\Merchandising\Models\CostingDetails;
use SkylarkSoft\GoRMG\SystemSettings\Models\ColorType;
use SkylarkSoft\GoRMG\SystemSettings\Requests\ColorTypeRequest;

class ColorTypeController extends Controller
{
    public function index()
    {
        $colorTypes = ColorType::with('factory')->orderBy('id', 'desc')->paginate();

        return view('system-settings::color-types.list', compact('colorTypes'));
    }

    public function store(ColorTypeRequest $request)
    {
        try {
            ColorType::create($request->all());
            Session::flash('success', 'Data Created Successfully');
        } catch (\Exception $exception) {
            Session::flash('error', 'Something Went Wrong');
        }

        return redirect('color-types');
    }

    public function show($id)
    {
        return ColorType::findOrFail($id);
    }

    public function update($id, ColorTypeRequest $request)
    {
        try {
            ColorType::findOrFail($id)->update($request->all());
            Session::flash('success', 'Data Updated Successfully');
        } catch (\Exception $exception) {
            Session::flash('error', 'Something Went Wrong');
        }

        return redirect('color-types');
    }

    public function delete($id)
    {
        $costingDetails = CostingDetails::query()->where('type', 'fabric_costing')->get();
        $colorTypeIdPQ = collect($costingDetails)->pluck('details.details.fabricForm')->flatten(1)->pluck('color_type_id')->map(function ($item) {
            return (int) $item;
        })->unique()->values();

        $budgetCostings = BudgetCostingDetails::query()->where('type', 'fabric_costing')->get();
        $colorTypeIdBudget = collect($budgetCostings)->pluck('details.details.fabricForm')->flatten(1)->pluck('color_type_id')->map(function ($item) {
            return (int) $item;
        })->unique()->values();


        if (! (collect($colorTypeIdBudget)->contains($id) || collect($colorTypeIdPQ)->contains($id))) {
            ColorType::find($id)->delete();
            Session::flash('error', 'Data deleted successfully');
        } else {
            Session::flash('error', 'Can Not be Deleted ! It is currently associated with Others');
        }

        return redirect('color-types');
    }

    public function search(Request $request)
    {
        $search = $request->get('search');
        $colorTypes = ColorType::with('factory')
            ->where('color_types', 'like', '%' . $search . '%')
            ->orWhere('status', 'like', '%' . $search . '%')
            ->orderBy('id', 'desc')
            ->paginate();

        return view('system-settings::color-types.list', compact('colorTypes', 'search'));
    }
}
