<?php

namespace SkylarkSoft\GoRMG\DyesStore\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\DyesStore\Requests\DsItemCategoryRequest;
use SkylarkSoft\GoRMG\DyesStore\Models\DsInvItemCategory;
use SkylarkSoft\GoRMG\DyesStore\Models\DsItem;

class DsItemCategoryController extends Controller
{
    public function index(Request $request)
    {
        $items_category = DsInvItemCategory::with(['parent'])
            ->filter($request->query('search'))
            ->orderBy("id", "DESC")->paginate();

        return view('dyes-store::pages.items_category', [
            "items_category" => $items_category,
        ]);
    }

    public function create()
    {
        $items_category = DsInvItemCategory::all(['id', 'name']);

        return view('dyes-store::forms.item_category', [
            "items_category" => $items_category,
            "category" => null,
        ]);
    }

    public function store(DsItemCategoryRequest $request, DsInvItemCategory $invItemCategory)
    {
        try {
            $invItemCategory->fill($request->all())->save();
            Session::flash('alert-success', 'Data Stored Successfully!!');

            return redirect('/dyes-store/items-category');
        } catch (\Exception $e) {
            Session::flash('alert-danger', "Something went wrong!{$e->getMessage()}");

            return redirect()->back();
        }
    }

    public function show($id)
    {
        //        $item_category = GsInvItemCategory::findOrFail($id);
        //        $brands = Brand::all(['id', 'name']);
        //        return view('settings::forms.item', [
        //            "item_category" => $item_category,
        //            "brands" => $brands
        //        ]);
    }

    public function edit($id)
    {
        $items_category = DsInvItemCategory::all(['id', 'name'])->except($id);
        $category = DsInvItemCategory::findOrFail($id);

        return view('dyes-store::forms.item_category', [
            'category' => $category,
            'items_category' => $items_category,
        ]);
    }

    public function update(DsItemCategoryRequest $request, $id)
    {
        try {
            $category = DsInvItemCategory::findOrFail($id);
            $category->fill($request->all())->save();
            Session::flash('alert-success', 'Data Updated Successfully!!');

            return redirect('/dyes-store/items-category');
        } catch (\Exception $e) {
            Session::flash('alert-danger', "Something went wrong!");

            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        try {
            DsInvItemCategory::query()->findOrFail($id)->delete();
            Session::flash('alert-danger', 'Data deleted Successfully!!');

            return redirect('/dyes-store/items-category');
        } catch (\Exception $e) {
            Session::flash('alert-danger', 'Something went wrong!');

            return back();
        }
    }

    public function brandsForItem($itemId)
    {
        $brands = DsItem::query()->where('item_id', $itemId)
            ->with('brand')
            ->get();
        $itemBrand = collect($brands)->pluck("brand.name", "brand.id");
        $data = ['options' => $itemBrand, 'placeholder' => 'Select a brand'];

        return view('dyes-store::partials.options', $data);
    }
}
