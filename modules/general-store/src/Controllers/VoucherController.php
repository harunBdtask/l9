<?php

namespace SkylarkSoft\GoRMG\GeneralStore\Controllers;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Redirect;
use SkylarkSoft\GoRMG\GeneralStore\Jobs\BarcodeBreak;
use SkylarkSoft\GoRMG\GeneralStore\Models\GsCustomer;
use SkylarkSoft\GoRMG\GeneralStore\Models\GsRack;
use SkylarkSoft\GoRMG\GeneralStore\Exceptions\VoucherIdNullException;
use SkylarkSoft\GoRMG\GeneralStore\Models\GsInvBarcode;
use SkylarkSoft\GoRMG\GeneralStore\Models\GsInvVoucher;
use SkylarkSoft\GoRMG\GeneralStore\Requests\GsInVoucherUpdateRequest;
use SkylarkSoft\GoRMG\GeneralStore\Requests\GsOutVoucherUpdateRequest;
use SkylarkSoft\GoRMG\GeneralStore\Requests\GsStockOutVoucherRequest;
use SkylarkSoft\GoRMG\GeneralStore\Services\FormService;
use SkylarkSoft\GoRMG\GeneralStore\Services\StoreStrategy;
use SkylarkSoft\GoRMG\GeneralStore\Models\GsItem;
use Symfony\Component\HttpFoundation\Response;

class VoucherController extends InventoryBaseController
{
    public function index($storeId)
    {
        $startDate = request('start_date');
        $endDate = request('end_date');
        $voucherNo = request('voucher_no');
        $type = request('type');

        request()->flash();

        $vouchers = GsInvVoucher::query()->where('store', $storeId)->latest();

        if ($type) {
            $vouchers->where('type', $type);
        }

        if ($startDate) {
            $vouchers->where('trn_date', '>=', Carbon::parse($startDate));
        }

        if ($endDate) {
            $vouchers->where('trn_date', '<=', Carbon::parse($endDate));
        }

        if ($voucherNo) {
            $vouchers->where('voucher_no', $voucherNo);
        }

        $vouchers = $vouchers->paginate();
        $items = GsItem::pluck('name', 'id');
        $data = ['vouchers' => $vouchers, 'items' => $items, 'storeId' => $storeId];

        return view('general-store::pages.vouchers', $data);
    }

    public function delete(GsInvVoucher $voucher): RedirectResponse
    {
        try {
            $voucher->delete();
            $this->alert('danger', 'Successfully Deleted Voucher!');
        } catch (Exception $e) {
            $this->alert('danger', 'Could Not Delete!');
        }

        return Redirect::back();
    }

    public function saveStockInVoucher(Request $req): JsonResponse
    {
        try {
            if ($req->has('id') && $req->id) {
                $voucher = GsInvVoucher::query()->findOrFail($req->id);
                $voucher->update($req->except('id'));
                return $this->jsonResponse(['message' => 'Successfully Updated Stock In Voucher!'], Response::HTTP_CREATED);
            }

            GsInvVoucher::create($req->all());
            return $this->jsonResponse(['message' => 'Successfully Saved Stock In Voucher!'], Response::HTTP_CREATED);
        } catch (Exception $e) {
            return $this->jsonResponse(['error' => true, 'msg' => 'Voucher Create Failed!', 'exception' => $e->getMessage()]);
        }
    }

    public function saveStockOutVoucher(GsStockOutVoucherRequest $req): JsonResponse
    {
        try {
            if ($req->has('id') && $req->id) {
                $voucher = GsInvVoucher::query()->findOrFail($req->id);
                $voucher->update($req->except('id'));
                BarcodeBreak::dispatchNow($req, $voucher);
                return $this->jsonResponse(['message' => 'Successfully Updated Stock Out Voucher!'], Response::HTTP_CREATED);
            }
            DB::beginTransaction();
            $voucher = GsInvVoucher::create($req->all());
            BarcodeBreak::dispatchNow($req, $voucher);
            DB::commit();
            return $this->jsonResponse(['message' => 'Successfully Saved Stock Out Voucher!'], Response::HTTP_CREATED);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->jsonResponse(['error' => true, 'msg' => "Voucher Create Failed!{$e->getMessage()}"]);
        }
    }

    public function voucherEditPage($store, $id, $type)
    {
        $storeStrategy = StoreStrategy::getStrategy($store, $type);
        $storeStrategy->setVoucherId($id);
        try {
            if ($type == 'in') {
                $data = $storeStrategy->stockInEditData();
                $data['racks'] = GsRack::all(['id', 'name'])->pluck("name", "id");
                $data['stores'] = get_key_val_stores();
                return view('general-store::forms.voucher_in', $data);
            }
            if ($type == 'out') {
                $data = $storeStrategy->stockOutEditData();
                $data['edit'] = true;
                $data['customer'] = GsCustomer::all(['id', 'name'])->pluck("name", 'id')->toArray();
                return view('general-store::forms.voucher_out', $data);
            }
            if ($data['voucher']->readonly) {
                $this->alert('danger', 'Voucher Is Readonly!!');
                return Redirect::route('vouchers', ['storeId' => $store]);
            }
        } catch (VoucherIdNullException $e) {
            $this->alert('danger', $e->getMessage());
            return $this->redirectBack();
        }

        return view('general-store::pages.voucher_edit.' . $type, $data);
    }

    public function inVoucherUpdate(GsInVoucherUpdateRequest $req): RedirectResponse
    {
        $voucherId = $req->input('id');
        $voucherData = $req->all(['trn_with', 'store', 'type', 'trn_date', 'requisition_id', 'reference']);
        $fields = $this->detailFields();
        $voucherData['details'] = $this->formatRows($req->all($fields));
        try {
            $voucher = GsInvVoucher::query()->findOrFail($voucherId);
            $voucher->update($voucherData);
            $this->alert('success', 'Successfully Updated Stock In Voucher!');
        } catch (\Exception $e) {
            $this->alert('danger', 'Could Not Update Stock In Voucher!');
        }
        return Redirect::route('vouchers');
    }

    public function outVoucherUpdate(GsOutVoucherUpdateRequest $req): RedirectResponse
    {
        $voucherId = $req->input('id');
        $voucherData = $req->all(['trn_with', 'store', 'type', 'trn_date', 'requisition_id']);
        $details = $req->all($this->detailFields());
        $voucherData['details'] = $this->formatRows($details);
        try {
            $voucher = GsInvVoucher::query()->findOrFail($voucherId);
            if ($voucher->readonly) {
                $this->alert('danger', 'Voucher Is Readonly!!');
                return Redirect::route('inv.vouchers');
            }
            $voucher->update($voucherData);
            $this->alert('success', 'Successfully Updated Stock Out Voucher!');
        } catch (\Exception $e) {
            $this->alert('danger', 'Could Not Update Stock Out Voucher!');
        }
        return Redirect::route('inv.vouchers');
    }

    public function show(GsInvVoucher $voucher)
    {
        $voucher->load("supplier");
        $itemIds = collect($voucher->details)->pluck('item_id');
        $items = GsItem::with('category')->whereIn('id', $itemIds)->get();
        return view('general-store::pages.voucher_view', compact('voucher', 'items'));
    }

    public function print(GsInvVoucher $voucher)
    {
        $voucher->load("supplier");
        $itemIds = collect($voucher->details)->pluck('item_id');
        $items = GsItem::with('category')->whereIn('id', $itemIds)->get();
        return view('general-store::print.voucher_print', compact('voucher', 'items'));
    }

    public function downloadVoucher(GsInvVoucher $voucher)
    {
        $voucher->load("supplier");
        $itemIds = collect($voucher->details)->pluck('item_id');
        $items = GsItem::with('category')->whereIn('id', $itemIds)->get();

        $pdf = PDF::loadView('general-store::print.voucher_download', [
            "voucher" => $voucher,
            "items" => $items,
        ]);
        return $pdf->download("{$voucher->voucher_no}_no_voucher.pdf");
    }

    public function downloadBarcode($voucherId)
    {
        $barcodes = GsInvBarcode::with('voucher', 'item', 'brand')
            ->where('voucher_id', $voucherId)
            ->get();

        $storeId = GsInvVoucher::query()->find($voucherId)->store;

        if (!count($barcodes)) {
            $this->alert('success', 'Barcode was not generated for this voucher!');
            return $this->redirect("vouchers/{$storeId}", "u");
        }

        $voucher = GsInvVoucher::query()->find($voucherId);
        $data = ['barcodes' => $barcodes, 'voucher' => $voucher];

        return view('general-store::barcode.print', $data);
    }

    private function formatRows(array $data): array
    {
        return (new FormService)->formatMulDimForm($data);
    }

    private function detailFields(): array
    {
        return ['item_id', 'item_desc', 'qty', 'rate', 'remarks', 'brand_id'];
    }
}
