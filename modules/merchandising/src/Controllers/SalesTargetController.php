<?php


namespace SkylarkSoft\GoRMG\Merchandising\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Merchandising\Models\SalesTarget;
use SkylarkSoft\GoRMG\Merchandising\Models\SalesTargetDetails;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\BuyingAgentModel;
use SkylarkSoft\GoRMG\SystemSettings\Models\Currency;
use SkylarkSoft\GoRMG\SystemSettings\Models\Team;

class SalesTargetController extends Controller
{
    public function index()
    {
        $sort = request('sort') ?? 'desc';
        $targets = SalesTarget::with('buyer', 'buyingAgent', 'teamLeader')->orderBy('id', $sort)->paginate(10);
        return view('merchandising::sales-target.index', compact('targets'));
    }

    public function create()
    {
        $buyers = Buyer::all();
        $buyingAgents = BuyingAgentModel::all();
        $leaders = Team::where('role', 'Leader')->get()->map(function ($teamMember) {
            return [
                'id' => $teamMember->member->id,
                'name' => $teamMember->member->first_name . ' ' . $teamMember->member->last_name,
            ];
        });
        $currencies = Currency::all();
        $months = $this->getMonths();

        return view('merchandising::sales-target.create', compact('buyers', 'buyingAgents', 'leaders', 'months', 'currencies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'buyer_id' => 'required|numeric',
            'buying_agent_id' => 'required|numeric',
            'team_leader_id' => 'required|numeric',
            'month' => 'required',
            'year' => 'required|numeric',
        ]);
        $salesTarget = $request->only('buyer_id', 'buying_agent_id', 'team_leader_id', 'month', 'year');
        DB::beginTransaction();

        try {
            $salesTarget = SalesTarget::create($salesTarget);
            foreach ($request->get('months') as $key => $month) {
                $details['sales_target_id'] = $salesTarget->id;
                $details['month'] = $request->get('months')[$key];
                $details['target'] = $request->get('targets')[$key];
                $details['value'] = $request->get('values')[$key];
                $details['currency_id'] = $request->get('currency')[$key];
                SalesTargetDetails::create($details);
            }
            DB::commit();
        } catch (\Exception $e) {
            Session::flash('alert-danger', 'Something went wrong');
            DB::rollback();

            return redirect()->back();
        }
        Session::flash('alert-success', 'Data create successfully');

        return redirect('sales-target-determination');
    }

    public function edit($id)
    {
        $buyers = Buyer::all();
        $buyingAgents = BuyingAgentModel::all();
        $leaders = Team::where('role', 'Leader')->get()->map(function ($teamMember) {
            return [
                'id' => $teamMember->member->id,
                'name' => $teamMember->member->first_name . ' ' . $teamMember->member->last_name,
            ];
        });
        $currencies = Currency::all();
        $months = $this->getMonths();
        $salesTarget = SalesTarget::with('details', 'buyer', 'buyingAgent', 'teamLeader')->findOrFail($id);

        return view('merchandising::sales-target.update', compact('buyers', 'buyingAgents', 'leaders', 'months', 'currencies', 'salesTarget'));
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'buyer_id' => 'required|numeric',
            'buying_agent_id' => 'required|numeric',
            'team_leader_id' => 'required|numeric',
            'month' => 'required',
            'year' => 'required|numeric',
        ]);
        $salesTarget = $request->only('buyer_id', 'buying_agent_id', 'team_leader_id', 'month', 'year');
        DB::beginTransaction();

        try {
            SalesTarget::findOrFail($id)->update($salesTarget);
            SalesTargetDetails::where('sales_target_id', $id)->delete();
            foreach ($request->get('months') as $key => $month) {
                $details['sales_target_id'] = $id;
                $details['month'] = $request->get('months')[$key];
                $details['target'] = $request->get('targets')[$key];
                $details['value'] = $request->get('values')[$key];
                $details['currency_id'] = $request->get('currency')[$key];
                SalesTargetDetails::create($details);
            }
            DB::commit();
        } catch (\Exception $e) {
            Session::flash('alert-danger', 'Something went wrong');
            DB::rollback();

            return redirect()->back();
        }
        Session::flash('alert-success', 'Data update successful');

        return redirect('sales-target-determination');
    }

    public function destroy($id, Request $request)
    {
        SalesTarget::findOrFail($id)->delete();
        Session::flash('alert-success', 'Data delete successfully');

        return redirect('sales-target-determination');
    }

    public function show($id)
    {
        $salesTarget = SalesTarget::with('details.currency', 'buyer', 'buyingAgent', 'teamLeader')->findOrFail($id);
        return view('merchandising::sales-target.view', compact('salesTarget'));
    }

    private function getMonths(): array
    {
        return [
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July',
            'August',
            'September',
            'October',
            'November',
            'December',
        ];
    }
}
