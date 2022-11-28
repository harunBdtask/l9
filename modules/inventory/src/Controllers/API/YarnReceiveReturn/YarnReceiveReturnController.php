<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\API\YarnReceiveReturn;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Inventory\Models\YarnReceiveDetail;
use SkylarkSoft\GoRMG\Inventory\Services\YarnReceiveReturn\YarnReceiveReturnStockService;
use SkylarkSoft\GoRMG\Inventory\Services\YarnStockSummaryService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Supplier;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\Inventory\Requests\YarnReceiveReturnFormRequest;
use SkylarkSoft\GoRMG\Inventory\Models\YarnReceiveReturn;
use SkylarkSoft\GoRMG\Inventory\Models\YarnReceiveReturnDetail;
use SkylarkSoft\GoRMG\Inventory\Requests\YarnReceiveReturnDetailsFormRequest;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Inventory\Models\YarnReceive;
use Illuminate\Database\Eloquent\Builder;
use PDF;
use Throwable;

class YarnReceiveReturnController extends Controller
{
    public function index(Request $request)
    {
        $receive_return_no = $request->input('receive_return_no');
        $supplier_id = $request->input('supplier_id');
        $return_date = $request->input('return_date');
        $year = $request->input('year');
        $factory_id = $request->input('factory_id');
        $receive_id = $request->input('receive_id');
        $return_qty = $request->input('return_qty');
        $suppliers = Supplier::query()->where('factory_id', factoryId())->where('party_type', 'like', '%Yarn Supplier%')->get();
        $companies = Factory::all();

        $data = YarnReceiveReturn::query()
            ->with('details', 'supplier', 'company', 'yarn_receive')
            ->when($receive_return_no, function (Builder $builder) use ($receive_return_no) {
                $builder->where('receive_return_no', $receive_return_no);
            })
            ->when($year, function (Builder $builder) use ($year) {
                $builder->whereYear('return_date', $year);
            })
            ->when($factory_id, function (Builder $builder) use ($factory_id) {
                $builder->where('factory_id', $factory_id);
            })
            ->when($supplier_id, function (Builder $builder) use ($supplier_id) {
                $builder->where('return_to', $supplier_id);
            })
            ->when($return_date, function (Builder $builder) use ($return_date) {
                $builder->where('return_date', $return_date);
            })
            ->when($receive_id, function (Builder $builder) use ($receive_id) {
                $builder->whereHas('yarn_receive', function ($query) use ($receive_id) {
                    $query->where('receive_no', $receive_id);
                });
            })
            ->when($return_qty, function (Builder $builder) use ($return_qty) {
                $builder->whereHas('details', function ($query) use ($return_qty) {
                    $query->where('return_qty', $return_qty);
                });
            })
            ->orderByDesc('id')
            ->paginate();

        return view('inventory::yarns.receive-return.index', compact('data', 'suppliers', 'companies'));
    }

    public function create()
    {
        return view('inventory::yarns.receive-return.create');
    }

    public function store(YarnReceiveReturnFormRequest $request): JsonResponse
    {
        try {
            if ($request->get('id')) {
                $receiveReturn = YarnReceiveReturn::query()->findOrFail($request->get('id'));
            } else {
                $receiveReturn = new YarnReceiveReturn();
            }
            $receiveReturn->fill($request->all())->save();
            return response()->json($receiveReturn, Response::HTTP_CREATED);

        } catch (\Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @throws Throwable
     */
    public function storeDetails(YarnReceiveReturnDetailsFormRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $stockService = new YarnReceiveReturnStockService();
            $yarnReceiveReturnDetail = YarnReceiveReturnDetail::query()->firstOrNew(['id' => $request->get('id')]);
            if ($request->get('id')) {
                $stockService->updated($yarnReceiveReturnDetail);
                $yarnReceiveReturnDetail->fill($request->all())->save();
            } else {
                $yarnReceiveReturnDetail->fill($request->all())->save();
                $stockService->created($yarnReceiveReturnDetail);
            }

            YarnReceiveDetail::query()->where('yarn_receive_id', $request->get('receive_id'))->decrement('balance_qty', $request->get('return_qty'));
            DB::commit();

            return response()->json($yarnReceiveReturnDetail, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getYarnReceives(Request $request): JsonResponse
    {
        $data = YarnReceive::query()
            ->with([
                'store', 'details', 'details.supplier'
            ])
            ->when($request->get('supplier_id'), function ($query) use ($request) {
                $query->whereHas('details.supplier', function ($q) use ($request) {
                    $q->where('supplier_id', $request->get('supplier_id'));
                });
            })
            ->when($request->get('from_date') && $request->get('to_date'), function ($query) use ($request) {
                $query->whereBetween('receive_date', [$request->get('from_date'), $request->get('to_date')]);
            })
            ->when($request->get('search_by') == '1', function ($query) use ($request) {
                $query->where('receive_no', $request->get('number'));
            })
            ->when($request->get('search_by') == '2', function ($query) use ($request) {
                $query->where('challan_no', $request->get('number'));
            })
            ->orderByDesc('id')
            ->get()
            ->map(function($receive) {
                return [
                    "id" => $receive->id,
                    "receive_no" => $receive->receive_no,
                    "year" => Carbon::parse($receive->receive_date)->year,
                    "challan_no" => $receive->challan_no,
                    "lc_no" => $receive->lc_no,
                    "receive_date" => $receive->receive_date,
                    "receive_basis" => $receive->receive_basis,
                    "receive_qty" => $receive->details->sum('receive_qty'),
                ];
            });

        return response()->json($data, Response::HTTP_OK);
    }

    public function getReceiveDetails(): JsonResponse
    {
        try {
            $data = YarnReceive::query()
                ->where('id', request('receive_id'))
                ->with('store', 'details', 'details.supplier', 'details.floor', 'details.room', 'details.rack', 'details.shelf', 'details.bin', 'details.uom', 'details.composition', 'details.yarn_count', 'details.type')
                ->first();

            $data->details->map(function ($yarn) {
                $summary = (new YarnStockSummaryService())->summary($yarn);
                $yarn->current_stock = $summary->balance;
                $yarn->meta = $summary->meta;
            });

            return response()->json($data, Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getReceiveIds(): JsonResponse
    {
        try {
            $receive_ids = YarnReceive::query()->where('factory_id', factoryId())
                ->orderByDesc('id')
                ->select('receive_no', 'challan_no')
                ->get();
            return response()->json($receive_ids, Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getReceiveReturns($id): JsonResponse
    {
        try {
            $receive_returns = YarnReceiveReturn::query()
                ->where('id', $id)
                ->with('yarn_receive', 'details', 'details.floor', 'details.room', 'details.rack', 'details.shelf', 'details.bin', 'details.uom', 'details.composition', 'details.yarn_count', 'details.type')
                ->first();
            if ($receive_returns) {
                $receive_returns = $receive_returns->details()->get()
                    ->map(function ($detail) use ($receive_returns) {
                        return [
                            'id' => $detail->id,
                            'receive_return_no' => $receive_returns->receive_return_no,
                            'receive_no' => $receive_returns->yarn_receive->receive_no,
                            'yarn_composition' => $detail->composition->yarn_composition ?? '',
                            'yarn_type' => $detail->type->name ?? '',
                            'yarn_count' => $detail->yarn_count->yarn_count ?? '',
                            'yarn_lot' => $detail->yarn_lot,
                            'yarn_color' => $detail->yarn_color,
                            'yarn_brand' => $detail->yarn_brand,
                            'return_qty' => $detail->return_qty,
                            'return_value' => $detail->return_value,
                            'rate' => $detail->rate,
                            'uom' => $detail->uom->unit_of_measurement ?? '',
                            'floor' => $detail->floor->name ?? '',
                            'room' => $detail->room->name ?? '',
                            'rack' => $detail->rack->name ?? '',
                            'shelf' => $detail->shelf->name ?? '',
                            'bin' => $detail->bin->name ?? '',
                        ];
                    });
            }
            return response()->json($receive_returns, Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @throws Throwable
     */
    public function deleteYarnReceiveDetail($id): JsonResponse
    {
        try {
            DB::beginTransaction();
            $yarn = YarnReceiveReturnDetail::query()->findOrFail($id);
            (new YarnReceiveReturnStockService())->deleted($yarn);
            $yarn->delete();
            DB::commit();
            return response()->json(['msg' => 'Delete Success'], Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @throws Throwable
     */
    public function deleteYarnReceiveReturn($id): RedirectResponse
    {
        try {
            $receiveReturn = YarnReceiveReturn::query()->find($id);
            DB::beginTransaction();

            if ($receiveReturn->details()->count() != 0) {
                session()->flash('danger', 'Please delete details first!');
            } else {
                $receiveReturn->delete();
                session()->flash('success', 'Successfully Deleted');
            }
            DB::commit();
            Session::flash('success', 'Delete Success');
            return redirect()->back();
        } catch (Exception $exception) {
            Session::flash('error', 'Delete Failed');
            return redirect()->back();
        }
    }

    public function show($id)
    {
        $data = $this->getYarnReceiveReturnData($id);
        return view('inventory::yarns.receive-return.view', compact('data'));
    }

    public function print($id)
    {
        $data = $this->getYarnReceiveReturnData($id);
        return view('inventory::yarns.receive-return.print', compact('data'));
    }

    private function getYarnReceiveReturnData($id)
    {
        return YarnReceiveReturn::query()
            ->where('id', $id)
            ->with('yarn_receive', 'details', 'company', 'supplier', 'details.floor', 'details.room', 'details.rack', 'details.shelf', 'details.bin', 'details.uom', 'details.composition', 'details.yarn_count', 'details.type')
            ->firstOrFail();
    }
}
