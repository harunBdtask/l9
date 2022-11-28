<?php

namespace SkylarkSoft\GoRMG\Commercial\Controllers\ActualCostEntry;

use App\Constants\ApplicationConstant;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Commercial\Models\ActualCost;

class ActualCostEntryController extends Controller
{
    public function index()
    {
        $actualCosts = ActualCost::with('company:id,factory_name')->latest()->paginate();
        $costHead = ActualCost::COST_HEAD;

        return view('commercial::actual-cost-entry.index', ['actualCosts' => $actualCosts, 'costHead' => $costHead]);
    }

    public function create()
    {
        return view('commercial::actual-cost-entry.create_update');
    }

    public function store(Request $request)
    {
        $this->validateRequest($request);

        if (isset($actualCost)) {
            $actualCost = ActualCost::where(['company_id' => $request->company_id, 'cost_head_id' => $request->cost_head_id])->first();
            $actualCost->update($request->all([
                'company_id',
                'cost_head_id',
                'incurred_date_from',
                'incurred_date_to',
                'applying_period_from',
                'applying_period_to',
                'amount',
                'based_on',
            ]));

            return response()->json(['message' => ApplicationConstant::S_UPDATED, 'actualCost' => $actualCost]);
        } else {
            try {
                $actualCost = new ActualCost($request->all());
                $actualCost->save();

                return response()->json(['message' => ApplicationConstant::S_CREATED, 'actualCost' => $actualCost]);
            } catch (\Exception $e) {
                return response()->json(['message' => $e->getMessage()]);
            }
        }
    }

    public function show(ActualCost $actualCost)
    {
        return response()->json($actualCost);
    }

    public function update(ActualCost $actualCost, Request $request)
    {
        $this->validateRequest($request);

        $actualCost->update($request->all([
            'company_id',
            'cost_head_id',
            'incurred_date_from',
            'incurred_date_to',
            'applying_period_from',
            'applying_period_to',
            'amount',
            'based_on',
        ]));

        return response()->json(['message' => ApplicationConstant::S_UPDATED]);
    }

    public function selectedOptions()
    {
        $data['cost_head'] = ActualCost::COST_HEAD;
        $data['based_on'] = ActualCost::BASED_ON;

        return response()->json($data);
    }

    public function destroy(ActualCost $actualCost)
    {
        $actualCost->delete();
        Session::flash('success', 'Successfully deleted');

        return redirect()->back();
    }

    private function validateRequest(Request $request)
    {
        $request->validate([
            'company_id' => 'required',
            'cost_head_id' => 'required',
            'incurred_date_from' => 'required',
            'incurred_date_to' => 'required',
            'applying_period_from' => 'required',
            'applying_period_to' => 'required',
            'amount' => 'required',
        ], [
            'required' => 'Required',
        ]);
    }
}
