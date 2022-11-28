<?php


namespace SkylarkSoft\GoRMG\Merchandising\Controllers\YarnPurchase;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use PDF;
use SkylarkSoft\GoRMG\Merchandising\Models\YarnPurchaseOrder;
use SkylarkSoft\GoRMG\Merchandising\Services\ReportSignatureService;
use SkylarkSoft\GoRMG\SystemSettings\Models\TermsAndCondition;

class YarnPurchaseOrderController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $yarnOrders = YarnPurchaseOrder::query()
            ->with(['buyer', 'supplier', 'yarnComposition', 'yarnCount', 'uom', 'details', 'factory'])
            ->where('wo_no', 'like', "%{$search}%")
            ->orWhere('delivery_date', 'like', "%{$search}%")
            ->orWhere('wo_date', 'like', "%{$search}%")
            ->orWhereHas('factory', function ($query) use ($search) {
                $query->where('factory_name', 'like', "%{$search}%");
            })
            ->orWhereHas('supplier', function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->latest()->paginate();
        $totalOrders = YarnPurchaseOrder::all()->count();

        $dashboardOverview = [
            "Total Orders" => $totalOrders
        ];

        return view('merchandising::yarn-purchase.Order.yarn-purchase-order-list', [
            'yarnOrders' => $yarnOrders,
            'dashboardOverview' => $dashboardOverview,
        ]);
    }

    public function create()
    {
        return view('merchandising::yarn-purchase.Order.yarn-purchase-order-form');
    }

    public function destroy($id): RedirectResponse
    {
        try {
            YarnPurchaseOrder::query()->findOrFail($id)->delete();
            Session::flash('alert-success', 'Deleted Successfully');
        } catch (\Exception $exception) {
            Session::flash('alert-danger', 'Something went wrong');
        } finally {
            return back();
        }
    }

    public function view($id)
    {
        $order = YarnPurchaseOrder::with('details')
            ->findOrFail($id);
        $signature = ReportSignatureService::getSignatures(YarnPurchaseOrder::class, $id);

        $termsConditions = TermsAndCondition::query()
            ->where('page_name', 'yarn_purchase_order')
            ->get();
        return view('merchandising::yarn-purchase.Order.view', compact('order', 'signature', 'termsConditions'));
    }

    public function print($id)
    {
        $order = YarnPurchaseOrder::with('details')
            ->findOrFail($id);

        $signature = ReportSignatureService::getSignatures(YarnPurchaseOrder::class, $id);

        $termsConditions = TermsAndCondition::query()
            ->where('page_name', 'yarn_purchase_order')
            ->get();
        return view('merchandising::yarn-purchase.Order.print', compact('order', 'signature', 'termsConditions'));
    }

    public function pdf($id)
    {

        $order = YarnPurchaseOrder::with('details')->findOrFail($id);
        $signature = ReportSignatureService::getApprovalSignature(YarnPurchaseOrder::class, $id);
        $termsConditions = TermsAndCondition::query()
            ->where('page_name', 'yarn_purchase_order')
            ->get();

        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView("merchandising::yarn-purchase.Order.pdf", compact('order', 'signature', 'termsConditions'))
            ->setPaper('a4')->setOrientation('landscape');

        return $pdf->stream("{$id}_yarn_purchase.pdf");
    }

    public function yarnBookingView($id)
    {
        $order = YarnPurchaseOrder::with('details.order')
            ->findOrFail($id);
        return view('merchandising::yarn-purchase.Order.booking-view', ['order' => $order]);
    }

    public function yarnBookingPdf($id)
    {
        $order = YarnPurchaseOrder::with('details.order')
            ->findOrFail($id);

        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView("merchandising::yarn-purchase.Order.booking-pdf", [
                'order' => $order
            ])
            ->setPaper('a4')->setOrientation('landscape');

        return $pdf->stream("{$id}_yarn_booking.pdf");
    }
}
