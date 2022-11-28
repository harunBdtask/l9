<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers\PreCosting;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use PDF;
use SkylarkSoft\GoRMG\Merchandising\Models\PreCosting\PreCosting;
use SkylarkSoft\GoRMG\Merchandising\Requests\PreCostingRequest;
use SkylarkSoft\GoRMG\Merchandising\Services\FileUploadRemoveService;
use SkylarkSoft\GoRMG\Merchandising\Services\PreCostingReport;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsItem;
use SkylarkSoft\GoRMG\SystemSettings\Models\Season;

class PreCostingController extends Controller
{
    public function create()
    {
        return view('merchandising::pre-costing.create');
    }

    public function index()
    {
        $preCostings = PreCosting::query()
            ->with('factory:id,factory_name', 'buyer:id,name', 'season:id,season_name', 'item:id,name')
            ->latest()->paginate();
        return view('merchandising::pre-costing.index', compact('preCostings'));
    }

    public function store(PreCostingRequest $request)
    {
        $data = $request->except('tp_file', 'tp_file_2', 'tp_file_3', 'costing_file', 'costing_file_2', 'costing_file_3');
        if ($request->get('tp_file')) {
            $data['tp_file'] = FileUploadRemoveService::fileUpload('preCosting', $request->get('tp_file'), 'application', 'tp_1');
        }
        if ($request->get('tp_file_2')) {
            $data['tp_file_2'] = FileUploadRemoveService::fileUpload('preCosting', $request->get('tp_file_2'), 'application','tp_2');
        }
        if ($request->get('tp_file_3')) {
            $data['tp_file_3'] = FileUploadRemoveService::fileUpload('preCosting', $request->get('tp_file_3'), 'application', 'tp_3');
        }
        if ($request->get('costing_file')) {
            $data['costing_file'] = FileUploadRemoveService::fileUpload('preCosting', $request->get('costing_file'), 'application','costing_file_1');
        }
        if ($request->get('costing_file_2')) {
            $data['costing_file_2'] = FileUploadRemoveService::fileUpload('preCosting', $request->get('costing_file_2'), 'application', 'costing_file_2');
        }
        if ($request->get('costing_file_3')) {
            $data['costing_file_3'] = FileUploadRemoveService::fileUpload('preCosting', $request->get('costing_file_3'), 'application', 'costing_file_3');
        }

        $preCosting = PreCosting::create($data);
        return response()->json($preCosting);
    }

    public function show(PreCosting $preCosting)
    {
        return response()->json($preCosting);
    }

    public function update(PreCosting $preCosting, PreCostingRequest $request)
    {
        $data = $request->except('tp_file', 'tp_file_2', 'tp_file_3', 'costing_file', 'costing_file_2', 'costing_file_3');
        if ($request->get('tp_file') && $preCosting->getOriginal('tp_file') !== $request->get('tp_file')) {
            FileUploadRemoveService::removeFile($preCosting->tp_file);
            $data['tp_file'] = FileUploadRemoveService::fileUpload('preCosting', $request->get('tp_file'), 'application','tp_1');
        }
        if ($request->get('tp_file_2') && $preCosting->getOriginal('tp_file_2') !== $request->get('tp_file_2')) {
            FileUploadRemoveService::removeFile($preCosting->tp_file_2);
            $data['tp_file_2'] = FileUploadRemoveService::fileUpload('preCosting', $request->get('tp_file_2'), 'application','tp_2');
        }
        if ($request->get('tp_file_3') && $preCosting->getOriginal('tp_file_3') !== $request->get('tp_file_3')) {
            FileUploadRemoveService::removeFile($preCosting->tp_file_3);
            $data['tp_file_3'] = FileUploadRemoveService::fileUpload('preCosting', $request->get('tp_file_3'), 'application','tp_3');
        }
        if ($request->get('costing_file') && $preCosting->getOriginal('costing_file') !== $request->get('costing_file')) {
            FileUploadRemoveService::removeFile($preCosting->costing_file);
            $data['costing_file'] = FileUploadRemoveService::fileUpload('preCosting', $request->get('costing_file'), 'application','costing_file_1');
        }
        if ($request->get('costing_file_2') && $preCosting->getOriginal('costing_file_2') !== $request->get('costing_file_2')) {
            FileUploadRemoveService::removeFile($preCosting->costing_file_2);
            $data['costing_file_2'] = FileUploadRemoveService::fileUpload('preCosting', $request->get('costing_file_2'), 'application','costing_file_2');
        }
        if ($request->get('costing_file_3') && $preCosting->getOriginal('costing_file_3') !== $request->get('costing_file_3')) {
            FileUploadRemoveService::removeFile($preCosting->costing_file_3);
            $data['costing_file_3'] = FileUploadRemoveService::fileUpload('preCosting', $request->get('costing_file_3'), 'application', 'costing_file_3');
        }
        $preCosting->update($data);

        return response()->json($data);
    }

    public function destroy(PreCosting $preCosting)
    {
        try {
            $this->deleteFiles($preCosting);
            $preCosting->delete();
            Session::flash('success', 'Data Deleted Successfully');
        } catch (\Exception $exception) {
            Session::flash('error', $exception->getMessage());
        }
        return redirect()->back();
    }

    private function deleteFiles($preCosting)
    {
        if ($preCosting->tp_file) {
            FileUploadRemoveService::removeFile($preCosting->tp_file);
        }
        if ($preCosting->tp_file_2) {
            FileUploadRemoveService::removeFile($preCosting->tp_file_2);
        }
        if ($preCosting->tp_file_3) {
            FileUploadRemoveService::removeFile($preCosting->tp_file_3);
        }
        if ($preCosting->costing_file) {
            FileUploadRemoveService::removeFile($preCosting->costing_file);
        }
        if ($preCosting->costing_file_2) {
            FileUploadRemoveService::removeFile($preCosting->costing_file_2);
        }
        if ($preCosting->costing_file_3) {
            FileUploadRemoveService::removeFile($preCosting->costing_file_3);
        }
    }

    public function removeFile($id, $type)
    {
        try {
            $preCosting = PreCosting::findOrFail($id);
            FileUploadRemoveService::removeFile($preCosting[$type]);
            $preCosting->update([
                $type => null
            ]);
//            if ($type == 'tp_file') {
//                FileUploadRemoveService::removeFile($preCosting->tp_file);
//                $preCosting->update([
//                    'tp_file' => null
//                ]);
//            } else {
//                FileUploadRemoveService::removeFile($preCosting->costing_file);
//                $preCosting->update([
//                    'costing_file' => null
//                ]);
//            }
            return response()->json([
                'status' => 'Success',
                'type' => 'File Deleted',
                'message' => 'File Removed Successfully'
            ], Response::HTTP_OK);

        } catch (\Exception $exception) {
            return response()->json([
                'status' => 'Error',
                'type' => 'File Delete Failed',
                'message' => $exception->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function view(Request $request)
    {
        $factoryId = $request->get('factory_id') ?? factoryId();
        $buyerId = $request->get('buyer_id') ?? null;
        $seasonId = $request->get('season_id') ?? null;
        $itemId = $request->get('item_id') ?? null;
        $toDate = $request->get('to_date') ? Carbon::parse($request->get('from_date'))->format('Y/m/d') :null;
        $fromDate = $request->get('from_date') ? Carbon::parse($request->get('from_date'))->format('Y/m/d') :null;
        $styleName = $request->get('style_name') ?? null;
        $buyers = $factoryId ? Buyer::query()->where('factory_id', $factoryId)->get() : [];
        $seasons = ($buyerId && $factoryId) ? Season::query()->where("factory_id", $factoryId)->where("buyer_id", $buyerId)->get() : [];
        $factories = Factory::all();
        $items = GarmentsItem::query()->get(['id','name']);
        $type = 'view';
        $preCostings = [];

        if ($factoryId) {
            $preCostings = PreCostingReport::reportData($factoryId, $buyerId, $seasonId, $itemId, $styleName, $fromDate, $toDate);
        }

        return view('merchandising::pre-costing.view', compact('factories', 'factoryId',
            'buyerId', 'buyers', 'seasons', 'seasonId', 'items', 'itemId', 'fromDate', 'toDate', 'styleName', 'preCostings', 'type'));
    }

    public function print(Request $request)
    {
        $buyerId = $request->get('buyer_id') ?? null;
        $factoryId = $request->get('factory_id') ?? null;
        $itemId = $request->get('item_id') ?? null;
        $seasonId = $request->get('season_id') ?? null;
        $toDate = $request->get('to_date') ? Carbon::parse($request->get('from_date'))->format('Y/m/d') :null;
        $fromDate = $request->get('from_date') ? Carbon::parse($request->get('from_date'))->format('Y/m/d') :null;
        $styleName = $request->get('style_name') ?? null;
        $type = null;
        $preCostings = PreCostingReport::reportData($factoryId, $buyerId, $seasonId, $itemId, $styleName, $fromDate, $toDate);

        return view('merchandising::pre-costing.print', compact('factoryId',
            'buyerId', 'seasonId', 'itemId', 'fromDate', 'toDate', 'styleName', 'preCostings', 'type'));
    }

    public function pdf(Request $request)
    {
        $factoryId = $request->get('factory_id') ?? null;
        $buyerId = $request->get('buyer_id') ?? null;
        $seasonId = $request->get('season_id') ?? null;
        $toDate = $request->get('to_date') ? Carbon::parse($request->get('from_date'))->format('Y/m/d') :null;
        $fromDate = $request->get('from_date') ? Carbon::parse($request->get('from_date'))->format('Y/m/d') :null;
        $itemId = $request->get('item_id') ?? null;
        $styleName = $request->get('style_name') ?? null;
        $type = null;
        $preCostings = PreCostingReport::reportData($factoryId, $buyerId, $seasonId, $itemId, $styleName, $fromDate, $toDate);
        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView('merchandising::pre-costing.pdf',
            compact('factoryId', 'buyerId', 'fromDate', 'toDate', 'itemId', 'request', 'preCostings', 'type')
        )->setPaper('a4')->setOrientation('landscape')->setOptions([
            'header-html' => view('skeleton::pdf.header'),
            'footer-html' => view('skeleton::pdf.footer'),
        ]);

        return $pdf->stream($factoryId . '_pre_costing.pdf');
    }
}
