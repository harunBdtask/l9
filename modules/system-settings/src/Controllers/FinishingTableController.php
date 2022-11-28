<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\FinishingTable;

class FinishingTableController extends Controller
{
    public function index(Request $request)
    {
        $factories = Factory::query()->pluck('factory_name', 'id');
        $finishingTables = FinishingTable::query()
            ->with([
                'floor',
                'factory'
            ])
            ->search($request->get('search'))
            ->orderBy('id', 'DESC')
            ->paginate();
        return view('system-settings::finishing.finishing_table_list',
            compact('factories', 'finishingTables'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'factory_id' => 'required',
            'name' => "required|not_regex:/([^\w\d\s&'.\-\_\)\(\/])+/i|unique:finishing_tables,name,". $request->get('id') . ',id',
        ]);
        (new FinishingTable())->fill($request->all())->save();
        Session::flash('alert-success', 'Successfully Stored');
        return redirect()->back();
    }

    /**
     * @param FinishingTable $finishingTable
     * @return JsonResponse
     */
    public function edit(FinishingTable $finishingTable): JsonResponse
    {
        return response()->json($finishingTable);
    }

    /**
     * @param Request $request
     * @param FinishingTable $finishingTable
     * @return RedirectResponse
     */
    public function update(Request $request, FinishingTable $finishingTable): RedirectResponse
    {
        $finishingTable->fill($request->all())->save();
        Session::flash('alert-success', 'Successfully Updated');
        return redirect()->back();
    }

    /**
     * @param FinishingTable $finishingTable
     * @return RedirectResponse
     */
    public function destroy(FinishingTable $finishingTable): RedirectResponse
    {
        $finishingTable->delete();
        Session::flash('alert-danger', 'Successfully Deleted');
        return redirect()->back();
    }
}
