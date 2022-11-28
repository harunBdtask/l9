<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\SubTextileControllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

use const S_DEL_MSG;
use const S_SAVE_MSG;
use const S_UPDATE_MSG;

use SkylarkSoft\GoRMG\Subcontract\Filters\Filter;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubTextileOrder;
use SkylarkSoft\GoRMG\Subcontract\Requests\SubTextileRequests\SubTextileOrderRequest;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Currency;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Supplier;

use const SOMETHING_WENT_WRONG;

use Symfony\Component\HttpFoundation\Response;
use Throwable;

class SubTextileOrderController extends Controller
{
    public function index(Request $request)
    {
        $company = $request->get('company');
        $party = $request->get('party');
        $order_no = $request->get('order_no');
        $receive_date = $request->get('receive_date');
        $currency = $request->get('currency');
        $payment_basis = $request->get('payment_basis');
        $description = $request->get('description');

        $subTextileOrders = SubTextileOrder::query()
            ->when($company, Filter::applyFilter('factory_id', $company))
            ->when($party, Filter::applyFilter('supplier_id', $party))
            ->when($order_no, function (Builder $query) use ($order_no) {
                $query->where('order_no', 'LIKE', "%$order_no%");
            })
            ->when($receive_date, Filter::applyFilter('receive_date', $receive_date))
            ->when($currency, Filter::applyFilter('currency_id', $currency))
            ->when($payment_basis, Filter::applyFilter('payment_basis', $payment_basis))
            ->when($description, function (Builder $query) use ($description) {
                $query->where('description', 'LIKE', "%$description%");
            })
            ->orderBy('id', 'desc')
            ->paginate(15);

        $factories = Factory::query()->pluck('factory_name', 'id');
        $currencies = Currency::query()->pluck('currency_name', 'id');
        $currencies = $currencies->prepend('Select Currency', 0);
        $parties = Buyer::query()
            ->where('party_type', 'Subcontract')
            ->factoryFilter()
            ->pluck('name', 'id')
            ->prepend('Select', 0);

        if ($party) {
            $supplier = Supplier::query()->find($party);
            $supplier = [$supplier->id => $supplier->name];
        }

        $payment_basis = SubTextileOrder::PAYMENT_BASIS_OPTIONS;

        return view('subcontract::textile_module.order_management.index', [
            'sub_textile_orders' => $subTextileOrders,
            'factories' => $factories,
            'currencies' => $currencies,
            'payment_basis' => $payment_basis,
            'supplier' => $supplier ?? [],
            'parties' => $parties ?? [],
        ]);
    }

    public function form()
    {
        return view('subcontract::textile_module.order_management.form');
    }

    public function store(SubTextileOrderRequest $request)
    {
        try {
            DB::beginTransaction();
            $subTextileOrder = new SubTextileOrder();
            $subTextileOrder->fill($request->except('_token'));
            $subTextileOrder->save();
            DB::commit();
            $data = $subTextileOrder;
            $status = Response::HTTP_CREATED;
            $message = S_SAVE_MSG;
        } catch (Exception $e) {
            DB::rollBack();
            $status = Response::HTTP_INTERNAL_SERVER_ERROR;
            $message = SOMETHING_WENT_WRONG;
            $error = $e->getMessage();
        }

        return response()->json([
            'data' => $data ?? null,
            'status' => $status,
            'message' => $message,
            'error' => $error ?? null,
        ], $status);
    }

    public function edit(SubTextileOrder $subTextileOrder): JsonResponse
    {
        try {
            return response()->json($subTextileOrder, Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(SubTextileOrder $subTextileOrder, SubTextileOrderRequest $request)
    {
        try {
            DB::beginTransaction();
            $subTextileOrder->fill($request->except('_token', '_method'));
            $subTextileOrder->save();
            DB::commit();
            $data = $subTextileOrder;
            $status = Response::HTTP_CREATED;
            $message = S_UPDATE_MSG;
        } catch (Exception $e) {
            DB::rollBack();
            $status = Response::HTTP_INTERNAL_SERVER_ERROR;
            $message = SOMETHING_WENT_WRONG;
            $error = $e->getMessage();
        }

        return response()->json([
            'data' => $data ?? null,
            'status' => $status,
            'message' => $message,
            'error' => $error ?? null,
        ], $status);
    }

    /**
     * @param SubTextileOrder $subTextileOrder
     * @return RedirectResponse
     * @throws Throwable
     */
    public function destroy(SubTextileOrder $subTextileOrder): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $subTextileOrder->subTextileOrderDetails()->delete();
            $subTextileOrder->delete();
            DB::commit();
            Session::flash('success', S_DEL_MSG);
        } catch (Exception $e) {
            DB::rollBack();
            Session::flash('error', $e->getMessage());
        }

        return redirect()->back();
    }
}
