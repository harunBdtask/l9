<?php

namespace SkylarkSoft\GoRMG\Dyeing\Controllers\DyeingGoodsDelivery;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Dyeing\PackageConst;
use Symfony\Component\HttpFoundation\Response;
use  SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\Dyeing\Actions\DyeingGoodsDeliveryAction;
use SkylarkSoft\GoRMG\Dyeing\Requests\DyeingGoodsDeliveryFormRequest;
use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\DyeingGoodsDelivery\DyeingGoodsDelivery;
use SkylarkSoft\GoRMG\Dyeing\Services\TextileServices\Formatters\DyeingGoodsDeliveryFormatter;

class DyeingGoodsDeliveryController extends Controller
{
    public function index(Request $request)
    {
        $dyeingGoodsDelivery =  DyeingGoodsDelivery::query()
                        ->with([
                            'buyer',
                            'dyeingGoodsDeliveryDetails',
                            'textileOrder',
                        ])
                        ->search($request)
                        ->withSum('dyeingGoodsDeliveryDetails as total_delivery_qty','delivery_qty')
                        ->paginate();
        $factories = Factory::query()
            ->pluck('factory_name', 'id')
            ->prepend('Select', 0);
                                
        $buyers = Buyer::query()
            ->pluck('name', 'id')
            ->prepend('Select', 0);

        return view(PackageConst::VIEW_PATH . 'textile_modules.dyeing_goods_delivery.index',[
            'dyeingGoodsDelivery' => $dyeingGoodsDelivery,
            'factories' => $factories,
            'buyers' => $buyers
        ]);
    }

    public function create()
    {
        return view(PackageConst::VIEW_PATH . 'textile_modules.dyeing_goods_delivery.form');
    }

    public function store(DyeingGoodsDeliveryFormRequest $request,
                        DyeingGoodsDeliveryAction $action,
                        DyeingGoodsDelivery $dyeingGoodsDelivery)
    {
        try {
            DB::beginTransaction();
            $dyeingGoodsDelivery->fill($request->all())->save();
            $action->storeDetails(
            $dyeingGoodsDelivery,
            $request->input('dyeing_goods_delivery_details')
            );
            DB::commit();
            return response()->json([
            'message' => 'Dyeing Goods Delivery Store Successfully',
            'data' => $dyeingGoodsDelivery,
            'status' => Response::HTTP_CREATED,
            ]);
        } catch(Exception $exception){
            return response()->json([
            'message' => $exception->getMessage(),
            'line' => $exception->getLine(),
            'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
    }

    public function edit(DyeingGoodsDelivery $dyeingGoodsDelivery,
    DyeingGoodsDeliveryFormatter $formatter)
    {
        try {
            return response()->json([
                'message' => 'Fetch Successfully',
                'data' => $formatter->format($dyeingGoodsDelivery),
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(
        DyeingGoodsDeliveryFormRequest $request,
        DyeingGoodsDeliveryAction $action,
        DyeingGoodsDelivery $dyeingGoodsDelivery)
    {
        try{
        DB::beginTransaction();
        $dyeingGoodsDelivery->fill($request->all())->save();

        $action->updateDetails(
            $dyeingGoodsDelivery,
            $request->input('dyeing_goods_delivery_details')
        );
        DB::commit();
        return response()->json([
            'message' => 'Dyeing Goods Delivery Update Successfully',
            'data' => $dyeingGoodsDelivery,
            'status' => Response::HTTP_CREATED,
        ]);
        } catch(Exception $exception){
        return response()->json([
            'message' => $exception->getMessage(),
            'line' => $exception->getLine(),
            'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(DyeingGoodsDelivery $dyeingGoodsDelivery)
    {
        try {
            $dyeingGoodsDelivery->delete();
            Session::flash('success', 'Dyeing Goods Delivery deleted successfully');
        } catch(Exception $exception) {
            Session::flash('error', $exception->getMessage());
        } finally {
            return back();
        }
    }
}