<?php


namespace SkylarkSoft\GoRMG\Dyeing\Controllers\Tumble;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Dyeing\PackageConst;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\Dyeing\Requests\TumbleFormRequest;
use SkylarkSoft\GoRMG\Dyeing\Actions\DyeingTumbleAction;
use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\Tumble\Tumble;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use  SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\Dyeing\Services\TextileServices\Formatters\TumbleFormatter;


class TumbleController extends Controller
{
    public function index(Request $request)
    {
        $tumbles =  Tumble::query()
                        ->with([
                            'buyer',
                            'tumbleDetails',
                            'textileOrder',
                        ])
                        ->search($request)
                        ->withSum('tumbleDetails as total_finish','finish_qty')
                        ->paginate();
        $factories = Factory::query()
            ->pluck('factory_name', 'id')
            ->prepend('Select', 0);
                                
        $buyers = Buyer::query()
            ->pluck('name', 'id')
            ->prepend('Select', 0);

        return view(PackageConst::VIEW_PATH . 'textile_modules.tumble.index',[
            'tumbles' => $tumbles,
            'factories' => $factories,
            'buyers' => $buyers
        ]);
    }

    public function create()
    {
        return view(PackageConst::VIEW_PATH . 'textile_modules.tumble.form');
    }

    public function store(TumbleFormRequest $request,
                         DyeingTumbleAction $action,
                         Tumble $tumble)
    {
        try {
            DB::beginTransaction();
            $tumble->fill($request->all())->save();
            $action->storeDetails(
                $tumble,
                $request->input('tumble_details')
            );
            DB::commit();
            return response()->json([
                'message' => 'Tumble Store Successfully',
                'data' => $tumble,
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

    public function edit(Tumble $tumble,
                        TumbleFormatter $formatter)
    {
        try {
            return response()->json([
                'message' => 'Fetch Successfully',
                'data' => $formatter->format($tumble),
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
                    TumbleFormRequest $request,
                    DyeingTumbleAction $action,
                    Tumble $tumble)
    {
        try{
        DB::beginTransaction();
        $tumble->fill($request->all())->save();

        $action->updateDetails(
            $tumble,
            $request->input('tumble_details')
        );
        DB::commit();
        return response()->json([
            'message' => 'Tumble Update Successfully',
            'data' => $tumble,
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

    public function destroy(Tumble $tumble)
    {
        try {
            $tumble->delete();
            Session::flash('success', 'Tumble deleted successfully');
        } catch(Exception $exception) {
            Session::flash('error', $exception->getMessage());
        } finally {
            return back();
        }
    }
}