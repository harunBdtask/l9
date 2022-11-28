<?php

namespace SkylarkSoft\GoRMG\Sample\Controllers;

use App\Http\Controllers\Controller;
use Excel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;
use SkylarkSoft\GoRMG\Sample\Exports\SampleTrimsIssueExport;
use SkylarkSoft\GoRMG\Sample\Models\SampleTrimsIssue;
use SkylarkSoft\GoRMG\Sample\Services\SampleTrimsIssueFetchService;
use Symfony\Component\HttpFoundation\Response;

class SampleTrimsIssueController extends Controller
{
    public function index(Request $request)
    {
        try {
            $q = $request->all() ?? null;
            $search = $q['search'] ?? null;
            $values = (new SampleTrimsIssueFetchService($search))->get(15);
            $values->issueBasis = SampleTrimsIssue::ISSUE_BASIS;

            return view('sample::trims-issue.index', compact('values'));
        } catch (Exception $e) {
            return redirect()->back()->with(["error" => $e->getMessage()]);
        }
    }

    public function create()
    {
        return view('sample::trims-issue.create');
    }

    public function save(Request $request)
    {
        try {
            DB::beginTransaction();
            if (isset($request->form['id'])) {
                $mainData = SampleTrimsIssue::find($request->form['id']);
                $mainData->update($request->form);
                $message = 'Successfully Updated!';
            } else {
                $mainData = new SampleTrimsIssue($request->form);
                $mainData->save();
                $message = 'Successfully Saved!';
            }
            $sampleTrimsIssue = new SampleTrimsIssue();
            $findData = $sampleTrimsIssue->find($mainData->id);
            foreach ($request->items as $item) {
                if (isset($item['id'])) {
                    $findData->trimsIssueDetails()->find($item['id'])
                    ->update($item);

                    continue;
                }
                $findData->trimsIssueDetails()->create($item);
            }
            $mainData->update(['total_calculation' => $request->total_calculation]);
            DB::commit();

            return response()->json(['message' => $message, 'id' => $mainData->id], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something Went Wrong!'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function delete(SampleTrimsIssue $sampleTrimsIssue)
    {
        try {
            DB::beginTransaction();
            $sampleTrimsIssue->trimsIssueDetails()->delete();
            $sampleTrimsIssue->delete();
            DB::commit();

            session()->flash('success', 'Successfully Deleted');
        } catch (\Throwable $e) {
            session()->flash('danger', $e->getMessage());
        }

        return redirect()->back();
    }

    public function show(SampleTrimsIssue $sampleTrimsIssue)
    {
        try {
            $sampleTrimsIssue->viewType = 'browse';
            $sampleTrimsIssue->issueBasis = SampleTrimsIssue::ISSUE_BASIS;

            return view('sample::trims-issue.main_details', compact('sampleTrimsIssue'));
        } catch (Exception $exception) {
            return redirect()->back()->with(["error" => $exception->getMessage()]);
        }
    }

    public function view(SampleTrimsIssue $sampleTrimsIssue)
    {
        try {
            $sampleTrimsIssue->viewType = 'blade';
            $sampleTrimsIssue->issueBasis = SampleTrimsIssue::ISSUE_BASIS;

            return view('sample::trims-issue.view', compact('sampleTrimsIssue'));
        } catch (Exception $exception) {
            return redirect()->back()->with(["error" => $exception->getMessage()]);
        }
    }

    public function pdf(SampleTrimsIssue $sampleTrimsIssue)
    {
        try {
            $sampleTrimsIssue->viewType = 'pdf';
            $sampleTrimsIssue->issueBasis = SampleTrimsIssue::ISSUE_BASIS;
            $pdf = PDF::loadView('sample::trims-issue.pdf', compact('sampleTrimsIssue'))
                ->setPaper('a4')->setOrientation('landscape')
                ->setOptions([
                    'header-html' => view('skeleton::pdf.header'),
                    'footer-html' => view('skeleton::pdf.footer'),
                ]);

            return $pdf->stream('trims-issue.pdf');
        } catch (Exception $exception) {
            return redirect()->back()->with(["error" => $exception->getMessage()]);
        }
    }

    public function excel(SampleTrimsIssue $sampleTrimsIssue)
    {
        $sampleTrimsIssue->viewType = 'excel';
        $sampleTrimsIssue->issueBasis = SampleTrimsIssue::ISSUE_BASIS;

        return Excel::download(
            new SampleTrimsIssueExport($sampleTrimsIssue),
            'trims-issue.xlsx'
        );
    }
}
