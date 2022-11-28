<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services\Report\FabricBookings;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Merchandising\Filters\Filter;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricBooking;
use SkylarkSoft\GoRMG\Merchandising\Models\Budget;

class FabricBookingDetailsReportService
{
    const BOOKING_DATE = 1;
    const DELIVERY_DATE = 2;

    public function getReportData(Request $request)
    {
        $buyerId = $request->input('buyer_id');
        $merchandiserId = $request->input('merchandiser_id');
        $budgetUniqueId = $request->input('detail_id');
        $uniqueId = $request->input('booking_id');
        $styleName = $request->input('style_name');
        $type = (int)$request->input('type');
        $fromDate = Carbon::make($request->input('form_date'));
        $todDate = Carbon::make($request->input('to_date'));

        return FabricBooking::query()
            ->with([
                'detailsBreakdown',
                'budget',
                'budget.order.teamLeader',
                'buyer:id,name',
                'factory:id,factory_name,factory_address',
                'supplier:id,name,address_1,address_2',
                'currency:id,currency_name',
            ])
            ->when($uniqueId, Filter::applyFilter('unique_id', $uniqueId))
            ->when($type === self::BOOKING_DATE, Filter::applyBetweenFilter('booking_date', [$fromDate, $todDate]))
            ->when($type === self::DELIVERY_DATE, Filter::applyBetweenFilter('delivery_date', [$fromDate, $todDate]))
            // ->when($booking_date, Filter::applyDateFilter('booking_date', $booking_date))
            // ->when($delivery_date, Filter::applyDateFilter('delivery_date', $delivery_date))
            ->when($buyerId, function (Builder $query) use ($buyerId) {
                $query->whereHas('buyer', Filter::applyFilter('id', $buyerId));
            })
            ->when($merchandiserId, function (Builder $query) use ($merchandiserId) {
                $query->whereHas('detailsBreakdown', function (Builder $query) use ($merchandiserId) {
                    $query->whereHas('budget', function (Builder $query) use ($merchandiserId) {
                        $query->whereHas('order', function (Builder $query) use ($merchandiserId) {
                            $query->whereHas('teamLeader', Filter::applyFilter('screen_name', $merchandiserId));
                        });
                    });
                });
            })
            ->when($budgetUniqueId, function (Builder $query) use ($budgetUniqueId) {
                $query->whereHas('detailsBreakdown', Filter::applyFilter('job_no', $budgetUniqueId));
            })
            ->when($styleName, function (Builder $query) use ($styleName) {
                $query->whereHas('detailsBreakdown', function (Builder $query) use ($styleName) {
                    $query->whereHas('budget', Filter::applyFilter('style_name', $styleName));
                });
            })->get()->map(function ($collection) {
                $job_no = $collection->getRelation('detailsBreakdown')
                    ->pluck('job_no')
                    ->unique()
                    ->values();

                $budget = Budget::query()
                    ->with([
                        'order.purchaseOrders.poDetails',
                        'order.purchaseOrders.country',
                        'order.productDepartment',
                        'order.season:id,season_name',
                        'order.dealingMerchant:id,email,screen_name,first_name,last_name',
                    ])
                    ->whereIn('job_no', $job_no)
                    ->get();

                $collection['details'] = self::fabricDetailsDataFormat($collection, $budget);
                $collection['team_leader'] = isset($budget[0]) ? $budget[0]->order->teamLeader->screen_name : null;
                $collection['combo'] = isset($budget[0]) ? $budget[0]->order->combo : null;
                $collection['countries'] = isset($budget[0])
                    ? $budget[0]->order->purchaseOrders->pluck('country.name')
                        ->unique()->join(', ')
                    : null;

                return $collection;
            });
    }

    private static function fabricDetailsDataFormat($fabricBookings, $budget)
    {
        return $fabricBookings->detailsBreakdown->where('uom', '!=', 4)->map(function ($value) use ($budget) {
            $uom = isset($value->uom_value) ? strtolower($value->uom_value) : null;

            $yards = self::getYards($value, $uom);

            return [
                'style_name' => collect($budget)->where('job_no', $value->job_no)->first()->style_name ?? '',
                'job_no' => $value->job_no,
                'gmts_color' => $value->gmt_color,
                'garments_item_name' => $value->garments_item_name,
                'po_no' => $value->po_no,
                'color' => $value->color,
                'process_loss' => $value->process_loss,
                'total_fabric_qty' => $value->actual_wo_qty,
                'amount' => ($value->rate * $value->actual_wo_qty),
                'rate' => $value->rate,
                'remarks' => $value->remarks,
                'yards' => $yards,
            ];
        })->values();
    }

    private static function getYards($value, $uom)
    {
        if ($value->yards != null) {
            return $value->yards;
        }

        return $uom == 'kg' ? self::convertKgToYards($value, $value->actual_wo_qty) : 0;
    }

    public static function convertKgToYards($value, $actualWorkOrderQty)
    {
        $kgs = $actualWorkOrderQty;
        $gsm = is_numeric($value['gsm']) ? $value['gsm'] : 0;
        $dia = is_numeric($value['dia']) ? $value['dia'] : 0;
        $const1 = 10000;
        $const2 = 36;
        $inch = 2.54;

        if ($kgs == 0 || $gsm == 0 || $dia == 0) {
            return 0;
        }

        $gsm_points = $gsm / 1000;
        $width_cm = $dia * $inch;
        $yards = ($kgs / $gsm_points * $const1 / $width_cm / $inch / $const2);

        return sprintf("%.4f", $yards);
    }

}
