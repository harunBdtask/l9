<?php

namespace SkylarkSoft\GoRMG\HR\Controllers\Api;

use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\HR\Repositories\MonthlyHolidayPaymentSummaryRepository;
use SkylarkSoft\GoRMG\HR\Repositories\MonthlyPaymentSummaryRepository;
use SkylarkSoft\GoRMG\HR\Requests\GenerateMonthlyPaymentSummaryRequest;

class PaymentController
{
    public function generateMonthlyPaymentSummary(GenerateMonthlyPaymentSummaryRequest $request)
    {
        $monthlyPaymentSummaryRepository = new MonthlyPaymentSummaryRepository();
        return response()->json($monthlyPaymentSummaryRepository->generateMonthlyPaymentSummary($request));
    }

    public function generateMonthlyHolidayPaymentSummary(Request $request)
    {
        $monthlyPaymentSummaryRepository = new MonthlyHolidayPaymentSummaryRepository();
        return response()->json($monthlyPaymentSummaryRepository->generateMonthlyHolidayPaymentSummary($request));
    }


}
