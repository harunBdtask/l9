<?php

namespace SkylarkSoft\GoRMG\Sample\Controllers;

use App\Http\Controllers\Controller;
use Excel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;
use SkylarkSoft\GoRMG\Sample\Exports\SampleTNAExport;
use SkylarkSoft\GoRMG\Sample\Models\SampleTemplate;
use SkylarkSoft\GoRMG\Sample\Models\SampleTNA;
use SkylarkSoft\GoRMG\Sample\Services\SampleTNAListFetchService;
use Symfony\Component\HttpFoundation\Response;

class SampleTNAController extends Controller
{
    public function index(Request $request)
    {
        try {
            $q = $request->all() ?? null;
            $search = $q['search'] ?? null;
            $values = (new SampleTNAListFetchService($search))->get(15);

            return view('sample::sample-tna.index', compact('values'));
        } catch (Exception $e) {
            return redirect()->back()->with(["error" => $e->getMessage()]);
        }
    }

    public function create()
    {
        return view('sample::sample-tna.create');
    }

    public function save(Request $request)
    {
        try {
            DB::beginTransaction();
            if (isset($request->form['id'])) {
                $formData = $request->form;
                $formData['items'] = $request->items;
                $formData['total_calculation'] = $request->total_calculation;
                $data = SampleTNA::findOrFail($request->form['id']);
                $data->update($formData);
                $message = 'Successfully Updated!';
            } else {
                $data = new SampleTNA($request->form);
                $data->items = $request->items;
                $data->total_calculation = $request->total_calculation;
                $data->save();
                $message = 'Successfully Saved!';
            }
            if ($request->get('is_template') && $request->get('template_name')) {
                $templateData = [
                    'type' => 'sample_tna',
                    'template_name' => $request->get('template_name'),
                    'items' => $request->items,
                    'total_calculation' => $request->total_calculation,
                ];
                $sampleTemplate = new SampleTemplate($templateData);
                $sampleTemplate->save();
            }
            DB::commit();

            return response()->json(['message' => $message, 'data' => $data], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something Went Wrong!'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function delete(SampleTNA $sampleTNA)
    {
        try {
            DB::beginTransaction();
            $sampleTNA->delete();
            DB::commit();

            session()->flash('success', 'Successfully Deleted');
        } catch (\Throwable $e) {
            session()->flash('danger', $e->getMessage());
        }

        return redirect()->back();
    }

    public function show(SampleTNA $sampleTNA)
    {
        try {
            $sampleTNA->viewType = 'browse';

            return view('sample::sample-tna.tna_details', compact('sampleTNA'));
        } catch (Exception $exception) {
            return redirect()->back()->with(["error" => $exception->getMessage()]);
        }
    }

    public function view(SampleTNA $sampleTNA)
    {
        try {
            $sampleTNA->viewType = 'blade';

            return view('sample::sample-tna.view', compact('sampleTNA'));
        } catch (Exception $exception) {
            return redirect()->back()->with(["error" => $exception->getMessage()]);
        }
    }

    public function pdf(SampleTNA $sampleTNA)
    {
        try {
            $sampleTNA->viewType = 'pdf';
            $pdf = PDF::loadView('sample::sample-tna.pdf', compact('sampleTNA'))
                ->setPaper('a4')->setOrientation('landscape')
                ->setOptions([
                    'header-html' => view('skeleton::pdf.header'),
                    'footer-html' => view('skeleton::pdf.footer'),
                ]);

            return $pdf->stream('sample-tna.pdf');
        } catch (Exception $exception) {
            return redirect()->back()->with(["error" => $exception->getMessage()]);
        }
    }

    public function excel(SampleTNA $sampleTNA)
    {
        $sampleTNA->viewType = 'excel';

        return Excel::download(
            new SampleTNAExport($sampleTNA),
            'sample-tna.xlsx'
        );
    }
}
