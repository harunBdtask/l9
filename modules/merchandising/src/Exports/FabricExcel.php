<?php

namespace SkylarkSoft\GoRMG\Merchandising\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use App\CustomExcelHeaderFooter;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Query\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomChunkSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\BeforeSheet;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricBooking;

class FabricExcel implements WithTitle, ShouldAutoSize, WithMapping, WithHeadings, FromQuery, WithEvents, ShouldQueue, WithCustomChunkSize
{
    use Exportable, CustomExcelHeaderFooter;

    private $search;
    private $sort;
    private $label;
    private $fabric_source;

    public function __construct($search, $sort, $label, $fabric_source)
    {
        $this->search = $search;
        $this->sort = $sort;
        $this->label = $label;
        $this->fabric_source = $fabric_source;
    }

    public function title(): string
    {
        return 'Samples List View';
    }

    public function query()
    {
        $search = $this->search;
        $sort = $this->sort;
        $label = $this->label;
        $fabric_source = $this->fabric_source;

        $fabricBookings = FabricBooking::with('factory', 'buyer', 'detailsBreakdown.budget.fabricCosting', 'detailsBreakdown')
            ->where('unique_id', 'like', '%' . $search . '%')
            ->orWhere('fabric_source', $fabric_source)
            ->orWhere('booking_date', 'like', '%' . $search . '%')
            ->orWhere('delivery_date', 'like', '%' . $search . '%')
            ->orWhere('level', $label)
            ->orWhereHas('factory', function ($query) use ($search) {
                return $query->where('factory_name', 'like', '%' . $search . '%');
            })
            ->orWhereHas('buyer', function ($query) use ($search) {
                return $query->where('name', 'like', '%' . $search . '%');
            })
            ->orWhereHas('detailsBreakdown.budget', function ($query) use ($search) {
                return $query->where('style_name', 'like', '%' . $search . '%')
                    ->orWhere('job_no', 'like', '%' . $search . '%');
            })
            ->orWhereHas('detailsBreakdown', function ($query) use ($search) {
                return $query->where('po_no', 'like', '%' . $search . '%');
            })
            ->orWhereHas('supplier', function ($query) use ($search) {
                return $query->where('name', 'like', '%' . $search . '%');
            })
            ->orderBy('id', $sort);

        return $fabricBookings;
    }

    public function map($fabricBooking): array
    {
        $factory_name = $fabricBooking->factory->factory_name;
        $buyer_name = $fabricBooking->buyer->name;
        $unique_id = $fabricBooking->unique_id;
        $style_name = $fabricBooking->style_name;
        $item_description = $fabricBooking->item_description;
        $budget_job_no = $fabricBooking->budget_job_no ?? 'N/A';
        $po_no = $fabricBooking->po_no;
        $uom = $fabricBooking->uom;
        $totalValue = !empty($fabricBooking->detailsBreakdown) ?
            (!empty(collect($fabricBooking->detailsBreakdown)->first()) ?
                (!empty(collect($fabricBooking->detailsBreakdown)->first()->budget->fabricCosting) ?
                    (!empty(collect($fabricBooking->detailsBreakdown)->first()->budget->fabricCosting->details) ?
                        (!empty(collect(collect($fabricBooking->detailsBreakdown)->first()->budget->fabricCosting->details)['details']['fabricForm']) ?
                            collect(collect(collect($fabricBooking->detailsBreakdown)->first()->budget->fabricCosting->details)['details']['fabricForm'])->pluck('grey_cons_total_quantity')->sum()
                            : 0.00)
                        : 0.00)
                    : 0.00)
                : 0.00)
            : 0.00;
        $total_fabric_booking_qty = $fabricBooking->total_fabric_booking_qty;
        $rate = $fabricBooking->rate;
        $amount = $fabricBooking->amount;
        $fabric_source_name = $fabricBooking->fabric_source_name;
        $booking_date = $fabricBooking->booking_date;
        $delivery_date = $fabricBooking->delivery_date;
        $supplier = $fabricBooking->supplier->name;
        $level_name = $fabricBooking->level_name;

        return [
            $factory_name,
            $buyer_name,
            $unique_id,
            $style_name,
            $item_description,
            $budget_job_no,
            $po_no,
            $uom,
            $totalValue,
            $total_fabric_booking_qty,
            $rate,
            $amount,
            $fabric_source_name,
            $booking_date,
            $delivery_date,
            $supplier,
            $level_name,
        ];
    }

    public function headings(): array
    {
        return [
            'Company',
            'Buyer',
            'Booking ID',
            'Style',
            'Item/Description',
            'Budget UQ Id',
            'PO No',
            'UOM',
            'Total Qty',
            'Booking Qty',
            'Rate',
            'Amount',
            'Source',
            'Booking Date',
            'Delivery Date',
            'Supplier',
            'Level'
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
                        'All Fabric Booking Report'
                    ],
                    [
                        ' ',
                    ]
                ]);
            },
            AfterSheet::class => function (AfterSheet $event) {
                $cellRange = 'A1:Q1';
                $getHighestRow = $event->sheet->getDelegate()->getHighestRow();
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle( 'A3:Q3')->getFont()->setBold(true);
                $event->sheet->mergeCells($cellRange);
                $event->sheet->getDelegate()->getStyle($cellRange)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('A3:Q3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
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
