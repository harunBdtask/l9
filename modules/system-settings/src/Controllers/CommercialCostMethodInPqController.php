<?php


namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\CommercialCostMethod;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Requests\CommercialCostMethodRequest;

class CommercialCostMethodInPqController extends Controller
{
    private function data()
    {
        $data = [
            'On Selling Price',
            'On Total Selling Price',
            'Yarn+ Trims+ Fabric Purchase',
            'Fabric Purchase + Trims Cost + Embellishment Cost + Garments Wash + Lab Test + Inspection +  Freight + Courier Cost + Certificate Cost + Design Cost + Studio Cost + Operating Expenses',
            'Fabric Purchase + Trims Cost + Embellishment Cost + Garments Wash + Lab Test + Inspection + CM Cost + Freight + Courier Cost + Certificate Cost + Design Cost + Studio Cost + Operating Expenses',
        ];

        return $data;
    }

    public function index()
    {
        $methods = $this->data();
        $factories = Factory::query()->userWiseFactories()->get(['id', 'factory_name']);
        $commercialCostMethods = CommercialCostMethod::orderBy('id', 'DESC')->paginate(10);

        return view('system-settings::pages.commercial_cost_method', compact('methods', 'commercialCostMethods', 'factories'));
    }

    public function store(CommercialCostMethodRequest $request)
    {
        CommercialCostMethod::create($request->all());
        Session::flash('success', 'Data insert successfully');

        return redirect()->back();
    }

    public function show($id)
    {
        return CommercialCostMethod::findOrFail($id);
    }

    public function update($id, CommercialCostMethodRequest $request)
    {
        try {
            $commercialCostMethod = CommercialCostMethod::findOrFail($id);
            $commercialCostMethod->update($request->all());
            Session::flash('success', 'Data updated successfully');

            return redirect()->back();
        } catch (\Exception $exception) {
            Session::flash('error', "{$exception->getMessage()}");

            return redirect()->back();
        }
    }

    public function destroy($id = null)
    {
        $commercialCostMethod = CommercialCostMethod::findOrFail($id);
        $commercialCostMethod->delete();
        Session::flash('alert-danger', 'Data deleted successfully!!');

        return redirect()->back();
    }

    public function search(Request $request)
    {
        $search = $request->get('search');
        $commercialCostMethods = CommercialCostMethod::where('method', 'like', '%' . $search . '%')
            ->orWhere('percentage', 'like', '%' . $search . '%')
            ->orWhere('writeable', 'like', '%' . $search . '%')->orderBy('id', 'DESC')->paginate();
        $methods = $this->data();

        return view('system-settings::pages.commercial_cost_method', compact('commercialCostMethods', 'search', 'methods'));
    }
}
