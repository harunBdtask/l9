<?php

namespace SkylarkSoft\GoRMG\GeneralStore\Controllers;

use App\Http\Controllers\Controller;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\GeneralStore\Requests\GsItemCategoryRequest;
use SkylarkSoft\GoRMG\GeneralStore\Models\GsBrand;
use SkylarkSoft\GoRMG\GeneralStore\Models\GsInvItemCategory;
use SkylarkSoft\GoRMG\GeneralStore\Models\GsItem;

class GsItemCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $items_category = GsInvItemCategory::with(['parent'])
            ->filter($request->query('search'))
            ->orderBy("id", "DESC")->paginate();
        return view('general-store::pages.items_category', [
            "items_category" => $items_category,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create()
    {
        $items_category = GsInvItemCategory::all(['id', 'name']);
        return view('general-store::forms.item_category', [
            //"items_category_parent" => $items_category_parent,
            "items_category" => $items_category,
            "category" => null,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param GsItemCategoryRequest $request
     * @param GsInvItemCategory $invItemCategory
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function store(GsItemCategoryRequest $request, GsInvItemCategory $invItemCategory)
    {
        try {
            $invItemCategory->fill($request->all())->save();
            Session::flash('alert-success', 'Data Stored Successfully!!');
            return redirect('/general-store/items-category/');
        } catch (\Exception $e) {
            Session::flash('alert-danger', "Something went wrong!{$e->getMessage()}");
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param GsInvItem $invItem
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function show($id)
    {
        //        $item_category = GsInvItemCategory::findOrFail($id);
        //        $brands = Brand::all(['id', 'name']);
        //        return view('settings::forms.item', [
        //            "item_category" => $item_category,
        //            "brands" => $brands
        //        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param GsInvItemCategory $invItemCategory
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function edit($id)
    {
        $items_category = GsInvItemCategory::all(['id', 'name'])->except($id);
        $category = GsInvItemCategory::findOrFail($id);
        return view('general-store::forms.item_category', [
            'category' => $category,
            'items_category' => $items_category,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(GsItemCategoryRequest $request, $id)
    {
        try {
            $category = GsInvItemCategory::findOrFail($id);
            $category->fill($request->all())->save();
            Session::flash('alert-success', 'Data Updated Successfully!!');
            return redirect('general-store/items-category');
        } catch (\Exception $e) {
            Session::flash('alert-danger', "Something went wrong!");
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param GsInvItemCategory $itemCategory
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            GsInvItemCategory::findOrFail($id)->delete();
            Session::flash('alert-danger', 'Data deleted Successfully!!');
            return redirect('general-store/items-category');
        } catch (\Exception $e) {
            Session::flash('alert-danger', 'Something went wrong!');
            return back();
        }
    }

    public function brandsForItem($itemId)
    {
        $brands = GsItem::where('item_id', $itemId)
            ->with('brand')
            ->get();
        $itemBrand = collect($brands)->pluck("brand.name", "brand.id");
        $data = ['options' => $itemBrand, 'placeholder' => 'Select a brand'];
        return view('settings::partials.options', $data);
    }
}
