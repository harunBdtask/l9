<?php

namespace SkylarkSoft\GoRMG\Commercial\Controllers;

use PDF;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Constants\ApplicationConstant;
use SkylarkSoft\GoRMG\Commercial\Models\ExportLC;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\Commercial\Models\B2BMarginLC;
use SkylarkSoft\GoRMG\Commercial\Models\SalesContract;
use SkylarkSoft\GoRMG\Commercial\Models\PrimaryMasterContract\PrimaryMasterContract;
use SkylarkSoft\GoRMG\Commercial\Services\Report\CommercialReportService;

class CommercialReportController extends Controller
{
    public function contractLcStatus(Request $request)
    {   
       
        $data = $this->get_Data($request);
        // return $data;

        $factories = Cache::get('factories') ?? [];
        $buyers = $buyers = Buyer::pluck('name', 'id')->prepend('Select Buyer','');
        $types = collect([ApplicationConstant::SEARCH_BY_PMC=>'Primary Contract', ApplicationConstant::SEARCH_BY_SC =>'Sales Contract', ApplicationConstant::SEARCH_BY_LC =>'Export LC'])->prepend('Select','');
        return view('commercial::reports.contract-lc-status.view', compact('data','factories','types','buyers'));
    }

    public function contractLcStatusPdf(Request $request)
    {
        $data = $this->get_Data($request);
        $pdf = PDF::loadView('commercial::reports.contract-lc-status.pdf', compact('data'))
        ->setPaper('a4')->setOrientation('landscape');
        return $pdf->stream('contract-lc-status');
    } 

    private function get_Data($request)
    {

        $data = collect();
        if ($request->has(['factory', 'year','month'])) {

            $type = $request->get('type');
            if(!$type || $type=='3'){

                $primary_contracts = PrimaryMasterContract::query()
                ->with([
                    'buyingAgent',
                    'amendments',
                    'salesContract.amendments',
                    'exportLc.amendments'
                    ])
                ->where('beneficiary_id', $request->get('factory'))
                ->whereYear('created_at', $request->get('year'))
                ->whereMonth('created_at', $request->get('month'))
                ->when($request->get('buyer'), function($q) use ($request){
                    $q->whereJsonContains('buyer_id', $request->get('buyer'));
                })
                ->get()
                // return $primary_contracts;
                ->map(function ($items) use ($request){
                    return $this->contractLcStatusFormat($items, 3);
                })->map(function($item) use ($data){
                    $data->push($item);
                });
            }

            if(!$type || $type=='2'){

                $sales_contract = SalesContract::query()
                    ->with([
                        'buyingAgent',
                        'amendments'
                        ])
                    ->where('beneficiary_id', $request->get('factory'))
                    ->whereYear('contract_date', $request->get('year'))
                    ->whereMonth('contract_date', $request->get('month'))
                    ->when($request->get('buyer'), function($q) use ($request){
                        $q->whereJsonContains('buyer_id', $request->get('buyer'));
                        $q->orWhere('buyer_id', $request->get('buyer'));
                    })
                    ->when($type, function(){}, function($q){
                        $q->whereNull('primary_contract_id');
                    })
                    ->get()
                    ->map(function ($items) use ($request){
                        return $this->contractLcStatusFormat($items, 2);
                    })->map(function($item) use ($data){
                        $data->push($item);
                    });
            }

            if(!$type || $type=='1'){

                $export_lc = ExportLC::query()
                    ->with([
                        'buyingAgent',
                        'amendments'
                        ])
                    ->where('beneficiary_id', $request->get('factory'))
                    ->whereYear('lc_date', $request->get('year'))
                    ->whereMonth('lc_date', $request->get('month'))
                    ->when($request->get('buyer'), function($q) use ($request){
                        $q->whereJsonContains('buyer_id', $request->get('buyer'));
                        $q->orWhere('buyer_id', $request->get('buyer'));
                    })
                    ->when($type, function(){}, function($q){
                        $q->whereNull('primary_contract_id');
                    })
                    ->get()
                    // return $export_lc;
                    ->map(function ($items) use ($request){
                        return $this->contractLcStatusFormat($items, 1);
                    })->map(function($item) use ($data){
                        $data->push($item);
                    });;
            }
        }
        return $data;
    }

    private function contractLcStatusFormat($collection, $type)
    {

        if ($type == 3){

            $pmc_amends = collect($collection->amendments)->map(function($item){
                return [
                    'pmc_amt_date' => $item->amend_date,
                    'pmc_amt_no' => $item->ex_contract_number."(".$item->amend_no.")",
                    'pmc_amt_value' => $item->contract_value
                ];
            });

            // salec contract
            $sc_list = collect($collection->salesContract)->map(function($item){
                //Amendments
                $sc_amends = collect($item->amendments)->map(function($amend){
                    return [
                        'amd_date' => $amend->amendment_date,
                        'amd_no' => $amend->internal_file_no,
                        'amd_value' => $amend->amendment_value
                    ];
                });
                return [
                    'sc_date' => $item->contract_date,
                    'sc_no' => $item->contract_number,
                    'sc_value' => $item->contract_value,
                    'expiry_date' => $item->expiry_date,
                    'qnty'=>'',
                    'sc_amends' => $sc_amends,
                    'sc_amends_count' => count($sc_amends)
                ];
            });

            // Export LC
            $lc_list = collect($collection->exportLc)->map(function($item){
                //Amendments
                $lc_amends = collect($item->amendments)->map(function($amend){
                    return [
                        'amd_date' => $amend->amendment_date,
                        'amd_no' => $amend->internal_file_no,
                        'amd_value' => $amend->amendment_value
                    ];
                });
                return [
                    'lc_date' => $item->lc_date,
                    'lc_no' => $item->lc_number,
                    'lc_value' => $item->lc_value,
                    'expiry_date' => $item->lc_expiry_date,
                    'qnty'=>'',
                    'lc_amends' => $lc_amends,
                    'lc_amends_count' => count($lc_amends)
                ];
            });
            return [
                'buying_agent' => $collection->buyingAgent->buying_agent_name,
                'buyer_id' => request()->get('buyer_id'),
                'buyer' => collect($collection->buyer_names)->implode('name',',') ?? null,
                'type' => 'PMC',
                'pmc_date' => $collection->created_at,
                'pmc_no' => $collection->ex_contract_number,
                'pmc_value' => $collection->contract_value,
                'pmc_amends' => $pmc_amends,
                'sc_list' => $sc_list,
                'lc_list' => $lc_list
            ];
        }
        else if($type==2){   // Sales Contract

            //Amendments
            $sc_amends = collect($collection->amendments)->map(function($amend){
                return [
                    'amd_date' => $amend->amendment_date,
                    'amd_no' => $amend->internal_file_no,
                    'amd_value' => $amend->amendment_value
                ];
            });
            return [
                // 'item_rows'=> $item_rows,
                'buying_agent' => $collection->buyingAgent->buying_agent_name,
                'buyer_id' => request()->get('buyer_id'),
                'buyer' => collect($collection->buyer_names)->implode('name',',') ?? null,
                'type' => 'SC',
                'pmc_date' => '',
                'pmc_no' => '',
                'pmc_value' => '',
                'pmc_amends' => [],
                'sc_date' => $collection->contract_date,
                'sc_no' => $collection->contract_number,
                'sc_value' => $collection->contract_value,
                'sc_amends' => $sc_amends,
                'lc_date' => '',
                'lc_no' => '',
                'lc_value' => '',
                'lc_amends' => [],
                'expiry_date' => $collection->expiry_date,
                'qnty'=>''
            ];
        }
        else if($type==1){   // Export LC

            //Amendments
            $lc_amends = collect($collection->amendments)->map(function($amend){
                return [
                    'amd_date' => $amend->amendment_date,
                    'amd_no' => $amend->internal_file_no,
                    'amd_value' => $amend->amendment_value
                ];
            });
            return [
                'buying_agent' => $collection->buyingAgent->buying_agent_name,
                'buyer_id' => request()->get('buyer_id'),
                'buyer' => collect($collection->buyer_names)->implode('name',',') ?? null,
                'type' => 'LC',
                'pmc_date' => '',
                'pmc_no' => '',
                'pmc_value' => '',
                'pmc_amends' => [],
                'sc_date' => '',
                'sc_no' => '',
                'sc_value' => '',
                'sc_amends' => [],
                'lc_date' => $collection->lc_date,
                'lc_no' => $collection->lc_number,
                'lc_value' => $collection->lc_value,
                'lc_amends' => $lc_amends,
                'expiry_date' => $collection->lc_expiry_date,
                'qnty'=>''
            ];
        }
    }

    public function btbStatus(Request $request)
    {   

        $data = CommercialReportService::btbStatus($request);
        // return $data;
        $factories = Cache::get('factories') ?? [];
        $buyers = $buyers = Buyer::pluck('name', 'id')->prepend('Select Buyer','');
        return view('commercial::reports.btb-status.view', compact('data','factories','buyers'));
    }

    public function btbStatusPdf(Request $request)
    {
        $data = CommercialReportService::btbStatus($request);
        $pdf = PDF::loadView('commercial::reports.btb-status.pdf', compact('data'))
        ->setPaper('a4')->setOrientation('landscape');
        return $pdf->stream('btb-status');
    } 

    //Export LC Status Report
    public function exportLcStatus(Request $request)
    {   
       
        
        $data = collect();
        if ($request->has(['factory', 'year','month'])) {

                $data = ExportLC::query()
                ->with([
                    'details',
                    'buyingAgent',
                    'primary_contract.salesContract',
                    'invoice',
                    'docSubmissionInfo.docSubmission.proceed_realization'
                    ])
                ->where('factory_id', $request->get('factory'))
                ->whereYear('lc_date', $request->get('year'))
                ->whereMonth('lc_date', $request->get('month'))
                ->when($request->get('export_lc_no'), function($q) use ($request){
                    $q->where('id', $request->get('export_lc_no'));
                })
                ->when($request->get('buyer'), function($q) use ($request){
                    $q->whereJsonContains('buyer_id', $request->get('buyer'));
                })
                ->get();

                // return $data;

        }



        $factories = Cache::get('factories') ?? [];
        $export_lcs = ExportLC::pluck('unique_id', 'id')->prepend('Select','');
        $buyers = $buyers = Buyer::pluck('name', 'id')->prepend('Select Buyer','');
        return view('commercial::reports.export-lc-status.view', compact('data','factories','buyers','export_lcs'));
    }
    public function exportLcStatusPdf(Request $request)
    {
        $data = collect();
        if ($request->has(['factory', 'year','month'])) {

            $data = ExportLC::query()
            ->with([
                'details',
                'buyingAgent',
                'primary_contract.salesContract',
                'invoice',
                'docSubmissionInfo.docSubmission.proceed_realization'
                ])
            ->where('factory_id', $request->get('factory'))
            ->whereYear('lc_date', $request->get('year'))
            ->whereMonth('lc_date', $request->get('month'))
            ->when($request->get('export_lc_no'), function($q) use ($request){
                $q->where('id', $request->get('export_lc_no'));
            })
            ->when($request->get('buyer'), function($q) use ($request){
                $q->whereJsonContains('buyer_id', $request->get('buyer'));
            })
            ->get();

            // return $data;

    }

        $pdf = PDF::loadView('commercial::reports.export-lc-status.pdf', compact('data'))
        ->setPaper('a4')->setOrientation('landscape');
        return $pdf->stream('export-lc-status');
    } 


    public function btbLiabilityCoverage()
    {
        $data = [1];
        return view('commercial::reports.btb-coverage.view', compact('data'));
    }

    public function btbLiabilityCoveragePdf()
    {
        $data = [1];
        $pdf = PDF::loadView('commercial::reports.btb-coverage.pdf', compact('data'))
        ->setPaper('a4')->setOrientation('landscape');
        return $pdf->stream('btb_coverage');
    }  

    public function exportCiStatement()
    {
        $data = [1];
        return view('commercial::reports.export-ci-statement.view', compact('data'));
    }

    public function exportCiStatementPdf()
    {
        $data = [1];
        $pdf = PDF::loadView('commercial::reports.export-ci-statement.pdf', compact('data'))
        ->setPaper('a4')->setOrientation('landscape');
        return $pdf->stream('btb_coverage');
    } 
    
    public function exportImportStatus()
    {
        $data = [1];
        return view('commercial::reports.export-import-status.view', compact('data'));
    }

    public function exportImportStatusPdf()
    {
        $data = [1];
        $pdf = PDF::loadView('commercial::reports.export-import-status.pdf', compact('data'))
        ->setPaper('a4')->setOrientation('landscape');
        return $pdf->stream('btb_coverage');
    }

    
    public function exportLcSales()
    {
        $data = [1];
        return view('commercial::reports.export-lc-sales.view', compact('data'));
    }

    public function exportLcSalesPdf()
    {
        $data = [1];
        $pdf = PDF::loadView('commercial::reports.export-lc-sales.pdf', compact('data'))
        ->setPaper('a4')->setOrientation('landscape');
        return $pdf->stream('Export_lc_sales');
    } 
    
    
    public function exportStatementToday()
    {
        $data = [1];
        return view('commercial::reports.export-statement-today.view', compact('data'));
    }

    public function exportStatementTodayPdf()
    {
        $data = [1];
        $pdf = PDF::loadView('commercial::reports.export-statement-today.pdf', compact('data'))
        ->setPaper('a4')->setOrientation('landscape');
        return $pdf->stream('export_statement_today');
    } 
    
    
    public function fileWiseExportImport()
    {
        $data = [1];
        return view('commercial::reports.file-wise-export-import.view', compact('data'));
    }

    public function fileWiseExportImportPdf()
    {
        $data = [1];
        $pdf = PDF::loadView('commercial::reports.file-wise-export-import.pdf', compact('data'))
        ->setPaper('a4')->setOrientation('landscape');
        return $pdf->stream('File_wise_export_status');
    } 

    public function fileWiseExportStatus()
    {
        $data = [1];
        return view('commercial::reports.file-wise-export-status.view', compact('data'));
    }

    public function fileWiseExportStatusPdf()
    {
        $data = [1];
        $pdf = PDF::loadView('commercial::reports.file-wise-export-status.pdf', compact('data'))
        ->setPaper('a4')->setOrientation('landscape');
        return $pdf->stream('File_wise_export_status');
    } 
    
    
    public function monthlyBankSubmission()
    {
        $data = [1];
        return view('commercial::reports.monthly-bank-submission.view', compact('data'));
    }

    public function monthlyBankSubmissionPdf()
    {
        $data = [1];
        $pdf = PDF::loadView('commercial::reports.monthly-bank-submission.pdf', compact('data'))
        ->setPaper('a4')->setOrientation('landscape');
        return $pdf->stream('monthly_bank_submission');
    } 
    

    public function yarnWorkOrderStatement()
    {
        $data = [1];
        return view('commercial::reports.yarn-work-order-statement.view', compact('data'));
    }

    public function yarnWorkOrderStatementPdf()
    {
        $data = [1];
        $pdf = PDF::loadView('commercial::reports.yarn-work-order-statement.pdf', compact('data'))
        ->setPaper('a4')->setOrientation('landscape');
        return $pdf->stream('yarn-work-order');
    } 
    
    public function monthlyExportImport()
    {
        $data = [1];
        return view('commercial::reports.monthly-export-import.view', compact('data'));
    }

    public function monthlyExportImportPdf()
    {
        $data = [1];
        $pdf = PDF::loadView('commercial::reports.monthly-export-import.pdf', compact('data'))
        ->setPaper('a4')->setOrientation('landscape');
        return $pdf->stream('monthly_export_import');
    } 

    public function orderWiseExportInvoice()
    {
        $data = [1];
        return view('commercial::reports.order-wise-export-invoice.view', compact('data'));
    }

    public function orderWiseExportInvoicePdf()
    {
        $data = [1];
        $pdf = PDF::loadView('commercial::reports.order-wise-export-invoice.pdf', compact('data'))
        ->setPaper('a4')->setOrientation('landscape');
        return $pdf->stream('File_wise_export_status');
    } 
}
