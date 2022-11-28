<?php


namespace SkylarkSoft\GoRMG\GeneralStore\Controllers;


use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\GeneralStore\Exports\ItemsReportExport;
use SkylarkSoft\GoRMG\GeneralStore\Models\GsUom;
use SkylarkSoft\GoRMG\GeneralStore\Requests\GsItemRequest;
use SkylarkSoft\GoRMG\GeneralStore\Models\GsBrand;
use SkylarkSoft\GoRMG\GeneralStore\Models\GsInvItemCategory;
use SkylarkSoft\GoRMG\GeneralStore\Models\GsItem;
use SkylarkSoft\GoRMG\GeneralStore\Services\Reports\ItemsReportService;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class GsItemController extends InventoryBaseController
{
    /**
     * @param Request $request
     * @return Application|Factory|View|BinaryFileResponse
     */
    public function index(Request $request)
    {
        $items = GsItem::with(["category", "brand", "store_details"])
            ->filter($request->query('search'))
            ->orderBy("id", "DESC");
        $data['items'] = $request->input('type') == 'excel' ? $items->get() : $items->paginate();
        if (!$request->has('type')) {
            return view('general-store::pages.items', $data);
        }
        if ($request->input('type') == 'pdf') {
            $fileName = 'items.pdf';
            $pdf = PDF::loadView('general-store::pages.items_pdf', $data);
            return $pdf->stream($fileName);
        }
        if ($request->input('type') == 'excel') {
            $reportExport = new ItemsReportExport(
                $data,
                'Item Report',
                'general-store::pages.items_excel'
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
        $uoms = GsUom::all(['id', 'name']);
        $stores = get_key_val_stores();
        $categories = GsInvItemCategory::all(['id', 'name', 'parent_id']);
        $categories_id = collect($categories)->pluck("parent_id")->filter(function ($data) {
            return $data !== null;
        })->unique()->toArray();
        $items_category = collect($categories)->whereNotIn("id", $categories_id)->pluck("name", "id");
        $brands = GsBrand::all(['id', 'name']);
        return view('general-store::forms.item', [
            "item" => null,
            "uoms" => $uoms,
            "stores" => $stores,
            "items_category" => $items_category,
            "brands" => $brands
        ]);
    }

    /**
     * @param GsItemRequest $request
     * @return RedirectResponse
     */
    public function store(GsItemRequest $request): RedirectResponse
    {
        $item = $request->only(['name', 'description', 'brand_id', 'category_id', 'uom', 'store', 'abbr', 'barcode', 'qty']);
        $item['uom'] = ucfirst($item['uom']);
        $prefix = $this->createPrefix($item);

        try {
            DB::beginTransaction();
            GsItem::create(array_merge(
                Arr::except($item, 'abbr'),
                ['prefix' => $prefix]
            ));
            DB::commit();
            $this->alert('success', "Data Stored Successfully!!");
        } catch (\Exception $e) {
            $this->alert('danger', "Something went {$e->getMessage()}!!");
        }

        return Redirect::route('items.index');
    }

    /**
     * @param $id
     * @return Application|Factory|View
     */
    public function show($id)
    {
        $item_category = GsInvItemCategory::query()->findOrFail($id);
        $brands = GsBrand::all(['id', 'name']);
        return view('general-store::forms.item', [
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
        $uoms = GsUom::all(['id', 'name']);
        $item = GsItem::query()->findOrFail($id);
        $categories = GsInvItemCategory::all(['id', 'name', 'parent_id']);
        $categories_id = collect($categories)->pluck("parent_id")->toArray();
        $items_category = collect($categories)->whereNotIn("id", $categories_id);
        $brands = GsBrand::all(['id', 'name']);
        return view('general-store::forms.item_edit', compact('item', 'stores', 'uoms', 'items_category', 'brands'));
    }

    /**
     * @param GsItemRequest $request
     * @param $id
     * @return RedirectResponse
     */
    public function update(GsItemRequest $request, $id): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $item = GsItem::query()->findOrFail($id);
            $request->merge(['prefix' => $this->createPrefix($request->only(['abbr', 'store']))]);
            $item->fill($request->all())->save();
            DB::commit();
            $this->alert('success', "Data Update Successfully!!");
        } catch (\Exception $e) {
            $this->alert('danger', $e->getMessage());
        }
        return Redirect::route('items.index');
    }

    /**
     * @param GsItem $itemBrand
     * @return Application|RedirectResponse|Redirector
     */
    public function destroy(GsItem $itemBrand)
    {
        try {
            $itemBrand->delete();
            Session::flash('alert-success', 'Data Deleted Successfully!!');
            return redirect('general-store/items');
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
        $brands = GsItem::query()->where('item_id', $itemId)
            ->with('brand')
            ->get();
        $itemBrand = collect($brands)->pluck("brand.name", "brand.id");
        $data = ['options' => $itemBrand, 'placeholder' => 'Select a brand'];
        return view('general-store::partials.options', $data);
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

    /**
     * @param Request $request
     * @return Application|Factory|View|BinaryFileResponse
     */
    public function report(Request $request)
    {
        $data = ItemsReportService::data($request);

        if (!$request->has('type')) {
            return view('general-store::pages.items', $data);
        }
        if ($request->input('type') == 'pdf') {
            $fileName = 'items.pdf';
            $pdf = PDF::loadView('general-store::pages.items_pdf', $data);
            return $pdf->stream($fileName);
        }
        if ($request->input('type') == 'excel') {
            $reportExport = new ItemsReportExport(
                $data,
                'Item Report',
                'general-store::pages.items_excel'
            );
            $fileName = 'items.xlsx';

            return Excel::download(
                $reportExport,
                $fileName
            );
        }
    }
}
