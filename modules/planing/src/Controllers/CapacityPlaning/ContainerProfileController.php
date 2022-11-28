<?php

namespace SkylarkSoft\GoRMG\Planing\Controllers\CapacityPlaning;

use App\Http\Controllers\Controller;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Planing\Actions\ContainerProfileAction;
use SkylarkSoft\GoRMG\Planing\Models\ContainerProfile\ContainerProfile;
use SkylarkSoft\GoRMG\Planing\Requests\ContainerProfileRequest;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ContainerProfileController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $containerProfiles = ContainerProfile::query()
            ->with('details')
            ->orderByDesc('id')
            ->when($search, function ($q) use ($search) {
                $q->whereHas('details', function ($query) use ($search) {
                    $query->where('container_no', 'LIKE', "%$search%")
                        ->Orwhere('ex_factory_date', $search);
                });
            })
            ->paginate(20);

        return view('planing::container_profile.index', [
            'containerProfiles' => $containerProfiles,
        ]);
    }

    public function create()
    {
        return view('planing::container_profile.form');
    }

    /**
     * @param ContainerProfileRequest $request
     * @param ContainerProfile $containerProfile
     * @param ContainerProfileAction $action
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(
        ContainerProfileRequest $request,
        ContainerProfile        $containerProfile,
        ContainerProfileAction  $action
    ): JsonResponse {
        try {
            DB::beginTransaction();
            $containerProfile->fill($request->all())->save();
            $action->storeDetails($containerProfile, $request->input('details'));
            DB::commit();

            return response()->json([
                'message' => 'Container profile created successfully',
                'data' => $containerProfile,
                'status' => Response::HTTP_CREATED,
            ], Response::HTTP_CREATED);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param ContainerProfile $containerProfile
     * @return JsonResponse
     */
    public function edit(ContainerProfile $containerProfile): JsonResponse
    {
        $containerProfile->load('details');

        return response()->json([
            'message' => 'Fetch Container profile successfully',
            'data' => $containerProfile,
            'status' => Response::HTTP_OK,
        ], Response::HTTP_OK);
    }

    /**
     * @param ContainerProfileRequest $request
     * @param ContainerProfile $containerProfile
     * @param ContainerProfileAction $action
     * @return JsonResponse
     * @throws Throwable
     */
    public function update(
        ContainerProfileRequest $request,
        ContainerProfile        $containerProfile,
        ContainerProfileAction  $action
    ): JsonResponse {
        try {
            DB::beginTransaction();
            $containerProfile->fill($request->all())->save();
            $action->updateDetails($containerProfile, $request->input('details'));
            DB::commit();

            return response()->json([
                'message' => 'Container profile created successfully',
                'data' => $containerProfile,
                'status' => Response::HTTP_CREATED,
            ], Response::HTTP_CREATED);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param ContainerProfile $containerProfile
     * @param ContainerProfileAction $action
     * @return RedirectResponse
     * @throws Throwable
     */
    public function destroy(ContainerProfile $containerProfile, ContainerProfileAction $action): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $action->destroyDetails($containerProfile);
            $containerProfile->delete();
            DB::commit();

            Session::flash('success', 'Container Profile deleted successfully');
        } catch (Exception $e) {
            DB::rollBack();

            Session::flash('error', $e->getMessage());
        } finally {
            return back();
        }
    }
}
