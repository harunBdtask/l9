<?php


namespace SkylarkSoft\GoRMG\Dyeing\Controllers\Dryer;

use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Dyeing\PackageConst;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use  SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\Dyeing\Actions\DyeingDryerAction;
use SkylarkSoft\GoRMG\Dyeing\Requests\DryerFormRequest;
use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\Dryer\Dryer;
use SkylarkSoft\GoRMG\Dyeing\Services\TextileServices\Formatters\DyeingDryerFormatter;

class DryerController extends Controller
{
    public function index(Request $request)
    {
        $dryers = Dryer::query()
            ->with([
                'buyer',
                'textileOrder',
                'dryerDetails',
                'machine'
            ])
            ->search($request)
            ->withSum('dryerDetails as total_finish', 'finish_qty')
            ->orderBy('id', 'desc')
            ->paginate();
        $factories = Factory::query()
            ->pluck('factory_name', 'id')
            ->prepend('Select', 0);

        $buyers = Buyer::query()
            ->pluck('name', 'id')
            ->prepend('Select', 0);
        return view(PackageConst::VIEW_PATH . 'textile_modules.dryer.index', [
            'dryers' => $dryers,
            'factories' => $factories,
            'buyers' => $buyers
        ]);
    }

    public function create()
    {
        return view(PackageConst::VIEW_PATH . 'textile_modules.dryer.form');
    }

    public function store(DryerFormRequest  $request,
                          Dryer             $dryer,
                          DyeingDryerAction $action)
    {
        try {
            DB::beginTransaction();
            $dryer->fill($request->all())->save();
            $action->storeDetails(
                $dryer,
                $request->input('dryer_details')
            );
            DB::commit();
            return response()->json([
                'message' => 'Dryer Store Successfully',
                'data' => $dryer,
                'status' => Response::HTTP_CREATED,
            ]);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function edit(
        Dryer                $dryer,
        DyeingDryerFormatter $formatter
    )
    {
        try {
            return response()->json([
                'message' => 'Fetch Successfully',
                'data' => $formatter->format($dryer),
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
        DryerFormRequest  $request,
        Dryer             $dryer,
        DyeingDryerAction $action
    )
    {
        try {
            DB::beginTransaction();
            $dryer->fill($request->all())->save();

            $action->updateDetails(
                $dryer,
                $request->input('dryer_details')
            );
            DB::commit();
            return response()->json([
                'message' => 'Dryer Update Successfully',
                'data' => $dryer,
                'status' => Response::HTTP_CREATED,
            ]);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(Dryer $dryer)
    {
        try {
            $dryer->delete();
            Session::flash('success', 'Dryer deleted successfully');
        } catch (Exception $exception) {
            Session::flash('error', $exception->getMessage());
        } finally {
            return back();
        }
    }
}
