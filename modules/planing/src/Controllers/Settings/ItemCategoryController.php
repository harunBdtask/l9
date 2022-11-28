<?php

namespace SkylarkSoft\GoRMG\Planing\Controllers\Settings;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Planing\Models\Settings\ItemCategory;
use SkylarkSoft\GoRMG\Planing\Requests\Settings\ItemCategoryFormRequest;

class ItemCategoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $itemCategories = ItemCategory::query()
            ->when($search, function (Builder $query) use ($search) {
                $query->where('name', 'LIKE', '%' . $search);
            })
            ->paginate();

        return view('planing::settings.item-category.index', [
            'itemCategories' => $itemCategories,
        ]);
    }

    public function create()
    {
        return view('planing::settings.item-category.form', [
            'itemCategory' => null,
        ]);
    }

    public function store(ItemCategoryFormRequest $request, ItemCategory $itemCategory): RedirectResponse
    {
        try {
            $itemCategory->fill($request->all())->save();
            Session::flash('alert-success', 'Item Category added successfully!!');
        } catch (Exception $exception) {
            Session::flash('alert-danger', 'Something Went Wrong!!');
        } finally {
            return back();
        }
    }

    public function edit($id)
    {
        $itemCategory = ItemCategory::query()->findOrFail($id);

        return view('planing::settings.item-category.form', [
            'itemCategory' => $itemCategory,
        ]);
    }

    public function update(ItemCategoryFormRequest $request, $id): RedirectResponse
    {
        try {
            $itemCategory = ItemCategory::query()->findOrFail($id);
            $itemCategory->fill($request->all())->save();
            Session::flash('alert-success', 'Item Category Updated successfully!!');
        } catch (Exception $exception) {
            Session::flash('alert-danger', 'Something Went Wrong!!');
        } finally {
            return back();
        }
    }

    public function destroy($id): RedirectResponse
    {
        try {
            ItemCategory::query()->findOrFail($id)->delete();
            Session::flash('alert-success', 'Item Category Deleted successfully!!');
        } catch (Exception $exception) {
            Session::flash('alert-danger', 'Something Went Wrong!!');
        } finally {
            return back();
        }
    }
}
