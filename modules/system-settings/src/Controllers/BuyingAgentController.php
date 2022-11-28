<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\BuyingAgentModel;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Requests\BuyingAgentRequest;

class BuyingAgentController extends Controller
{
    public function index()
    {
        $buyingAgents = BuyingAgentModel::orderBy('id', 'desc')->paginate();
        $factories = Factory::query()->userWiseFactories()->pluck('factory_name', 'id');
        return view('system-settings::buying-agent.list', compact(['factories', 'buyingAgents']));
    }

    public function store(BuyingAgentRequest $request)
    {
        try {
            $buyingAgent = BuyingAgentModel::create($request->all());

            $this->associateWithUpdateOrCreate($request->get('associate_with'), $buyingAgent);

            Session::flash('success', 'Data Saved Successfully');
        } catch (\Exception $e) {
            Session::flash('error', 'Data stored Failed');
        }

        return redirect('buying-agent');
    }

    public function show($id): JsonResponse
    {
        $buyingAgent = BuyingAgentModel::query()->with('buyingAgentWiseFactories')->findOrFail($id);
        $associateWith = $buyingAgent->buyingAgentWiseFactories->pluck('factory_id')->values();
        return response()->json([
            'buyingAgent' => $buyingAgent,
            'associateWith' => $associateWith
        ]);
    }

    public function update($id, BuyingAgentRequest $request)
    {
        try {
            $buyingAgent = BuyingAgentModel::findOrFail($id);
            $buyingAgent->update($request->all());

            $associateWith = $request->get('associate_with');
            $buyingAgent->buyingAgentWiseFactories()->whereNotIn('factory_id', $associateWith)->delete();

            $this->associateWithUpdateOrCreate($associateWith, $buyingAgent);

            Session::flash('success', 'Data Updated Successfully');
        } catch (\Exception $e) {
            Session::flash('error', 'Data stored Failed');
        }

        return redirect('buying-agent');
    }

    public function delete($id)
    {
        BuyingAgentModel::findOrFail($id)->delete();
        Session::flash('error', 'Data Deleted successfully!!');

        return redirect('buying-agent');
    }

    public function search(Request $request)
    {
        $search = $request->get('search');
        $buyingAgents = BuyingAgentModel::where('buying_agent_name', 'like', '%' . $search . '%')->orderBy('id', 'desc')->paginate();

        return view('system-settings::buying-agent.list', compact('buyingAgents', 'search'));
    }

    public function associateWithUpdateOrCreate($associateWiths, $buyer)
    {
        foreach ($associateWiths as $associateWith) {
            $buyer->buyingAgentWiseFactories()->updateOrCreate(['factory_id' => $associateWith]);
        }
    }
}
