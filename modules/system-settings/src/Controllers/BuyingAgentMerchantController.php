<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\BuyingAgentMerchantModel;
use SkylarkSoft\GoRMG\SystemSettings\Models\BuyingAgentModel;
use SkylarkSoft\GoRMG\SystemSettings\Requests\BuyingAgentMerchantRequest;

class BuyingAgentMerchantController extends Controller
{
    public function index()
    {
        $buyingAgents = BuyingAgentModel::query()->where('factory_id', factoryId())->orderBy('id', 'desc')->get();
        $buyingAgentMerchants = BuyingAgentMerchantModel::with('buyingAgent')->orderBy('id', 'desc')->paginate();

        return view('system-settings::buying-agent-merchant.list', compact('buyingAgents', 'buyingAgentMerchants'));
    }

    public function store(BuyingAgentMerchantRequest $request)
    {
        try {
            BuyingAgentMerchantModel::create($request->all());
            Session::flash('success', 'Data Saved Successfully');
        } catch (\Exception $e) {
            Session::flash('error', 'Data stored Failed');
        }

        return redirect('buying-agent-merchant');
    }

    public function show($id)
    {
        return BuyingAgentMerchantModel::findOrFail($id);
    }

    public function update($id, BuyingAgentMerchantRequest $request)
    {
        try {
            BuyingAgentMerchantModel::findOrFail($id)->update($request->all());
            Session::flash('success', 'Data Updated Successfully');
        } catch (\Exception $e) {
            Session::flash('error', 'Data stored Failed');
        }

        return redirect('buying-agent-merchant');
    }

    public function delete($id)
    {
        BuyingAgentMerchantModel::findOrFail($id)->delete();
        Session::flash('error', 'Data Deleted successfully!!');

        return redirect('buying-agent-merchant');
    }

    public function search(Request $request)
    {
        $search = $request->get('search');
        $buyingAgents = BuyingAgentModel::query()->where('factory_id', factoryId())->orderBy('id', 'desc')->get();
        $buyingAgentMerchants = BuyingAgentMerchantModel::with('buyingAgent')
            ->where('buying_agent_merchant_name', 'like', '%' . $search . '%')
            ->orWhere('mobile', 'like', '%' . $search . '%')
            ->orWhere('email', 'like', '%' . $search . '%')
            ->orWhereHas('buyingAgent', function ($query) use ($search) {
                $query->where('buying_agent_name', 'like', '%' . $search . '%');
            })
            ->orderBy('id', 'desc')->paginate();
        return view('system-settings::buying-agent-merchant.list', compact('buyingAgents', 'buyingAgentMerchants', 'search'));
    }
}
