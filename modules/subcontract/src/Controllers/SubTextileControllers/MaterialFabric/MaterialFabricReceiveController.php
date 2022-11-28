<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\SubTextileControllers\MaterialFabric;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Subcontract\Actions\OrderBasisReceiveAction;
use SkylarkSoft\GoRMG\Subcontract\Actions\StockSummaryAction;
use SkylarkSoft\GoRMG\Subcontract\Actions\SyncFabricReceiveDetails;
use SkylarkSoft\GoRMG\Subcontract\Filters\Filter;
use SkylarkSoft\GoRMG\Subcontract\Models\Libraries\SubContractGreyStore;
use SkylarkSoft\GoRMG\Subcontract\Models\Libraries\SubcontractVariableSetting;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubGreyStoreReceive;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubGreyStoreReceiveDetails;
use SkylarkSoft\GoRMG\Subcontract\Requests\SubTextileRequests\FabricReceiveFormRequest;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class MaterialFabricReceiveController extends Controller
{
    /**
     * @param Request $request
     * @return Application|\Illuminate\Contracts\View\Factory|View
     */
    public function index(Request $request)
    {
        $subGreyReceives = SubGreyStoreReceive::query()
            ->with([
                'factory',
                'supplier',
                'challanOrders.textileOrder',
            ])
            ->when($request->get('factory'), Filter::applyFilter('factory_id', $request->get('factory')))
            ->when($request->get('supplier'), Filter::applyFilter('supplier_id', $request->get('supplier')))
            ->when($request->get('store'), Filter::applyFilter('sub_grey_store_id', $request->get('store')))
            ->when($request->get('receive_basis'), Filter::applyFilter('receive_basis', $request->get('receive_basis')))
            ->when($request->get('challan_date'), Filter::applyFilter('challan_date', $request->get('challan_date')))
            ->when($request->get('challan_no'), function (Builder $query) use ($request) {
                return $query->where('challan_no', 'like', "%{$request->get('challan_no')}%");
            })
            ->when($request->get('order_no'), function ($query) use ($request) {
                $query->whereHas('textileOrder', function ($q) use ($request) {
                    return $q->where('order_no', 'LIKE', "%{$request->get('order_no')}%");
                });
            })
            ->groupBy('challan_no')
            ->orderBy('id', 'desc')
            ->paginate();

        $factories = Factory::query()
            ->pluck('factory_name', 'id')
            ->prepend('Select', 0);

        $stores = SubContractGreyStore::query()
            ->pluck('name', 'id')
            ->prepend('Select', 0);

        $receiveBasises = [
            0 => 'Select',
            1 => 'Independent',
            2 => 'Order',
        ];

        $variableSetting = SubcontractVariableSetting::query()
            ->where('factory_id', factoryId())
            ->first();

        $supplier = Buyer::query()
            ->where('party_type', 'Subcontract')
            ->factoryFilter()
            ->pluck('name', 'id')
            ->prepend('Select', 0);
//        dd($supplier);

        return view('subcontract::textile_module.material-fabric.receive.index', [
            'subGreyReceives' => $subGreyReceives,
            'factories' => $factories,
            'stores' => $stores,
            'receiveBasises' => $receiveBasises,
            'variableSetting' => $variableSetting,
            'supplier' => $supplier,
        ]);
    }

    /**
     * @return Application|\Illuminate\Contracts\View\Factory|View
     */
    public function create()
    {
        return view('subcontract::textile_module.material-fabric.receive.form');
    }

    /**
     * @param FabricReceiveFormRequest $request
     * @param OrderBasisReceiveAction $orderBasisReceiveAction
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(
        FabricReceiveFormRequest $request,
        OrderBasisReceiveAction  $orderBasisReceiveAction
    ): JsonResponse {
        try {
            DB::beginTransaction();

            if (! empty($request->get('sub_textile_order_id')) && is_array($request->get('sub_textile_order_id'))) {
                foreach ($request->get('sub_textile_order_id') as $item) {
                    $subGreyStoreReceive = new SubGreyStoreReceive();
                    $subGreyStoreReceive->fill($request->all());
                    $subGreyStoreReceive->sub_textile_order_id = $item;
                    $subGreyStoreReceive->save();

                    $orderBasisReceiveAction->attach($subGreyStoreReceive);

                    if ($subGreyStoreReceive->receive_basis == 2) {
                        $this->makeRequiredOperation($subGreyStoreReceive);
                    }
                }
            } else {
                $subGreyStoreReceive = new SubGreyStoreReceive();
                $subGreyStoreReceive->fill($request->all())->save();
                $orderBasisReceiveAction->attach($subGreyStoreReceive);

                if ($subGreyStoreReceive->receive_basis == 2) {
                    $this->makeRequiredOperation($subGreyStoreReceive);
                }
            }

            DB::commit();

            return response()->json([
                'data' => $subGreyStoreReceive,
                'message' => 'fabric-receive created successfully',
                'status' => Response::HTTP_CREATED,
            ], Response::HTTP_CREATED);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param SubGreyStoreReceive $subGreyStoreReceive
     * @return JsonResponse
     */
    public function edit(SubGreyStoreReceive $subGreyStoreReceive): JsonResponse
    {
        try {
            $sub_textile_order_ids = collect(SubGreyStoreReceive::query()
            ->where('challan_no', $subGreyStoreReceive->challan_no)
            ->get())->pluck('sub_textile_order_id');
            if (! empty($sub_textile_order_ids)) {
                $subGreyStoreReceive->sub_textile_order_id = $sub_textile_order_ids;
                $data = $subGreyStoreReceive->load('receiveDetailsByChallanNo');
                $subGreyStoreReceive->receiveDetails = $data->receiveDetailsByChallanNo;
            } else {
                $subGreyStoreReceive->load('receiveDetails');
            }

            return response()->json([
                'message' => 'fabric-receive fetched successfully',
                'data' => $subGreyStoreReceive,
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param FabricReceiveFormRequest $request
     * @param SubGreyStoreReceive $subGreyStoreReceive
     * @param SyncFabricReceiveDetails $syncFabricReceiveDetails
     * @return JsonResponse
     * @throws Throwable
     */
    public function update(
        FabricReceiveFormRequest $request,
        SubGreyStoreReceive      $subGreyStoreReceive,
        SyncFabricReceiveDetails $syncFabricReceiveDetails
    ): JsonResponse {
        try {
            dd($request->all());
            DB::beginTransaction();

            if (! empty($request->get('sub_textile_order_id')) && is_array($request->get('sub_textile_order_id'))) {
                $subGreyStoreReceives = SubGreyStoreReceive::query()
                    ->where('challan_no', $subGreyStoreReceive->challan_no)
                    ->update([
                        'factory_id' => $request->get('factory_id'),
                        'supplier_id' => $request->get('supplier_id'),
                        'receive_basis' => $request->get('receive_basis'),
                        'sub_grey_store_id' => $request->get('sub_grey_store_id'),
                        'challan_date' => $request->get('challan_date'),
                        'required_operations' => $request->get('required_operations'),
                        'remarks' => $request->get('remarks'),
                    ]);

                $details = SubGreyStoreReceiveDetails::query()
                ->where('challan_no', $subGreyStoreReceive->challan_no)
                ->update([
                    'sub_grey_store_id' => $request->get('sub_grey_store_id'),
                    'challan_no' => $request->get('challan_no'),
                    'challan_date' => $request->get('challan_date'),
                ]);
            } else {
                $subGreyStoreReceive->fill($request->all())->save();
                $syncFabricReceiveDetails->handle($subGreyStoreReceive);
            }

            DB::commit();

            return response()->json([
                'data' => $subGreyStoreReceive,
                'message' => 'fabric-receive updated successfully',
                'status' => Response::HTTP_CREATED,
            ], Response::HTTP_CREATED);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param SubGreyStoreReceive $subGreyStoreReceive
     * @param StockSummaryAction $receiveDetailsAction
     * @return RedirectResponse
     * @throws Throwable
     */
    public function destroy(SubGreyStoreReceive $subGreyStoreReceive, StockSummaryAction $receiveDetailsAction): RedirectResponse
    {
        try {
            DB::beginTransaction();
            foreach ($subGreyStoreReceive->receiveDetailsByChallanNo as $receiveDetail) {
                $receiveDetail->delete();
                $receiveDetailsAction->attachToStockSummaryReport($receiveDetail);
                $receiveDetailsAction->attachToDailyStockSummaryReport($receiveDetail);
            }

            $subGreyStoreReceive->where('challan_no', $subGreyStoreReceive->challan_no)->delete();
            DB::commit();

            Session::flash('success', S_DEL_MSG);
        } catch (Exception $exception) {
            Session::flash('error', $exception->getMessage());
        } finally {
            return back();
        }
    }

    protected function makeRequiredOperation($subGreyStoreReceive)
    {
        $subGreyStoreReceive['required_operations'] = $subGreyStoreReceive->receiveDetails
            ->pluck('operation.name')
            ->implode(', ');

        $subGreyStoreReceive->save();
    }
}
