<?php

namespace SkylarkSoft\GoRMG\Commercial\Controllers\BTBMarginLC;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Session;
use PDF;
use SkylarkSoft\GoRMG\Commercial\Models\B2BMarginLC;
use SkylarkSoft\GoRMG\Commercial\Models\ProformaInvoice;

class B2BMarginLCPadPreviewController extends Controller
{
    public function index(B2BMarginLC $b2BMarginLC)
    {
        try {
            $b2BMarginLC->load([
                'lienBank',
                'supplier',
                'factory',
                'currency',
                'details.salesContract',
                'details.exportLC'
            ]);

            $proformaInvoice = ProformaInvoice::query()->with('item')->whereIn('id', $b2BMarginLC->pi_ids)->get();
            $proformaInvoiceFirst = collect($proformaInvoice)->first();
            $tennorStatus = 'DELIVERY';

            if ($proformaInvoiceFirst->goods_rcv_status) {
                $tennorStatus = $proformaInvoiceFirst->source == 1 ? 'DELIVERY' : 'ACCEPTANCE';

                if ($proformaInvoiceFirst->goods_rcv_status != 1) {
                    $tennorStatus = $proformaInvoiceFirst->source == 1 ? 'BL/AWB' : 'DELIVERY';
                }
            }

            $b2BMarginLC['proformaInvoice'] = $proformaInvoice->map(function ($item) {
                return $item->pi_no . " DATE " . ($item->pi_receive_date ? Carbon::make($item->pi_receive_date)->format('d.m.Y') : '') . "// HS CODE $item->hs_code /" . $item->item->name;
            })->implode(', ');

            $exportLc = $b2BMarginLC->details->map(function ($collection) {
                return [
                    'lc_no_date' => $collection->salesContract ?
                        ('EXPORT L/C NO. ' . $collection->salesContract->contract_number . ' DATE: '
                            . (isset($collection->salesContract->contract_date) ? Carbon::make($collection->salesContract->contract_date)->format('d.m.Y') : null)) :
                        ('EXPORT L/C NO. ' . $collection->exportLC->lc_number . ' DATE: '
                            . (isset($collection->exportLC->lc_date) ? Carbon::make($collection->exportLC->lc_date)->format('d.m.Y') : null)),
                    'bank_file_no' => $collection->salesContract ?
                        $collection->salesContract->bank_file_no :
                        $collection->exportLC->bank_file_no
                ];
            });
            return view('commercial::BTB-Margin-Lc.pad-preview.view', [
                'b2BMarginLC' => $b2BMarginLC,
                'exportLc' => $exportLc,
                'tennorStatus' => $tennorStatus,
            ]);
        } catch (Exception $exception) {
            Session::flash('error', 'Something went wrong');
            return redirect()->back();
        }
    }

    public function getPdf(B2BMarginLC $b2BMarginLC)
    {
        try {
            $b2BMarginLC->load([
                'lienBank',
                'supplier',
                'factory',
                'currency',
                'details.salesContract',
                'details.exportLC'
            ]);

            $proformaInvoice = ProformaInvoice::query()->with('item')->whereIn('id', $b2BMarginLC->pi_ids)->get();

            $b2BMarginLC['proformaInvoice'] = $proformaInvoice->map(function ($item) {
                return $item->pi_no . " DATE " . ($item->pi_receive_date ? Carbon::make($item->pi_receive_date)->format('d.m.Y') : '') . "// HS CODE $item->hs_code /" . $item->item->name;
            })->implode(', ');

            $proformaInvoiceFirst = collect($proformaInvoice)->first();
            $tennorStatus = 'DELIVERY';

            if ($proformaInvoiceFirst->goods_rcv_status) {
                $tennorStatus = $proformaInvoiceFirst->source == 1 ? 'DELIVERY' : 'ACCEPTANCE';

                if ($proformaInvoiceFirst->goods_rcv_status != 1) {
                    $tennorStatus = $proformaInvoiceFirst->source == 1 ? 'BL/AWB' : 'DELIVERY';
                }
            }

            $exportLc = $b2BMarginLC->details->map(function ($collection) {
                return [
                    'lc_no_date' => $collection->salesContract ?
                        ('EXPORT L/C NO. ' . $collection->salesContract->contract_number . ' DATE: '
                            . (isset($collection->salesContract->contract_date) ? Carbon::make($collection->salesContract->contract_date)->format('d.m.Y') : null)) :
                        ('EXPORT L/C NO. ' . $collection->exportLC->lc_number . ' DATE: '
                            . (isset($collection->exportLC->lc_date) ? Carbon::make($collection->exportLC->lc_date)->format('d.m.Y') : null)),
                    'bank_file_no' => $collection->salesContract ?
                        $collection->salesContract->bank_file_no :
                        $collection->exportLC->bank_file_no
                ];
            });
            $pdf = PDF::setOption('enable-local-file-access', true)
                ->loadView('commercial::BTB-Margin-Lc.pad-preview.pdf', [
                    'b2BMarginLC' => $b2BMarginLC,
                    'exportLc' => $exportLc,
                    'tennorStatus' => $tennorStatus,
                ])
                ->setPaper('a4')
                ->setOptions([
                    'header-html' => view('skeleton::pdf.header'),
                    'footer-html' => view('skeleton::pdf.footer')
                ]);
            return $pdf->stream('btb-lc-pad-preview.pdf');
        } catch (Exception $exception) {
            Session::flash('error', 'Something went wrong');
            return redirect()->back();
        }
    }
}
