<?php

namespace SkylarkSoft\GoRMG\Approval\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Approval\ApprovalPermissionPagesConst;
use SkylarkSoft\GoRMG\Approval\Models\Approval;
use SkylarkSoft\GoRMG\Approval\Requests\PermissionRequest;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use Symfony\Component\HttpFoundation\Response;

class PermissionController extends Controller
{
    public function index(Request $request)
    {

        $approvals = Approval::query()
            ->with('factory', 'user', 'alternativeUser')
            ->when($request->get('search'), function ($query) use ($request) {
                return $query->whereHas('user', function ($q) use ($request) {
                    return $q->where('screen_name', 'LIKE', '%' . $request->get('search'));
                })->orWhereHas('alternativeUser', function ($q) use ($request) {
                    return $q->where('screen_name', 'LIKE', '%' . $request->get('search'));
                })->orWhere('page_name', 'LIKE', '%' . $request->get('search'));
            })
            ->latest()
            ->paginate();

        return view('approval::approvals.permissions.index', compact('approvals'));
    }

    public function create()
    {
        return view('approval::approvals.permissions.create');
    }

    /**
     * @param PermissionRequest $request
     * @return JsonResponse
     */
    public function store(PermissionRequest $request): JsonResponse
    {
        try {
            foreach ($request->all() as $permission) {
                if (in_array('all', $permission['buyer_ids'])) {
                    $permission['buyer_ids'] = Buyer::query()->pluck('id')->implode(',');
                } else {
                    $permission['buyer_ids'] = collect($permission['buyer_ids'])->implode(',');
                }
                Approval::query()->updateOrCreate(['id' => $permission['id'] ?? null], $permission);
            }

            return response()->json($request->all(), Response::HTTP_CREATED);
        } catch (\Exception $exception) {
            return response()->json(
                [
                    'message' => $exception->getMessage(),
                    'line' => $exception->getLine()
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function get($id): JsonResponse
    {
        try {
            $approval = Approval::query()->findOrFail($id);
            $approvals = Approval::query()
                ->with(['user', 'alternativeUser'])
                ->where('page_name', $approval->page_name)
                ->where('factory_id', $approval->factory_id)
                ->get()->map(function ($collection) {
                    $collection['buyers'] = collect($collection->buyer_names)->pluck('name')->implode(', ');
                    $collection['user_name'] = $collection->user->screen_name;
                    $collection['full_name'] = $collection->user->full_name;
                    $collection['designation'] = $collection->user->designation;
                    $collection['alternative_user_name'] = $collection->alternativeUser->screen_name;
                    return $collection;
                });

            return response()->json($approvals, Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_OK);
        }
    }

    /**
     * @param $id
     * @return RedirectResponse
     * @throws \Throwable
     */
    public function destroy($id): RedirectResponse
    {
        try {
            DB::beginTransaction();
            Approval::query()->findOrFail($id)->delete();
            DB::commit();
            session()->flash('success', "Successfully deleted");
        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('error', "Something Went Wrong");
        } finally {
            return redirect()->back();
        }
    }

    /**
     * @param $id
     * @return JsonResponse
     * @throws \Throwable
     */
    public function deleteDetails($id): JsonResponse
    {
        try {
            DB::beginTransaction();
            Approval::query()->findOrFail($id)->delete();
            DB::commit();
            return response()->json(null, Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json($e->getMessage(), Response::HTTP_OK);
        }
    }

    /**
     * @return JsonResponse
     */
    public function fetchPermissionPages(): JsonResponse
    {
        return response()->json(ApprovalPermissionPagesConst::PERMISSION_PAGES);
    }
}
