<?php


namespace SkylarkSoft\GoRMG\Commercial\Controllers\Imports;

use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Commercial\Models\B2BMarginLC;
use SkylarkSoft\GoRMG\Commercial\Models\ProformaInvoice;

class ImportDocumentB2BLCSearchController
{
    public function __invoke(Request $request)
    {
        $factoryId = $request->get('factory_id');
        $itemId = $request->get('item_id');
        $supplierId = $request->get('supplier_id');
        $lcNumber = $request->get('lc_number');


        $b2bMarginLc = B2BMarginLC::with([
            'factory:id,factory_name as name',
            'lienBank:id,name',
            'supplier:id,name',
            'item:id,item_name',
        ])
            ->where('factory_id', $factoryId)
            ->where('item_id', $itemId)
            ->where('supplier_id', $supplierId)
            ->when($lcNumber, function ($query) use ($lcNumber) {
                return $query->where('lc_number', $lcNumber);
            })
            ->get()
            ->map(function ($lc) {
                $lc['lc_type_name'] = $lc['lc_type'] == 1 ? 'BTB LC' : 'Margin LC';
                if (! $lc['pi_ids']) {
                    $lc['proforma_invoices'] = [];

                    return $lc;
                }
                $lc['proforma_invoices'] = ProformaInvoice::withSum('importDocumentPiInfos', 'current_acceptance_value')
                    ->whereIn('id', $lc['pi_ids'])
                    ->get()
                    ->map(function ($pi) use ($lc) {
                        return [
                            'id' => null,
                            'pi_id' => $pi['id'],
                            'pi_number' => $pi['pi_no'],
                            'item_id' => $pi['item_category'],
                            'pi_value' => collect($pi['details'])->get('total'),
                            'current_acceptance_value' => 0,
                            'mrr_value' => null,
                            'cumulative_accepted_value' => $pi['import_document_pi_infos_sum_current_acceptance_value'],
                            'balance' => null,
                            'main_cumulative_accepted_value' => $pi['import_document_pi_infos_sum_current_acceptance_value'],
                            'main_current_acceptance_value' => 0,

                        ];
                    });

                return $lc;
            });

        return response()->json($b2bMarginLc);
    }
}
