<?php

namespace SkylarkSoft\GoRMG\Commercial\Controllers\lien;

use Excel;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use PDF;
use SkylarkSoft\GoRMG\Commercial\Exports\LienExport;
use SkylarkSoft\GoRMG\Commercial\Models\Lien\LienDetail;
use SkylarkSoft\GoRMG\Inventory\Exports\DailyYarnIssueReport;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\Commercial\Models\Lien\Lien;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\AdvisingBank;
use SkylarkSoft\GoRMG\Commercial\Requests\LienFormRequest;
use SkylarkSoft\GoRMG\Commercial\Services\Commercial\LienDataService;

class LienController extends Controller
{
    public function index(Request $request)
    {
        $view['factories'] = Factory::query()->get();
        $view['banks'] = AdvisingBank::query()->get(['id', 'name']);
        $view['lienList'] = Lien::query()->with(['factory', 'bank'])->paginate();
        return view('commercial::lien.index', $view);
    }

    public function create()
    {
        return view('commercial::lien.create');
    }

    /**
     * @throws \Throwable
     */
    public function store(LienFormRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $lien = Lien::query()->firstOrNew(['id' => $request->input('id')]);
            $lien->fill($request->all())->save();
            $lienData = (new LienDataService())->for($lien);
            DB::commit();
            return response()->json($lienData->first(), Response::HTTP_CREATED);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @throws \Throwable
     */
    public function storeDetails(Request $request, $lien_id)
    {
        try {
            DB::beginTransaction();
            if (is_array($request->all())) {
                $ids=[];
                foreach ($request->all() as $requestItem) {
                    $newRequestItem = Arr::add($requestItem, 'lien_id', $lien_id);
                    $lienDetail = LienDetail::query()->firstOrNew(['id'=>$newRequestItem['id']]);
                    $lienDetail->fill($newRequestItem)->save();
                    $ids[]=$lienDetail->id;
                }
                LienDetail::query()->where('lien_id', $lien_id)->whereNotIn('id', $ids)->delete();
            }
            $lien = Lien::query()->findOrFail($lien_id);
            $lienData = (new LienDataService())->for($lien);
            DB::commit();
            return response()->json($lienData->first(), Response::HTTP_CREATED);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function edit($id): JsonResponse
    {
        try {
            $lien = Lien::query()->findOrFail($id);
            $lienData = (new LienDataService())->for($lien);
            return response()->json($lienData->first(), Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function view($id)
    {
        $view = Lien::query()->with('bank', 'details', 'factory')->findOrFail($id);
        return view('commercial::lien.view.view', $view);
    }

    public function pdf($id)
    {
        $view = Lien::query()->with('bank', 'details', 'factory')->findOrFail($id);
        return PDF::loadView('commercial::lien.view.pdf', $view)->stream('lien');
    }

    public function excel($id)
    {
        $view = Lien::query()->with('bank', 'details', 'factory')->findOrFail($id);
        return Excel::download(new LienExport($view), 'lien.xlsx');
    }

    /**
     * @throws \Throwable
     */
    public function destroy($id): \Illuminate\Http\RedirectResponse
    {
        try {
            DB::beginTransaction();
            $lien = Lien::query()->findOrFail($id);
            $lien->delete();
            DB::commit();
            return back()->with('success', 'Success Fully Delete Lien');
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

}
