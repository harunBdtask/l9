<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\Approval\Filters\Filter;
use SkylarkSoft\GoRMG\BasicFinance\Models\Voucher;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\BasicFinance\Models\Department;
use SkylarkSoft\GoRMG\SystemSettings\Models\BfVariableSetting;

class VoucherApprovePanelController extends Controller
{
    public function index(Request $request)
    {
        $factoryId = $request->get('factory_id');
        $typeId = $request->get('type_id');
        $voucherNo = $request->get('voucher_no');
        // $dateType = $request->get('date_type');
        $fromDate = $request->get('from_date') ?? Carbon::now()->format('Y-m-01');
        $toDate = $request->get('to_date')?? Carbon::now()->format('Y-m-d');
        $projectId = $request->get('project_id');
        $unitId = $request->get('unit_id');

        $typesId = collect(Voucher::$statuses)->except([Voucher::POSTED, Voucher::CANCELED])->keys();
        $factories = Factory::query()->pluck('factory_name', 'id');
        $voucherTypes = Voucher::VOUCHER_TYPE;
        $vouchers = Voucher::query()
            ->when($factoryId, Filter::applyFilter('factory_id', $factoryId))
            ->when($typeId, Filter::applyFilter('type_id', $typeId))
            ->when($voucherNo, Filter::applyFilter('voucher_no', $voucherNo))
            ->when($fromDate && $toDate == null, Filter::applyFilter('trn_date', $fromDate))
            ->when($fromDate && $toDate, Filter::applyBetweenFilter('trn_date', [$fromDate, $toDate]))
            ->when($projectId, Filter::applyFilter('project_id', $projectId))
            ->when($unitId, Filter::applyFilter('unit_id', $unitId));

        $unapproveVouchers = (clone $vouchers)->whereIn('status_id', $typesId)->get();
        $approveVouchers = (clone $vouchers)->with(['createdUser','comments.commenter'])->where('status_id', Voucher::POSTED)->get();
        $voucher_post_status = Voucher::POSTED;

        $variable = BfVariableSetting::first();
        $isDeptApproval = !empty($variable->departmental_approval)?true:false;

        return view('basic-finance::forms.approvals.approve_panels', [
            'factories' => $factories,
            'voucherTypes' => $voucherTypes,
            'approveVouchers' => $approveVouchers,
            'unapproveVouchers' => $unapproveVouchers,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
            'voucher_post_status' => $voucher_post_status,
            'isDeptApproval' => $isDeptApproval,
        ]);
    }
}
