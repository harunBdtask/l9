<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\Reports;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use PDF;
use Excel;
use PhpOffice\PhpSpreadsheet\Writer\Exception;
use SkylarkSoft\GoRMG\Inventory\Exports\ChallanWiseReceiveReport;
use SkylarkSoft\GoRMG\Inventory\Exports\GoodsReceivedWithoutLCExport;
use SkylarkSoft\GoRMG\Inventory\Filters\Filter;
use SkylarkSoft\GoRMG\Inventory\Models\Store;
use SkylarkSoft\GoRMG\Inventory\Models\YarnReceive;
use SkylarkSoft\GoRMG\Inventory\Models\YarnReceiveDetail;
use SkylarkSoft\GoRMG\Inventory\Services\YarnReceive\GoodsReceivedWithoutLCReportService;
use SkylarkSoft\GoRMG\Inventory\Services\YarnReceive\YarnReceiveReportService;
use SkylarkSoft\GoRMG\SystemSettings\Models\CompositionType;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Supplier;
use SkylarkSoft\GoRMG\Inventory\Exports\DailyYarnReceiveReport;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnComposition;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnCount;

class YarnReceiveReportController
{
    public function requiredData($request): array
    {
        $data['yarnReceive'] = YarnReceive::query()->get();
        $data['factories'] = Factory::query()->get();

        $data['lotNos'] = YarnReceiveDetail::query()
            ->when($request->get('store_id'), Filter::applyFilter('store_id', $request->get('store_id')))
            ->pluck('yarn_lot')
            ->unique()->values();

        $data['loanParty'] = Supplier::query()
            ->where('party_type','like', '%' . Supplier::LOAN_PARTY . '%')
            ->get();

        $data['piNos'] = $data['yarnReceive']->where('receive_basis', 'pi')
            ->pluck('receive_basis_no')
            ->unique()->values();

        $data['lcNos'] = $data['yarnReceive']->pluck('lc_no')->unique()->values();
        $data['receiveNos'] = $data['yarnReceive']->pluck('receive_no')->unique()->values();
        $data['challanNos'] = $data['yarnReceive']->pluck('challan_no')->unique()->values();
        $data['storeIds'] = $data['yarnReceive']->pluck('store_id')->unique()->values();
        $data['stores'] = Store::query()->whereIn('id', $data['storeIds'])->get();

        return $data;
    }

    /**
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws Exception
     */
    public function getDailyStatement(Request $request)
    {
        $reportData = $this->requiredData($request);
        $reportData['reportData'] = YarnReceiveReportService::setData($request)->dailyWiseFormat();

        if ($request->get('type') == 'pdf') {
            $pdf = PDF::loadView('inventory::yarns.reports.daily-yarn-receive-statement.pdf', $reportData)
                ->setPaper('a4', 'landscape')
                ->setOptions([
                    'header-html' => view('skeleton::pdf.header'),
                    'footer-html' => view('skeleton::pdf.footer'),
                ]);
            return $pdf->stream('daily_yarn_receive_report.pdf');
        }

        if ($request->get('type') == 'excel') {
            return Excel::download(new DailyYarnReceiveReport($reportData['reportData']), 'daily_yarn_receive_statement.xlsx');
        }

        return view('inventory::yarns.reports.daily-yarn-receive-statement.daily-yarn-receive-statement', $reportData);
    }

    /**
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws Exception
     */
    public function getChallanWiseStatement(Request $request)
    {
        $reportData = $this->requiredData($request);
        $reportData['reportData'] = YarnReceiveReportService::setData($request)->challanWiseFormat();

        if ($request->get('type') == 'pdf') {
            $pdf = PDF::loadView('inventory::yarns.reports.challan-wise-receive-statement.pdf', $reportData)
                ->setPaper('a4', 'landscape')
                ->setOptions([
                    'header-html' => view('skeleton::pdf.header'),
                    'footer-html' => view('skeleton::pdf.footer'),
                ]);
            return $pdf->stream('challan_wise_yarn_receive_report.pdf');
        }

        if ($request->get('type') == 'excel') {
            return Excel::download(new ChallanWiseReceiveReport($reportData['reportData']), 'challan_wise_receive_statement.xlsx');
        }

        return view('inventory::yarns.reports.challan-wise-receive-statement.challan-wise-receive-statement',$reportData);
    }


    /**
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws Exception
     */
    public function getGoodsReceiveWithoutLC(Request $request)
    {
        $request->request->add(['report_type' => 'without_lc']);
        $request->request->add(['date_column' => 'pi_receive_date']);

        $reportData['factories'] = Factory::query()->get();
        $reportData['yarnReceive'] = YarnReceive::query()->get();

        $reportData['lotNos'] = YarnReceiveDetail::query()
            ->when($request->get('store_id'), Filter::applyFilter('store_id', $request->get('store_id')))
            ->pluck('yarn_lot')
            ->unique()->values();

        $reportData['loanParty'] = Supplier::query()
            ->where('party_type','like', '%' . Supplier::LOAN_PARTY . '%')
            ->get();

        $reportData['piNos'] = $reportData['yarnReceive']->where('receive_basis', 'pi')
            ->pluck('receive_basis_no')
            ->unique()->values();

        $reportData['storeIds'] = $reportData['yarnReceive']->pluck('store_id')->unique()->values();
        $reportData['stores'] = Store::query()->whereIn('id', $reportData['storeIds'])->get();
        $reportData['yarnCount'] = YarnCount::query()->get();
        $reportData['yarnType'] = CompositionType::query()->get();
        $reportData['yarnComposition'] = YarnComposition::query()->get();

        $reportData['reportData'] = GoodsReceivedWithoutLCReportService::setData($request)->withoutLCFormat();

        if ($request->get('type') == 'pdf') {
            $pdf = PDF::loadView('inventory::yarns.reports.goods-receive-without-lc.pdf', $reportData)
                ->setPaper('a4', 'landscape')
                ->setOptions([
                    'header-html' => view('skeleton::pdf.header'),
                    'footer-html' => view('skeleton::pdf.footer'),
                ]);
            return $pdf->stream('goods-receive-without-lc.pdf');
        }

        if ($request->get('type') == 'excel') {
            return Excel::download(new GoodsReceivedWithoutLCExport($reportData['reportData']), 'goods-receive-without-lc.xlsx');
        }

        return view('inventory::yarns.reports.goods-receive-without-lc.goods-receive-without-lc', $reportData);
    }

    /**
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws Exception
     */
    public function getGoodsReceiveWithLC(Request $request)
    {
        $request->request->add(['report_type' => 'with_lc']);
        $request->request->add(['date_column' => 'lc_receive_date']);

        $reportData['factories'] = Factory::query()->get();
        $reportData['yarnReceive'] = YarnReceive::query()->get();

        $reportData['lotNos'] = YarnReceiveDetail::query()
            ->when($request->get('store_id'), Filter::applyFilter('store_id', $request->get('store_id')))
            ->pluck('yarn_lot')
            ->unique()->values();

        $reportData['loanParty'] = Supplier::query()
            ->where('party_type','like', '%' . Supplier::LOAN_PARTY . '%')
            ->get();

        $reportData['piNos'] = $reportData['yarnReceive']->where('receive_basis', 'pi')
            ->pluck('receive_basis_no')
            ->unique()->values();

        $reportData['lcNos'] = $reportData['yarnReceive']->pluck('lc_no')->unique()->values();
        $reportData['storeIds'] = $reportData['yarnReceive']->pluck('store_id')->unique()->values();
        $reportData['stores'] = Store::query()->whereIn('id', $reportData['storeIds'])->get();
        $reportData['yarnCount'] = YarnCount::query()->get();
        $reportData['yarnType'] = CompositionType::query()->get();
        $reportData['yarnComposition'] = YarnComposition::query()->get();

        $reportData['reportData'] = GoodsReceivedWithoutLCReportService::setData($request)->withLCFormat();

        if ($request->get('type') == 'pdf') {
            $pdf = PDF::loadView('inventory::yarns.reports.goods-receive-with-lc.pdf', $reportData)
                ->setPaper('a4', 'landscape')
                ->setOptions([
                    'header-html' => view('skeleton::pdf.header'),
                    'footer-html' => view('skeleton::pdf.footer'),
                ]);
            return $pdf->stream('goods-receive-with-lc.pdf');
        }

        if ($request->get('type') == 'excel') {
            return Excel::download(new GoodsReceivedWithoutLCExport($reportData['reportData']), 'goods-receive-with-lc.xlsx');
        }

        return view('inventory::yarns.reports.goods-receive-with-lc.goods-receive-with-lc', $reportData);
    }
}
