<?php

namespace SkylarkSoft\GoRMG\GeneralStore\Controllers;

use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\GeneralStore\Models\GsCustomer;
use SkylarkSoft\GoRMG\GeneralStore\Models\GsItem;
use SkylarkSoft\GoRMG\GeneralStore\Models\GsRack;
use SkylarkSoft\GoRMG\GeneralStore\Services\StoreStrategy;

class StoreController extends Controller
{

    public function index()
    {
        //        $stores = get_inv_stores();
        //        return view('inventory::stores.index', compact('stores'));
    }

    public function stockInPage($store)
    {
        $storeStrategy = StoreStrategy::getStrategy($store, 'in');
        $stockInFormData = $storeStrategy->stockInData();
        $stockInFormData['racks'] = GsRack::all(['id', 'name'])->pluck("name", "id");
        $stockInFormData['stores'] = get_key_val_stores();
        $stockInFormData['items'] = GsItem::all();
        if ($store == 1) {
            return view('general-store::forms.voucher_in', $stockInFormData);
        }
        if ($store == 2) {
            return view('general-store::forms.voucher_in', $stockInFormData);
        }
        // if ($store == 3) {
        //     $systemGenerateId = "FB-" . time();
        //     $bookingDate = date('Y-m-d');
        //     $supplier = Supplier::all()->pluck('name', 'id');
        //     $buyers = Buyers::all()->pluck('name', 'id');
        //     $style = Style::all()->pluck('style_name', 'id');
        //     $po = PurchaseOrder::all()->pluck('po', 'id');
        //     $color = Color::all()->pluck('name', 'id');
        //     $garments = Garments::all()->pluck('name', 'id');
        //     $fabric = Fabrication::all()->pluck('name', 'id');
        //     $uom = Uom::all()->pluck('name', 'id');
        //     $rack = Rack::all(['id', 'name'])->pluck("name", "id");
        //     return view('inventory::forms.fab_voucher_in', [
        //         'fabricVoucher'    => null,
        //         'systemGenerateId' => $systemGenerateId,
        //         'bookingDate'      => $bookingDate,
        //         'supplier'         => $supplier,
        //         'buyers'           => $buyers,
        //         'style'            => $style,
        //         'po'               => $po,
        //         'color'            => $color,
        //         'garments'         => $garments,
        //         'fabric'           => $fabric,
        //         'uom'              => $uom,
        //         'rack'             => $rack,
        //     ]);
        // }
        // if ($store == 4) {
        //     return view('inventory::forms.voucher_in', $stockInFormData);
        // }
    }


    public function demoStockOutPage($store)
    {
        return view('general-store::forms.out_demo');
    }

    public function stockOutPage($store)
    {
        $type = 'out';
        $storeStrategy = StoreStrategy::getStrategy($store, $type);
        $stockOutFormData = $storeStrategy->stockOutData();
        $stockOutFormData['customer'] = GsCustomer::all(['id', 'name'])->pluck("name", 'id')->toArray();
        $stockOutFormData['items'] = GsItem::all();
        // dd($stockOutFormData);

        return view('general-store::forms.voucher_out', $stockOutFormData);
    }
}
