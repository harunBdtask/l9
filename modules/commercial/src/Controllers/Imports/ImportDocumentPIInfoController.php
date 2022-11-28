<?php


namespace SkylarkSoft\GoRMG\Commercial\Controllers\Imports;

use App\Constants\ApplicationConstant;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Commercial\Models\Imports\ImportDocumentAcceptance;
use SkylarkSoft\GoRMG\Commercial\Models\Imports\ImportDocumentPIInfo;
use Symfony\Component\HttpFoundation\Response;

class ImportDocumentPIInfoController extends Controller
{
    public $response = [];
    public $status = 200;

    public function index(ImportDocumentAcceptance $importDocument): JsonResponse
    {
        $piInfos = $importDocument->piInfos()->get();
        $this->response = $piInfos;

        return response()->json($piInfos);
    }

    public function piHistories(): JsonResponse
    {
        $piId = request('pi_id');

        $piHistories = ImportDocumentPIInfo::with('importDocument', 'proformaInvoice')
            ->where('pi_id', $piId)
            ->get();

        $this->response = $piHistories->map(function ($pi) {
            return [
                'pi_no' => $pi->proformaInvoice->pi_no,
                'invoice_no' => $pi->importDocument->invoice_number,
                'invoice_date' => $pi->importDocument->invoice_date,
                'invoice_value' => $pi->current_acceptance_value ?? 0,
            ];
        });

        return response()->json($this->response);
    }

    public function show(ImportDocumentAcceptance $importDocumentAcceptance): JsonResponse
    {
        $this->response = $importDocumentAcceptance->piInfos()->get();

        return response()->json($this->response);
    }

    public function delete(ImportDocumentPIInfo $PIInfo)
    {
        $PIInfo->delete();
        $this->response['message'] = ApplicationConstant::S_DELETED;

        return response()->json($this->response);
    }
}
