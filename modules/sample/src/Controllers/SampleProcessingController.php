<?php

namespace SkylarkSoft\GoRMG\Sample\Controllers;

use App\Http\Controllers\Controller;
use Excel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;
use SkylarkSoft\GoRMG\Sample\Exports\SampleProcessingExport;
use SkylarkSoft\GoRMG\Sample\Models\SampleProcessing;
use SkylarkSoft\GoRMG\Sample\Models\SampleProcessingDetails;
use SkylarkSoft\GoRMG\Sample\Services\SampleProcessingFetchService;
use Symfony\Component\HttpFoundation\Response;

class SampleProcessingController extends Controller
{
    public function index(Request $request)
    {
        try {
            $q = $request->all() ?? null;
            $search = $q['search'] ?? null;
            $values = (new SampleProcessingFetchService($search))->get(15);

            return view('sample::sample-processing.index', compact('values'));
        } catch (Exception $e) {
            return redirect()->back()->with(["error" => $e->getMessage()]);
        }
    }

    public function create()
    {
        return view('sample::sample-processing.create');
    }

    public function createOrUpdate(Request $request)
    {
        try {
            DB::beginTransaction();
            if (isset($request->mainForm['id'])) {
                $mainForm = SampleProcessing::find($request->mainForm['id']);
                $mainForm->update($request->mainForm);
                $message = 'Successfully Updated!';
            } else {
                $mainForm = new SampleProcessing($request->mainForm);
                $mainForm->save();
                $message = 'Successfully Saved!';
            }
            $sampleProcessing = new SampleProcessing();
            $findData = $sampleProcessing->find($mainForm->id);
            foreach ($request->items as $item) {
                if (isset($item['id'])) {
                    $findData->processingDetails()->find($item['id'])
                    ->update($item);

                    continue;
                }
                $findData->processingDetails()->create($item);
            }
            $mainForm->update(['total_calculation' => $request->total_calculation]);
            DB::commit();

            return response()->json(['message' => $message, 'id' => $mainForm->id], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something Went Wrong!'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function delete(SampleProcessing $sampleProcessing)
    {
        try {
            DB::beginTransaction();
            $sampleProcessing->processingDetails()->delete();
            $sampleProcessing->sampleProductionDetails()->delete();
            $sampleProcessing->productions()->delete();
            $sampleProcessing->delete();
            DB::commit();

            session()->flash('success', 'Successfully Deleted');
        } catch (\Throwable $e) {
            session()->flash('danger', $e->getMessage());
        }

        return redirect()->back();
    }

    public function show(SampleProcessing $sampleProcessing)
    {
        try {
            $sampleProcessing->viewType = 'browse';

            return view('sample::sample-processing.details_info', compact('sampleProcessing'));
        } catch (Exception $exception) {
            return redirect()->back()->with(["error" => $exception->getMessage()]);
        }
    }

    public function view(SampleProcessing $sampleProcessing)
    {
        try {
            $sampleProcessing->viewType = 'blade';

            return view('sample::sample-processing.view', compact('sampleProcessing'));
        } catch (Exception $exception) {
            return redirect()->back()->with(["error" => $exception->getMessage()]);
        }
    }

    public function pdf(SampleProcessing $sampleProcessing)
    {
        try {
            $sampleProcessing->viewType = 'pdf';
            $pdf = PDF::loadView('sample::sample-processing.pdf', compact('sampleProcessing'))
                ->setPaper('a4')->setOrientation('landscape')
                ->setOptions([
                    'header-html' => view('skeleton::pdf.header'),
                    'footer-html' => view('skeleton::pdf.footer'),
                ]);

            return $pdf->stream('sample-processing.pdf');
        } catch (Exception $exception) {
            return redirect()->back()->with(["error" => $exception->getMessage()]);
        }
    }

    public function excel(SampleProcessing $sampleProcessing)
    {
        $sampleProcessing->viewType = 'excel';

        return Excel::download(
            new SampleProcessingExport($sampleProcessing),
            'sample-processing.xlsx'
        );
    }

    public function deleteDetails(SampleProcessingDetails $sampleProcessingDetails, Request $request)
    {
        try {
            DB::beginTransaction();
            $mainData = SampleProcessing::find($sampleProcessingDetails->sample_processing_id);
            $mainData->update(['total_calculation' => $request->total_calculation]);
            $sampleProcessingDetails->delete();
            DB::commit();

            return response()->json(['message' => 'Successfully Deleted'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something Went Wrong!'], Response::HTTP_BAD_REQUEST);
        }
    }
}
