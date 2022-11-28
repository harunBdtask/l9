<?php


namespace SkylarkSoft\GoRMG\Merchandising\Controllers\YarnPurchase;

use \App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseRequisitions\YarnPurchaseRequisition;

class RequisitionController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $yarnRequisitions = YarnPurchaseRequisition::with(['factory', 'merchant'])
            ->where('requisition_no', 'like', "%{$search}%")
            ->orWhere('required_date', 'like', "%{$search}%")
            ->orWhere('requisition_date', 'like', "%{$search}%")
            ->orWhereHas('factory', function ($query) use ($search) {
                $query->where('factory_name', 'like', "%{$search}%");
            })
            ->orWhereHas('merchant', function ($query) use ($search) {
                $query->where('screen_name', 'like', "%{$search}%");
            })
            ->latest()->paginate();
            $totalRequisition = YarnPurchaseRequisition::all()->count();
           
            $dashboardOverview = [
                "Total Requisition" => $totalRequisition
            ];

        return view('merchandising::yarn-purchase.yarn-purchase-list', [
            'yarnRequisitions' => $yarnRequisitions,
            'dashboardOverview' => $dashboardOverview,

        ]);
    }

    public function create()
    {
        return view('merchandising::yarn-purchase.yarn-purchase-form');
    }

    public function destroy($id): RedirectResponse
    {
        try {
            YarnPurchaseRequisition::query()->findOrFail($id)->delete();
            Session::flash('alert-success', 'Deleted Successfully');
        } catch (\Exception $exception) {
            Session::flash('alert-danger', 'Something went wrong');
        } finally {
            return back();
        }
    }
}
