<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\API\YarnIssue;

use Illuminate\Http\Response;
use PDF;
use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\Inventory\Models\YarnIssue;
use SkylarkSoft\GoRMG\Inventory\Models\YarnReceiveDetail;

class IssueChallanController extends Controller
{
    protected function data($id): array
    {

        $yarnIssue = YarnIssue::query()->with([
            'details.composition',
            'details.yarn_count',
            'details.requisition.program',
            'details.type',
            'details.floor',
            'details.room',
            'details.rack',
            'details.shelf',
            'details.store',
            'details.bin',
            'details.uom',
            'loanParty',
            'supplier',
            'buyer'
        ])->find($id);
        $view['yarnIssue'] = $yarnIssue;
        $receiveDetails = YarnReceiveDetail::query()
            ->whereIn('yarn_lot', $yarnIssue->details()->pluck('yarn_lot')->unique())
            ->get();
        $view['lc_no'] =ltrim($receiveDetails
            ->pluck('yarnReceive.lc_no')
            ->values()
            ->unique()
            ->join(', '),',');
        $view['lc_date'] =ltrim($receiveDetails
            ->pluck('yarnReceive.lc_receive_date_format')
            ->values()
            ->unique()
            ->join(', '),',');
        return $view;
    }

    public function index($id)
    {
        return view('inventory::yarns.yarn-issue.view.challan-view', $this->data($id));
    }

    public function pdf($id)
    {
        return PDF::loadView('inventory::yarns.yarn-issue.view.challan-pdf', $this->data($id))->stream('Yarn-Issue-Challan.pdf');
    }

    public function print($id)
    {
        return view('inventory::yarns.yarn-issue.view.challan-print', $this->data($id));
    }


    public function yarnChallanPdf($id)
    {
        return PDF::loadView('inventory::yarns.yarn-issue.yarn-challan.pdf', $this->data($id))
            ->setPaper('a4')->setOrientation('landscape')->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ])
            ->stream('Yarn-Issue-Challan.pdf');
    }

    public function yarnChallanView($id)
    {
        return view('inventory::yarns.yarn-issue.yarn-challan.view', $this->data($id));
    }

    public function YarnChallanPrint($id)
    {
        return view('inventory::yarns.yarn-issue.yarn-challan.print', $this->data($id));
    }
}
