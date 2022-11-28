<?php

namespace SkylarkSoft\GoRMG\Dyeing\Controllers\TextileOrder;

use PDF;
use Exception;
use Throwable;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Dyeing\PackageConst;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Contracts\Foundation\Application;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Currency;
use SkylarkSoft\GoRMG\Dyeing\Actions\TextileOrderAction;
use SkylarkSoft\GoRMG\Dyeing\Requests\TextileOrderRequest;
use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\TextileOrders\TextileOrder;
use SkylarkSoft\GoRMG\Dyeing\Services\TextileServices\Formatters\TextileOrderFormatter;

class TextileOrderController extends Controller
{

    /**
     * @param Request $request
     * @return Application|\Illuminate\Contracts\View\Factory|View
     */
    public function index(Request $request)
    {
        $textileOrders = TextileOrder::query()
            ->with(['factory:id,factory_name', 'fabricSalesOrder:id,booking_type', 'buyer:id,name', 'currency:id,currency_name'])
            ->orderBy('id', 'desc')
            ->search($request)
            ->paginate();

        $factories = Factory::query()
            ->pluck('factory_name', 'id')
            ->prepend('Select', '0');

        $buyers = Buyer::query()
            ->pluck('name', 'id')
            ->prepend('Select', '0');

        $currencies = Currency::query()
            ->pluck('currency_name', 'id')
            ->prepend('Select', '0');

        $paymentBasis = collect(TextileOrder::PAYMENT_BASIS)
            ->prepend('Select', '0');

        return view(PackageConst::VIEW_PATH . 'textile_modules.textile_orders.index', [
            'textileOrders' => $textileOrders,
            'paymentBasis' => $paymentBasis,
            'currencies' => $currencies,
            'factories' => $factories,
            'buyers' => $buyers,
        ]);
    }

    /**
     * @return Application|\Illuminate\Contracts\View\Factory|View
     */
    public function create()
    {
        return view(PackageConst::VIEW_PATH . 'textile_modules.textile_orders.form');
    }

    /**
     * @param TextileOrderRequest $request
     * @param TextileOrder $textileOrder
     * @param TextileOrderAction $textileOrderAction
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(TextileOrderRequest $request,
                          TextileOrder        $textileOrder,
                          TextileOrderAction  $textileOrderAction): JsonResponse
    {
        try {
            DB::beginTransaction();
            $textileOrder->fill($request->all())->save();

            $textileOrderAction->storeDetails(
                $textileOrder,
                $request->input('textile_order_details')
            );
            DB::commit();

            return response()->json([
                'message' => 'Textile order stored successfully',
                'data' => $textileOrder,
                'status' => Response::HTTP_CREATED,
            ], Response::HTTP_CREATED);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param TextileOrder $textileOrder
     * @return JsonResponse
     */
    public function edit(TextileOrder $textileOrder): JsonResponse
    {
        try {
            return response()->json([
                'message' => 'Textile order fetch successfully',
                'data' => (new TextileOrderFormatter())->format($textileOrder),
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param TextileOrderRequest $request
     * @param TextileOrder $textileOrder
     * @param TextileOrderAction $textileOrderAction
     * @return JsonResponse
     * @throws Throwable
     */
    public function update(TextileOrderRequest $request,
                           TextileOrder        $textileOrder,
                           TextileOrderAction  $textileOrderAction): JsonResponse
    {
        try {
            DB::beginTransaction();
            $textileOrder->fill($request->all())->save();

            $textileOrderAction->updateDetails(
                $textileOrder,
                $request->input('textile_order_details')
            );
            DB::commit();

            return response()->json([
                'message' => 'Textile order stored successfully',
                'data' => $textileOrder,
                'status' => Response::HTTP_CREATED,
            ], Response::HTTP_CREATED);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param TextileOrder $textileOrder
     * @return RedirectResponse
     * @throws Throwable
     */
    public function destroy(TextileOrder $textileOrder): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $textileOrder->textileOrderDetails()->delete();
            $textileOrder->delete();
            DB::commit();

            Session::flash('success', 'Textile order deleted successfully');
        } catch (Exception $exception) {
            Session::flash('success', $exception->getMessage());
        } finally {
            return back();
        }
    }

    public function view($id)
    {
        $textileOrders = TextileOrder::query()
            ->with([
                'textileOrderDetails.fabricSalesOrderDetailId',
                'textileOrderDetails.subTextileOperation',
                'textileOrderDetails.subTextileProcess',
                'textileOrderDetails.bodyPart',
                'textileOrderDetails.fabricComposition',
                'textileOrderDetails.fabricType',
                'textileOrderDetails.color',
                'textileOrderDetails.colorType',
                'textileOrderDetails.unitOfMeasurement',
                'textileOrderDetails.customerBuyer',
                'textileOrderDetails.customerStyle',
                'buyer',
                'fabricSalesOrder',
                'currency'
            ])
            ->where('id',$id)
            ->first();
        return view(PackageConst::VIEW_PATH . 'textile_modules.textile_orders.view',[
            'textileOrders' => $textileOrders
        ]);
    }

    public function pdf($id)
    {
        $textileOrders = TextileOrder::query()
            ->with([
                'textileOrderDetails.fabricSalesOrderDetailId',
                'textileOrderDetails.subTextileOperation',
                'textileOrderDetails.subTextileProcess',
                'textileOrderDetails.bodyPart',
                'textileOrderDetails.fabricComposition',
                'textileOrderDetails.fabricType',
                'textileOrderDetails.color',
                'textileOrderDetails.colorType',
                'textileOrderDetails.unitOfMeasurement',
                'textileOrderDetails.customerBuyer',
                'textileOrderDetails.customerStyle',
                'buyer',
                'fabricSalesOrder',
                'currency'
            ])
            ->where('id',$id)
            ->first();

        $pdf = PDF::setOption('enable-local-file-access', true)
        ->loadView('dyeing::textile_modules.textile_orders.pdf', [
            'textileOrders' => $textileOrders,
        ])->setPaper('a4')->setOptions([
            'header-html' => view('skeleton::pdf.header'),
            'footer-html' => view('skeleton::pdf.footer'),
        ]);

        return $pdf->stream('dyeing_textile_orders.pdf');
    }

}
