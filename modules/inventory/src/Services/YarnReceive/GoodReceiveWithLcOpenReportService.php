<?php

namespace SkylarkSoft\GoRMG\Inventory\Services\YarnReceive;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Commercial\Models\ProformaInvoice;
use SkylarkSoft\GoRMG\Inventory\Filters\Filter;
use SkylarkSoft\GoRMG\Inventory\Models\YarnReceive;
use SkylarkSoft\GoRMG\Inventory\Models\YarnReceiveDetail;

class GoodReceiveWithLcOpenReportService
{
    public function getReportData(Request $request)
    {
        $partyType = $request->get('party_type');
        $count = $request->get('count');
        $type = $request->get('type');
        $composition = $request->get('composition');
        $color = $request->get('color');
        $certification = $request->get('certification');
        $lcDateOrPiDate = $request->get('lc_date_or_pi_date');
        $fromDate = $request->get('form_date');
        $toDate = $request->get('to_date');
        $lcNo = $request->get('lc_no');
        $piNo = $request->get('pi_no');
        $lotNo = $request->get('lot_no');

//        dd($partyType,$count,$type,$composition,$color,$certification,$lcDateOrPiDate,$fromDate,$toDate,$lcNo,$piNo,$lotNo);

        return YarnReceiveDetail::query()
            ->with([
                'yarn_count',
                'yarnReceive',
                'type',
                'composition',
            ])
            ->when($count, Filter::applyFilter('yarn_count_id', $count))
            ->when($type, Filter::applyFilter('yarn_type_id', $type))
            ->when($composition, Filter::applyFilter('yarn_composition_id', $composition))
            ->when($color, Filter::applyFilter('yarn_color', $color))
            ->when($certification, Filter::applyFilter('certification', $certification))
            ->when($lotNo, Filter::applyFilter('yarn_lot', $lotNo))
            ->whereHas('yarnReceive', function (Builder $builder)
            use ($lcDateOrPiDate, $partyType, $lcNo, $piNo, $fromDate, $toDate) {
                return $builder->when($partyType, Filter::applyFilter('loan_party_id', $partyType))
                    ->when($lcNo, Filter::applyFilter('lc_no', $lcNo))
                    ->when($piNo, function ($query) use ($piNo) {
                        return $query->where('receive_basis', YarnReceive::PI_BASIS)
                            ->where('receive_basis_no', $piNo);
                    })
                    ->when($lcDateOrPiDate == 'lc_date', function ($query) use ($fromDate, $toDate) {
                        return $query->when(
                            $fromDate && $toDate,
                            Filter::betweenFilter('lc_receive_date', [$fromDate, $toDate])
                        );
                    })
                    ->when($lcDateOrPiDate == 'pi_date', function ($query) use ($fromDate, $toDate) {
                        return $query->whereHas('pi', function ($q) use ($fromDate, $toDate) {
                            $q->when(
                                $fromDate && $toDate,
                                Filter::betweenFilter('pi_receive_date', [$fromDate, $toDate])
                            );
                        });
                    });
            })
            ->get()
            ->map(function ($data) {

                $piDetails = [];
                if ($data->yarnReceive->receive_basis == 'pi') {
                    $piDetails = $data->yarnReceive->pi->details;
                    $pi = ProformaInvoice::query()->find($data->yarnReceive->receive_basis_id);
                    $PiComposition = collect(optional($pi->details)->details)
                        ->where('uuid', $data->basis_details_unique)
                        ->map(function ($item) {
                            return $item->count ? $item->count . ' - ' : '' . $item->composition . ($item->color ? ' - ' . $item->color : '');
                        })->first();
                    $totalReceiveQty = YarnReceiveDetail::query()->whereHas('yarnReceive', function ($query) {
                        $query->where('receive_basis', 'pi');
                    })->where('basis_details_unique', $data->basis_details_unique)->sum('receive_qty');
                } else {
                    $totalReceiveQty = $data->receive_qty;
                }
                $piDetails = $piDetails ? collect($piDetails->details)->where('uuid', $data->basis_details_unique) : collect([]);
                $piQty = $piDetails->sum('quantity');

                return [
                    'party_name' => $data->yarnReceive->loanParty->name,
                    'count' => $data->yarn_count->yarn_count,
                    'type' => $data->type->name,
                    'brand' => $data->yarn_brand,
                    'composition' => $data->composition->yarn_composition,
                    'color' => $data->yarn_color,
                    'certification' => $data->certification,
                    'lot_no' => $data->yarn_lot,
                    'lc_no' => $data->yarnReceive->lc_no ?? null,
                    'lc_date' => $data->yarnReceive->lc_receive_date ?? null,
                    'pi_no' => $data->yarnReceive->pi->pi_no ?? null,
                    'pi_date' => $data->yarnReceive->pi->pi_receive_date ?? null,
                    'pi_qty' => $piQty,
                    'rec_qty' => $data->receive_qty,
                    'rate' => $data->rate,
                    'rcv_value' => $data->receive_qty * $data->rate,
                    'pi_unique_id' => $data->basis_details_unique,
                ];

            });

    }
}
