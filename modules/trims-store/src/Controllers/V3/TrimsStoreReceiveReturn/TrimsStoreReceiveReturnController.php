<?php

namespace SkylarkSoft\GoRMG\TrimsStore\Controllers\V3\TrimsStoreReceiveReturn;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use PDF;
use SkylarkSoft\GoRMG\Inventory\Models\Store;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\TrimsStore\Actions\V3\ReceiveReturnDetailsUpdateAction;
use SkylarkSoft\GoRMG\TrimsStore\Actions\V3\StockSummaryAction;
use SkylarkSoft\GoRMG\TrimsStore\Models\V3\TrimsStoreReceiveReturn\TrimsStoreReceiveReturn;
use SkylarkSoft\GoRMG\TrimsStore\PackageConst;
use SkylarkSoft\GoRMG\TrimsStore\Requests\V3\TrimsStoreReceiveReturn\TrimsStoreReceiveReturnFormRequest;
use Symfony\Component\HttpFoundation\Response;

class TrimsStoreReceiveReturnController extends Controller
{
    /**
     * @return Application|\Illuminate\Contracts\View\Factory|View
     */
    public function index(Request $request)
    {
        $receiveReturns = TrimsStoreReceiveReturn::query()
            ->orderByDesc('id')
            ->search($request)
            ->paginate();

        $factories = Factory::query()->pluck('factory_name', 'id')
            ->prepend('Select', 0);

        $stores = Store::query()->pluck('name', 'id')
            ->prepend('Select', 0);

        $sources = collect(TrimsStoreReceiveReturn::SOURCES)->prepend('Select', 0);
        $receiveReturnBasis = collect(TrimsStoreReceiveReturn::RETURN_BASIS)->prepend('Select', 0);
        $receiveReturnTypes = collect(TrimsStoreReceiveReturn::RETURN_TYPES)->prepend('Select', 0);

        return view(PackageConst::VIEW_NAMESPACE . '::v3.receive-return.index', [
            'receiveReturns' => $receiveReturns,
            'factories' => $factories,
            'stores' => $stores,
            'sources' => $sources,
            'receiveReturnBasis' => $receiveReturnBasis,
            'receiveReturnTypes' => $receiveReturnTypes,
        ]);
    }

    /**
     * @return Application|\Illuminate\Contracts\View\Factory|View
     */
    public function create()
    {
        return view(PackageConst::VIEW_NAMESPACE . '::v3.receive-return.create');
    }

    /**
     * @param TrimsStoreReceiveReturnFormRequest $request
     * @param TrimsStoreReceiveReturn $receiveReturn
     * @return JsonResponse
     */
    public function store(
        TrimsStoreReceiveReturnFormRequest $request,
        TrimsStoreReceiveReturn            $receiveReturn
    ): JsonResponse {
        try {
            $receiveReturn->fill($request->all())->save();

            return response()->json([
                'message' => 'Trims store receive return stored successfully',
                'data' => $receiveReturn,
                'status' => Response::HTTP_CREATED,
            ], Response::HTTP_CREATED);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param TrimsStoreReceiveReturn $receiveReturn
     * @return JsonResponse
     */
    public function edit(TrimsStoreReceiveReturn $receiveReturn): JsonResponse
    {
        try {
            return response()->json([
                'message' => 'Fetch trims store receive return successfully',
                'data' => $receiveReturn,
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param TrimsStoreReceiveReturn $receiveReturn
     * @return Application|\Illuminate\Contracts\View\Factory|View
     */
    public function view(TrimsStoreReceiveReturn $receiveReturn)
    {
        $receiveReturn->load(
            'factory:id,factory_name,factory_address',
            'store:id,name',
            'details.buyer:id,name',
            'details.currency',
            'details.uom',
            'details.floor',
            'details.supplier:id,name,address_1',
            'details.color',
            'details.size',
            'details.itemGroup',
            'details.room',
            'details.rack',
            'details.shelf',
            'details.bin',
            'details.order:id,style_name,item_details'
        );

        return view(PackageConst::VIEW_NAMESPACE . '::v3.receive-return.view', compact('receiveReturn'));
    }

    /**
     * @param TrimsStoreReceiveReturn $receiveReturn
     * @return mixed
     */
    public function pdf(TrimsStoreReceiveReturn $receiveReturn)
    {
        $receiveReturn->load(
            'factory:id,factory_name,factory_address',
            'store:id,name',
            'details.buyer:id,name',
            'details.currency',
            'details.uom',
            'details.floor',
            'details.supplier:id,name,address_1',
            'details.color',
            'details.size',
            'details.itemGroup',
            'details.room',
            'details.rack',
            'details.shelf',
            'details.bin',
            'details.order:id,style_name,item_details'
        );

        $pdf = PDF::setOption('enable-local-file-access', true)->loadView(
            PackageConst::VIEW_NAMESPACE . '::v3.receive-return.pdf',
            compact('receiveReturn')
        )->setPaper('a4')->setOrientation('landscape')->setOptions([
            'header-html' => view('skeleton::pdf.header'),
            'footer-html' => view('skeleton::pdf.footer'),
        ]);

        return $pdf->stream('trims_store_receive_return_report.pdf');
    }

    /**
     * @param TrimsStoreReceiveReturnFormRequest $request
     * @param ReceiveReturnDetailsUpdateAction $action
     * @param TrimsStoreReceiveReturn $receiveReturn
     * @return JsonResponse
     */
    public function update(
        TrimsStoreReceiveReturnFormRequest $request,
        ReceiveReturnDetailsUpdateAction   $action,
        TrimsStoreReceiveReturn            $receiveReturn
    ): JsonResponse {
        try {
            $receiveReturn->fill($request->all())->save();
            $action->update($receiveReturn);

            return response()->json([
                'message' => 'Trims store receive return updated successfully',
                'data' => $receiveReturn,
                'status' => Response::HTTP_CREATED,
            ], Response::HTTP_CREATED);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param TrimsStoreReceiveReturn $receiveReturn
     * @param StockSummaryAction $action
     * @return RedirectResponse
     */
    public function destroy(
        TrimsStoreReceiveReturn $receiveReturn,
        StockSummaryAction      $action
    ): RedirectResponse {
        try {
            $receiveReturn->details()->each(function ($detail) use ($action) {
                $detail->delete();
                $action->attachToStockSummary($detail);
                $action->attachToDailyStockSummary($detail);
            });
            $receiveReturn->delete();
            Session::flash('success', 'Trims store receive return deleted successfully');
        } catch (Exception $e) {
            Session::flash('success', $e->getMessage());
        } finally {
            return back();
        }
    }
}
