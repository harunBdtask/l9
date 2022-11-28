<?php

namespace SkylarkSoft\GoRMG\Knitting\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Approval\Filters\Filter;
use SkylarkSoft\GoRMG\Knitting\Models\KnitProgramRoll;

class KnittingRollController extends Controller
{
    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $type = $request->get('type');
        $data = KnitProgramRoll::query()
            ->when($search, function ($q) use ($search) {
                $q->where('id', (int)$search)
                    ->orWhere('roll_weight', $search)
                    ->orWhereHas('knitCard', Filter::applyFilter('knit_card_no', $search))
                    ->orWhereHas('knitCard.buyer', Filter::applyFilter('name', $search))
                    ->orWhereHas('knitCard', Filter::applyFilter('color', $search))
                    ->orWhereHas('shift', Filter::applyFilter('shift_name', $search))
                    ->orWhereHas('operator', Filter::applyFilter('operator_name', $search));
            })
            ->when($type, function ($query) use ($type) {
                $query->whereHas('planningInfo', function ($query) use ($type) {
                    $query->where('booking_type', $type);
                });
            })
            ->with(['planningInfo', 'shift', 'operator', 'knitCard.buyer'])
            ->latest()
            ->paginate();
        $dashboardOverview = [
            'Total Roll' => 0
        ];

        return view('knitting::knitting-roll.index', [
            'data' => $data,
            'dashboardOverview' => $dashboardOverview
        ]);
    }


    public function view(KnitProgramRoll $knitProgramRoll)
    {
        $knitProgramRoll->load(
            'factory',
            'shift',
            'operator',
            'planningInfo',
            'knittingProgram',
            'knitCard.machine',
            'knitCard.yarnDetails.yarn_composition',
            'knitCard.yarnDetails.yarn_count',
            'knitCard.yarnDetails.yarn_type'
        );

        return view('knitting::knitting-roll.view', compact('knitProgramRoll'));
    }
}
