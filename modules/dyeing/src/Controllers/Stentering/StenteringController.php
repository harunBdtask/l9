<?php

namespace SkylarkSoft\GoRMG\Dyeing\Controllers\Stentering;

use App\Http\Controllers\Controller;
use Exception;
use SkylarkSoft\GoRMG\Dyeing\PackageConst;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\Stentering\Stentering;
use SkylarkSoft\GoRMG\Dyeing\Requests\StenteringFormRequest;
use SkylarkSoft\GoRMG\Dyeing\Actions\DyeingStenteringAction;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use  SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\Dyeing\Services\TextileServices\Formatters\StenteringFormatter;

class StenteringController extends Controller
{
    public function index(Request $request)
    {
        $stenterings =  Stentering::query()
                        ->with([
                            'buyer',
                            'stenteringDetails',
                            'textileOrder',
                            'machine'
                        ])
                        ->search($request)
                        ->withSum('stenteringDetails as total_finish','finish_qty')
                        ->paginate();
        $factories = Factory::query()
            ->pluck('factory_name', 'id')
            ->prepend('Select', 0);
                                
        $buyers = Buyer::query()
            ->pluck('name', 'id')
            ->prepend('Select', 0);

        return view(PackageConst::VIEW_PATH . 'textile_modules.stentering.index',[
            'stenterings' => $stenterings,
            'factories' => $factories,
            'buyers' => $buyers
        ]);
    }

    public function create()
    {
        return view(PackageConst::VIEW_PATH . 'textile_modules.stentering.form');
    }

    public function store(StenteringFormRequest $request,
                         DyeingStenteringAction $action,
                         Stentering $stentering)
    {
        try {
            DB::beginTransaction();
            $stentering->fill($request->all())->save();
            $action->storeDetails(
                $stentering,
                $request->input('stentering_details')
            );
            DB::commit();
            return response()->json([
                'message' => 'Stentering Store Successfully',
                'data' => $stentering,
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

    public function edit(Stentering          $stentering,
                        StenteringFormatter $formatter)
    {
        try {
            return response()->json([
                'message' => 'Fetch Successfully',
                'data' => $formatter->format($stentering),
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
            StenteringFormRequest $request,
            Stentering        $stentering,
            DyeingStenteringAction  $action
            )
    {
        try{
        DB::beginTransaction();
        $stentering->fill($request->all())->save();

        $action->updateDetails(
            $stentering,
            $request->input('stentering_details')
        );
        DB::commit();
        return response()->json([
            'message' => 'Stentering Update Successfully',
            'data' => $stentering,
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

    public function destroy(Stentering $stentering)
    {
        try {
            $stentering->delete();
            Session::flash('success', 'Stentering deleted successfully');
        } catch(Exception $exception) {
            Session::flash('error', $exception->getMessage());
        } finally {
            return back();
        }
    }
}