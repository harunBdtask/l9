<?php

namespace SkylarkSoft\GoRMG\Sample\Controllers;

use App\Http\Controllers\Controller;
use Excel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;
use SkylarkSoft\GoRMG\Sample\Exports\SampleTrimsReceiveExport;
use SkylarkSoft\GoRMG\Sample\Models\SampleTrimsReceive;
use SkylarkSoft\GoRMG\Sample\Models\SampleTrimsReceiveDetails;
use SkylarkSoft\GoRMG\Sample\Services\SampleTrimsReceiveFetchService;
use Symfony\Component\HttpFoundation\Response;

class SampleTrimsReceiveController extends Controller
{
    public function index(Request $request)
    {
        try {
            $q = $request->all() ?? null;
            $search = $q['search'] ?? null;
            $values = (new SampleTrimsReceiveFetchService($search))->get(15);

            return view('sample::trims-receive.index', compact('values'));
        } catch (Exception $e) {
            return redirect()->back()->with(["error" => $e->getMessage()]);
        }
    }

    public function create()
    {
        return view('sample::trims-receive.create');
    }

    public function save(Request $request)
    {
        try {
            DB::beginTransaction();
            if (isset($request->form['id'])) {
                $mainData = SampleTrimsReceive::find($request->form['id']);
                $mainData->update($request->form);
                $message = 'Successfully Updated!';
            } else {
                $mainData = new SampleTrimsReceive($request->form);
                $mainData->save();
                $message = 'Successfully Saved!';
            }
            $sampleTrimsIssue = new SampleTrimsReceive();
            $findData = $sampleTrimsIssue->find($mainData->id);
            foreach ($request->items as $item) {
                if (isset($item['id'])) {
                    $findData->trimsReceiveDetails()->find($item['id'])
                    ->update($item);

                    continue;
                }
                $findData->trimsReceiveDetails()->create($item);
            }
            $mainData->update(['total_calculation' => $request->total_calculation]);
            DB::commit();

            return response()->json(['message' => $message, 'id' => $mainData->id], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something Went Wrong!'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function delete(SampleTrimsReceive $sampleTrimsReceive)
    {
        try {
            DB::beginTransaction();
            $sampleTrimsReceive->trimsReceiveDetails()->delete();
            $sampleTrimsReceive->delete();
            DB::commit();

            session()->flash('success', 'Successfully Deleted');
        } catch (\Throwable $e) {
            session()->flash('danger', $e->getMessage());
        }

        return redirect()->back();
    }

    public function show(SampleTrimsReceive $sampleTrimsReceive)
    {
        try {
            $sampleTrimsReceive->viewType = 'browse';

            return view('sample::trims-receive.main_details', compact('sampleTrimsReceive'));
        } catch (Exception $exception) {
            return redirect()->back()->with(["error" => $exception->getMessage()]);
        }
    }

    public function view(SampleTrimsReceive $sampleTrimsReceive)
    {
        try {
            $sampleTrimsReceive->viewType = 'blade';

            return view('sample::trims-receive.view', compact('sampleTrimsReceive'));
        } catch (Exception $exception) {
            return redirect()->back()->with(["error" => $exception->getMessage()]);
        }
    }

    public function pdf(SampleTrimsReceive $sampleTrimsReceive)
    {
        try {
            $sampleTrimsReceive->viewType = 'pdf';
            $pdf = PDF::loadView('sample::trims-receive.pdf', compact('sampleTrimsReceive'))
                ->setPaper('a4')->setOrientation('landscape')
                ->setOptions([
                    'header-html' => view('skeleton::pdf.header'),
                    'footer-html' => view('skeleton::pdf.footer'),
                ]);

            return $pdf->stream('trims-receive.pdf');
        } catch (Exception $exception) {
            return redirect()->back()->with(["error" => $exception->getMessage()]);
        }
    }

    public function excel(SampleTrimsReceive $sampleTrimsReceive)
    {
        $sampleTrimsReceive->viewType = 'excel';

        return Excel::download(
            new SampleTrimsReceiveExport($sampleTrimsReceive),
            'trims-receive.xlsx'
        );
    }

    public function deleteDetails(SampleTrimsReceiveDetails $sampleTrimsReceiveDetails, Request $request)
    {
        try {
            DB::beginTransaction();
            $mainData = SampleTrimsReceive::find($sampleTrimsReceiveDetails->str_id);
            $mainData->update(['total_calculation' => $request->total_calculation]);
            $sampleTrimsReceiveDetails->delete();
            DB::commit();

            return response()->json(['message' => 'Successfully Deleted'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something Went Wrong!'], Response::HTTP_BAD_REQUEST);
        }
    }
}
