<?php

namespace SkylarkSoft\GoRMG\Merchandising\Requests\Bookings;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\TrimsBooking;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\TrimsBookingDetails;
use SkylarkSoft\GoRMG\SystemSettings\Models\MerchandisingVariableSettings;

class TrimsBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    private function getTrimsBooking($id)
    {
        return TrimsBooking::query()->findOrFail($id);
    }

    private function getTrimsBookingDetail($id)
    {
        return TrimsBookingDetails::query()->findOrFail($id);
    }

    public function rules(): array
    {
        $id = $this->input('id');
        $bookingId = $this->input('bookingId');
        $trimsBooking = $this->getTrimsBooking($bookingId);
        $buyerId = $trimsBooking->buyer_id;
        $factoryId = $trimsBooking->factory_id;
        $budgetUniqueId = request()->input('budgetUniqueId');
        $itemId = request()->input('itemId');
        $trimsBookingDetail = $this->getTrimsBookingDetail($id);

        $variable = MerchandisingVariableSettings::query()
            ->where([
                'factory_id' => $factoryId,
                'buyer_id' => $buyerId,
            ])->first();

        $qtyValidation = $variable->variables_details['budget_validation_with_trims_booking']['item_wise_total_qty'] ?? null;
        $isMoqMaintain = isset($variable->variables_details['moq_maintain']) ? (int)$variable->variables_details['moq_maintain'] : null;
        if ($qtyValidation == 2 && $isMoqMaintain === 2) {
            return [];
        }

        $data = $this->input('details');

        $query = TrimsBookingDetails::query()
            ->where('budget_unique_id', $budgetUniqueId)
            ->where('item_id', $itemId)
            ->where('id', '!=', request('id'))
            ->where('po_no', $this->input('poNo'));

        $workOrderAmount = $query->sum('work_order_amount');

        $balanceAmount = format($trimsBookingDetail->total_amount) - format($workOrderAmount);

        return [
            'details' => [function ($attribute, $value, $fail) use ($data, $balanceAmount) {
                $totalAmount = collect($data)->sum('amount');

                if (format($balanceAmount) < format($totalAmount)) {
                    $fail('Total amount is large than Budget Quantity');
                }
            }],
        ];
    }
}
