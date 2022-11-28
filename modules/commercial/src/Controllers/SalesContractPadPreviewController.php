<?php

namespace SkylarkSoft\GoRMG\Commercial\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Session;
use PDF;
use SkylarkSoft\GoRMG\Commercial\Models\SalesContract;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;

class SalesContractPadPreviewController extends Controller
{
    public function index(SalesContract $contract)
    {
        try {
            $contract->load('lienBank','details');
            $contract->buyer = Buyer::whereIn('id', (!is_array($contract->buyer_id)?explode(" ", $contract->buyer_id):$contract->buyer_id))->get();
            $contract->notifying_party = Buyer::where('id', $contract->notifying_party_id)->get();
            $contract->buyers = collect($contract->buyer)->implode('name', ',');
            $contract->styles = '';
            if(isset($contract->details))
            {
                $order_id = PurchaseOrder::whereIn('id', collect($contract->details)->pluck('po_id')->toArray())->pluck('order_id')->toArray();
                $style = Order::whereIn('id',$order_id)->pluck('style_name')->toArray();
                $contract->styles = implode(" & ", $style);
            }
            return view('commercial::sales-contract.pad-preview.view', compact('contract'));
        } catch (Exception $exception) {
            Session::flash('error', 'Something went wrong');
            return redirect()->back();
        }
    }

    public function getPdf(SalesContract $contract)
    {
        try {
            $contract->load('lienBank','details');
            $contract->buyer = Buyer::whereIn('id', (!is_array($contract->buyer_id)?explode(" ", $contract->buyer_id):$contract->buyer_id))->get();
            $contract->notifying_party = Buyer::where('id', $contract->notifying_party_id)->get();
            $contract->buyers = collect($contract->buyer)->implode('name', ',');
            $contract->styles = '';
            if(isset($contract->details))
            {
                $order_id = PurchaseOrder::whereIn('id', collect($contract->details)->pluck('po_id')->toArray())->pluck('order_id')->toArray();
                $style = Order::whereIn('id',$order_id)->pluck('style_name')->toArray();
                $contract->styles = implode(" & ", $style);
            }
            $pdf = PDF::setOption('enable-local-file-access', true)
                ->loadView('commercial::sales-contract.pad-preview.pdf', compact('contract'))
                ->setPaper('a4')
                ->setOptions([
                    'header-html' => view('skeleton::pdf.header'),
                    'footer-html' => view('skeleton::pdf.footer')
                ]);
            return $pdf->stream('btb-lc-pad-preview.pdf');
        } catch (Exception $exception) {
            Session::flash('error', 'Something went wrong');
            return redirect()->back();
        }
    }

}
