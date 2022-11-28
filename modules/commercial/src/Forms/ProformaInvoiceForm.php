<?php


namespace SkylarkSoft\GoRMG\Commercial\Forms;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SkylarkSoft\GoRMG\Commercial\Models\ProformaInvoice;

class ProformaInvoiceForm extends Form
{
    public function persist(): ProformaInvoice
    {
        $proformaInvoice = ProformaInvoice::firstOrNew(['id' => $this->id ?: null]);

        if ($this->hasFile('file')) {
            $file = $this->file('file');
            $path = Storage::put('invoices/', $file);
            $this->merge(['file_path' => $path]);
        }
        if ($this->hasFile('bill_entry_file2')) {
            $fileTwo = $this->file('bill_entry_file2');
            $pathTwo = Storage::put('invoices/', $fileTwo);
            $this->merge(['bill_entry_file' => $pathTwo]);
        }
        if ($this->hasFile('import_docs2')) {
            $fileThree = $this->file('import_docs2');
            $pathThree = Storage::put('invoices/', $fileThree);
            $this->merge(['import_docs' => $pathThree]);
        }

        try {
            $proformaInvoice = $proformaInvoice->fill($this->except(['details']));
            $proformaInvoice->save();

            return $proformaInvoice;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function rules(): array
    {
        return [
            'item_category' => 'required',
            'importer_id' => 'required',
            'supplier_id' => 'required',
            'pi_receive_date' => 'required',
            'currency' => 'required',
            'source' => 'required',
            'pi_basis' => 'required',
            'pay_term' => 'required|string',
            'hs_code' => 'required',
            'pi_no' => 'required',
            'pi_created_date' => 'required',
            'last_shipment_date' => 'nullable',
            'pi_validity_date' => 'nullable',
            'indentor_name' => 'nullable',
            'internal_file_no' => 'nullable',
            'lc_group_no' => 'nullable',
            'remarks' => 'nullable',
            'file' => 'nullable',
            'bill_entry_file' => 'nullable',
            'import_docs' => 'nullable',
            'goods_rcv_status' => 'required'
            // 'goods_rcv_status' => $this->input('pi_basis') == 1 ? 'required' : 'nullable',

        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Required',
        ];
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
}
