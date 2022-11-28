<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers\GatePassChallan;


use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use PDF;
use SkylarkSoft\GoRMG\Merchandising\Filters\Filter;
use SkylarkSoft\GoRMG\Merchandising\Models\GatePassChallan\GatePasChallan;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\Merchandising\Requests\GatePassChallanRequest;
use SkylarkSoft\GoRMG\Merchandising\Services\FileUploadRemoveService;
use SkylarkSoft\GoRMG\Merchandising\Services\GatePassChallan\GatePassChallanReportService;
use SkylarkSoft\GoRMG\Merchandising\Services\ReportSignatureService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\ProductDepartments;
use SkylarkSoft\GoRMG\SystemSettings\Models\Supplier;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;
use Symfony\Component\HttpFoundation\Response;

class GatePassChallanController extends Controller
{
    public function index()
    {
        $status = GatePasChallan::STATUS;
        $goods = GatePasChallan::GOODS;
        $factories = Factory::all();
        $parties = Supplier::all();
        $partyContactDetails = GatePasChallan::all();
        $departments = ProductDepartments::all();

        $gatePassChallan = GatePasChallan::query()
            ->when(request()->get('factory_id'), Filter::applyFilter('factory_id', request()->get('factory_id')))
            ->when(request()->get('challan_no'), Filter::applyFilter('challan_no', request()->get('challan_no')))
            ->when(request()->get('challan_date'), function ($q) {
                $q->whereDate('challan_date', request()->get('challan_date'));
            })
            ->when(request()->get('department'), Filter::applyFilter('department_id', request()->get('department')))
            ->when(request()->get('supplier_id'), Filter::applyFilter('supplier_id', request()->get('supplier_id')))
            ->when(request()->get('status'), Filter::applyFilter('status', request()->get('status')))
            ->when(request()->get('good_id'), Filter::applyFilter('good_id', request()->get('good_id')))
            ->when(request()->get('is_approve') && request()->get('is_approve') == '1', Filter::applyFilter('is_approve', 1))
            ->when(request()->get('is_approve') && request()->get('is_approve') == '2', Filter::applyFilter('is_approve', null))
            ->with(['merchant:id,screen_name', 'party:id,name,contact_person', 'department:id,product_department', 'factory'])
            ->orderBy('id', 'desc')
            ->paginate();
        $totalChallan = GatePasChallan::all()->count();
        $totalGatePass = GatePasChallan::query()->where('returnable', 2)->count();
        $returnableGatePass = GatePasChallan::query()->where('returnable', 1)->count();

        $dashboardOverview = [
            "Total Challan" => $totalChallan,
            'Total Gate Pass' => $totalGatePass,
            'Returnable Gate Pass' => $returnableGatePass,
        ];

        return view('merchandising::gate-pass-challan.index', compact('gatePassChallan', 'status', 'goods', 'factories', 'parties', 'departments', 'dashboardOverview', 'partyContactDetails'));
    }

    public function search(Request $request)
    {
        $search = $request->search;
        $status = GatePasChallan::STATUS;
        $goods = GatePasChallan::GOODS;
        $gatePassChallan = GatePasChallan::query()
            ->where('challan_no', 'LIKE', '%' . $search . '%')
            ->with('merchant:id,screen_name', 'party:id,name,contact_person',
                'department:id,product_department', 'factory')
            ->orderBy('id', 'desc')->paginate();
        $search = null;
        return view('merchandising::gate-pass-challan.index', compact('gatePassChallan', 'search', 'status', 'goods'));


    }

    public function create()
    {
        return view('merchandising::gate-pass-challan.create_update');
    }

    public function store(GatePassChallanRequest $request, GatePasChallan $gatePasChallan)
    {
        try {
            DB::beginTransaction();

            $gatePasChallan->fill($request->except('file'))->save();
            if ($request->get('file') &&
                strpos($request->get('file'), 'image') !== false &&
                strpos($request->get('file'), 'base64') !== false) {
                $image_path = FileUploadRemoveService::fileUpload('gate-pass', $request->get('file'), 'image');
                $gatePasChallan->update(['file' => $image_path]);
            }

            DB::commit();
            return response()->json($gatePasChallan);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json($exception->getMessage());
        }
    }

    public function show(GatePasChallan $gatePasChallan)
    {
        $data = $gatePasChallan->load('merchant', 'party');
        return response()->json($this->formatData($data));
    }

    private function formatData($data)
    {
        return [
            'id' => $data->id,
            'challan_no' => $data->challan_no,
            'factory_id' => $data->factory_id,
            'file' => $data->file,
            'challan_date' => $data->challan_date,
            'department_id' => $data->department_id,
            'merchant_id' => $data->merchant_id,
            'supplier_id' => $data->supplier_id,
            'good_id' => $data->good_id,
            'status' => $data->status,
            'remarks' => $data->remarks,
            'goods_details' => $data->goods_details,
            'merchant_name' => $data->merchant->screen_name,
            'phone_no' => $data->merchant->phone_no,
            'merchant_email' => $data->merchant->email,
            'merchant_address' => $data->merchant->address,
            'attn' => $data->party->contact_person,
            'contact_no' => $data->party->contact_no,
            'party_email' => $data->party->email,
            'party_address' => $data->party->address_1,
            'unapprove_request' => $data->unapprove_request,
            'step' => $data->step,
            'is_approve' => $data->is_approve,
            'ready_to_approve' => $data->ready_to_approve,
            'returnable' => $data->returnable,
            'vehicle_no' => $data->vehicle_no,
            'driver_name' => $data->driver_name,
            'lock_no' => $data->lock_no,
            'bag_quantity' => $data->bag_quantity,
            'party_attn' => $data->party_attn,
            'party_contact_no' => $data->party_contact_no,
            'supplier_address' => $data->supplier_address,
            'supplier_email_address' => $data->supplier_email_address

        ];
    }

    public function update(GatePassChallanRequest $request, GatePasChallan $gatePasChallan)
    {
        try {
            $gatePasChallan->fill($request->except('file'))->save();
            if ($request->get('file') &&
                strpos($request->get('file'), 'image') !== false &&
                strpos($request->get('file'), 'base64') !== false) {
                $image_path = FileUploadRemoveService::fileUpload('gate-pass', $request->get('file'), 'image');
                if ($request->get('file') && Storage::disk('public')->exists($gatePasChallan->file)) {
                    Storage::delete($gatePasChallan->file);
                }
                $gatePasChallan->update(['file' => $image_path]);
            }
            return response()->json($gatePasChallan);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage());
        }
    }

    public function imageRemove(Request $request)
    {
        $gatePassChallan = GatePasChallan::query()->where("id", $request->get('id'))->first();
        if (isset($gatePassChallan->file)) {
            $image_name_to_delete = $gatePassChallan->file;
            if (Storage::disk('public')->exists($image_name_to_delete) && $image_name_to_delete) {
                Storage::delete($image_name_to_delete);
            }
        }
        $gatePassChallan->update([
            "file" => null,
        ]);

        return response()->json($gatePassChallan);
    }

    public function destroy(GatePasChallan $gatePasChallan)
    {
        try {
            $gatePasChallan->delete();
            Session::flash('error', 'Data Deleted Successfully');
            return redirect('/gate-pass-challan');
        } catch (\Exception $exception) {
            Session::flash('error', 'Something went wrong !');
            return redirect('/gate-pass-challan');
        }
    }

    public function view($id)
    {
        $goods = GatePasChallan::GOODS;
        $data = GatePassChallanReportService::fetchData($id);
        $signatures = $this->signatures($data);
        $signature = ReportSignatureService::getApprovalSignature(GatePasChallan::class, $id, 'gatepass-approval');
        return view('merchandising::gate-pass-challan.report.view', compact('data', 'goods', 'signatures', 'signature'));
    }

    public function pdf($id)
    {
        $goods = GatePasChallan::GOODS;
        $data = GatePassChallanReportService::fetchData($id);
        $signatures = $this->signatures($data);
        $signature = ReportSignatureService::getApprovalSignature(GatePasChallan::class, $id, 'gatepass-approval');
        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView('merchandising::gate-pass-challan.report.pdf', compact('data', 'goods', 'signatures', 'signature'))
            ->setPaper('a4')
            ->setOrientation('landscape')
            ->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);
        return $pdf->stream("{$data->id}_gate_pass_challan.pdf");

    }

    private function signatures($data): array
    {
        $approveUserArr = json_decode($data->approved_by);
        $signatures = [];
        collect($approveUserArr)->map(function ($item) use ($signatures) {
            return $signatures[] = User::query()
                ->where('id', $item)
                ->first()->signature ?? '';
        });
        return $signatures;
    }

    public function exitPointScanView(Request $request)
    {
        $message = '';
        $data = $signatures = $goods = [];
        $search = $request->get('search');

        $gatePassChallanId = GatePasChallan::query()->where('id', (int)$search)->first();
        if ($gatePassChallanId) {
            $data = GatePassChallanReportService::fetchData($gatePassChallanId->id);
            if (empty($data->gp_exit_point_scanned_by) && empty($data->gp_exit_point_scanned_at) && is_null($data->gp_exit_point_scanned_by) && is_null($data->gp_exit_point_scanned_at)) {
                $goods = GatePasChallan::GOODS;
                $signatures = $this->signatures($data);
                return view('merchandising::gate-pass-challan.gate_pass_exit_point_scan', compact('search', 'message', 'data', 'goods', 'signatures'));
            }
            $message = "Gate Pass Challan Has Already Been Exited.";
        } else {
            $message = "Please Scan a Valid Gate Pass Challan.";
        }

        return view('merchandising::gate-pass-challan.gate_pass_exit_point_scan', compact('search', 'message', 'data', 'goods', 'signatures'));
    }

    public function exitPointScanUpdate(Request $request)
    {

        $message = '';
        $search = $request->search;
        $data = $signatures = $goods = [];
        $gatePass = GatePasChallan::query()->where('id', (int)$search)
            ->whereNull('gp_exit_point_scanned_by')
            ->whereNull('gp_exit_point_scanned_at')
            ->first();

        if ($gatePass) {
            $gatePass->update([
                'gp_exit_point_scanned_by' => \Auth::user()->id,
                'gp_exit_point_scanned_at' => now()
            ]);
            Session::flash('success', 'Gate Pass Challan Has Been Successfully Scanned.');
        } else {
            Session::flash('error', 'Please Enter a valid Challan No.');
        }

        return view('merchandising::gate-pass-challan.gate_pass_exit_point_scan', compact('message', 'data', 'goods', 'signatures'));
    }

    public function exitList(Request $request)
    {
        $status = $request->get('status');
        $goodAt = $request->get('good_id');
        $challanNo = $request->get('challan_no');
        $factoryId = $request->get('factory_id');
        $isApprove = $request->get('is_approve');
        $department = $request->get('department');
        $supplierId = $request->get('supplier_id');
        $scannedAt = $request->get('gp_exit_point_scanned_at');
        $formDate = $request->get('from_date');
        $toDate = $request->get('to_date');

        $gatePassChallan = GatePasChallan::query()
            ->with(['merchant:id,screen_name', 'party:id,name,contact_person', 'department:id,product_department', 'factory'])
            ->whereNotNull('gp_exit_point_scanned_by')
            ->whereNotNull('gp_exit_point_scanned_at')
            ->when($factoryId, Filter::applyFilter('factory_id', $factoryId))
            ->when($challanNo, Filter::applyFilter('challan_no', $challanNo))
            ->when($scannedAt, Filter::applyDateFilter('gp_exit_point_scanned_at', $scannedAt))
            ->when($department, Filter::applyFilter('department_id', $department))
            ->when($supplierId, Filter::applyFilter('supplier_id', $supplierId))
            ->when($status, Filter::applyFilter('status', $status))
            ->when($goodAt, Filter::applyFilter('good_id', $goodAt))
            ->when($isApprove && $isApprove == '1', Filter::applyFilter('is_approve', 1))
            ->when($isApprove && $isApprove == '2', Filter::applyFilter('is_approve', null))
            ->when($formDate && $toDate, Filter::applyBetweenFilter('challan_date', [$formDate,$toDate]))
            ->orderBy('gp_exit_point_scanned_at', 'desc')
            ->paginate();


        $status = GatePasChallan::STATUS;
        $goods = GatePasChallan::GOODS;
        $factories = Factory::all();
        $parties = Supplier::all();
        $departments = ProductDepartments::all();
        $totalChallans = GatePasChallan::all()->count();

        $dashboardOverview = [
            "Total Challan" => $totalChallans
        ];

        return view('merchandising::gate-pass-challan.gate_pass_exit_list', compact('gatePassChallan', 'status', 'goods', 'factories', 'parties', 'departments', 'dashboardOverview'));
    }

    public function updateReturnable(GatePasChallan $gatePasChallan, Request $request): JsonResponse
    {
        $returnAbleStatus = $request->get('returnable');
        $gatePasChallan->update(['returnable' => $returnAbleStatus]);

        return response()->json([
            'message' => 'Updated Successfully',
            'data' => $gatePasChallan,
            'status' => Response::HTTP_OK,
        ], Response::HTTP_OK);

    }
}
