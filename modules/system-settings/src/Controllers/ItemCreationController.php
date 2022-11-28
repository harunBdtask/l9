<?php


namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\FabricNature;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\ItemCreation;
use SkylarkSoft\GoRMG\SystemSettings\Models\ItemGroup;
use SkylarkSoft\GoRMG\SystemSettings\Requests\FabricNatureRequest;
use SkylarkSoft\GoRMG\SystemSettings\Requests\ItemRequest;
use Throwable;

class ItemCreationController extends Controller
{
    public function index(Request $request)
    {
        $itemCreations = ItemCreation::with('factory', 'itemGroup.item', 'itemGroup.consUOM')
            ->when($request->get('q'), function ($query) use ($request) {
                return $query->whereHas('itemGroup.item', function ($query) use ($request) {
                    return $query->where('item_name', 'LIKE', "%{$request->get('q')}%");
                });
            })
            ->orderBy('id', 'desc')->paginate();

        return view('system-settings::pages.item_creations', compact('itemCreations'));
    }

    public function create()
    {
        $itemCreation = null;
        $factories = Factory::query()
            ->userWiseFactories()
            ->pluck('factory_name', 'id');
        $itemCategories = ItemGroup::with('item')
            ->get()
            ->map(function ($itemCategory) {
                return [
                    'category' => $itemCategory->item->item_name,
                    'id' => $itemCategory->item->id,
                ];
            })->pluck('category', 'id');

        return view('system-settings::forms.item_creation', compact('itemCreation', 'factories', 'itemCategories'));
    }

    public function getItemWiseGroup($itemId)
    {
        return ItemGroup::where('item_id', $itemId)->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'factory_id' => 'required',
            'item_group_id' => 'required',
            'sub_group_code' => 'required',
        ]);

        $data = $request->except('_token', 'item_category');

        try {
            ItemCreation::create($data);
            Session::flash('success', 'Data Created Successfully');
        } catch (\Exception $exception) {
            Session::flash('alert-danger', 'Something Went Wrong');
        }

        return redirect('item-creations');
    }

    public function edit($id)
    {
        $itemCreation = ItemCreation::findOrFail($id);
        $factories = Factory::query()->userWiseFactories()->pluck('factory_name', 'id');
        $itemCategories = ItemGroup::with('item')->get()->map(function ($itemCategory) {
            return [
                'category' => $itemCategory->item->item_name,
                'id' => $itemCategory->item->id,
            ];
        })->pluck('category', 'id');

        return view('system-settings::forms.item_creation', compact('itemCreation', 'factories', 'itemCategories'));
    }

    /**
     * @throws Throwable
     */
    public function update(ItemRequest $request)
    {
        $items = $request->id ?? '';
        try {
            DB::beginTransaction();
            $item = ItemCreation::query()->findOrFail($items);
            $item->fill($request->all())->save();
            DB::commit();
            Session::flash('alert-success', 'Data Updated Successfully!!');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('alert-danger', 'Something went wrong!');
        }
        return redirect('item-creations');
    }

    public function delete($id)
    {
        ItemCreation::query()->find($id)->delete();
        Session::flash('error', 'Data Deleted Successfully');

        return redirect()->back();
    }
}
