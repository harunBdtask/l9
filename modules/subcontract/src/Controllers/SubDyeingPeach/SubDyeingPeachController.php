<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\SubDyeingPeach;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubDyeingPeach\SubDyeingPeach;
use SkylarkSoft\GoRMG\Subcontract\Requests\SubTextileRequests\SubDyeingPeachFormRequest;
use SkylarkSoft\GoRMG\Subcontract\Services\SubTextileServices\Formatters\SubDyeingPeachFormatter;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class SubDyeingPeachController extends Controller
{
    /**
     * @return Application|Factory|View
     */
    public function index()
    {
        $subDyeingPeaches = SubDyeingPeach::query()
            ->with([
                'peachDetails.color',
                'machine',
            ])
            ->orderBy('id', 'desc')
            ->paginate();

        return view('subcontract::textile_module.peach.index', [
            'subDyeingPeaches' => $subDyeingPeaches,
        ]);
    }

    /**
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('subcontract::textile_module.peach.form');
    }

    /**
     * @param SubDyeingPeachFormRequest $request
     * @param SubDyeingPeach $dyeingPeach
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(SubDyeingPeachFormRequest $request, SubDyeingPeach $dyeingPeach): JsonResponse
    {
        try {
            DB::beginTransaction();
            $dyeingPeach->fill($request->all())->save();
            $dyeingPeach->peachDetails()->createMany($request->input('peach_details'));
            DB::commit();

            return response()->json([
                'message' => 'Peach stored successfully',
                'data' => $dyeingPeach,
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
     * @param SubDyeingPeach $dyeingPeach
     * @param SubDyeingPeachFormatter $subDyeingPeachFormatter
     * @return JsonResponse
     */
    public function edit(SubDyeingPeach $dyeingPeach, SubDyeingPeachFormatter $subDyeingPeachFormatter): JsonResponse
    {
        try {
            return response()->json([
                'message' => 'Fetch peach edit data successfully',
                'data' => $subDyeingPeachFormatter->format($dyeingPeach),
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
     * @param SubDyeingPeachFormRequest $request
     * @param SubDyeingPeach $dyeingPeach
     * @return JsonResponse
     * @throws Throwable
     */
    public function update(SubDyeingPeachFormRequest $request, SubDyeingPeach $dyeingPeach): JsonResponse
    {
        try {
            DB::beginTransaction();
            $dyeingPeach->fill($request->all())->save();
            // Update details
            foreach ($request->input('peach_details') as $detail) {
                $dyeingPeach->peachDetails()->updateOrCreate([
                    'id' => $detail['id'],
                ], $detail);
            }
            DB::commit();

            return response()->json([
                'message' => 'Peach updated successfully',
                'data' => $dyeingPeach,
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
     * @param SubDyeingPeach $dyeingPeach
     * @return RedirectResponse
     * @throws Throwable
     */
    public function destroy(SubDyeingPeach $dyeingPeach): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $dyeingPeach->peachDetails()->delete();
            $dyeingPeach->delete();
            DB::commit();

            Session::flash('success', S_DEL_MSG);
        } catch (Exception $exception) {
            Session::flash('error', $exception->getMessage());
        } finally {
            return back();
        }
    }
}
