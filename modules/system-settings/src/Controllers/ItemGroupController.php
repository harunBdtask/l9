<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Finance\Models\Account;
use SkylarkSoft\GoRMG\SystemSettings\Models\Item;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\ItemGroup;
use SkylarkSoft\GoRMG\SystemSettings\Models\ItemSubgroup;
use SkylarkSoft\GoRMG\Merchandising\Models\CostingDetails;
use SkylarkSoft\GoRMG\SystemSettings\Models\GroupWiseField;
use SkylarkSoft\GoRMG\SystemSettings\Models\UnitOfMeasurement;
use SkylarkSoft\GoRMG\SystemSettings\Requests\ItemGroupRequest;
use SkylarkSoft\GoRMG\Merchandising\Models\BudgetCostingDetails;
use Symfony\Component\HttpFoundation\Response;

class ItemGroupController extends Controller
{
    public function index(Request $request)
    {
        $searchKey = $request->get('q') ?? null;

        if (Auth::user()->role_id == 1) {
            $itemGroups = ItemGroup::query()
                ->with([
                    'factory',
                    'item',
                    'orderUOM:id,unit_of_measurement',
                    'consUOM:id,unit_of_measurement'
                ])
                ->search($searchKey)
                ->orderBy('id', 'DESC')
                ->paginate();
        } else {
            $itemGroups = ItemGroup::query()
                ->with([
                    'factory',
                    'item',
                    'orderUOM:id,unit_of_measurement',
                    'consUOM:id,unit_of_measurement'
                ])
                ->where([
                    'factory_id' => Auth::user()->factory_id
                ])
                ->search($searchKey)
                ->orderBy('id', 'DESC')
                ->paginate();
        }

        return view('system-settings::item-group.item_group_list', compact('itemGroups'));
    }

    public function addItemGroup()
    {
        if (getRole() == 'super-admin') {
            $factories = Factory::query()->userWiseFactories()->pluck('factory_name', 'id');
        } else {
            $factories = [];
        }
        $data['factories'] = $factories;
        $data['item_group'] = null;
        $data['factory_id'] = Auth::user()->factory_id;
        $data['items'] = Item::query()->pluck('item_name', 'id');
        $data['uom'] = UnitOfMeasurement::query()->pluck('unit_of_measurement', 'id');
        $data['item_subgroups'] = ItemSubgroup::query()->where('status', 1)->pluck('name', 'id');
        $data['groups'] = GroupWiseField::query()->pluck('group_name', 'id')->prepend('SELECT', 0);
        $data['control_ledgers'] =  Account::query()
            ->where('account_type', Account::CONTROL)
            ->pluck('name','id')
            ->prepend('SELECT', 0);

        return view('system-settings::item-group.add_update_item_group', $data);
    }

    public function itemGroupStore(ItemGroupRequest $request)
    {
        try {
            DB::beginTransaction();
            $itemGroup = new ItemGroup($request->all());
            $itemGroup->save();

            ItemGroup::query()->find($itemGroup->id)->update([
                'product_id' => 'SLS - ' . $itemGroup->id,
            ]);
            DB::commit();

            Session::flash('alert-success', 'Data Stored Successfully');
        } catch (\Exception $exception) {
            DB::rollBack();
            Session::flash('alert-danger', 'Something Went Wrong');
        }

        if ($request->ajax()) {
            return response()->json($itemGroup->load('consUOM'));
        }

        return redirect('item-group');
    }

    public function edit($id)
    {
        if (getRole() == 'super-admin') {
            $factories = Factory::query()->userWiseFactories()->pluck('factory_name', 'id');
        } else {
            $factories = [];
        }
        $data['factories'] = $factories;
        $data['item_group'] = $itemGroup = ItemGroup::find($id);
        $data['factory_id'] = Auth::user()->factory_id;
        $data['items'] = Item::pluck('item_name', 'id');
        $data['uom'] = UnitOfMeasurement::pluck('unit_of_measurement', 'id');
        $data['item_subgroups'] = ItemSubgroup::query()->where('status', 1)->pluck('name', 'id');
        $data['groups'] = GroupWiseField::query()->pluck('group_name', 'id')->prepend('SELECT', 0);
        $data['control_ledgers'] =  Account::query()
            ->where('account_type', Account::CONTROL)
            ->pluck('name','id')
            ->prepend('SELECT', 0);
        $data['ledger_accounts'] = [];
        if(!empty($itemGroup->control_ledger)){
            $data['ledger_accounts'] = (new Account())->getLedgerAccountsByControlId($itemGroup->control_ledger)->pluck('text', 'id');
        }

        return view('system-settings::item-group.add_update_item_group', $data);
    }

    public function update(ItemGroupRequest $request)
    {
        $is_update = ItemGroup::findorFail($request->id)->update($request->all());
        if ($is_update) {
            Session::flash('alert-success', 'Data Updated Successfully');
        } else {
            Session::flash('alert-danger', 'Something Went Wrong');
        }

        return redirect('item-group');
    }

    public function destroy($id)
    {
        $itemGroupId = CostingDetails::query()->where('type', 'trims_costing')->get();
        $itemGroupIdPQ = collect($itemGroupId)->pluck('details.details')->flatten(1)->pluck('group_id')->unique()->values()->map(function ($item) {
            return (int)$item;
        });

        $budgetCostings = BudgetCostingDetails::query()->where('type', 'trims_costing')->get();
        $itemGroupIdBudget = collect($budgetCostings)->pluck('details.details')->flatten(1)->pluck('group_id')->map(function ($item) {
            return (int)$item;
        })->unique()->values();

        if (!(collect($itemGroupIdPQ)->contains($id) || collect($itemGroupIdBudget)->contains($id))) {
            try {
                DB::beginTransaction();
                $item_group = ItemGroup::findOrFail($id);
                $item_group->delete();
                DB::commit();
                Session::flash('alert-success', 'Data Deleted Successfully!!');

                return redirect('item-group');
            } catch (\Exception $e) {
                DB::rollback();
                Session::flash('alert-danger', 'Something went wrong!Error Code ItemGroupSeeder.D-102');

                return redirect()->back();
            }
        } else {
            Session::flash('alert-danger', 'Can Not be Deleted ! It is currently associated with Others');

            return redirect('item-group');
        }
    }

    public function getItemGroupsOnFactoryId($factory_id)
    {
        $query = ItemGroup::where(['factory_id' => $factory_id, 'status' => 1]);
        if ($query->count() > 0) {
            $item_groups = $query->get();
            $status = 200;
        } else {
            $item_groups = [];
            $status = 500;
        }

        return response()->json(['status' => $status, 'item_groups' => $item_groups]);
    }

    public function itemGroups()
    {
        try {
            $items = ItemGroup::all();
            $uoms = UnitOfMeasurement::all();
            $data = [
                'item_groups' => $items,
                'uoms' => $uoms,
            ];
            return response()->json($data, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
