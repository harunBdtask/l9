<?php

namespace SkylarkSoft\GoRMG\Merchandising\Requests\Bookings;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricBooking;
use SkylarkSoft\GoRMG\SystemSettings\Models\MerchandisingVariableSettings;

class FabricBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        $data = $this->all();
        $bookingId = collect($this->all())->pluck('booking_id')->first();
        $booking = FabricBooking::query()->findOrFail($bookingId);
        $variableData = MerchandisingVariableSettings::query()
            ->where("factory_id", $booking->factory_id)
            ->where("buyer_id", $booking->buyer_id)
            ->first()->variables_details;

        $adjQtyStatus = $variableData['adj_qty_maintain'] ?? null;
        $isProduction = $booking->fabric_source == 1;
        return [
            '*' => [function ($attribute, $value, $fail) use ($data, $adjQtyStatus, $isProduction) {
                foreach ($data as $datum) {
                    $finalAmount = 0;
                    if ($adjQtyStatus == 1) {
                        if ($isProduction) {
                            $finalAmount = format($datum['wo_qty']);
                        } else {
                            $finalAmount = format($datum['wo_qty']) * format($datum['rate']);
                        }
                    } else {
                        $finalAmount = $datum['amount'];
                    }

                    if ($isProduction) {
                        $actualAmount = (double)format($datum['balance_qty']);
                    } else {
                        $actualAmount = (double)format($datum['rate']) * (double)format($datum['balance_qty']);
                    }


                    if ((int)format($actualAmount) < (int)format($finalAmount)) {
                        $fail('Total amount is large than actual amount');
                    }
                }
            }],
        ];
    }
}
