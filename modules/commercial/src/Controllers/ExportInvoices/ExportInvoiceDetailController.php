<?php

namespace SkylarkSoft\GoRMG\Commercial\Controllers\ExportInvoices;

use App\Constants\ApplicationConstant;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Commercial\Models\ExportInvoice;
use SkylarkSoft\GoRMG\Commercial\Models\ExportInvoiceColorSizeDetail;
use SkylarkSoft\GoRMG\Commercial\Models\ExportInvoiceDetail;
use SkylarkSoft\GoRMG\Commercial\Models\ExportLCDetail;
use SkylarkSoft\GoRMG\Commercial\Models\SalesContractDetail;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsItem;

class ExportInvoiceDetailController extends Controller
{
    public function index(ExportInvoice $invoice): JsonResponse
    {
        $details = $invoice->details()->get();

        return response()->json($details);
    }

    public function store(ExportInvoice $invoice, Request $request): JsonResponse
    {
        try {
            \DB::beginTransaction();
            foreach ($request->all() as $item) {
                if ($id = $item['id'] ?? null) {
                    $invoice->details()->find($id)->update((array) $item);

                    if (! count($item['color_size_details'])) {
                        continue;
                    }

                    $this->updateOrCreateColorSizeDetails($item['color_size_details'], $id);

                    continue;
                }

                $detail = $invoice->details()->create((array) $item);

                if (! count($item['color_size_details'])) {
                    continue;
                }

                $this->updateOrCreateColorSizeDetails($item['color_size_details'], $detail->id);
            }
            \DB::commit();

            return response()->json(['message' => ApplicationConstant::S_STORED]);
        } catch (\Throwable $e) {
            \DB::rollBack();

            return response()->json(['message' => $e->getMessage(), 'line' => $e->getLine()], 500);
        }
    }

    private function updateOrCreateColorSizeDetails($colorSizeDetails, $exportInvoiceDetailId)
    {
        $keysToRemove = ['total_order_qty', 'total_rate', 'total_amount', 'total_invoice_qty', 'total_invoice_rate', 'total_invoice_amount'];
        foreach (collect($colorSizeDetails)->forget($keysToRemove) as $idx => $colorSizeDetailData) {
            $data = (array) $colorSizeDetailData;
            $data['export_invoice_detail_id'] = $exportInvoiceDetailId;

            if ($id = $data['id'] ?? null) {
                $colorSizeDetail = ExportInvoiceColorSizeDetail::find($id);
                $colorSizeDetail->fill($data);
                $colorSizeDetail->save();

                continue;
            }


            $colorSizeDetail = new ExportInvoiceColorSizeDetail($data);
            $colorSizeDetail->save();
        }
    }

    public function delete(ExportInvoiceDetail $detail): JsonResponse
    {
        try {
            \DB::beginTransaction();
            $detail->colorSizeDetails()->delete();
            $detail->delete();
            \DB::commit();

            return response()->json(['message' => ApplicationConstant::S_DELETED]);
        } catch (\Throwable $e) {
            \DB::rollBack();

            return response()->json(['message' => ApplicationConstant::SOMETHING_WENT_WRONG], 500);
        }
    }

    public function poDetails(): JsonResponse
    {
        request()->validate(['po_id' => 'required']);
        $detailId = request('detail_id');
        $purchaseOrderId = request('po_id');
        $exportLcId = request('export_lc_id') ?? null;
        $salesContractId = request('sales_contract_id') ?? null;
        $rate_query = null;
        if ($exportLcId != 'null' && $purchaseOrderId) {
            $rate_query = ExportLCDetail::query()
                ->where([
                    'export_lc_id' => $exportLcId,
                    'po_id' => $purchaseOrderId,
                ])->first();
        }
        if ($salesContractId != 'null' && $purchaseOrderId) {
            $rate_query = SalesContractDetail::query()
                ->where([
                    'sales_contract_id' => $salesContractId,
                    'po_id' => $purchaseOrderId,
                ])->first();
        }
        $lc_sc_rate = $rate_query ? $rate_query->rate : null;

        if ($detailId && $detailId != 'undefined') {
            $documentSubmissionDetail = ExportInvoiceDetail::with(
                ['colorSizeDetails.color', 'colorSizeDetails.size', 'colorSizeDetails.garmentsItem']
            )->findOrFail($detailId);

            if (count($documentSubmissionDetail->colorSizeDetails)) {
                $responseData = collect($documentSubmissionDetail->colorSizeDetails)
                    ->map(\Closure::fromCallable([$this, 'colorSizeDetailsFormatter']));

                return response()->json($responseData);
            }
        }


        $poDetails = $this->fetchPoDetails($purchaseOrderId);
        $formattedData = $this->formatPurchaseOrderData($poDetails, $lc_sc_rate);

        return response()->json($formattedData);
    }

    private function colorSizeDetailsFormatter($detail): array
    {
        return [
            'id' => $detail->id,
            'order_id' => $detail->order_id,
            'po_id' => $detail->po_id,
            'garments_item_id' => $detail->garments_item_id,
            'garments_item' => $detail->garmentsItem->name,
            'color_id' => $detail->color_id,
            'size_id' => $detail->size_id,
            'color' => $detail->color->name,
            'size' => $detail->size->name,
            'po_rate' => $detail->po_rate,
            'po_qty' => $detail->po_qty,
            'po_amount' => sprintf("%.2f", $detail->po_amount),
            'invoice_qty' => $detail->invoice_qty,
            'invoice_rate' => $detail->invoice_rate,
            'invoice_amount' => $detail->invoice_amount,
            'article_no' => $detail->article_no,
        ];
    }

    private function fetchPoDetails($purchaseOrderId): Collection
    {
        $purchaseOrder = PurchaseOrder::query()
            ->with('poDetails')
            ->findOrFail($purchaseOrderId);

        return $purchaseOrder->poDetails;
    }

    public function formatPurchaseOrderData($purchaseOrder, $lc_sc_rate): array
    {
        $garments = GarmentsItem::whereIn('id', collect($purchaseOrder)->pluck('garments_item_id'))->pluck('name', 'id');

        return collect($purchaseOrder)->map(function ($poDetails) use ($garments, $lc_sc_rate) {
            return collect($poDetails['colors'])->map(function ($color) use ($poDetails, $garments, $lc_sc_rate) {
                return collect($poDetails['sizes'])->map(function ($size) use ($poDetails, $color, $garments, $lc_sc_rate) {
                    $rate = $this->breakdownData($poDetails['quantity_matrix'], $color, $size, PurchaseOrder::RATE);
                    $quantity = $this->breakdownData($poDetails['quantity_matrix'], $color, $size, PurchaseOrder::QTY);
                    $amount = (double) $rate * (double) $quantity;

                    return [
                        'id' => null,
                        'order_id' => $poDetails['order_id'],
                        'po_id' => $poDetails['purchase_order_id'],
                        'garments_item_id' => $poDetails['garments_item_id'],
                        'garments_item' => $garments[$poDetails['garments_item_id']],
                        'color_id' => (int) $color,
                        'size_id' => (int) $size,
                        'color' => $this->getColor($poDetails['quantity_matrix'], $color),
                        'size' => $this->getSize($poDetails['quantity_matrix'], $size),
                        'po_rate' => $lc_sc_rate ?? $rate,
                        'po_qty' => $quantity,
                        'po_amount' => sprintf("%.2f", $amount),
                        'invoice_qty' => 0,
                        'invoice_rate' => $lc_sc_rate ?? $rate,
                        'invoice_amount' => 0,
                        'article_no' => $this->breakdownData($poDetails['quantity_matrix'], $color, $size, PurchaseOrder::ARTICLE_NO),
                    ];
                });
            });
        })->flatten(2)->toArray();
    }

    private function breakdownData($data, $colorId, $sizeId, $particular)
    {
        $target = collect($data)->where('color_id', $colorId)
            ->where('size_id', $sizeId)
            ->where('particular', $particular)
            ->first();

        return $target ? $target['value'] : null;
    }

    private function getColor($data, $colorId)
    {
        $target = collect($data)->where('color_id', $colorId)
            ->first();

        return $target ? $target['color'] : null;
    }

    private function getSize($data, $sizeId)
    {
        $target = collect($data)
            ->where('size_id', $sizeId)
            ->first();

        return $target ? $target['size'] : null;
    }
}
