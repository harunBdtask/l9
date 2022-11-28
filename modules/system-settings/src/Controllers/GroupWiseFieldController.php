<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\GroupWiseField;
use SkylarkSoft\GoRMG\SystemSettings\Models\ItemGroup;
use SkylarkSoft\GoRMG\SystemSettings\Services\FieldsService;
use Throwable;

class GroupWiseFieldController extends Controller
{
    public function index()
    {
        $fields = FieldsService::getAllFields();
        $groupWiseFields = GroupWiseField::query()->paginate();
        $itemGroups = ItemGroup::query()->pluck('item_group', 'id')->prepend('Select', 0);

        return view('system-settings::group_wise_fields.index', [
            'fields' => $fields,
            'groupWiseFields' => $groupWiseFields,
            'itemGroups' => $itemGroups,
        ]);
    }

    /**
     * @param Request $request
     * @param GroupWiseField $groupWiseField
     * @return RedirectResponse
     * @throws Throwable
     */
    public function store(Request $request, GroupWiseField $groupWiseField): RedirectResponse
    {
        $request->validate([
            'group_name' => "required|not_regex:/([^\w\d\s&'])+/i",
            'fields' => 'required',
        ]);
        try {
            DB::beginTransaction();
            $groupWiseField->fill($request->all())->save();

            Session::flash('alert-success', S_SAVE_MSG);
            DB::commit();
        } catch (Exception $e) {
            Session::flash('alert-danger', $e->getMessage());
        } finally {
            return redirect('/group-wise-fields');
        }
    }

    public function edit(GroupWiseField $groupWiseField)
    {
        $fields = FieldsService::getAllFields();
        $groupWiseFields = GroupWiseField::query()->paginate();
        $itemGroups = ItemGroup::query()->pluck('item_group', 'id')->prepend('Select', 0);

        return view('system-settings::group_wise_fields.index', [
            'fields' => $fields,
            'groupWiseFields' => $groupWiseFields,
            'groupWiseFieldData' => $groupWiseField,
            'itemGroups' => $itemGroups,
        ]);
    }

    /**
     * @param GroupWiseField $groupWiseField
     * @return RedirectResponse
     * @throws Throwable
     */
    public function destroy(GroupWiseField $groupWiseField): RedirectResponse
    {
        DB::beginTransaction();
        $groupWiseField->delete();
        DB::commit();
        Session::flash('alert-success', S_DELETE_MSG);
        return redirect()->back();
    }
}
