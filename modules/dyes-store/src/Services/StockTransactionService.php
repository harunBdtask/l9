<?php

namespace SkylarkSoft\GoRMG\DyesStore\Services;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\DyesStore\Events\DyesTransactionCompleted;
use SkylarkSoft\GoRMG\DyesStore\Models\DsItem;
use SkylarkSoft\GoRMG\DyesStore\Models\DyesChemicalsIssue;
use SkylarkSoft\GoRMG\DyesStore\Models\DyesChemicalsReceive;
use SkylarkSoft\GoRMG\DyesStore\Models\DyesChemicalTransaction;

class StockTransactionService
{
    private $type, $id;

    private const IN = 'in';
    private const OUT = 'out';

    /**
     * @param $type
     * @param $id
     */
    public function __construct($type, $id)
    {
        $this->type = $type;
        $this->id = $id;
    }


    /**
     * @throws Exception
     */
    public function handle()
    {
        try {
            DB::beginTransaction();
            if ($this->type === self::IN) {
                $data = DyesChemicalsReceive::query()->findOrFail($this->id);
                $data->update(['readonly' => 0]);

                event(new DyesTransactionCompleted($data));
            } elseif ($this->type === self::OUT) {
                $data = DyesChemicalsIssue::query()->findOrFail($this->id);
                $data->update(['readonly' => 0]);
            }

            $dyesChemicalsTransaction = [];
            if (!empty($data->details)) {
                foreach ($data->details as $value) {
                    $dyesChemicalsTransaction[] = $this->formatTransaction($data, $value, $this->type);
                }
            }
            DyesChemicalTransaction::query()->insert($dyesChemicalsTransaction);
            DB::commit();
        } catch (\Throwable $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    private function formatTransaction($data, $details, $type): array
    {
        if ($type === self::IN) {
            $data = $this->formatReceiveArray($data, $details);
        } elseif ($type === self::OUT) {
            $dyesTransaction = DyesChemicalTransaction::query()->where('item_id', $details['item_id'])->get(['trn_type', 'qty']);
            $availableQty = $this->qtyCalculation($dyesTransaction);
            if (($availableQty - $details['delivery_qty']) < 0) {
                $message = "{$details['item_name']} is not available in store. ";
                $message .= $details['delivery_qty'] - $availableQty;
                $message .= " {$details['uom_name']} more is needed.";
                throw new Exception($message);
            }
            $data = $this->formatIssueArray($data, $details);
        }
        return $data;
    }

    private function qtyCalculation($dyesTransaction): int
    {
        $data = collect($dyesTransaction);
        $totalInQty = $data->where('trn_type', self::IN)->sum('qty') ?: 0;
        $totalOutQty = $data->where('trn_type', self::OUT)->sum('qty') ?: 0;
        return $totalInQty - $totalOutQty;
    }

    private function formatReceiveArray($data, $details): array
    {
        $item = DsItem::query()->find($details['item_id']);

        return [
            'item_id' => $details['item_id'] ?? null,
            'category_id' => $details['category_id'] ?? null,
            'brand_id' => $details['brand_id'] ?? null,
            'qty' => $details['receive_qty'],
            'rate' => $details['rate'],
            'trn_date' => $data->receive_date,
            'trn_type' => self::IN,
            'dyes_chemical_receive_id' => $data->id,
            'created_by' => Auth::user()->id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'uom_id' => $details['uom_id'] ?? null,
            'sr_no' => $details['details']['sr_no'] ?? null,
            'lot_no' => $details['details']['lot_no'] ?? null,
            'mrr_no' => $details['details']['mrr_no'] ?? null,
            'batch_no' => $details['details']['batch_no'] ?? null,
            'life_end_days' => $details['details']['life_end_days'] ?? null,
            'dyes_chemical_issue_id' => null,
            'generate_barcodes' => $item->barcode,
            'barcode_id' => null,
        ];
    }

    private function formatIssueArray($data, $details): array
    {
        return [
            'item_id' => $details['item_id'] ?? null,
            'category_id' => $details['category_id'] ?? null,
            'brand_id' => $details['brand_id'] ?? null,
            'qty' => $details['delivery_qty'],
            'rate' => $details['rate'],
            'trn_date' => $data->delivery_date,
            'trn_type' => self::OUT,
            'sub_store_id' => $data->store_id === 0 ? null : $data->store_id,
            'dyes_chemical_receive_id' => null,
            'created_by' => Auth::user()->id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'uom_id' => $details['uom_id'] ?? null,
            'sr_no' => $details['sr_no'] ?? null,
            'lot_no' => $details['lot_no'] ?? null,
            'mrr_no' => $details['mrr_no'] ?? null,
            'batch_no' => $details['batch_no'] ?? null,
            'life_end_days' => $details['life_end_days'] ?? null,
            'dyes_chemical_issue_id' => $data->id,
            'generate_barcodes' => 0,
            'barcode_id' => $details['barcode_id'] ?? null,
        ];
    }
}
