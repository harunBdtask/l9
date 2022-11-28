<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\ReportSignature;
use SkylarkSoft\GoRMG\SystemSettings\Models\ReportSignatureDetail;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;
use SkylarkSoft\GoRMG\SystemSettings\PageReportNameConst;
use Symfony\Component\HttpFoundation\Response;

class ReportSignatureController extends Controller
{
    public function index()
    {
        $reportSignatures = ReportSignature::query()->isNotTemplate()->orderBy('id', 'DESC')->paginate();
        return view('system-settings::report_signature.index', compact('reportSignatures'));
    }

    public function create()
    {
        return view('system-settings::report_signature.create');
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $buyerIds = $request->get('buyer_id');

            $request['buyer_id'] = $this->filterBuyers($request->get('factory_id'), $buyerIds);

            if ($request->get('is_template')) {
                $reportSignature = ReportSignature::query()->create($request->all());
            } else {
                $reportSignature = ReportSignature::query()->firstOrCreate([
                    'buyer_id' => $request->get('buyer_id'),
                    'page_name' => $request->get('page_name'),
                    'is_template' => $request->get('is_template')
                ], $request->all());
            }

            $this->updateDetails($request->get('details'), $reportSignature->id);

            return response()->json([
                'message' => 'Saved Successfully!',
                'data' => $reportSignature,
                'status' => Response::HTTP_CREATED,
            ], Response::HTTP_CREATED);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    private function filterBuyers($factoryId, $buyerIds)
    {
        $arr = [];

        if (gettype($buyerIds) != "integer") {
            foreach ($buyerIds as $buyer) {
                $arr[] = (int)$buyer;
            }
            if ($buyerIds && in_array('all', $buyerIds)) {
                $arr = Buyer::where('factory_id', $factoryId)->pluck('id');
            }
        } else {
            $arr[] = $buyerIds;
        }

        return json_encode($arr);
    }

    public function show(Request $request, $id): JsonResponse
    {
        $isTemplate = $request->get('template');

        $reportSignature = ReportSignature::query()->with('details')->findOrFail($id);

        if ($isTemplate) {

            $details = [];

            foreach ($reportSignature['details'] as $detail) {
                $details[] = [
                    'designation' => $detail['designation'],
                    'name' => $detail['name'],
                    'sequence' => $detail['sequence'],
                    'username' => $detail['username'],
                    'user_id' => $detail['user_id']
                ];
            }

            $reportSignature = [
                'factory_id' => $reportSignature['factory_id'],
                'buyer_id' => $reportSignature['buyer_id'],
                'page_name' => $reportSignature['page_name'],
                'view_button' => $reportSignature['view_button'],
                'is_active' => $reportSignature['is_active'],
                'is_template' => false,
                'template_name' => null,
                'details' => $details
            ];
        }

        return response()->json($reportSignature);
    }

    public function update(Request $request, $id): JsonResponse
    {
        try {
            $buyerIds = $request->get('buyer_id');

            $request['buyer_id'] = $this->filterBuyers($request->get('factory_id'), $buyerIds);

            $reportSignature = ReportSignature::query()->findOrFail($id);
            $reportSignature->fill($request->all())->save();
            $this->updateDetails($request->get('details'), $id);
            return response()->json([
                'message' => 'Updated Successfully!',
                'data' => $reportSignature,
                'status' => Response::HTTP_CREATED,
            ], Response::HTTP_CREATED);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy($id)
    {
        try {
            $reportSignature = ReportSignature::query()->with('details')->findOrFail($id);

            foreach ($reportSignature->details as $detail) {
                $signature = ReportSignatureDetail::query()->where('id', $detail['id'])->first();
                if (Storage::disk('public')->exists($signature->image)) {
                    Storage::delete($signature->image);
                }
            }

            $reportSignature->details()->delete();
            $reportSignature->delete();
            Session::flash('error', 'Data Deleted Successfully');
            return redirect()->back();
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), $exception->getCode());
        }
    }


    public function updateDetails($details, $reportSignatureId)
    {
        foreach ($details as $detail) {
            $detail['report_signature_id'] = $reportSignatureId;
            ReportSignatureDetail::query()->updateOrCreate(['id' => $detail['id'] ?? null], $detail);
        }
    }

    public function deleteDetails($id): JsonResponse
    {
        try {
            $signature = ReportSignatureDetail::query()->where('id', $id)->first();

            if ($signature->image && Storage::disk('public')->exists($signature->image)) {
                Storage::delete($signature->image);
            }

            $signature->delete();
            return response()->json([
                'message' => 'Deleted Successfully!',
                'data' => null,
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), $exception->getCode());
        }
    }

    public function fetchPages(): JsonResponse
    {
        return response()->json(array_flip(PageReportNameConst::PAGES));
    }

    public function fetchTemplates(): JsonResponse
    {
        $templates = ReportSignature::query()->where('is_template', 1)
            ->get(['id', 'template_name as text']);
        return response()->json($templates);
    }

    public function fetchUserSignatures(): JsonResponse
    {
        try {
            $user = User::query()->get(['id', 'screen_name as text', 'signature']);
            return response()->json($user, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
