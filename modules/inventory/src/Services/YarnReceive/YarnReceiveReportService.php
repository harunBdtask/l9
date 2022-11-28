<?php

namespace SkylarkSoft\GoRMG\Inventory\Services\YarnReceive;

use Illuminate\Database\Eloquent\Builder;
use SkylarkSoft\GoRMG\Inventory\Filters\Filter;
use SkylarkSoft\GoRMG\Inventory\Models\YarnReceive;
use SkylarkSoft\GoRMG\Commercial\Models\ProformaInvoice;
use SkylarkSoft\GoRMG\Inventory\Models\YarnReceiveDetail;
use SkylarkSoft\GoRMG\Inventory\YarnItemAction;

class YarnReceiveReportService
{
    public static $reportData;

    public static function setData($request): YarnReceiveReportService
    {
        $date = $request->get('date');
        $lcNo = $request->get('lc_no');
        $piNo = $request->get('pi_no');
        $refNo = $request->get('ref_no');
        $lotNo = $request->get('lot_no');
        $toDate = $request->get('to_date');
        $storeId = $request->get('store_id');
        $fromDate = $request->get('from_date');
        $factoryId = $request->get('factory_id');
        $partyType = $request->get('party_type');
        $receiveBy = $request->get('receive_by');
        $receiveId = $request->get('receive_id');
        $challanNo = $request->get('challan_no');
        $receiveBasis = $request->get('receive_basis');
        $reportType = $request->get('report_type');

        self::$reportData = YarnReceiveDetail::query()
            ->with(['yarnReceive.factory', 'yarnReceive.loanParty', 'yarnReceive.wo', 'yarnReceive.yarnReceiveReturn.details', 'yarnReceive.pi', 'yarn_count', 'composition', 'type'])
            ->when($lotNo, Filter::applyFilter('yarn_lot', $lotNo))
            ->when($refNo, Filter::applyFilter('product_code', $refNo))
            ->whereHas('yarnReceive', function (Builder $builder)
            use ($factoryId, $storeId, $partyType, $receiveBasis, $lcNo, $piNo, $receiveId, $challanNo, $date, $fromDate, $toDate, $reportType) {
                $builder->where('factory_id', $factoryId);
                $builder->where('store_id', $storeId);
                $builder->when($partyType, Filter::applyFilter('loan_party_id', $partyType));
                $builder->when($receiveBasis, Filter::applyFilter('receive_basis', $receiveBasis));
                $builder->when($lcNo, Filter::applyFilter('lc_no', $lcNo));
                $builder->when($reportType == 'without_lc', function ($query) use ($piNo) {
                    $query->whereNull('lc_no');
                });
                $builder->when($piNo, function ($query) use ($piNo) {
                    $query->where('receive_basis', YarnReceive::PI_BASIS);
                    $query->where('receive_basis_no', $piNo);
                });
                $builder->when($receiveId, Filter::applyFilter('receive_id', $receiveId));
                $builder->when($challanNo, Filter::applyFilter('challan_no', $challanNo));
                $builder->when($date, Filter::dateFilter('receive_date', $date));
                $builder->when($fromDate && $toDate, Filter::betweenFilter('receive_date', [$fromDate, $toDate]));
            })->get();

        return new static();
    }

    public function dailyWiseFormat()
    {
        $yarnReceiveDetails = self::$reportData;
        return $yarnReceiveDetails->map(function ($reportData) use ($yarnReceiveDetails) {

            $PiComposition = '';
            $piDetails = [];
            if ($reportData->yarnReceive->receive_basis == 'pi') {
                $piDetails = $reportData->yarnReceive->pi->details;
                $pi = ProformaInvoice::query()->find($reportData->yarnReceive->receive_basis_id);
                $PiComposition = collect(optional($pi->details)->details)
                    ->where('uuid', $reportData->basis_details_unique)
                    ->map(function ($item) {
                        return $item->count ? $item->count . ' - ' : '' . $item->composition . ($item->color ? ' - ' . $item->color : '');
                    })->first();
                $totalReceiveQty = YarnReceiveDetail::query()->whereHas('yarnReceive', function($query) use($reportData) {
                    $query->where('receive_basis', 'pi');
                })->where('basis_details_unique', $reportData->basis_details_unique)->sum('receive_qty');
            } else {
                $totalReceiveQty = $reportData->receive_qty;
            }
            $piDetails = $piDetails ? collect($piDetails->details)->where('uuid', $reportData->basis_details_unique) : collect([]);
            $piQty = $piDetails->sum('quantity');
            $piAmount = $piDetails->sum('amount');
            $piRate = $piDetails->sum('rate') == 0 || $piDetails->count() == 0 ? 0 : $piDetails->sum('rate') / $piDetails->count();
            $color = $reportData->yarn_color ? ' - ' . $reportData->yarn_color : null;
            $composition = $reportData->yarn_count->yarn_count . ' - ' . $reportData->composition->yarn_composition . $color;
            $balanceOfSpiMill = ($piQty - $reportData->receive_qty) < 0 ? null : $piQty - $reportData->receive_qty;
            $returnQty = $reportData->yarnReceive->yarnReceiveReturn
                ? collect($reportData->yarnReceive->yarnReceiveReturn)
                    ->pluck('details')
                    ->flatten(1)
                    ->pluck('return_qty')
                    ->sum()
                : 0;

            return [
                'loan_party_id' => $reportData->yarnReceive->loanParty->id,
                'party_name' => $reportData->yarnReceive->loanParty->name,
                'lc_no' => $reportData->yarnReceive->lc_no,
                'lc_date' => $reportData->yarnReceive->lc_receive_date,
                'lc_value' => null,
                'pi_no' => $reportData->yarnReceive->pi->pi_no,
                'pi_date' => $reportData->yarnReceive->pi->pi_receive_date,
                'pi_qty' => $piQty,
                'pi_rate' => $piRate,
                'pi_value' => $piQty * $piRate,
                'pi_unique_id' => $reportData->basis_details_unique,
                'challan_no' => $reportData->yarnReceive->challan_no,
                'challan_receive_date' => date('d-m-Y', strtotime($reportData->yarnReceive->receive_date)) ?? null,
                'pi_yarn_composition' => $PiComposition,
                'yarn_composition' => $composition,
                'lot_no' => $reportData->yarn_lot,
                'bag' => $reportData->no_of_bag,
                'cone' => $reportData->no_of_cone_per_bag,
                'weight_per_bag' => $reportData->weight_per_bag,
                'product_code' => $reportData->product_code, //product_code = ref no
                'today_receive_qty' => $reportData->receive_qty,
                'return_qty' => $returnQty,
                'total_receive_qty' => $totalReceiveQty,
                'balance_of_spi_mill' => $balanceOfSpiMill,
                'rate' => $reportData->rate,
                'receive_value' => $reportData->amount,
                'bal_value' => $totalReceiveQty * $reportData->rate, // total pi qty - total receive qty * rate
                'remarks' => $reportData->remarks,
                'no_of_box' => $reportData->no_of_box,
                'yarn_band' => $reportData->yarn_brand,
            ];
        });
    }

    public function challanWiseFormat()
    {
        $yarnReceiveDetails = self::$reportData;
        return $yarnReceiveDetails->map(function ($reportData) use ($yarnReceiveDetails) {
            $piDetails = $reportData->yarnReceive->receive_basis == 'pi' ? $reportData->yarnReceive->pi->details : [];
            $piDetails = $piDetails ? collect($piDetails->details)->where('uuid', $reportData->basis_details_unique) : collect([]);
            $piQty = $piDetails->sum('quantity');
            $piAmount = $piDetails->sum('amount');
            $piRate = $piDetails->sum('rate') == 0 || $piDetails->count() == 0 ? 0 : $piDetails->sum('rate') / $piDetails->count();
            $color = $reportData->yarn_color ? ', ' . $reportData->yarn_color : null;
            $composition = $reportData->yarn_count->yarn_count . ', ' . $reportData->composition->yarn_composition . $color;
            $balanceOfSpiMill = ($piQty - $reportData->receive_qty) < 0 ? null : $piQty - $reportData->receive_qty;

            $totalReceiveQty = $yarnReceiveDetails
                ->where(YarnItemAction::itemCriteria($reportData))
                ->sum('receive_qty');

            return [
                'loan_party_id' => $reportData->yarnReceive->loanParty->id,
                'party_name' => $reportData->yarnReceive->loanParty->name,
                'lc_no' => $reportData->yarnReceive->lc_no,
                'lc_date' => $reportData->yarnReceive->lc_receive_date,
                'lc_value' => null,
                'pi_no' => $reportData->yarnReceive->pi->pi_no,
                'pi_date' => $reportData->yarnReceive->pi->pi_receive_date,
                'pi_qty' => $piQty,
                'pi_value' => $piQty * $piRate,
                'pi_rate' => $piRate,
                'pi_amount' => $reportData->yarnReceive->pi->net_total ?? 0,
                'challan_no' => $reportData->yarnReceive->challan_no,
                'challan_receive_date' => $reportData->yarnReceive->receive_date,
                'yarn_composition' => $composition,
                'lot_no' => $reportData->yarn_lot,
                'bag' => $reportData->no_of_bag,
                'receive_qty' => $reportData->receive_qty,
                'balance_of_spi_mill' => $balanceOfSpiMill,
                'total_receive_qty' => $totalReceiveQty,
                'rate' => $reportData->rate,
                'bal_value' => $reportData->receive_qty * $reportData->rate,
                'remarks' => $reportData->remarks,
            ];
        });
    }
}
