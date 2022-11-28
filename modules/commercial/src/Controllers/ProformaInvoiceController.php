<?php


namespace SkylarkSoft\GoRMG\Commercial\Controllers;

use PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use SkylarkSoft\GoRMG\DyesStore\Models\DsItem;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Commercial\Models\ProformaInvoice;
use SkylarkSoft\GoRMG\Commercial\Forms\ProformaInvoiceForm;
use SkylarkSoft\GoRMG\Merchandising\Models\YarnPurchaseOrder;
use SkylarkSoft\GoRMG\SystemSettings\Models\EmbellishmentItem;
use SkylarkSoft\GoRMG\SystemSettings\Models\TermsAndCondition;
use SkylarkSoft\GoRMG\Commercial\Models\ProformaInvoiceDetails;
use SkylarkSoft\GoRMG\Commercial\Requests\ProformaInvoiceRequest;
use SkylarkSoft\GoRMG\Merchandising\Services\ReportSignatureService;
use SkylarkSoft\GoRMG\Commercial\Services\Bookings\TrimsBookingService;
use SkylarkSoft\GoRMG\Commercial\Services\Bookings\EmbellishmentService;
use SkylarkSoft\GoRMG\Commercial\Services\Bookings\FabricBookingService;
use SkylarkSoft\GoRMG\Commercial\Services\Bookings\YarnPurchaseOrderService;
use SkylarkSoft\GoRMG\Merchandising\Models\WorkOrders\EmbellishmentWorkOrder;

class ProformaInvoiceController extends Controller
{
    const PAGE_NAME = 'proforma_invoice';

    public function index()
    {
        $invoices = ProformaInvoice::query()
            ->with('importer', 'supplier', 'item')
            ->latest()
            ->paginate();

        return view('commercial::proforma-invoice.index', compact('invoices'));
    }

    public function create()
    {
        return view('commercial::proforma-invoice.create');
    }


    public function store(ProformaInvoiceForm $form): JsonResponse
    {
        try {
            $invoice = $form->persist();

            return response()->json(['invoice' => $invoice, 'message' => 'Successfully Created!'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function show(ProformaInvoice $invoice): JsonResponse
    {
        return response()
            ->json($invoice);
    }

    public function edit(ProformaInvoice $invoice)
    {
        return view('commercial::proforma-invoice.create', compact('invoice'));
    }

    public function update(ProformaInvoice $invoice, ProformaInvoiceRequest $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = Storage::put('invoices', $file);
            $request->merge(['file_path' => $path]);
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
            $proformaInvoice = $invoice->fill($request->all());
            $proformaInvoice->save();

            return response()->json(['invoice' => $invoice, 'message' => 'Successfully Updated!'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something Went Wrong!'], 500);
        }
    }

    public function search()
    {
        $q = request('search');

        // DB::enableQueryLog();
        $invoices = ProformaInvoice::query()
            ->with('importer', 'supplier', 'item')
            ->where('pi_no', 'LIKE', '%' . $q . '%')
            ->orWhereDate('pi_receive_date', $q)
            ->orWhere('hs_code', 'LIKE', '%' . $q . '%')
            ->orWhereHas('importer', function ($query) use ($q) {
                return $query->where('factory_name', 'LIKE', '%' . $q . '%');
            })
            ->orWhereHas('supplier', function ($query) use ($q) {
                return $query->where('name', 'LIKE', '%' . $q . '%');
            })
            ->orWhereHas('item', function ($query) use ($q) {
                return $query->where('item_name', 'LIKE', '%' . $q . '%');
            })
            ->when($q == 'open', function ($query) {
                return $query->orWhereNull('b_to_b_margin_lc_id');
            })
            ->when($q == 'close', function ($query) {
                return $query->orWhereNotNull('b_to_b_margin_lc_id');
            })
            ->orderBy('id')
            ->paginate(15);
        // return DB::getQueryLog();

        return view('commercial::proforma-invoice.index', compact('invoices'));

//        return response()->json($invoices);
    }

    public function saveDetails(ProformaInvoice $invoice, Request $request)
    {
        try {
            $invoice->details = $request->all();
            $invoice->save();

            return response()->json(['invoice' => $invoice, 'message' => 'Successfully Saved!'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something Went Wrong!'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function saveFabricTrimsPIDetails(ProformaInvoice $invoice, Request $request)
    {
        $invoice_id = $invoice->id;
        if ($invoice->details) {
            ProformaInvoiceDetails::query()->where('invoice_id', $invoice_id)->forceDelete();
        }

        $proformaInvoiceDetails = collect($request->details)->unique('details_id')->map(function ($val) use ($invoice_id) {
            return [
                'booking_details_id' => $val['details_id'],
                'type' => $val['type'],
                'invoice_id' => $invoice_id,
                'booking_id' => $val['booking_id'],
            ];
        });


        try {
            DB::beginTransaction();
            foreach ($proformaInvoiceDetails as $value) {
                $invoiceDetails = new ProformaInvoiceDetails();
                $invoiceDetails->invoice_id = $value['invoice_id'];
                $invoiceDetails->booking_details_id = $value['booking_details_id'];
                $invoiceDetails->type = $value['type'];
                $invoiceDetails->booking_id = $value['booking_id'];
                $invoiceDetails->save();
            }
            $invoice->details = $request->all();
            $invoice->save();
            DB::commit();

            return response()->json(['invoice' => $invoice, 'message' => 'Successfully Saved!'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['message' => 'Something Went Wrong!'], Response::HTTP_BAD_REQUEST);
        }
    }

    // Yarn Details
    public function saveYarnPIDetails(ProformaInvoice $invoice, Request $request)
    {
        $invoice_id = $invoice->id;
        if ($invoice->details) {
            ProformaInvoiceDetails::query()->where('invoice_id', $invoice_id)->forceDelete();
        }

        $proformaInvoiceDetails = collect($request->details)->unique('details_id')->map(function ($val) use ($invoice_id) {
            return [
                'booking_details_id' => $val['details_id'],
                'type' => $val['type'],
                'invoice_id' => $invoice_id,
                'booking_id' => $val['booking_id'],
            ];
        });


        try {
            DB::beginTransaction();
            foreach ($proformaInvoiceDetails as $value) {
                $invoiceDetails = new ProformaInvoiceDetails();
                $invoiceDetails->invoice_id = $value['invoice_id'];
                $invoiceDetails->booking_details_id = $value['booking_details_id'];
                $invoiceDetails->type = $value['type'];
                $invoiceDetails->booking_id = $value['booking_id'];
                $invoiceDetails->save();
            }
            $invoice->details = $request->all();
            $invoice->save();
            DB::commit();

            return response()->json(['invoice' => $invoice, 'message' => 'Successfully Saved!'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['message' => 'Something Went Wrong!'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function deleteDetails(ProformaInvoice $invoice, Request $request)
    {
        try {
            $invoice->details = empty($request);
            $invoice->save();

            return response()->json(['invoice' => $invoice, 'message' => 'Successfully Deleted!'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function deleteFabricTrimsDetails(ProformaInvoice $invoice, Request $request)
    {
        try {
            DB::beginTransaction();
            ProformaInvoiceDetails::query()->where('invoice_id', $invoice->id)->forceDelete();
            $invoice->details = empty($request);
            $invoice->save();
            DB::commit();

            return response()->json(['invoice' => $invoice, 'message' => 'Successfully Deleted!'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function embellishmentDetails(ProformaInvoice $invoice)
    {
        $embellishment_name_id = collect($invoice->details->details)->map(function ($q) {
            return $q->embellishment_name;
        });


        $embellishment_names = [];
        for ($i = 0; $i < count($embellishment_name_id); $i++) {
            $embellishment = EmbellishmentItem::query()
                ->where('id', $embellishment_name_id[$i])
                ->pluck('name');
            array_push($embellishment_names, $embellishment);
        }

        $embellishment_types = [];
        for ($i = 0; $i < count($embellishment_name_id); $i++) {
            $embellishment = EmbellishmentItem::query()
                ->where('name', 'LIKE', $embellishment_names[$i])
                ->get(['id as value', 'type as text']);
            array_push($embellishment_types, $embellishment);
        }


        return response()->json(['invoice' => ($invoice), 'types' => $embellishment_types]);
    }

    public function fetchTrimsBookingsDate(Request $request)
    {
        $mainTrimsBookings = collect(TrimsBookingService::mainTrimsData($request))->map(function ($value) {
            return [
                'year' => $value->booking_date,
                'type' => 'main',
                'wo_date' => $value->booking_date,
                'buyer' => $value->buyer->name,
                'buyer_id' => $value->buyer->id,
                'supplier' => $value->supplier->name,
                'supplier_id' => $value->supplier->id ?? '',
                'booking_details' => $value->bookingDetails,
                'shipment_date' => $value->delivery_date,
                'wo_no' => $value->unique_id,
            ];
        });

        $shortTrimsBookings = collect(TrimsBookingService::shortTrimsData($request))->map(function ($value) {
            return [
                'year' => $value->booking_date,
                'type' => 'short',
                'wo_date' => $value->booking_date,
                'buyer' => $value->buyer->name,
                'buyer_id' => $value->buyer->id,
                'supplier' => $value->supplier->name,
                'supplier_id' => $value->supplier->id ?? '',
                'booking_details' => $value->bookingDetails,
                'shipment_date' => $value->delivery_date,
                'wo_no' => $value->unique_id,

            ];
        });

        $trimsBookings = array_merge([$mainTrimsBookings], [$shortTrimsBookings]);

        return collect($trimsBookings)->flatten(1);
    }

    public function fetchFabricBookingsDate(Request $request)
    {
//       return $mainFabricBookings = collect(FabricBookingService::mainFabricData($request)) ;
        $mainFabricBookings = collect(FabricBookingService::mainFabricData($request))->map(function ($value) {
            return [
                'wo_no' => $value->unique_id,
                'wo_date' => $value->booking_date,
                'buyer' => $value->buyer->name,
                'unique_id' => $value->unique_id,
                'buyer_id' => $value->buyer->id,
                'supplier' => $value->supplier->name,
                'booking_type' => 'main',
                'details_breakdown' => $this->formateDetails($value)
            ];
        });


        $shortFabricBookings = collect(FabricBookingService::shortFabricData($request))->map(function ($value) {
            return [
                'wo_no' => $value->unique_id,
                'wo_date' => $value->booking_date,
                'unique_id' => $value->unique_id,
                'buyer_id' => $value->buyer->id,
                'buyer' => $value->buyer->name,
                'supplier' => $value->supplier->name,
                'booking_type' => 'short',
                'details_breakdown' => $this->formateDetails($value)
            ];
        });


        $fabricBookings = array_merge([$mainFabricBookings], [$shortFabricBookings]);

        return collect($fabricBookings)->flatten(1);
    }

    public function fetchYarnBookingsDate(Request $request)
    {
        $orders = YarnPurchaseOrder::query();
        $yarnPurchases = collect(YarnPurchaseOrderService::formatData2($orders, $request))->map(function ($value) {

            return [
                'year' => $value->wo_date,
                'type' => 'main',
                'wo_no' => $value->wo_no,
                'wo_date' => $value->wo_date,
                'buyer' => $value->buyer->name,
                'buyer_id' => $value->buyer->id,
                'supplier' => $value->supplier->name,
                'supplier_id' => $value->supplier->id ?? '',
                'purchase_details' => $value->details,
                'shipment_date' => $value->delivery_date

            ];
        });

        return $yarnPurchases;
    }

    private function formateDetails($value)
    {
        return collect($value->detailsBreakdown)->map(function ($item) {
            $order = Order::query()->with('purchaseOrders')->where('job_no', $item['job_no'])->first();
            $po = explode(',', $item['po_no']);

            $purchase_order_ids = collect($order['purchaseOrders'])->whereIn('po_no', $po)->pluck('id')->unique()->implode(',');
            $style_name = $order['style_name'] ?? '';
            $order_id = $order['id'] ?? '';

            $budget = $item['budget'] ?: null;
            $fabric = $budget['fabricCosting'] ?: null;
            $costings = $fabric['details'] ?: null;
            $fabricForm = $costings ? collect($costings)->pluck('fabricForm') : null;
            $gsm = $item['gsm'];
            $body_part_id = $item['body_part_id'];
            $dia_type = $item['dia_type'];
            $fabricCompositionValue = $item['construction'] . ' [' . $item['composition'] . ']';
            $fabricItem = collect($fabricForm)->flatten(1)->where('gsm', '=', $gsm)->where('body_part_id', '=', $body_part_id)->
            where('dia_type', '=', $dia_type)->where('fabric_composition_value', '=', $fabricCompositionValue)->first();

            $fabric_composition_id = $fabricItem['fabric_composition_id'] ?? null;
            $contrast_colors = $fabricItem ? collect($fabricItem['greyConsForm']['details'])->pluck('contrast')->flatten(1)->pluck('fabric_color_name')->unique()->implode(',') : null;
            $contrast_color_id = $fabricItem ? collect($fabricItem['greyConsForm']['details'])->pluck('contrast')->flatten(1)->pluck('fabric_color_id')->unique()->implode(',') : null;

            unset($item['budget']);
            array_add($item, 'style_name', $style_name);
            array_add($item, 'order_id', $order_id);
            array_add($item, 'contrast_colors', $contrast_colors);
            array_add($item, 'purchase_order_ids', $purchase_order_ids);
            array_add($item, 'fabric_composition_id', $fabric_composition_id);
            array_add($item, 'contrast_color_id', $contrast_color_id);
            return $item;
        });
    }

    public function fetchEmbellishmentData(Request $request)
    {
        if ($request->get('goods_rcv_status') == 2) {
//            $embldata = TrimsBookingService::beforeGoodReceive(EmbellishmentWorkOrder::query(), $request);
            $embldata = EmbellishmentWorkOrder::query();
            $emblDetails = EmbellishmentService::formatData($embldata, $request);
        } elseif ($request->get('goods_rcv_status') == 1) {
//            $embldata = TrimsBookingService::afterGoodReceive(EmbellishmentWorkOrder::query(), $request);
            $embldata = EmbellishmentWorkOrder::query();
            $emblDetails = EmbellishmentService::formatData($embldata, $request);
        } else {
            $emblDetails = [];
        }
//        $emblDetails = EmbellishmentService::formatData(EmbellishmentWorkOrder::query(), $request);
//        embellishmentType

        return collect($emblDetails)->map(function ($value) {
            return [
                'year' => $value->booking_date,
                'type' => 'main',
                'wo_date' => $value->booking_date,
                'buyer' => $value->buyer->name,
                'supplier' => $value->supplier->name,
                'booking_details' => $value->bookingDetails,

            ];
        });
    }


    public function view(ProformaInvoice $invoice)
    {
        $invoice = $invoice->load('supplier');
        $ds_items = DsItem::query()->with('uomDetails')->get();
        $terms = TermsAndCondition::query()->where('page_name', self::PAGE_NAME)->get();

        return view('commercial::proforma-invoice.view', compact('invoice', 'terms','ds_items'));
    }

    public function pdf(ProformaInvoice $invoice)
    {

        $invoice = $invoice->load('supplier');
        $signature = ReportSignatureService::getSignatures("PROFORMA INVOICE");
        $terms = TermsAndCondition::query()->where('page_name', self::PAGE_NAME)->get();
        $dateTime = Carbon::make($invoice->created_at)->toFormattedDateString();
        $ds_items = DsItem::query()->with('uomDetails')->get();

        $pdf = PDF::loadView('commercial::proforma-invoice.pdf', [
            'header-html' => view('skeleton::pdf.header'),
            'invoice' => $invoice,
            'signature' => $signature,
            'date_time' => $dateTime,
            'terms' => $terms,
            'ds_items' => $ds_items
        ])->setPaper('a4', 'landscape')->setOptions([
            'header-html' => view('skeleton::pdf.header'),
            'footer-html' => view('skeleton::pdf.footer'),
        ]);

        return $pdf->stream("{$invoice->id}_invoice.pdf");

    }

    public function file_view(ProformaInvoice $invoice)
    {

        $path = storage_path('/app/public/' . $invoice->file_path);
        // header
        $header = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $invoice->file_path . '"'
        ];
        return response()->file($path, $header);

    }
}
