<?php

namespace SkylarkSoft\GoRMG\Merchandising\Exports;

use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use App\CustomExcelHeaderFooter;
use Illuminate\Database\Query\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomChunkSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\BeforeSheet;
use SkylarkSoft\GoRMG\Merchandising\Models\Budget;
use SkylarkSoft\GoRMG\Merchandising\Models\PriceQuotation;

class BudgetAllExcel implements WithTitle, ShouldAutoSize, WithMapping, WithHeadings, FromQuery, WithEvents, ShouldQueue, WithCustomChunkSize
{
    use Exportable, CustomExcelHeaderFooter;

    private $search;

    public function __construct($search)
    {
        $this->search = $search;
    }

    public function title(): string
    {
        return 'Budget List View';
    }

    public function query()
    {
        $search = $this->search;

        $uomValue = strtolower($search);
        $uom = array_search(ucfirst($uomValue), PriceQuotation::STYLE_UOM) ?? $search;
        $time = collect(explode('/', $search))->implode('-');
        $date = strtotime($time) ? Carbon::parse($time)->format('Y-m-d') : $search;
        $budgets = Budget::with('productDepartment', 'order.purchaseOrders.poDetails', 'createdBy')
            ->withSum('purchaseOrders as total_po_quantity', 'po_quantity')
            ->where('job_no', 'like', '%' . $search . '%')
            ->orWhere('order_uom_id', $uom)
            ->orWhereDate('costing_date', 'LIKE', $date)
            ->orWhereDate('approve_date', "LIKE", $date)
            ->orWhere('style_name', 'like', '%' . $search . '%')
            ->orWhere('job_qty', 'like', '%' . $search . '%')
            ->orWhere('region', 'like', '%' . $search . '%')
            ->orWhere('machine_line', 'like', '%' . $search . '%')
            ->orWhere('incoterm_place', 'like', '%' . $search . '%')
            ->orWhereHas('productDepartment', function ($query) use ($search) {
                $query->where('product_department', 'like', '%' . $search . '%');
            })
            ->orWhereHas('buyer', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            });
        return $budgets;
    }

    public function map($budget): array
    {
        $factory_short_name = $budget->factory->factory_short_name;
        $buyer_name = $budget->buyer->name;
        $job_no = $budget->job_no;
        $style_name = $budget->style_name;
        $job_qty = $budget->job_qty;
        $fob = collect($budget->order->PurchaseOrders)->average('avg_rate_pc_set');
        $revenue = collect($budget->order->PurchaseOrders)->map(function ($item) {
            $rate = $item['avg_rate_pc_set'] ?? 0;
            $qty = $item['po_quantity'] ?? 0;
            return $rate * $qty;
        })->sum();
        $po_quantity = collect($budget->order->PurchaseOrders)->map(function ($item) {
            return $item['po_quantity'] ?? 0;
        })->sum();

        if (!empty($budget->costing['fabric_cost'])) {
            if (!empty($budget->costing['fabric_cost']['budgeted_cost'])) {
                $fabric_cost = $budget->costing['fabric_cost']['budgeted_cost'] ?? 0.00;
            }else{
                $fabric_cost = 0.00 ;
            }
        }else{
            $fabric_cost = 0.00 ;
        }

        if (!empty($budget->costing['trims_cost'])) {
            if (!empty($budget->costing['trims_cost']['budgeted_cost'])) {
                $trims_cost = $budget->costing['trims_cost']['budgeted_cost'] ?? 0.00;
            }else{
                $trims_cost = 0.00 ;
            }
        }else{
            $trims_cost = 0.00 ;
        }

        if (!empty($budget->costing['total_cost'])) {
            if (!empty($budget->costing['total_cost']['budgeted_cost'])) {
                $total_cost = $budget->costing['total_cost']['budgeted_cost'] ?? 0.00;
            }else{
                $total_cost = 0.00 ;
            }
        }else{
            $total_cost = 0.00 ;
        }

        $others_cost = ((double)$total_cost - ((double)$fabric_cost + (double)$trims_cost)) ?? 0.00;
        $total_earning = ($revenue - ($fob * $po_quantity)) ?? 0.00;
        $product_department = $budget->productDepartment->product_department;
        $unit_of_measurement = $budget->unit_of_measurement;
        $incoterm_place = $budget->incoterm_place ?? 'N/A';
        $costing_date = formatDate($budget->costing_date);
        $approve_date = formatDate($budget->approve_date);
        $region = $budget->region ?? 'N/A';
        $screen_name = $budget->createdBy->screen_name;
        $created_at = \Carbon\Carbon::parse($budget->created_at)->format('M d, Y');

        return [
            $factory_short_name,
            $buyer_name,
            $job_no,
            $style_name,
            $job_qty,
            $fob,
            $revenue,
            $fabric_cost,
            $trims_cost,
            $others_cost,
            $total_earning,
            $product_department,
            $unit_of_measurement,
            $incoterm_place,
            $costing_date,
            $approve_date,
            $region,
            $screen_name,
            $created_at
        ];
    }

    public function headings(): array
    {
        return [
            'Company Name',
            'Buyer',
            'Unique ID',
            'Style Name',
            'Job QTY.',
            'FOB',
            'Total Revenue',
            'Fabric Cost',
            'Trims/Accessories Cost',
            'Wash, Test, Commercial and Others Cost',
            'Total Earnings',
            'Product Dept.',
            'UOM',
            'Incoterm Place',
            'Costing Date',
            'Approve Date',
            'Region',
            'Created By',
            'Create Date'
        ];
    }

    public function registerEvents(): array
    {
        return [
            BeforeExport::class => function (BeforeExport $event) {
                $event->writer->getProperties()->setCreator('Skylark Soft Limited');
            },
            BeforeSheet::class => function (BeforeSheet $event) {
                $event->sheet->append([
                    [
                        'All Budget Report'
                    ],
                    [
                        ' ',
                    ]
                ]);
            },
            AfterSheet::class => function (AfterSheet $event) {
                $cellRange = 'A1:S1';
                $getHighestRow = $event->sheet->getDelegate()->getHighestRow();
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle( 'A3:S3')->getFont()->setBold(true);
                $event->sheet->mergeCells($cellRange);
                $event->sheet->getDelegate()->getStyle($cellRange)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('A3:S3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            }];
    }

    /**
     * @return int
     */
    public function chunkSize(): int
    {
        return 10;
    }

}
