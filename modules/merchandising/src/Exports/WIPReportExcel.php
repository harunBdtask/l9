<?php

namespace SkylarkSoft\GoRMG\Merchandising\Exports;
use App\CustomExcelHeaderFooter;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use SkylarkSoft\GoRMG\Merchandising\Services\WipReport\WipReportService;

class WIPReportExcel implements WithTitle, ShouldAutoSize, FromView, ShouldQueue, withEvents, WithDrawings
{
    use Exportable;

    private $wipReport;
    private $fabricDetails;
    private $trimsDetails;
    private $factoryData = [];
    private $customerPo = [];
    private $wipData = [];
    private $maxLength = 0;
    private $colorWisePo = [];

    public function __construct($data)
    {
        $this->wipReport = WipReportService::showWipData($data);
        $this->customerPo = $this->wipReport['customer_wise_po'];
        $this->colorWisePo = $this->wipReport['color_breakdown_as_per_po'];
        array_unshift($this->customerPo, ['Customer', 'PO', 'Group', 'Brand', 'FTY Del.', 'Order Qty']);
        array_unshift($this->colorWisePo, 'Color Break down as per PO');

        $style = $this->wipReport['wip_style'] ?? $this->wipReport['style'];
        array_push($this->factoryData, ['Factory', $this->wipReport['assign_factory']]);
        array_push($this->factoryData, ['Style',  $style]);
        array_push($this->factoryData, ['Order Qty', $this->wipReport['order_qty']]);
        array_push($this->factoryData, ['POs Received Date', $this->wipReport['po_received_date']]);
        array_push($this->factoryData, ['POs Issued to FTY', $this->wipReport['po_issued_to_fty']]);
        array_push($this->factoryData, ['Fabric PI rcvd date', $this->wipReport['fabric_pi_recieved_date']]);
        array_push($this->factoryData, ['SC Issue date', $this->wipReport['sc_issue_date']]);
        array_push($this->factoryData, ['Revised SC Issue date', $this->wipReport['revised_sc_issue_date']]);


        array_push($this->wipData, ['WIP Date :', $this->wipReport['wip_date']]);
        array_push($this->wipData, ['BULK TP RCVD DATE:', $this->wipReport['bulk_tp_received_date']]);
        array_push($this->wipData, ['PCD:', $this->wipReport['pcd']]);
        array_push($this->wipData, ['PO Delivery date', $this->wipReport['po_delivery_date']]);
        array_push($this->wipData, ['Final Costing Approved', $this->wipReport['final_costing_approved']]);
        array_push($this->wipData, ['Costing YY', $this->wipReport['costing_yy']]);
        array_push($this->wipData, ['Packing Info + UPC', $this->wipReport['packing_info_upc']]);
        array_push($this->wipData, ['SIP Due date:', $this->wipReport['ship_due_date']]);
        array_push($this->wipData, ['Image', $this->wipReport['image']]);
        array_push($this->wipData, ['Garments Item', $this->wipReport['garments_item']]);

        $factoryPoCount = count($this->factoryData) + count($this->colorWisePo);

        $this->maxLength = (max($factoryPoCount, count($this->wipData), count($this->customerPo)));

        $this->fabricDetails = $this->wipReport ['fabric_booking_details'];
        $this->trimsDetails = $this->wipReport ['trims_booking_details'];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'WIP Report';
    }

    /**
     * @return View
     */
    public function view(): View
    {
        $wipReport = collect($this->wipReport)->except('fabric_booking_data', 'trims_booking_details');
        $trimsDetails = $this->trimsDetails;
        $fabricDetails = $this->fabricDetails;
        $maxLength = $this->maxLength;
        $factoryData = $this->factoryData;
        $wipData = $this->wipData;
        $customerPo = $this->customerPo;
        $colorWisePo = $this->colorWisePo;
//        dd($factoryData[0][0]);
        return view('merchandising::wip.excel', compact('wipReport', 'trimsDetails', 'fabricDetails', 'maxLength', 'wipData', 'factoryData', 'customerPo', 'colorWisePo'));
        //return view('merchandising::wip.view', compact('wipReport','fabricDetails','trimsDetails'));
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {

        return [AfterSheet::class => function (AfterSheet $event) {

            // Custom

            $cellRange = 'A1:AD1';

            $getHighestRow = $event->sheet->getDelegate()->getHighestRow();
//            $event->sheet->getDelegate()->getActiveCell()->mergeCell()
            $event->sheet->getDelegate()->mergeCells('L11:L12:L13');
            $event->sheet->getDelegate()->getStyle('A1:AD1' . $getHighestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }];
    }

    public function drawings()
    {
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setCoordinates('N3');
        $drawing->setOffsetX(0);
        $drawing->setName('Item Image');
        $drawing->setDescription('Item Image');
        $logo = ($this->wipReport['image'] && file_exists(('./storage/'. $this->wipReport['image']))) ?
            storage_path() . '/app/public/'.$this->wipReport['image']  : public_path() . '/images/no_image.jpg';

        $drawing->setPath( $logo);

        $drawing->setWidthAndHeight(850, 200);
        return $drawing;
    }
}
