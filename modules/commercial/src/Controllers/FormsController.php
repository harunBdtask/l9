<?php


namespace SkylarkSoft\GoRMG\Commercial\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\SystemSettings\Models\EmbellishmentItem;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsItem;
use SkylarkSoft\GoRMG\SystemSettings\Models\Item;
use SkylarkSoft\GoRMG\SystemSettings\Models\ItemCreation;
use SkylarkSoft\GoRMG\SystemSettings\Models\ItemGroup;
use SkylarkSoft\GoRMG\SystemSettings\Models\NewFabricComposition;
use SkylarkSoft\GoRMG\SystemSettings\Models\Supplier;
use SkylarkSoft\GoRMG\SystemSettings\Models\UnitOfMeasurement;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnComposition;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnCount;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnType;

class FormsController extends Controller
{
    public function importers(): JsonResponse
    {
        $importers = Factory::orderBy('factory_name')->get([
            'id as value',
            'factory_name as text',
        ]);

        return response()->json($importers);
    }

    public function suppliers(): JsonResponse
    {
        $suppliers = Supplier::query()
            ->withoutGlobalScopes()
            ->filterWithAssociateFactory('supplierWiseFactories', factoryId())
            ->get(['id as value','name as text']);

        return response()->json($suppliers);
    }


    public function approveUsers(): JsonResponse
    {
        $users = User::query()
            ->selectRaw("id, id as value, email, screen_name, first_name, last_name, CONCAT(first_name, ' ', last_name) as text")
            ->orderBy('text')
            ->get();

        return response()->json($users);
    }

    public function yarnCounts()
    {
        $counts = YarnCount::query()
            ->orderBy('yarn_count')
            ->get([
                'id as value',
                'yarn_count as text',
            ]);

        return response()->json($counts);
    }

    public function yarnCompositions()
    {
        $compositions = YarnComposition::query()
            ->get([
                'id as value',
                'yarn_composition as text',
            ]);

        return response()->json($compositions);
    }

    public function unitsOfMeasurements()
    {
        $uoms = UnitOfMeasurement::where('status', 'Active')
            ->get([
                'id as value',
                'unit_of_measurement as text',
            ]);

        return response()->json($uoms);
    }

    public function yarnTypes()
    {
        $types = YarnType::query()
            ->get([
                'id as value',
                'yarn_type as text',
            ]);

        return response()->json($types);
    }

    public function itemCategories()
    {
        $types = Item::query()
            ->where('status', 'Active')
            ->get([
                'id',
                'id as value',
                'item_name as text',
            ]);

        return response()->json($types);
    }
    public function itemGroups()
    {
        $types = ItemGroup::query()
            ->get([
                'id',
                'id as value',
                'item_group as text',
            ]);

        return response()->json($types);
    }

    public function garmentsType()
    {
        $types = GarmentsItem::query()
            ->where('status', 'Active')
            ->get([
                'id as value',
                'name as text',
            ]);

        return response()->json($types);
    }

    public function embellishmentNames()
    {
        $types = EmbellishmentItem::query()
            ->groupBy('name')
//            ->where()
            ->get(['id as value', 'name as text']);
//            ->map(function ($value) {
//                return [
//                    'id' => $value->id,
//                    'text' => $value->name,
        ////                    'types' => [
        ////                        $value->where('name', 'LIKE', $value->name)
        ////                            ->whereNotNull('type')
        ////                            ->get(['id as value', 'type as text'])
        ////                    ]
//                ];
//            });

        return response()->json($types);
    }

    public function embellishmentTypes(EmbellishmentItem $embellishmentItem)
    {
        $types = EmbellishmentItem::query()
            ->where('name', 'LIKE', $embellishmentItem->name)
            ->get([
                'id as value',
                'type as text',
            ]);

        return response()->json($types);
    }

    public function fabricCompositions()
    {
        $category_name = Item::where('id', request('category-id'))->first()->item_name;
        $fabric_compositions = NewFabricComposition::query()
            ->whereHas('fabricNature', function ($query) use ($category_name) {
                $value = '%' . $category_name . '%';

                return $query->where('name', 'LIKE', $value);
            })->get();
        $fabric_compositions = $this->compositions($fabric_compositions);

        return response()->json($fabric_compositions);
    }

    public function filterFabricCompositions()
    {
        $gsm = request('gsm');
        $construction = request('construction');
        $category_name = Item::where('id', request('category-id'))->first()->item_name;

        $fabric_compositions = NewFabricComposition::query()
            ->whereHas('fabricNature', function ($query) use ($category_name) {
                $value = '%' . $category_name . '%';

                return $query->where('name', 'LIKE', $value);
            })
            ->when($gsm, function ($query) use ($gsm) {
                $value = '%' . $gsm . '%';

                return $query->where('gsm', 'LIKE', $value);
            })
            ->when($construction, function ($query) use ($construction) {
                $value = '%' . $construction . '%';

                return $query->where('construction', 'LIKE', $value);
            })
            ->get();
        $fabric_compositions = $this->compositions($fabric_compositions);

        return response()->json($fabric_compositions);
    }

    private function compositions($fabric_compositions)
    {
        return $fabric_compositions->map(function ($fabric_composition) {
            $composition = '';
            $first_key = $fabric_composition->newFabricCompositionDetails->keys()->first();
            $fabric_name = $fabric_composition->fabricNature->name;
            $last_key = $fabric_composition->newFabricCompositionDetails->keys()->last();
            $fabric_composition->newFabricCompositionDetails()->each(function ($fabric_item, $key) use (&$composition, $first_key, $last_key, $fabric_composition) {
                $composition .= ($key === $first_key) ? "{$fabric_composition->construction} [" : '';
                $composition .= "{$fabric_item->yarnComposition->yarn_composition} {$fabric_item->percentage}%";
                $composition .= ($key !== $last_key) ? ', ' : ']';
            });

            return [
                "composition" => $composition,
                "fabric_nature_name" => $fabric_name,
                "construction" => $fabric_composition->construction,
                "gsm" => $fabric_composition->gsm,
            ];
        });
    }

    public function fetchItemCreations()
    {
        $itemCreations = ItemCreation::query()
            ->where('factory_id', request('factory_id'))
            ->with(['itemGroup.item:id,item_name', 'itemGroup.consUOM:id,unit_of_measurement', 'itemGroup:id,item_group,item_id,cons_uom'])
            ->get(['id', 'item_group_id', 'item_description', 'item_size']);

        return response()->json($itemCreations);
    }

    public function filterItemCreations()
    {
        $item_name = '%' . request('item_name') . '%';
        $item_group = '%' . str_replace('"', "", request('item_group')) . '%';
        $item_size = '%' . request('item_size') . '%';
        $item_uom = '%' . request('item_uom') . '%';

        $itemCreations = ItemCreation::query()
//            ->whereHas('itemGroup.item', function ($query) use ($item_name) {
//                return $query->where('item_name', 'LIKE', $item_name);
//            })
            ->when($item_group, function ($query) use ($item_group) {
                $query->whereHas('itemGroup', function ($query) use ($item_group) {
                    return $query->where('item_group', 'LIKE', $item_group);
                });
            })
//            ->When($item_uom, function ($query) use ($item_uom) {
//                $query->whereHas('itemGroup.consUOM', function ($query) use ($item_uom) {
//                    return $query->where('unit_of_measurement', 'LIKE', $item_uom);
//                });
//            })
            ->when($item_size, function ($query) use ($item_size) {
                return $query->where('item_size', 'LIKE', $item_size);
            })
            ->where('factory_id', request('factory_id'))
            ->with(['itemGroup.item:id,item_name', 'itemGroup.consUOM:id,unit_of_measurement', 'itemGroup:id,item_group,item_id,cons_uom'])
            ->get(['id', 'item_group_id', 'item_description', 'item_size']);

        return response()->json($itemCreations);
    }
}
