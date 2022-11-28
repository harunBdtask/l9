<?php

namespace SkylarkSoft\GoRMG\DyesStore\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use SkylarkSoft\GoRMG\DyesStore\Models\DsItem;
use SkylarkSoft\GoRMG\DyesStore\Models\DyesChemicalBarcode;

class DyesBarcodeBreak implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const Insert = 'Insert';
    const Update = 'Update';

    private $req, $voucher, $type;

    /**
     * Create a new job instance.
     *
     * @param $request
     * @param $voucher
     * @param $type
     */
    public function __construct($request, $voucher, $type)
    {
        $this->req = $request;
        $this->voucher = $voucher;
        $this->type = $type;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $req = $this->req;
        $voucher = $this->voucher;
        foreach ($req['details'] as $value) {
            $itemDetails = DsItem::find($value['item_id']);

            if ($itemDetails->barcode && isset($value['barcode_id'])) {
                if ($this->type == self::Update) {
                    $previousIssueItemDetails = collect($voucher)->where('item_id', $value['item_id'])->first();
                    $previousIssueDeliveryQty = $previousIssueItemDetails->delivery_qty;
                    $this->removeOldQty($value['barcode_id'], $previousIssueDeliveryQty);
                }
                $this->updateBarcode($value['barcode_id'], $value['delivery_qty']);
            }
        }
    }

    private function removeOldQty(int $barcodeId, float $originIssueQty)
    {
        $barcode = DyesChemicalBarcode::where('id', $barcodeId)->first();

        if ($barcode) {
            $barcode->delivery_qty -= $originIssueQty;
            $barcode->save();
        }
    }

    private function updateBarcode($barcodeId, $issueQty)
    {
        $barcode = DyesChemicalBarcode::find($barcodeId);
        $barcode->delivery_qty += $issueQty;
        if ($barcode->qty === $barcode->delivery_qty) {
            $barcode->status = 0;
        } else {
            $barcode->status = 1;
        }
        $barcode->save();
    }
}
