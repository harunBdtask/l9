<?php

namespace SkylarkSoft\GoRMG\Sample\Controllers;

use App\Http\Controllers\Controller;
use Excel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;
use SkylarkSoft\GoRMG\Sample\Exports\SampleRequisitionExport;
use SkylarkSoft\GoRMG\Sample\Models\SampleOrderRequisition;
use SkylarkSoft\GoRMG\Sample\Services\SampleListFetchService;
use SkylarkSoft\GoRMG\Sample\Services\SampleRequisition\SampleOrderRequisitionService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsItem;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsSample;
use SkylarkSoft\GoRMG\SystemSettings\Models\Season;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;
use SkylarkSoft\GoRMG\SystemSettings\Services\DiaTypesService;
use Symfony\Component\HttpFoundation\Response;

class SampleOrderRequisitionController extends Controller
{
    public function index(Request $request)
    {
        try {
            $q = $request->all() ?? null;
            $search = $q['search'] ?? null;
            $paginateNumber = request('paginateNumber') ?? 15;
            $searchedSamples = 15;
            $samples = (new SampleListFetchService($search))->get($paginateNumber);
            $samples->getCollection()->transform(function ($item, $key) {
                $sample_ids = collect($item->details)->pluck('sample_id');
                $item->sample_types = GarmentsSample::whereIn('id', $sample_ids)->get()->implode('name', ', ');
                $gmts_item_ids = collect($item->details)->pluck('gmts_item_id');
                $item->gmts_items = GarmentsItem::whereIn('id', $gmts_item_ids)->get()->implode('name', ', ');

                return $item;
            });
            $totalSampleRequisition = SampleOrderRequisition::all()->count();
            $dashboardOverview = [
                "Total Sample Requisition" => $totalSampleRequisition,
            ];

            return view('sample::sample-requisitions.index', [
                'samples' => $samples,
                'dashboardOverview' => $dashboardOverview,
                'paginateNumber' => $paginateNumber,
                'searchedSamples' => $searchedSamples,
                'searchedValue' => $search,

            ]);
        } catch (Exception $e) {
            return redirect()->back()->with(["error" => $e->getMessage()]);
        }
    }

    public function create()
    {
        return view('sample::sample-requisitions.create');
    }

    public function save(Request $request)
    {
        try {
            $id = $request->get('id') ?? null;
            if ($id) {
                $data = SampleOrderRequisition::findOrFail($id);
                $data->update($request->all());
            } else {
                $data = new SampleOrderRequisition($request->all());
                $data->save();
            }

            return response()->json(['message' => 'Successfully Saved!', 'data' => $data], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something Went Wrong!'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function createOrUpdate(Request $request)
    {
        try {
            DB::beginTransaction();
            $id = $request->sampleData['id'] ?? null;
            if ($id) {
                $sampleData = SampleOrderRequisition::find($id);
                $sampleData->update($request->sampleData);
                $message = 'Successfully Updated!';
            } else {
                $sampleData = new SampleOrderRequisition($request->sampleData);
                $sampleData->save();
                $message = 'Successfully Saved!';
            }
            $sampleOrderRequisition = new SampleOrderRequisition();
            $findData = $sampleOrderRequisition->find($sampleData->id);
            foreach ($request->items as $item) {
                $detailsId = $item['id'] ?? null;
                if ($detailsId) {
                    $findData->details()->find($detailsId)
                    ->update($item);

                    continue;
                }
                $findData->details()->create($item);
            }
            $sampleData->update(['requis_details_cal' => $request->total_calculation]);
            DB::commit();

            return response()->json(['message' => $message, 'sampleId' => $sampleData->id,'data' => $sampleData, 'samples' => $sampleData->details], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something Went Wrong!'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function show(SampleOrderRequisition $sampleOrderRequisition)
    {
        $buyers = Buyer::where('factory_id', $sampleOrderRequisition->factory_id)->get(['id', 'name as text']);
        $seasons = Season::where('buyer_id', $sampleOrderRequisition->buyer_id)->get(['id', 'season_name as text']);
        $merchants = User::where('factory_id', $sampleOrderRequisition->factory_id)->where('email', '<>', 'super@skylarksoft.com')->get()->map(function ($user) {
            return [
                'id' => $user->id,
                'text' => $user->first_name . ' ' . $user->last_name,
            ];
        });

        return response()->json(compact('sampleOrderRequisition', 'buyers', 'seasons', 'merchants'));
    }

    public function delete(SampleOrderRequisition $sampleOrderRequisition)
    {
        try {
            DB::beginTransaction();
            $sampleOrderRequisition->details()->delete();
            $sampleOrderRequisition->fabrics()->delete();
            $sampleOrderRequisition->fabricDetails()->delete();
            $sampleOrderRequisition->accessories()->delete();
            $sampleOrderRequisition->delete();
            DB::commit();

            session()->flash('success', 'Successfully Deleted');
        } catch (\Throwable $e) {
            session()->flash('danger', $e->getMessage());
        }

        return redirect()->back();
    }

    public function view(SampleOrderRequisition $sampleOrderRequisition)
    {
        try {
            $sampleOrderRequisition->fabricMain = collect($sampleOrderRequisition->fabrics)->first();
            $sampleOrderRequisition->dia_types = DiaTypesService::diaTypes();
            $sampleOrderRequisition->fabricUoms = SampleOrderRequisitionService::fabricUoms();
            $sampleOrderRequisition->fabricSources = SampleOrderRequisitionService::fabricSources();
            $sampleOrderRequisition->viewType = 'blade';

            return view('sample::sample-requisitions.view', compact('sampleOrderRequisition'));
        } catch (Exception $exception) {
            return redirect()->back()->with(["error" => $exception->getMessage()]);
        }
    }

    public function pdf(SampleOrderRequisition $sampleOrderRequisition)
    {
        try {
            $sampleOrderRequisition->fabricMain = collect($sampleOrderRequisition->fabrics)->first();
            $sampleOrderRequisition->dia_types = DiaTypesService::diaTypes();
            $sampleOrderRequisition->fabricUoms = SampleOrderRequisitionService::fabricUoms();
            $sampleOrderRequisition->fabricSources = SampleOrderRequisitionService::fabricSources();
            $sampleOrderRequisition->viewType = 'pdf';
            $pdf = PDF::loadView('sample::sample-requisitions.pdf', compact('sampleOrderRequisition'))
                ->setPaper('a4')->setOrientation('landscape')
                ->setOptions([
                    'header-html' => view('skeleton::pdf.header'),
                    'footer-html' => view('skeleton::pdf.footer'),
                ]);

            return $pdf->stream('sample-requisition.pdf');
        } catch (Exception $exception) {
            return redirect()->back()->with(["error" => $exception->getMessage()]);
        }
    }

    public function excel(SampleOrderRequisition $sampleOrderRequisition)
    {
        $sampleOrderRequisition->fabricMain = collect($sampleOrderRequisition->fabrics)->first();
        $sampleOrderRequisition->dia_types = DiaTypesService::diaTypes();
        $sampleOrderRequisition->fabricUoms = SampleOrderRequisitionService::fabricUoms();
        $sampleOrderRequisition->fabricSources = SampleOrderRequisitionService::fabricSources();
        $sampleOrderRequisition->viewType = 'excel';

        return Excel::download(
            new SampleRequisitionExport($sampleOrderRequisition),
            'sample-requisition.xlsx'
        );
    }

    public function sampleFabBookingview(SampleOrderRequisition $sampleOrderRequisition)
    {
        try {
            $sampleOrderRequisition->fabricMain = collect($sampleOrderRequisition->fabrics)->first();
            $sampleOrderRequisition->dia_types = DiaTypesService::diaTypes();
            $sampleOrderRequisition->fabricUoms = SampleOrderRequisitionService::fabricUoms();
            $sampleOrderRequisition->fabricSources = SampleOrderRequisitionService::fabricSources();
            $sampleOrderRequisition->viewType = 'withoutAccessories';

            return view('sample::sample-requisitions.view', compact('sampleOrderRequisition'));
        } catch (Exception $exception) {
            return redirect()->back()->with(["error" => $exception->getMessage()]);
        }
    }
}
