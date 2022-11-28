<?php

namespace SkylarkSoft\GoRMG\Planing\Controllers\API;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PDF;
use SkylarkSoft\GoRMG\Planing\DTO\CapacityAvailabilityDTO;

class CapacityAvailabilityAPIController extends Controller
{
    public function searchCapacity(Request $request): array
    {
        $requestedMonth = $request->get('month') ? $request->get('month') + 1 : null;

        $capacityAvailability = new CapacityAvailabilityDTO();
        $capacityAvailability->setCompany($request->get('company'));
        $capacityAvailability->setMonth($requestedMonth);
        $capacityAvailability->setYear($request->get('year') ?? date('Y'));
        $capacityAvailability->generateCapacity();

        $floorWiseCapacity = $capacityAvailability->floorWiseCapacity();
        $categoryWiseCapacity = $capacityAvailability->categoryWiseCapacity();
        return [
            'chart_data' => [
                'categories' => $capacityAvailability->getCategories(),
                'series' => $capacityAvailability->getDashboardData(),
                'yearMonths' => $capacityAvailability->getYearMonths(),
            ],
            'floor_wise_capacity' => $floorWiseCapacity,
            'category_wise_capacity' => $categoryWiseCapacity,
            'floor_with_category_capacity' => $capacityAvailability->floorWithCategoryCapacity(),
        ];
    }

    public function pdf(Request $request)
    {
        $report = $this->searchCapacity($request);
        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView('planing::reports.downloadable.pdf.capacity-availability',
                ['reports' => $report])
            ->setPaper('a4', 'landscape')
            ->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer',),
            ]);
        return $pdf->stream('capacity-availability.pdf');
    }
}
