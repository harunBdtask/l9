<?php


namespace SkylarkSoft\GoRMG\Commercial\Controllers\Imports;

use App\Constants\ApplicationConstant;
use DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Session;
use SkylarkSoft\GoRMG\Commercial\Constants\CommercialConstant;
use SkylarkSoft\GoRMG\Commercial\Models\Imports\ImportDocumentAcceptance;
use Symfony\Component\HttpFoundation\Response;

class ImportDocumentAcceptanceController
{
    public $response = [];
    public $status = 200;

    public function importDocumentlist()
    {
        $ImporDocuments = ImportDocumentAcceptance::with('shippingInfo', 'piInfos')->latest()->paginate();

        return view('commercial::import-document-acceptance.import-document-list', ['imporDocuments' => $ImporDocuments]);
    }

    public function store(Request $request)
    {
        try {
            \DB::beginTransaction();
            $importDocument = new ImportDocumentAcceptance($request->input('mainForm'));
            $importDocument->save();
            $importDocument->shippingInfo()->create($request->input('shippingInfo'));
            foreach ($request->input('piInfos') as $item) {
                if ($id = $item['id'] ?? null) {
                    $importDocument->piInfos()->find($id)->update((array) $item);

                    continue;
                }

                $importDocument->piInfos()->create((array) $item);
            }
//            collect($request->input('piInfos'))->tap($this->saveOrUpdatePIInfo($importDocument));
            \DB::commit();

            $importDocument->load('shippingInfo', 'piInfos');
            $this->response = ['message' => ApplicationConstant::S_STORED, 'importDocument' => $importDocument];
            $this->status = Response::HTTP_CREATED;
        } catch (\Exception $e) {
            \DB::rollBack();
            $this->response = ['message' => ApplicationConstant::SOMETHING_WENT_WRONG, 'errMsg' => $e->getMessage(), 'line' => $e->getLine()];
            $this->status = Response::HTTP_BAD_REQUEST;
        }

        return response()->json($this->response, $this->status);
    }

    public function update(ImportDocumentAcceptance $importDocument, Request $request): JsonResponse
    {
        try {
            \DB::rollBack();
            $importDocument->update($request->input('mainForm'));
            $importDocument->shippingInfo()->update($request->input('shippingInfo'));
            foreach ($request->input('piInfos') as $item) {
                if ($id = $item['id'] ?? null) {
                    $importDocument->piInfos()->find($id)->update((array) $item);

                    continue;
                }

                $importDocument->piInfos()->create((array) $item);
            }
//            collect($request->input('piInfos'))->tap($this->saveOrUpdatePIInfo($importDocument));
            \DB::commit();

            $this->response = ['message' => ApplicationConstant::S_UPDATED, 'importDocument' => $importDocument->load('shippingInfo', 'piInfos')];
        } catch (\Exception $e) {
            $this->response = ['message' => ApplicationConstant::SOMETHING_WENT_WRONG, 'errMsg' => $e->getMessage()];
            $this->status = Response::HTTP_BAD_REQUEST;
        }

        return response()->json($this->response, $this->status);
    }

    public function show(ImportDocumentAcceptance $importDocument): JsonResponse
    {
        $this->response = $importDocument->load('shippingInfo', 'piInfos', 'piInfos.item:id,item_name', 'piInfos.proformaInvoice:id,pi_no', 'supplier:id,name', 'factory:id,factory_name');

        return response()->json($this->response);
    }

    private function saveOrUpdatePIInfo(ImportDocumentAcceptance $importDocument): \Closure
    {
        return function ($item) use ($importDocument) {
            if ($id = $item['id'] ?? null) {
                $importDocument->piInfos()->find($id)->update((array) $item);

                return;
            }

            $importDocument->piInfos()->create((array) $item);
        };
    }

    public function deleteList(ImportDocumentAcceptance $importDocument)
    {
        try {
            DB::beginTransaction();
            $importDocument->shippingInfo()->delete();
            $importDocument->piInfos()->delete();
            $importDocument->delete();
            DB::commit();
            Session::flash('error', 'Data Deleted Successfully');
        } catch (\Exception $exception) {
            Session::flash('error', 'Something Wrong');
        }

        return redirect('commercial/import-document-acceptance');
    }

    public function delete(ImportDocumentAcceptance $importDocument): JsonResponse
    {
        try {
            \DB::beginTransaction();
            $importDocument->shippingInfo()->delete();
            $importDocument->piInfos()->delete();
            $importDocument->delete();
            \DB::commit();
            $this->response = ['message' => ApplicationConstant::S_DELETED];
        } catch (\Exception $e) {
            \DB::rollBack();
            $this->response = ['message' => ApplicationConstant::SOMETHING_WENT_WRONG, 'errMsg' => $e->getMessage()];
        }

        return response()->json($this->response, $this->status);
    }

    public function retireSource()
    {
        $data = [];
        foreach (CommercialConstant::RETIRE_SOURCES as $k => $v) {
            array_push($data, [
               'id' => $k,
                'text' => $v,
            ]);
        }

        return response()->json($data);
    }

    public function AcceptanceTime()
    {
        $data = [];
        foreach (CommercialConstant::ACCEPTANCE_TIME as $k => $v) {
            array_push($data, [
               'id' => $k,
                'text' => $v,
            ]);
        }

        return response()->json($data);
    }
}
