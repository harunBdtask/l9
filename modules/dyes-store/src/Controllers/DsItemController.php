<?php

namespace SkylarkSoft\GoRMG\DyesStore\Controllers;

use PDF;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use SkylarkSoft\GoRMG\DyesStore\Exports\ItemsReportExport;
use SkylarkSoft\GoRMG\DyesStore\Models\DsBrand;
use SkylarkSoft\GoRMG\DyesStore\Models\DsInvItemCategory;
use SkylarkSoft\GoRMG\DyesStore\Models\DsItem;
use SkylarkSoft\GoRMG\DyesStore\Models\DsStoreModel;
use SkylarkSoft\GoRMG\DyesStore\Models\DsUom;
use SkylarkSoft\GoRMG\DyesStore\Requests\DsItemRequest;
use SkylarkSoft\GoRMG\DyesStore\Services\Reports\ItemsReportService;
use Throwable;

class DsItemController extends InventoryBaseController
{
    public function index(Request $request)
    {
        $items = DsItem::with(["category", "brand", "store_details"])
            ->filter($request->query('search'))
            ->orderBy("id", "DESC");
        $data['items'] = $request->input('type') == 'excel' ? $items->get() : $items->paginate();
        if (!$request->has('type')) {
            return view('dyes-store::pages.items', $data);
        }
        if ($request->input('type') == 'pdf') {
            $fileName = 'items.pdf';
            $pdf = PDF::loadView('dyes-store::pages.items_pdf', $data);

            return $pdf->stream($fileName);
        }
        if ($request->input('type') == 'excel') {
            $reportExport = new ItemsReportExport(
                $data,
                'Item Report',
                'dyes-store::pages.items_excel'
            );
            $fileName = 'items.xlsx';

            return Excel::download(
                $reportExport,
                $fileName
            );
        }
    }

    /**
     * @return Application|Factory|View
     */
    public function create()
    {
        $uoms = DsUom::all(['id', 'name']);
        $stores = DsStoreModel::get()->pluck('name', 'id');
        $categories = DsInvItemCategory::all(['id', 'name', 'parent_id']);
        $categories_id = collect($categories)->pluck("parent_id")->filter(function ($data) {
            return $data !== null;
        })->unique()->toArray();
        $items_category = collect($categories)->whereNotIn("id", $categories_id)->pluck("name", "id");
        $itemCategory = DsInvItemCategory::query()->where('name', 'Dyes and Chemicals')->first()['id'] ?? null;
        $defaultUom = DsUom::query()->where('name', 'Kg')->first()['id'] ?? null;
        $brands = DsBrand::all(['id', 'name']);
        $defaultBrand = DsBrand::query()->where('name', 'Chemicals')->first()['id'] ?? null;

        return view('dyes-store::forms.item', [
            "item" => null,
            "uoms" => $uoms,
            "stores" => $stores,
            "items_category" => $items_category,
            "brands" => $brands,
            "itemCategory" => $itemCategory,
            "defaultUom" => $defaultUom,
            "defaultBrand" => $defaultBrand,
        ]);
    }

    /**
     * @throws Throwable
     */
    public function store(DsItemRequest $request): RedirectResponse
    {
        $item = $request->only(['name', 'description', 'brand_id', 'category_id', 'uom', 'store', 'abbr', 'barcode', 'qty']);
        $item['uom'] = ucfirst($item['uom']);
        $prefix = $this->createPrefix($item);

        try {
            DB::beginTransaction();
            DsItem::query()->create(array_merge(
                Arr::except($item, 'abbr'),
                ['prefix' => $prefix]
            ));
            DB::commit();
            $this->alert('success', "Data Stored Successfully!!");
        } catch (\Exception $e) {
            $this->alert('danger', "Something went wrong!! {$e->getMessage()}");
        }

        return redirect('/dyes-store/items');
    }

    /**
     * @param $id
     * @return Application|Factory|View
     */
    public function show($id)
    {
        $item_category = DsInvItemCategory::query()->findOrFail($id);
        $brands = DsBrand::all(['id', 'name']);

        return view('dyes-store::forms.item', [
            "item_category" => $item_category,
            "brands" => $brands
        ]);
    }

    /**
     * @param $id
     * @return Application|Factory|View
     */
    public function edit($id)
    {
        $stores = get_key_val_stores();
        $uoms = DsUom::all(['id', 'name']);
        $item = DsItem::query()->findOrFail($id);
        $categories = DsInvItemCategory::all(['id', 'name', 'parent_id']);
        $categories_id = collect($categories)->pluck("parent_id")->toArray();
        $items_category = collect($categories)->whereNotIn("id", $categories_id);
        $brands = DsBrand::all(['id', 'name']);

        return view('dyes-store::forms.item_edit', compact('item', 'stores', 'uoms', 'items_category', 'brands'));
    }

    /**
     * @throws Throwable
     */
    public function update(DsItemRequest $request, $id): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $item = DsItem::query()->findOrFail($id);
            $request->merge(['prefix' => $this->createPrefix($request->only(['abbr', 'store']))]);
            $item->fill($request->all())->save();
            DB::commit();
            $this->alert('success', "Data Update Successfully!!");
        } catch (\Exception $e) {
            $this->alert('danger', $e->getMessage());
        }

        return redirect('/dyes-store/items');
    }

    public function destroy(DsItem $itemBrand)
    {
        try {
            $itemBrand->delete();
            Session::flash('alert-success', 'Data Deleted Successfully!!');

            return redirect('/dyes-store/items');
        } catch (\Exception $e) {
            Session::flash('alert-danger', 'Something went wrong!');

            return back();
        }
    }

    /**
     * @param $itemId
     * @return Application|Factory|View
     */
    public function brandsForItem($itemId)
    {
        $brands = DsItem::query()->where('item_id', $itemId)
            ->with('brand')
            ->get();
        $itemBrand = collect($brands)->pluck("brand.name", "brand.id");
        $data = ['options' => $itemBrand, 'placeholder' => 'Select a brand'];

        return view('dyes-store::partials.options', $data);
    }

    /**
     * @param array $item
     * @return string
     */
    private function createPrefix(array $item): string
    {
        $store = get_store_name($item['store']) ?? '';
        $storePrefix = strtoupper(substr($store, 0, 1));
        $namePrefix = strtoupper($item['abbr']);

        return $storePrefix . $namePrefix;
    }

    public function report(Request $request)
    {
        $data = ItemsReportService::data($request);

        if (!$request->has('type')) {
            return view('dyes-store::pages.items', $data);
        }
        if ($request->input('type') == 'pdf') {
            $fileName = 'items.pdf';
            $pdf = PDF::loadView('dyes-store::pages.items_pdf', $data);

            return $pdf->stream($fileName);
        }
        if ($request->input('type') == 'excel') {
            $reportExport = new ItemsReportExport(
                $data,
                'Item Report',
                'dyes-store::pages.items_excel'
            );
            $fileName = 'items.xlsx';

            return Excel::download(
                $reportExport,
                $fileName
            );
        }
    }
}
