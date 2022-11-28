<?php

namespace SkylarkSoft\GoRMG\TrimsStore\Controllers\V3\TrimsStoreIssueReturn;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\TrimsStore\Models\V3\TrimsStoreIssueReturn\TrimsStoreIssueReturn;
use SkylarkSoft\GoRMG\TrimsStore\PackageConst;
use SkylarkSoft\GoRMG\TrimsStore\Requests\V3\TrimsStoreIssueReturn\TrimsStoreIssueReturnFormRequest;
use Symfony\Component\HttpFoundation\Response;

class TrimsStoreIssueReturnController extends Controller
{
    public function index()
    {
        // TODO;
    }

    public function create()
    {
        return view(PackageConst::VIEW_NAMESPACE . '::v3.issue-return.create');
    }

    /**
     * @param TrimsStoreIssueReturnFormRequest $request
     * @param TrimsStoreIssueReturn $issueReturn
     * @return JsonResponse
     */
    public function store(
        TrimsStoreIssueReturnFormRequest $request,
        TrimsStoreIssueReturn            $issueReturn
    ): JsonResponse {
        try {
            $issueReturn->fill($request->all())->save();

            return response()->json([
                'message' => 'Trims store issue return stored successfully',
                'data' => $issueReturn,
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
     * @param TrimsStoreIssueReturn $issueReturn
     * @return JsonResponse
     */
    public function edit(TrimsStoreIssueReturn $issueReturn): JsonResponse
    {
        try {
            return response()->json([
                'message' => 'Fetch trims store issue return successfully',
                'data' => $issueReturn,
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
     * @param TrimsStoreIssueReturnFormRequest $request
     * @param TrimsStoreIssueReturn $issueReturn
     * @return JsonResponse
     */
    public function update(
        TrimsStoreIssueReturnFormRequest $request,
        TrimsStoreIssueReturn            $issueReturn
    ): JsonResponse {
        try {
            $issueReturn->fill($request->all())->save();

            return response()->json([
                'message' => 'Trims store issue return updated successfully',
                'data' => $issueReturn,
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
     * @param TrimsStoreIssueReturn $issueReturn
     * @return RedirectResponse
     */
    public function destroy(TrimsStoreIssueReturn $issueReturn): RedirectResponse
    {
        try {
            $issueReturn->delete();
            Session::flash('success', 'Trims store issue return deleted successfully');
        } catch (Exception $e) {
            Session::flash('success', $e->getMessage());
        } finally {
            return back();
        }
    }
}
