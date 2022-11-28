<?php

namespace SkylarkSoft\GoRMG\Finance\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\Approval\Filters\Filter;
use SkylarkSoft\GoRMG\Finance\Models\Voucher;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class VoucherApprovePanelController extends Controller
{
    public function index(Request $request)
    {
        $factoryId = $request->get('factory_id');
        $typeId = $request->get('type_id');
        $voucherNo = $request->get('voucher_no');
        // $dateType = $request->get('date_type');
        $fromDate = $request->get('from_date') ?? Carbon::now()->format('Y-m-d');
        $toDate = $request->get('to_date');
        $projectId = $request->get('project_id');
        $unitId = $request->get('unit_id');

        $typesId = collect(Voucher::$statuses)->except([Voucher::POSTED, Voucher::CANCELED])->keys();
        $factories = Factory::query()->pluck('factory_name', 'id');
        $voucherTypes = Voucher::VOUCHER_TYPE;
        $vouchers = Voucher::query()
            ->with('company')
            ->when($factoryId, Filter::applyFilter('factory_id', $factoryId))
            ->when($typeId, Filter::applyFilter('type_id', $typeId))
            ->when($voucherNo, Filter::applyFilter('voucher_no', $voucherNo))
            ->when($fromDate && $toDate == null, Filter::applyFilter('trn_date', $fromDate))
            ->when($fromDate && $toDate, Filter::applyBetweenFilter('trn_date', [$fromDate, $toDate]))
            ->when($projectId, Filter::applyFilter('project_id', $projectId))
            ->when($unitId, Filter::applyFilter('unit_id', $unitId));

        $unapproveVouchers = (clone $vouchers)->whereIn('status_id', $typesId)->get();
        $approveVouchers = (clone $vouchers)->where('status_id', Voucher::POSTED)->get();

        return view('finance::forms.approvals.approve_panels', [
            'factories' => $factories,
            'voucherTypes' => $voucherTypes,
            'approveVouchers' => $approveVouchers,
            'unapproveVouchers' => $unapproveVouchers,
            'fromDate'=>$fromDate
        ]);
    }
}
