<?php

namespace SkylarkSoft\GoRMG\GeneralStore\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use SkylarkSoft\GoRMG\GeneralStore\Models\GsInvBarcode;
use SkylarkSoft\GoRMG\GeneralStore\Models\GsItem;

class BarcodeBreak implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $req, $voucher;

    /**
     * Create a new job instance.
     *
     * @param $request
     * @param $voucher
     */
    public function __construct($request, $voucher)
    {
        $this->req = $request;
        $this->voucher = $voucher;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $req = $this->req;
        foreach ($req->details as $key => $value) {
            $invItem = GsItem::find($req->details[$key]['item_id']);
            if (!$invItem->barcode || ($req->details[$key]['qty'] === $req->details[$key]['delivery_qty'])) {
                continue;
            }
            GsInvBarcode::where("code", $req->details[$key]['code'])->update([
                "status" => false
            ]);
            $break_barcode = GsInvBarcode::where("code", $req->details[$key]['code'])->first();
            $new_barcode = $break_barcode->replicate()->fill([
                "voucher_id" => $this->voucher->id,
                "parent_id" => $break_barcode->id,
                "qty" => $req->details[$key]['qty'] - $req->details[$key]['delivery_qty'],
                "status" => true,
                "type" => "out"
            ]);
            $new_barcode->save();
            $new_barcode->code = $invItem->prefix . sprintf('%05d', $new_barcode->id);
            $new_barcode->save();
        }
    }
}
