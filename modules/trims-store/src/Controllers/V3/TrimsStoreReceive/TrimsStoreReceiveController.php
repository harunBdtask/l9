<?php

namespace SkylarkSoft\GoRMG\TrimsStore\Controllers\V3\TrimsStoreReceive;

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
use SkylarkSoft\GoRMG\TrimsStore\Actions\V3\ReceiveDetailsUpdateAction;
use SkylarkSoft\GoRMG\TrimsStore\Actions\V3\StockSummaryAction;
use SkylarkSoft\GoRMG\TrimsStore\Models\V3\TrimsStoreReceive\TrimsStoreReceive;
use SkylarkSoft\GoRMG\TrimsStore\PackageConst;
use SkylarkSoft\GoRMG\TrimsStore\Requests\V3\TrimsStoreReceive\TrimsStoreReceiveFormRequest;
use Symfony\Component\HttpFoundation\Response;

class TrimsStoreReceiveController extends Controller
{
    /**
     * @return Application|\Illuminate\Contracts\View\Factory|View
     */
    public function index(Request $request)
    {
        $receives = TrimsStoreReceive::query()
            ->orderByDesc('id')
            ->search($request)
            ->paginate();

        $factories = Factory::query()->pluck('factory_name', 'id')
            ->prepend('Select', 0);

        $sources = collect(TrimsStoreReceive::SOURCES)->prepend('Select', 0);

        $stores = Store::query()->pluck('name', 'id')
            ->prepend('Select', 0);

        $receiveBasis = collect(TrimsStoreReceive::RECEIVE_BASIS)->prepend('Select', 0);

        $payModes = collect(TrimsStoreReceive::PAY_MODES)->prepend('Select', 0);

        return view(PackageConst::VIEW_NAMESPACE . '::v3.receive.index', [
            'receives' => $receives,
            'factories' => $factories,
            'sources' => $sources,
            'stores' => $stores,
            'receiveBasis' => $receiveBasis,
            'payModes' => $payModes,
        ]);
    }

    /**
     * @return Application|\Illuminate\Contracts\View\Factory|View
     */
    public function create()
    {
        return view(PackageConst::VIEW_NAMESPACE . '::v3.receive.create');
    }

    /**
     * @param TrimsStoreReceiveFormRequest $request
     * @param TrimsStoreReceive $receive
     * @return JsonResponse
     */
    public function store(
        TrimsStoreReceiveFormRequest $request,
        TrimsStoreReceive            $receive
    ): JsonResponse {
        try {
            $receive->fill($request->all())->save();

            return response()->json([
                'message' => 'Trims store receive stored successfully',
                'data' => $receive,
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
     * @param TrimsStoreReceive $receive
     * @return JsonResponse
     */
    public function edit(TrimsStoreReceive $receive): JsonResponse
    {
        try {
            return response()->json([
                'message' => 'Fetch trims store receive successfully',
                'data' => $receive,
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
     * @param TrimsStoreReceive $receive
     * @return Application|\Illuminate\Contracts\View\Factory|View
     */
    public function view(TrimsStoreReceive $receive)
    {
        $receive->load(
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
            'details.order:id,style_name'
        );

        return view(PackageConst::VIEW_NAMESPACE . '::v3.receive.view', compact('receive'));
    }

    /**
     * @param TrimsStoreReceive $receive
     * @return Application|\Illuminate\Contracts\View\Factory|View
     */
    public function pdf(TrimsStoreReceive $receive)
    {
        $receive->load(
            'factory:id,factory_name,factory_address',
            'store:id,name',
            'details.buyer:id,name',
            'details.currency',
            'details.uom',
            'details.floor',
            'details.buyer',
            'details.supplier:id,name,address_1',
            'details.color',
            'details.size',
            'details.itemGroup',
            'details.room',
            'details.rack',
            'details.shelf',
            'details.bin',
            'details.order:id,style_name'
        );

        $pdf = PDF::setOption('enable-local-file-access', true)->loadView(
            PackageConst::VIEW_NAMESPACE . '::v3.receive.pdf',
            compact('receive')
        )->setPaper('a4')->setOrientation('landscape')->setOptions([
            'header-html' => view('skeleton::pdf.header'),
            'footer-html' => view('skeleton::pdf.footer'),
        ]);

        return $pdf->stream('trims_store_receive_report.pdf');
    }

    /**
     * @param TrimsStoreReceiveFormRequest $request
     * @param ReceiveDetailsUpdateAction $action
     * @param TrimsStoreReceive $receive
     * @return JsonResponse
     */
    public function update(
        TrimsStoreReceiveFormRequest $request,
        ReceiveDetailsUpdateAction   $action,
        TrimsStoreReceive            $receive
    ): JsonResponse {
        try {
            $receive->fill($request->all())->save();
            $action->update($receive);

            return response()->json([
                'message' => 'Trims store receive updated successfully',
                'data' => $receive,
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
     * @param TrimsStoreReceive $receive
     * @param StockSummaryAction $action
     * @return RedirectResponse
     */
    public function destroy(TrimsStoreReceive $receive, StockSummaryAction $action): RedirectResponse
    {
        try {
            $receive->details()->each(function ($detail) use ($action) {
                $detail->delete();
                $action->attachToStockSummary($detail);
                $action->attachToDailyStockSummary($detail);
            });

            $receive->delete();
            Session::flash('success', 'Trims store receive deleted successfully');
        } catch (Exception $e) {
            Session::flash('success', $e->getMessage());
        } finally {
            return back();
        }
    }
}
