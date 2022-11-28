<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnCount;
use SkylarkSoft\GoRMG\SystemSettings\Models\ColorRange;
use SkylarkSoft\GoRMG\SystemSettings\Models\FabricNature;
use SkylarkSoft\GoRMG\Merchandising\Models\CostingDetails;
use SkylarkSoft\GoRMG\SystemSettings\Models\CompositionType;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnComposition;
use SkylarkSoft\GoRMG\Merchandising\Models\BudgetCostingDetails;
use SkylarkSoft\GoRMG\SystemSettings\Models\NewFabricComposition;
use SkylarkSoft\GoRMG\SystemSettings\Models\FabricConstructionEntry;
use SkylarkSoft\GoRMG\SystemSettings\Models\NewFabricCompositionDetail;
use SkylarkSoft\GoRMG\SystemSettings\Requests\NewFabricCompositionRequest;

class NewFabricCompositionController extends Controller
{
    public function index(Request $request)
    {
        $searchKey = $request->get('q');
        $fabric_compositions = NewFabricComposition::query()
            ->with(['colorRange'])
            ->when($searchKey, function ($query) use ($searchKey) {
                $query->where('construction', 'LIKE', "%$searchKey%")
                ->orWhereHas('fabricNature',function(Builder $builder) use($searchKey){
                    $builder->where('name', 'LIKE', "%$searchKey%");
                })
                ->orWhere('gsm','LIKE',"%$searchKey%")
                ->orWhereHas('colorRange',function(Builder $builder) use($searchKey){
                    $builder->where('name', 'LIKE', "%$searchKey%");
                })
                ->orWhere('stitch_length','LIKE',"%$searchKey%");
            })
            ->orderBy('id', 'DESC')
            ->paginate();

        return view('system-settings::new_fabric_composition.list', [
            'fabric_compositions' => $fabric_compositions,
        ]);
    }

    public function create()
    {
        $fabric_composition = null;
        $fabric_natures = FabricNature::all()->pluck('name', 'id');
        $color_ranges = ColorRange::all()->pluck('name', 'id');
        $yarn_compositions = YarnComposition::all()->pluck('yarn_composition', 'id');
        $yarn_counts = YarnCount::all()->pluck('yarn_count', 'id');
        $composition_types = CompositionType::all()->pluck('name', 'id');
        $compositions = FabricConstructionEntry::all()->pluck('construction_name', 'construction_name');
        $status = NewFabricComposition::STATUS;

        return view('system-settings::new_fabric_composition.create_update_form', [
            'fabric_composition' => $fabric_composition,
            'fabric_natures' => $fabric_natures,
            'color_ranges' => $color_ranges,
            'yarn_compositions' => $yarn_compositions,
            'yarn_counts' => $yarn_counts,
            'status' => $status,
            'composition_types' => $composition_types,
            'compositions' => $compositions,
        ]);
    }

    /**
     * @param NewFabricCompositionRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function store(NewFabricCompositionRequest $request)
    {
        try {
            DB::beginTransaction();
            $fabric_composition = new NewFabricComposition();
            $fabric_composition->fabric_nature_id = $request->fabric_nature_id;
            $fabric_composition->construction = $request->construction;
            $fabric_composition->color_range_id = $request->color_range_id;
            $fabric_composition->gsm = $request->gsm;
            $fabric_composition->machine_dia = $request->machine_dia;
            $fabric_composition->finish_fabric_dia = $request->finish_fabric_dia;
            $fabric_composition->machine_gg = $request->machine_gg;
            $fabric_composition->stitch_length = $request->stitch_length;
            $fabric_composition->fabric_code = $request->fabric_code;
            $fabric_composition->status = $request->status;
            $fabric_composition->save();

            $new_fab_comp_id = $fabric_composition->id;
            if ($request->has('yarn_composition_id')) {
                foreach ($request->yarn_composition_id as $key => $val) {
                    $fabric_composition_detail_id = $request->fab_composition_details_id[$key];
                    $fabric_composition_detail = NewFabricCompositionDetail::findOrNew($fabric_composition_detail_id);
                    $fabric_composition_detail->new_fab_comp_id = $new_fab_comp_id;
                    $fabric_composition_detail->yarn_composition_id = $val;
                    $fabric_composition_detail->percentage = $request->percentage[$key];
                    $fabric_composition_detail->yarn_count_id = $request->yarn_count_id[$key];
                    $fabric_composition_detail->composition_type_id = $request->composition_type_id[$key];
                    $fabric_composition_detail->save();
                }
            }
            DB::commit();

            if ($request->input('type') == 1) {
                $composition = '';
                $first_key = $fabric_composition->newFabricCompositionDetails->keys()->first();
                $last_key = $fabric_composition->newFabricCompositionDetails->keys()->last();
                $fabric_composition->newFabricCompositionDetails->each(function ($fabric_item, $key) use (&$composition, $first_key, $last_key, $fabric_composition) {
                    $composition .= ($key === $first_key) ? "{$fabric_composition->construction} [" : '';
                    $composition .= "{$fabric_item->yarnComposition->yarn_composition} {$fabric_item->percentage}%";
                    $composition .= ($key !== $last_key) ? ', ' : ']';
                });

                $data = [
                    'id' => $fabric_composition->id,
                    'text' => $composition,
                    'gsm' => $fabric_composition->gsm,
                ];

                return response()->json([
                    'message' => 'Fabric composition and composition details stored successfully',
                    'data' => $data,
                    'status' => Response::HTTP_CREATED,
                ], Response::HTTP_CREATED);
            }

            $html = view('skeleton::partials.flash_message', [
                'message_class' => "success",
                'message' => "Data stored successfully!",
            ])->render();

            return response()->json([
                'status' => 'success',
                'errors' => null,
                'message' => $html,
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            $html = view('skeleton::partials.flash_message', [
                'message_class' => "danger",
                'message' => "Sorry Something went wrong!!",
            ])->render();

            return response()->json([
                'status' => 'danger',
                'errors' => $e->getMessage(),
                'message' => $html,
            ]);
        }
    }

    public function edit($id)
    {
        $fabric_composition = NewFabricComposition::findOrFail($id);
        $fabric_natures = FabricNature::all()->pluck('name', 'id');
        $color_ranges = ColorRange::all()->pluck('name', 'id');
        $yarn_compositions = YarnComposition::all()->pluck('yarn_composition', 'id');
        $yarn_counts = YarnCount::all()->pluck('yarn_count', 'id');
        $composition_types = CompositionType::all()->pluck('name', 'id');
        $compositions = FabricConstructionEntry::all()->pluck('construction_name', 'construction_name');
        $status = NewFabricComposition::STATUS;

        return view('system-settings::new_fabric_composition.create_update_form', [
            'fabric_composition' => $fabric_composition,
            'fabric_natures' => $fabric_natures,
            'color_ranges' => $color_ranges,
            'yarn_compositions' => $yarn_compositions,
            'yarn_counts' => $yarn_counts,
            'status' => $status,
            'composition_types' => $composition_types,
            'compositions' => $compositions,
        ]);
    }

    public function update($id, NewFabricCompositionRequest $request)
    {
        try {
            DB::beginTransaction();
            $fabric_composition = NewFabricComposition::findOrFail($id);
            $fabric_composition->fabric_nature_id = $request->fabric_nature_id;
            $fabric_composition->construction = $request->construction;
            $fabric_composition->color_range_id = $request->color_range_id;
            $fabric_composition->gsm = $request->gsm;
            $fabric_composition->machine_dia = $request->machine_dia;
            $fabric_composition->finish_fabric_dia = $request->finish_fabric_dia;
            $fabric_composition->machine_gg = $request->machine_gg;
            $fabric_composition->stitch_length = $request->stitch_length;
            $fabric_composition->fabric_code = $request->fabric_code;
            $fabric_composition->status = $request->status;
            $fabric_composition->save();

            $new_fab_comp_id = $fabric_composition->id;
            if ($request->has('yarn_composition_id')) {
                foreach ($request->yarn_composition_id as $key => $val) {
                    $fabric_composition_detail_id = $request->fab_composition_details_id[$key];
                    $fabric_composition_detail = NewFabricCompositionDetail::findOrNew($fabric_composition_detail_id);
                    $fabric_composition_detail->new_fab_comp_id = $new_fab_comp_id;
                    $fabric_composition_detail->yarn_composition_id = $val;
                    $fabric_composition_detail->percentage = $request->percentage[$key];
                    $fabric_composition_detail->yarn_count_id = $request->yarn_count_id[$key];
                    $fabric_composition_detail->composition_type_id = $request->composition_type_id[$key];
                    $fabric_composition_detail->save();
                }
            }
            DB::commit();
            $html = view('skeleton::partials.flash_message', [
                'message_class' => "success",
                'message' => "Data updated successfully!",
            ])->render();

            return response()->json([
                'status' => 'success',
                'errors' => null,
                'message' => $html,
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            $html = view('skeleton::partials.flash_message', [
                'message_class' => "danger",
                'message' => "Sorry something went wrong!!",
            ])->render();

            return response()->json([
                'status' => 'danger',
                'errors' => $e->getMessage(),
                'message' => $html,
            ]);
        }
    }

    public function destroy($id)
    {
        $costingDetails = CostingDetails::query()->where('type', 'fabric_costing')->get();
        $budgetCostings = BudgetCostingDetails::query()->where('type', 'fabric_costing')->get();

        $fabricCompositionIdPQ = collect($costingDetails)->pluck('details.details.fabricForm')->flatten(1)->pluck('fabric_composition_id')->map(function ($item) {
            return (int)$item;
        })->unique()->values();

        $fabricCompositionIdBudget = collect($budgetCostings)->pluck('details.details.fabricForm')->flatten(1)->pluck('fabric_composition_id')->map(function ($item) {
            return (int)$item;
        })->unique()->values();

        if (!(collect($fabricCompositionIdBudget)->contains($id) || collect($fabricCompositionIdPQ)->contains($id))) {
            try {
                DB::beginTransaction();
                $fabric_composition = NewFabricComposition::findOrFail($id);
                $fabric_composition->delete();
                DB::commit();
                Session::flash('alert-danger', 'Data Deleted Successfully!!');

                return redirect('fabric-compositions');
            } catch (Exception $e) {
                DB::rollback();
                Session::flash('alert-danger', 'Something went wrong!');

                return redirect()->back();
            }
        } else {
            Session::flash('alert-danger', 'Can Not be Deleted ! It is currently associated with Others');

            return redirect('fabric-compositions');
        }
    }

    public function deleteDetails($id, Request $request)
    {
        if (!$request->ajax()) {
            return abort(404);
        }

        try {
            DB::beginTransaction();
            $fabric_composition_detail = NewFabricCompositionDetail::findOrFail($id);
            $fabric_composition_detail->delete();
            DB::commit();

            return response()->json([
                'status' => 'success',
                'errors' => null,
            ]);
        } catch (Exception $e) {
            DB::rollback();

            return response()->json([
                'status' => 'success',
                'errors' => $e->getMessage(),
            ]);
        }
    }

    public function fabricCompositions()
    {
        try {
            $fabricCompositions = NewFabricComposition::all();
            $data = $fabricCompositions->map(function ($fabricComposition) {
                $composition = '';
                $last_key = $fabricComposition->newFabricCompositionDetails->keys()->last();
                $fabricComposition->newFabricCompositionDetails()->each(function($item, $key) use (&$composition, $last_key) {
                    $composition .= $item->percentage.'% '.$item->yarnComposition->yarn_composition.' '.$item->yarnCount->yarn_count.' '.$item->compositionType->name;
                    $composition .= ($key != $last_key) ? ', ' : '';
                });
                return [
                    'id'           => $fabricComposition->id,
                    'construction' => $fabricComposition->construction,
                    'composition'  => $composition,
                ];
            });
            return response()->json($data, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
