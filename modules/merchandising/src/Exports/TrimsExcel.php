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
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\TrimsBooking;

/**
 *
 */
class TrimsExcel implements WithTitle, ShouldAutoSize, WithMapping, WithHeadings, FromQuery, WithEvents, ShouldQueue, WithCustomChunkSize
{
    use Exportable, CustomExcelHeaderFooter;

    private $q;
    private $sort;
    private $pay_mode;
    private $source;

    public function __construct($q, $sort, $pay_mode, $source)
    {
        $this->q = $q;
        $this->sort = $sort;
        $this->pay_mode = $pay_mode;
        $this->source = $source;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Samples List View';
    }

    public function query()
    {
        $q = $this->q ?? null;
        $sort = $this->sort ?? null;
        $pay_mode = $this->pay_mode ?? null;
        $source = $this->source ?? null;

        $trimsBookings = TrimsBooking::query()
            ->with('bookingDetails.budget.trimCosting',
                    'buyer',
                            'factory',
                            'supplier'
            )
            ->where('unique_id', 'LIKE', '%' . $q . '%')
            ->orWhere('booking_date', 'like', '%' . $q . '%')
            ->orWhere('delivery_date', 'like', '%' . $q . '%')
            ->orWhereHas('buyer', function ($query) use ($q) {
                return $query->where('name', 'LIKE', '%' . $q . '%');
            })
            ->orWhereHas('factory', function ($query) use ($q) {
                return $query->where('factory_name', 'LIKE', '%' . $q . '%');
            })
            ->orWhereHas('supplier', function ($query) use ($q) {
                return $query->where('name', 'LIKE', '%' . $q . '%');
            })
            ->orWhereHas('bookingDetails', function ($query) use ($q) {
                return $query->where('po_no', 'LIKE', '%' . $q . '%');
            })
            ->orWhereHas('bookingDetails.budget', function ($query) use ($q) {
                return $query->where('style_name', 'LIKE', '%' . $q . '%')
                    ->orWhere('job_no', 'LIKE', '%' . $q . '%')
                    ->orWhere('internal_ref', 'LIKE', '%' . $q . '%');
            })
            ->orWhere('pay_mode', $pay_mode)
            ->orWhere('source', $source)
            ->orderBy('id', $sort);

            return $trimsBookings;
    }

    public function map($trimsBooking): array
    {
        $company = $trimsBooking->factory->factory_name;
        $buyer = $trimsBooking->buyer->name;
        $booking_id =  $trimsBooking->unique_id;
        $style = $trimsBooking->style;
        $item_description = $trimsBooking->item_description;
        $budget_job_no = $trimsBooking->budget_job_no ?? 'N/A';
        $po_no = $trimsBooking->po_no;
        $uom = $trimsBooking->uom;
        if(!empty($trimsBooking->bookingDetails)){
            if(!empty(collect($trimsBooking->bookingDetails)->first())){
                if(!empty(collect($trimsBooking->bookingDetails)->first()->budget->trimCosting)){
                    if(!empty(collect(collect($trimsBooking->bookingDetails)->first()->budget->trimCosting->details))){
                        if(!empty(collect(collect($trimsBooking->bookingDetails)->first()->budget->trimCosting->details)['details'])){
                            $value = collect(collect(collect($trimsBooking->bookingDetails)->first()->budget->trimCosting->details)['details']) ;
                            $totalValue = $value->map(function ($v){
                                return ['total_quantity' => (float) preg_replace('/[^0-9.]/', '', $v['total_quantity']),];
                            })->pluck('total_quantity')->sum();
                        }
                    }
                }
            }
        }
        $total_trims_booking_qty = $trimsBooking->total_trims_booking_qty;
        $rate = $trimsBooking->rate;
        $amount = $trimsBooking->amount;
        $source_value = $trimsBooking->source_value;
        $booking_date = $trimsBooking->booking_date;
        $delivery_date = $trimsBooking->delivery_date;
        $supplier_name = $trimsBooking->supplier->name;

        return [
                $company ,
                $buyer ,
                $booking_id ,
                $style ,
                $item_description ,
                $budget_job_no ,
                $po_no ,
                $uom ,
                $totalValue ?? 0.00 ,
                $total_trims_booking_qty ,
                $rate ,
                $amount ,
                $source_value ,
                $booking_date ,
                $delivery_date ,
                $supplier_name
        ];
    }

    /**
     * @return array
     */
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
                        'All Trims Booking Report'
                    ],
                    [
                        ' ',
                    ]
                ]);
            },
            AfterSheet::class => function (AfterSheet $event) {
                $cellRange = 'A1:P1';
                $getHighestRow = $event->sheet->getDelegate()->getHighestRow();
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle('A3:P3')->getFont()->setBold(true);
                $event->sheet->mergeCells($cellRange);
                $event->sheet->getDelegate()->getStyle($cellRange)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('A3:P3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
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
