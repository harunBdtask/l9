<?php


namespace SkylarkSoft\GoRMG\Merchandising\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use SkylarkSoft\GoRMG\Merchandising\Models\POFileModel;
use SkylarkSoft\GoRMG\Merchandising\Models\PriceQuotation;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\Merchandising\Requests\POFilesFormRequest;
use SkylarkSoft\GoRMG\PoPDF\PoFileConverter;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsItem;
use SkylarkSoft\GoRMG\SystemSettings\Models\PoFileIssueModel;
use SkylarkSoft\GoRMG\SystemSettings\Models\Size;
use SkylarkSoft\GoRMG\SystemSettings\Services\LogToDB;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class POFilesController extends Controller
{
    const STORAGE_PO_FILES = "/storage/po_files/";
    const PO_FILES = "/po_files/";

    /**
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {
        $po_files = POFileModel::query()
            ->with('buyer')
            ->when($request->query('search'), function ($query) use ($request) {
                $query->where("po_no", "LIKE", "%{$request->query('search')}%")
                    ->orWhere("created_at", "LIKE", "%{$request->query('search')}%")
                    ->orWhere("style", "LIKE", "%{$request->query('search')}%");
            })->orderBy("id", "DESC")->paginate();
        $buyers = Buyer::query()->where("pdf_conversion_key", "!=", null)->get();
        $styles = PriceQuotation::query()->get(['style_name', 'style_name']);
        $alreadyReadPo = PurchaseOrder::query()->distinct('po_no')->pluck('po_no')->toArray();
        $totalFiles = POFileModel::all()->count();
           
        $dashboardOverview = [
            "Total Files" => $totalFiles
        ];
        return view("merchandising::po_files.po_files", [
            "po_files" => $po_files,
            "buyers" => $buyers,
            "styles" => $styles,
            'alreadyReadPo' => $alreadyReadPo,
            'dashboardOverview'=> $dashboardOverview
        ]);
    }

    /**
     * @param POFilesFormRequest $request
     * @return RedirectResponse
     * @throws Throwable
     */
    public function store(POFilesFormRequest $request): RedirectResponse
    {
        DB::beginTransaction();
        $po_file = new POFileModel();
        $requested_data = $request->all();
        $buyer_code = Buyer::query()->where("id", $request->get("buyer_id"))->first();
        if (!$buyer_code->pdf_conversion_key) {
            Session::flash('alert-danger', "PDF conversion key not found.");
            return back();
        }
        $requested_data['buyer_code'] = $buyer_code->pdf_conversion_key;
        $file_issues = $request->get('file_issues') ?? [];
        if ($request->hasFile('file')) {
            $file = $request->file;
            $path = $request->get("po_no") . "_" . $request->get("style") . "." . $file->extension();
            $file->storeAs('po_files', trim($path));
            $requested_data['file'] = trim($path);
            $requested_data['processed'] = 1;
        }

        $requested_data['file_issues'] = $file_issues;
        $po_file->fill($requested_data)->save();

        $conversion_file_path = public_path(self::STORAGE_PO_FILES . $po_file->file);
        $text_file_path = self::PO_FILES . str_replace(".pdf", ".txt", $po_file->file);
        $converter = new PoFileConverter($po_file->buyer_code, $conversion_file_path, $request->get('flag'));
        $content = $converter->getContent();
        Storage::put($text_file_path, $content);
        try {
            $_matrix_data = $converter->convert();
            $colorType = $request->get('flag') == 'color' ? 1 : 2;
            $matrix_data = $this->quantityMatrix($_matrix_data, $request->get('style'), $colorType);
            $po_file->quantity_matrix = $matrix_data;
            $po_file->po_quantity = collect($matrix_data)
                ->where('particulars', PurchaseOrder::QTY)
                ->sum('value');
            $po_file->is_read = 1;
            $po_file->save();
            if (!$matrix_data) {
                $po_file->processed = 0;
                $po_file->is_read = 0;
                $po_file->save();
                Session::flash('alert-danger', "Item Not Found. Create item first then reprocess.");
            } else {
                Session::flash('alert-success', 'Data stored successfully!');
            }
        } catch (Exception $exception) {
            Log::info("Po File Id : {$po_file->id} - error - {$exception->getMessage()}");
            $error = [
                'po_id' => $po_file->id,
                'message' => $exception->getMessage()
            ];
            LogToDB::log($error);
            Session::flash('alert-danger', 'File was not processed successfully. Please update file content!');
        }

        DB::commit();
        return redirect()->back();
    }

    public function editContent($id)
    {
        $po_file = POFileModel::query()->where("id", $id)->first();
        $text_file_path = self::STORAGE_PO_FILES . str_replace(".pdf", ".txt", $po_file->file);
        $content = File::get(public_path($text_file_path));
        return view("merchandising::po_files.content_edit", [
            "po_file" => $po_file,
            "content" => $content,
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function updateContent(Request $request, $id): RedirectResponse
    {
        try {
            $po_file = POFileModel::query()->where("id", $id)->first();
            $text_file_path = self::PO_FILES . str_replace(".pdf", ".txt", $po_file->file);
            Storage::put($text_file_path, $request->get('content'));
            Session::flash('alert-success', 'File content updated successfully!');
        } catch (Exception $exception) {
            Session::flash('alert-danger', "Something went wrong");
        } finally {
            return redirect('/po_files');
        }
    }

    public function download($id)
    {
        $po_file = POFileModel::query()->where("id", $id)->first();
        if (isset($po_file->file)) {
            $file_name = $po_file->file;
            if (Storage::disk('public')->exists(self::PO_FILES . $file_name)) {
                $file_name = public_path(self::STORAGE_PO_FILES . $file_name);
                return response()->download($file_name);
            } else {
                Session::flash('alert-danger', "File not found");
            }
        }

        return back();
    }

    public function buyerWiseIssue($buyerId): JsonResponse
    {
        $file_issue = PoFileIssueModel::query()->where("buyer_id", $buyerId)->get();

        return response()->json($file_issue, Response::HTTP_OK);
    }

    /**
     * @param $id
     * @return RedirectResponse
     */
    public function reProcess($id): RedirectResponse
    {
        $poFile = POFileModel::query()->findOrFail($id);
        $conversion_file_path = public_path(self::STORAGE_PO_FILES . $poFile->file);
        if (!Storage::disk('public')->exists(self::PO_FILES . $poFile->file)) {
            Session::flash('alert-danger', "File Not Found.");
            return back();
        }
        try {
            $converter = new PoFileConverter($poFile->buyer_code, $conversion_file_path, $poFile->flag);
            $_matrix_data = $converter->convert();
            $colorType = $poFile->flag == 'color' ? 1 : 2;
            $matrix_data = $this->quantityMatrix($_matrix_data, $poFile->style, $colorType);
            $poFile->quantity_matrix = $matrix_data;
            $poFile->po_quantity = collect($matrix_data)
                ->where('particulars', PurchaseOrder::QTY)
                ->sum('value');
            $poFile->processed = 1;
            $poFile->is_read = 1;
            $poFile->save();
            if (!$matrix_data) {
                $poFile->processed = 0;
                $poFile->is_read = 0;
                $poFile->save();
                Session::flash('alert-danger', "Item Not Found. Create item first then reprocess.");
            } else {
                Session::flash('alert-success', 'PDF File Reprocessed successfully!');
            }
        } catch (Exception  $exception) {
            Log::info("Po File Id : $id - error - {$exception->getMessage()}");
            $error = [
                'po_id' => $id,
                'message' => $exception->getMessage()
            ];
            LogToDB::log($error);
            Session::flash('alert-danger', "File was not processed successfully");
        }

        return redirect()->back();
    }

    public function view($id): JsonResponse
    {
        $poFile = POFileModel::query()->findOrFail($id);
        $conversion_file_path = public_path(self::STORAGE_PO_FILES . $poFile->file);
        if (!Storage::disk('public')->exists(self::PO_FILES . $poFile->file)) {
            return response()->json(['message' => 'File Not Found'], Response::HTTP_OK);
        }

        $converter = new PoFileConverter($poFile->buyer_code, $conversion_file_path, $poFile->flag);
        $_matrix_data = $converter->convert();
        $colorType = $poFile->flag == 'color' ? 1 : 2;
        $matrix_data = $this->quantityMatrix($_matrix_data, $poFile->style, $colorType);
        $poQuantity = collect($matrix_data)
            ->where('particulars', PurchaseOrder::QTY)
            ->sum('value');
        $response = [
            'file_content' => $_matrix_data,
            'matrix_data' => $matrix_data,
            'poQuantity' => $poQuantity
        ];
        return response()->json($response, Response::HTTP_OK);

    }

    /**
     * @param $_matrix_data
     * @param null $styleName
     * @return array
     * @throws Exception
     */
    private function quantityMatrix($_matrix_data, $styleName = null, $colorType = 2): array
    {
        try {
            return collect(json_decode($_matrix_data, true))->map(function ($value) use ($styleName, $colorType) {
                $item = PriceQuotation::query()->where('style_name', $styleName)->first();
                $itemDetails = collect($item->item_details)->pluck('garment_item_id')->whereNotNull()->first();
                $item = GarmentsItem::query()->where("id", $itemDetails)->first();
                $color = Color::query()->firstOrCreate(
                    ["name" => $value['color']],
                    ['status' => $colorType]
                );
                $size = Size::query()->firstOrCreate(["name" => $value['size']]);

                return [
                    "item_id" => $item->id,
                    "item" => $item->name,
                    "color" => $color->name,
                    "color_id" => $color->id,
                    "size" => $size->name,
                    "size_id" => $size->id,
                    "particulars" => $value['particulars'],
                    "value" => $value['value'],
                    "league" => $value['league'] ?? '',
                    "x_factory_date" => $value['x_factory_date'] ? Carbon::create($value['x_factory_date'])->format('Y-m-d') : '',
                    "customer" => $value['customer'] ?? '',
                ];
            })->toArray();
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }

    public function destroy($id): RedirectResponse
    {
        try {
            $po_file = POFileModel::query()->where("id", $id)->first();
            if (isset($po_file->file)) {
                $file_name_to_delete = $po_file->file;
                $text_file_path = self::PO_FILES . str_replace(".pdf", ".txt", $po_file->file);
                if (Storage::disk('public')->exists(self::PO_FILES . $file_name_to_delete)
                    && $file_name_to_delete) {
                    Storage::delete('po_files/' . $file_name_to_delete);
                }

                if (Storage::disk('public')->exists(self::PO_FILES . $text_file_path)
                    && $text_file_path) {
                    Storage::delete('po_files/' . $text_file_path);
                }
            }
            $po_file->delete();
            Session::flash('alert-success', 'Data Deleted successfully!');

            return redirect()->back();
        } catch (Exception $exception) {
            Session::flash('alert-danger', "Something went wrong!{$exception->getMessage()}");

            return redirect()->back();
        }
    }

    public function getPoQuantity($poNo): JsonResponse
    {
        try {
            $poQuantity = POFileModel::query()->where('po_no', $poNo)->first();
            return response()->json($poQuantity, Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_OK);
        }
    }
}
