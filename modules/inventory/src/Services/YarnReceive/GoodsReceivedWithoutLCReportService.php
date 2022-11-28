<?php

namespace SkylarkSoft\GoRMG\Inventory\Services\YarnReceive;

use Illuminate\Database\Eloquent\Builder;
use SkylarkSoft\GoRMG\Commercial\Models\ProformaInvoice;
use SkylarkSoft\GoRMG\Inventory\Filters\Filter;
use SkylarkSoft\GoRMG\Inventory\Models\YarnReceive;
use SkylarkSoft\GoRMG\Inventory\Models\YarnReceiveDetail;

class GoodsReceivedWithoutLCReportService
{
    public static $reportData;

    public static function setData($request): GoodsReceivedWithoutLCReportService
    {
        $piNo = $request->get('pi_no');
        $lcNo = $request->get('lc_no');
        $lotNo = $request->get('lot_no');
        $toDate = $request->get('to_date');
        $storeId = $request->get('store_id');
        $fromDate = $request->get('from_date');
        $yarnBrand = $request->get('yarn_brand');
        $partyType = $request->get('party_type');
        $yarnColor = $request->get('yarn_color');
        $factoryId = $request->get('factory_id');
        $reportType = $request->get('report_type');
        $dateColumn = $request->get('date_column');
        $yarnCountId = $request->get('yarn_count_id');
        $certification = $request->get('certification');
        $yarnCompositionId = $request->get('yarn_composition_id');

        self::$reportData = YarnReceiveDetail::query()
            ->with(['yarnReceive.factory', 'yarnReceive.loanParty', 'yarnReceive.wo','yarnReceive.pi', 'yarn_count', 'composition', 'type'])
            ->when($lotNo, Filter::applyFilter('yarn_lot', $lotNo))
            ->when($yarnBrand, Filter::applyFilter('yarn_brand', $yarnBrand))
            ->when($yarnColor, Filter::applyFilter('yarn_color', $yarnColor))
            ->when($yarnCountId, Filter::applyFilter('yarn_count_id', $yarnCountId))
            ->when($certification, Filter::applyFilter('certification', $certification))
            ->when($yarnCompositionId, Filter::applyFilter('yarn_composition_id', $yarnCompositionId))
            ->whereHas('yarnReceive', function (Builder $builder)
            use ($factoryId, $storeId, $partyType, $piNo, $fromDate, $toDate, $reportType) {
                $builder->where('factory_id', $factoryId);
                $builder->where('store_id', $storeId);
                $builder->when($partyType, Filter::applyFilter('loan_party_id', $partyType));
                $builder->when($piNo, function ($query) use ($piNo) {
                    $query->where('receive_basis', YarnReceive::PI_BASIS);
                    $query->where('receive_basis_no', $piNo);
                });
                $builder->when($reportType == 'without_lc', function ($query) {
                    $query->whereNull('lc_no');
                });
                $builder->when($reportType == 'with_lc', function ($query) {
                    $query->whereNotNull('lc_no');
                });
            })
            ->whereHas('yarnReceive.pi', function ($builder) use($fromDate, $toDate, $lcNo, $dateColumn) {
                $builder->when($fromDate && $toDate, Filter::betweenFilter($dateColumn, [$fromDate, $toDate]));
            })
            ->get();

        return new static();
    }

    public function withoutLCFormat()
    {
        $yarnReceiveDetails = self::$reportData;

        $piUniqueID = $yarnReceiveDetails->pluck('basis_details_unique')->unique()->values()->toArray();
        $piReceiveBasisNo = $yarnReceiveDetails->pluck('yarnReceive')->where('receive_basis', 'pi')->pluck('receive_basis_no')->toArray();
        $piData = ProformaInvoice::query()->whereIn('pi_no', $piReceiveBasisNo)->get();

        return $yarnReceiveDetails->map(function ($reportData) use ($yarnReceiveDetails, $piData) {
            $piQty = 0;
            $piDetails = [];
            $piComposition = '';
            if ($reportData->yarnReceive->receive_basis == 'pi') {
                $piDetails = $reportData->yarnReceive->pi->details;
                $pi = $piData->where('id', $reportData->yarnReceive->receive_basis_id)->first();

                if (isset($pi->details->details)) {
                    $piQty = collect($pi->details->details)->keyBy('uuid')->sum('quantity');
                    $piComposition = collect(optional($pi->details)->details)
                        ->where('uuid', $reportData->basis_details_unique)
                        ->map(function ($item) {
                            return $item->count . ' - ' . $item->composition . ($item->color ? ' - ' . $item->color : '');
                        })
                        ->first();
                }
            }

            return [
                'pi_qty' => $piQty,
                'rate' => $reportData->rate,
                'yarn_lot' => $reportData->yarn_lot,
                'yarn_type' => $reportData->type->name,
                'yarn_color' => $reportData->yarn_color,
                'yarn_brand' => $reportData->yarn_brand,
                'receive_qty' => $reportData->receive_qty,
                'certification' => $reportData->certification,
                'pi_no' => $reportData->yarnReceive->pi->pi_no,
                'lc_no' => $reportData->yarnReceive->pi->lc_group_no,
                'lc_date' => $reportData->yarnReceive->pi->lc_receive_date,
                'yarn_count' => $reportData->yarn_count->yarn_count,
                'party_id' => $reportData->yarnReceive->loanParty->id,
                'party_name' => $reportData->yarnReceive->loanParty->name,
                'pi_date' => $reportData->yarnReceive->pi->pi_receive_date,
                'receive_value' => $reportData->receive_qty * $reportData->rate,
                'yarn_composition' => $reportData->composition->yarn_composition,
            ];
        });
    }


    public function withLCFormat()
    {
        return $this->withoutLCFormat();
    }
}
