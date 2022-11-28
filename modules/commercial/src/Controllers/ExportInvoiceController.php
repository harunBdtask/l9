<?php

namespace SkylarkSoft\GoRMG\Commercial\Controllers;

use App\Constants\ApplicationConstant;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use SkylarkSoft\GoRMG\Commercial\Models\ExportInvoice;
use SkylarkSoft\GoRMG\Commercial\Models\ExportInvoiceDetail;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;

class ExportInvoiceController extends Controller
{
    public function createPage()
    {
        return view('commercial::export-invoice.export-invoice-form');
    }

    public function index()
    {
        $invoices = ExportInvoice::latest()->paginate();

        return view('commercial::export-invoice.export-invoice-list', ['invoices' => $invoices]);
    }

    public function view(ExportInvoice $invoice)
    {
        return view('commercial::export-invoice.view', compact('invoice'));
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
           'invoice_no' => 'required',
           'buyer_id' => 'required',
           'invoice_date' => 'required',
           'location' => 'required',
        ]);
        try {
            $invoice = new ExportInvoice($request->all());
            if ($request->get('file') &&
                strpos($request->get('file'), 'base64') !== false) {
                $image_path = $this->fileUpload('commercial', $request->get('file'), null);
                $invoice->file = $image_path;
            }
            if ($request->get('bill_file') &&
                strpos($request->get('bill_file'), 'base64') !== false) {
                $image_path = $this->fileUpload('commercial', $request->get('bill_file'), null);
                $invoice->bill_file = $image_path;
            }
            $invoice->save();

            return response()->json(['message' => ApplicationConstant::S_CREATED, 'invoice' => $invoice]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()]);
        }
    }

    // Upload The File and return the File Path
    private function fileUpload($path, $file, $type): string
    {
        $folderPath = $path . '/' . $type . '/';
        $fileParts = explode(";base64,", $file);
        $fileTypeAux = isset($fileParts[0]) ? explode($type . "/", $fileParts[0]) : null;
        $filePart = isset($fileTypeAux[1]) ? trim($fileTypeAux[1]) : null;
        $fileBase64 = isset($fileParts[1]) ? base64_decode($fileParts[1]) : null;
        $file = $folderPath . time() . rand(10000, 99999) . '.' . $filePart;
        Storage::disk('public')->put($file, $fileBase64);

        return $file;
    }

    public function storeAdditionalInfo(ExportInvoice $invoice, Request $request): JsonResponse
    {
        $invoice->update($request->all([
            'cargo_delivery_to',
            'main_mark',
            'net_weight',
            'cbm',
            'place_of_delivery',
            'side_mark',
            'gross_weight',
            'invoice_value',
            'invoice_qty',
            'add_upcharge',
            'net_invoice_value',
            'discount_percentage',
            'annual_bonus_percentage',
            'claim_percentage',
            'commission_percentage',
            'other_deduction_percentage',
            'discount_amount',
            'bonus_amount',
            'claim_amount',
            'commission_amount',
            'other_deduction_amount',
        ]));

        return response()->json(['message' => ApplicationConstant::S_UPDATED]);
    }

    public function storeShippingInfo(ExportInvoice $invoice, Request $request): JsonResponse
    {
        try {
            $shippingInfo = $invoice->shippingInformation()->firstOrNew();
            $shippingInfo->fill($request->all())->save();

            return response()->json(['message' => ApplicationConstant::S_STORED]);
        } catch (Exception $e) {
            return response()->json(['message' => ApplicationConstant::SOMETHING_WENT_WRONG, 'errorMsg' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function newStore(Request $request)
    {
        try {
            $request->validate([
                'invoice_no' => 'required',
                'buyer_id' => 'required',
                'invoice_date' => 'required',
                'location' => 'required',
            ]);
            $id = $request->get('id') ?? null;
            if ($request->hasFile('file_name')) {
                $file = $request->file('file_name');
                $path = Storage::put('invoices', $file);
                $request->merge(['file' => $path]);
            }
            if ($request->hasFile('bill_file_path')) {
                $fileTwo = $request->file('bill_file_path');
                $pathTwo = Storage::put('invoices/', $fileTwo);
                $request->merge(['bill_file' => $pathTwo]);
            }
            if ($id) {
                $exportInvoice = ExportInvoice::findOrFail($id);
                if ($exportInvoice->file && $request->hasFile('file_name')) {
                    Storage::delete($exportInvoice->file);
                }
                if ($exportInvoice->bill_file && $request->hasFile('bill_file_path')) {
                    Storage::delete($exportInvoice->bill_file);
                }
                $exportInvoice->update($request->except(['buyer_address','swift_code']));
            }else {
                $exportInvoice = new ExportInvoice($request->except(['buyer_address','swift_code']));
                $exportInvoice->save();
            }

            return response()->json(['invoice' => $exportInvoice, 'message' => 'Successfully Updated!'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something Went Wrong!'], 500);
        }
    }

    public function update(ExportInvoice $invoice, Request $request): JsonResponse
    {
        try {
            $invoice->fill($request->all());
            if ($request->get('file') &&
                strpos($request->get('file'), 'base64') !== false) {
                $image_path = $this->fileUpload('commercial', $request->get('file'), null);
                $invoice->file = $image_path;
            }
            if ($request->get('bill_file') &&
                strpos($request->get('bill_file'), 'base64') !== false) {
                $image_path = $this->fileUpload('commercial', $request->get('bill_file'), null);
                $invoice->bill_file = $image_path;
            }
            $invoice->save();

            return response()->json(['message' => ApplicationConstant::S_UPDATED]);
        } catch (Exception $e) {
            return response()->json(['message' => ApplicationConstant::SOMETHING_WENT_WRONG], 500);
        }
    }

    public function show(ExportInvoice $invoice): JsonResponse
    {
        $invoice->load([
            'buyer',
            'shippingInformation',
            'details.exportInvoice',
            'details.order',
            'details.po.exportInvoiceDetails',
        ]);
        $export_lc_id = $invoice->export_lc_id ?? null;
        $sales_contract_id = $invoice->sales_contract_id ?? null;
        $file_no = $export_lc_id ? $invoice->exportLc->internal_file_no : ($sales_contract_id ? $invoice->salesContract->internal_file_no : null);
        $lc_sc_no = $export_lc_id ? $invoice->exportLc->lc_number : ($sales_contract_id ? $invoice->salesContract->contract_number : null);
        $year = $export_lc_id ? $invoice->exportLc->year : ($sales_contract_id ? $invoice->salesContract->year : null);
        $unique_id = $invoice->uniq_id;
        $details = [];
        if ($invoice->details()->count()) {
            foreach ($invoice->details as $detail) {
                $po_details = $detail->po->poDetails->first();
                $fixed_rate = $detail->exportLcDetail->count() ? $detail->exportLcDetail->rate : ($detail->salesContractDetail->count() ? $detail->salesContractDetail->rate : 0);
                $po_id = $detail->po_id;
                $cumu_invoice_qty = $po_id ? ExportInvoiceDetail::query()->where('po_id', $po_id)->sum('current_invoice_qty') : 0;
                $cumu_invoice_value = $po_id ? ExportInvoiceDetail::query()->where('po_id', $po_id)->sum('current_invoice_value') : 0;
                $po_balance_qty = $detail->attach_qty - $cumu_invoice_qty;
                $merchandiser_id = $detail->exportLcDetail->count() ? $detail->exportLcDetail->order->order->dealing_merchant_id : ($detail->salesContractDetail->count() ? $detail->salesContractDetail->order->order->dealing_merchant_id : 0);
                $merchandiser = $detail->exportLcDetail->count() ? $detail->exportLcDetail->order->order->dealingMerchant->full_name_with_email : ($detail->salesContractDetail->count() ? $detail->salesContractDetail->order->order->dealingMerchant->full_name_with_email : 0);
                $details[] = [
                    'id' => $detail->id,
                    'export_invoice_id' => $detail->export_invoice_id,
                    'export_lc_id' => $detail->export_lc_id,
                    'export_lc_detail_id' => $detail->export_lc_detail_id,
                    'sales_contract_id' => $detail->sales_contract_id,
                    'sales_contract_detail_id' => $detail->sales_contract_detail_id,
                    'order_id' => $detail->order_id,
                    'po' => $detail->po,
                    'po_id' => $detail->po_id,
                    'article_no' => collect($po_details->quantity_matrix)->where('particular', PurchaseOrder::ARTICLE_NO)->values()->first()['value'] ?? null,
                    'shipment_date' => $detail->po->country_ship_date,
                    'attach_qty' => $detail->attach_qty,
                    'rate' => $detail->rate,
                    'fixed_rate' => $fixed_rate,
                    'current_invoice_qty' => $detail->current_invoice_qty ?? null,
                    'current_invoice_value' => $detail->current_invoice_value ?? null,
                    'cumu_invoice_qty' => $cumu_invoice_qty,
                    'fixed_cumu_invoice_qty' => $cumu_invoice_qty - $detail->current_invoice_qty ?? null,
                    'po_balance_qty' => $po_balance_qty,
                    'cumu_invoice_value' => $cumu_invoice_value,
                    'fixed_cumu_invoice_value' => $cumu_invoice_value - $detail->current_invoice_value ?? null,
                    'ex_factory_qty' => null,
                    'merchandiser_id' => $merchandiser_id,
                    'merchandiser' => $merchandiser,
                    'production_source' => $detail->production_source,
                    'color_size_details' => $detail->colorSizeDetails,
                    'color_size_details_status' => $detail->color_size_details_status,
                    'factory_id' => factoryId(),
                ];
            }
        }
        $invoice_qty = $invoice->invoice_qty ?? 0;
        $invoice_value = $invoice->invoice_value ?? 0;
        $net_invoice_value = $invoice->net_invoice_value ?? 0;
        $data = clone $invoice;
        $data['unique_id'] = $unique_id;
        $data['file_no'] = $file_no;
        $data['lc_sc_no'] = $lc_sc_no;
        $data['export_lc_id'] = $export_lc_id;
        $data['sales_contract_id'] = $sales_contract_id;
        $data['beneficiary'] = $invoice->beneficiary->factory_name ?? null;
        $data['lien_bank'] = $invoice->lienBank->name ?? null;
        $data['applicant'] = $invoice->applicant->name ?? null;
        $data['location'] = $invoice->beneficiary->factory_address ?? null;
        $data['country'] = $invoice->country->name ?? null;
        $data['country_code'] = $invoice->country->iso_alpha_2_code;
        $data['year'] = $year;
        $data['invoice_qty'] = $invoice_qty;
        $data['invoice_value'] = $invoice_value;
        $data['net_invoice_value'] = $net_invoice_value;
        $data['buyer'] = $invoice->buyer->name ?? null;
        $data['details'] = $details;


        return response()->json($data);
    }

    public function delete(ExportInvoice $invoice): RedirectResponse
    {
        try {
            $invoice->details()->delete();
            $invoice->delete();
            Session::flash('success', 'Data Deleted successfully!');

            return redirect()->back();
        } catch (Exception $e) {
            Session::flash('error', "Something went wrong!{$e->getMessage()}");

            return redirect()->back();
        }
    }
}
