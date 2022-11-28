<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers\PriceQuotation;

use App\Http\Controllers\Controller;
use App\Library\Services\Validation;
use Exception;
use Excel;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Session;
use SkylarkSoft\GoRMG\Merchandising\Actions\PriceQuotationNotification;
use SkylarkSoft\GoRMG\Merchandising\Actions\PriceQuotationFilterFormat;
use SkylarkSoft\GoRMG\Merchandising\Exports\PriceQuotation\PriceQuotationExcel;
use SkylarkSoft\GoRMG\Merchandising\Actions\StyleEntryAction;
use SkylarkSoft\GoRMG\Merchandising\Models\Budget;
use SkylarkSoft\GoRMG\Merchandising\Models\CostingDetails;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PriceQuotation;
use SkylarkSoft\GoRMG\Merchandising\Models\PriceQuotationAttachment;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\Merchandising\Models\QuotationInquiry;
use SkylarkSoft\GoRMG\Merchandising\Requests\PriceQuotationRequest;
use SkylarkSoft\GoRMG\Merchandising\Services\ChartService\Consumers\PriceQuotationChartService;
use SkylarkSoft\GoRMG\Merchandising\Services\PriceQuotation\CostingMultiplierService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\BuyingAgentMerchantModel;
use SkylarkSoft\GoRMG\SystemSettings\Models\BuyingAgentModel;
use SkylarkSoft\GoRMG\SystemSettings\Models\ColorRange;
use SkylarkSoft\GoRMG\SystemSettings\Models\Currency;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsItem;
use SkylarkSoft\GoRMG\SystemSettings\Models\Incoterm;
use SkylarkSoft\GoRMG\SystemSettings\Models\MerchandisingVariableSettings;
use SkylarkSoft\GoRMG\SystemSettings\Models\ProductCateory;
use SkylarkSoft\GoRMG\SystemSettings\Models\ProductDepartments;
use SkylarkSoft\GoRMG\SystemSettings\Models\Season;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class PriceQuotationController extends Controller
{
    /**
     * @param Request $request
     * @return Application|\Illuminate\Contracts\View\Factory|View
     */
    public function index(Request $request)
    {
        $sort = request('sort') ?? 'desc';
        $paginateNumber = request('paginateNumber') ?? 15;
        $searchedOrders = 15;
        $price_quotations = PriceQuotation::query()
            ->userWiseBuyerFilter()
            ->factoryWiseFilter()
            ->with([
                'attachments',
                'quotationInquiry',
                'buyer',
                'productDepartment',
                'season',
                'currency',
                'factory:id,factory_short_name,factory_name',
                'createdBy:id,screen_name',
            ])
            ->orderBy('id', $sort)
            ->filter($request->get('search'))
            ->paginate($paginateNumber);

        $chartService = new PriceQuotationChartService();
        $dashboardOverview = $chartService->dashboardOverview();

        return view('merchandising::price_quotation.list', [
            'price_quotations' => $price_quotations,
            "search" => $request->get('search'),
            'chartService' => $chartService,
            'dashboardOverview' => $dashboardOverview,
            'paginateNumber' => $paginateNumber,
            'searchedOrders' => $searchedOrders
        ]);
    }

    /**
     * @param Request $request
     * @return Application|\Illuminate\Contracts\View\Factory|View
     */
    public function orderListExcelAll(Request $request, PriceQuotationFilterFormat $quotationFilterFormat)
    {
        $search = $request->get('search');
        $sort = $request->get('sort') ?? 'DESC';
        $quotations = $quotationFilterFormat->handleAll($search, $sort);
        return Excel::download(new PriceQuotationExcel($quotations), 'quotation-list-all.xlsx');
    }

    public function orderListExcelList(Request $request, PriceQuotationFilterFormat $quotationFilterFormat)
    {
        $search = $request->get('search');
        $sort = $request->get('sort') ?? 'DESC';
        $page = $request->get('page');
        $paginateNumber = $request->get('paginateNumber');
        $quotations = $quotationFilterFormat->handle($search, $sort, $page, $paginateNumber);
        return Excel::download(new PriceQuotationExcel($quotations), 'quotation-list-all.xlsx');
    }

    public function mainSectionForm(Request $request)
    {
        if ($request->get('quotation_id')) {
            $quotation_id = $request->get('quotation_id');
        }
        $price_quotation = $request->get('quotation_id') ?
            PriceQuotation::query()->with(['styleEntry', 'attachments'])
                ->where('quotation_id', $request->get('quotation_id'))
                ->first() : null;
        $quotation_inquiries = [];
        QuotationInquiry::with('buyer')->select('quotation_id', 'id', 'buyer_id', 'style_name', 'style_description')->get()
            ->map(function ($item) use (&$quotation_inquiries) {
                $quotation_inquiries[$item->id] = $item->quotation_id . '[' . $item->buyer->name . ',' . $item->style_name . ']';
            });
        $factories = Factory::query()->userWiseFactories()->pluck('factory_name', 'id');
        $buying_agent_merchant = BuyingAgentMerchantModel::query()->when($price_quotation, function ($query) use ($price_quotation) {
            $query->where('id', $price_quotation->bh_merchant);
        })->pluck('buying_agent_merchant_name', 'id');
//        $team_member = Team::with("member")->groupBy("member_id")->get()->pluck("member.full_name", "member.id");
        $buyers = Buyer::all()->pluck('name', 'id');
        $product_departments = ProductDepartments::all()->pluck('product_department', 'id');
        $seasons = $price_quotation ? Season::where('factory_id', $price_quotation['factory_id'])->where('buyer_id', $price_quotation['buyer_id'])->pluck('season_name', 'id') : [];
        $style_uoms = PriceQuotation::STYLE_UOM;
        $costing_per_vals = PriceQuotation::COSTING_PER;
        $buying_agents = BuyingAgentModel::all()->pluck('buying_agent_name', 'id');
        $regions = PriceQuotation::REGIONS;
        $currencies = Currency::all()->pluck('currency_name', 'id');
        $incoterms = Incoterm::all()->pluck('incoterm', 'id');
        $color_ranges = ColorRange::all()->pluck('name', 'id');
        $ready_to_approve_status = PriceQuotation::READY_TO_APPROVE;
        $garment_items = GarmentsItem::all()->pluck('name', 'id');
        $quot_date = date('Y-m-d');

        // for item modal
        $garmentsItem = null;
        $productCategories = ProductCateory::pluck('category_name', 'id');

        return view('merchandising::price_quotation.main_section_form', [
            'quotation_id' => $quotation_id ?? '',
            'quotation_inquiries' => $quotation_inquiries,
            'factories' => $factories,
            'buying_agent_merchant' => $buying_agent_merchant,
//            'team_member' => $team_member,
            'product_departments' => $product_departments,
            'seasons' => $seasons,
            'style_uoms' => $style_uoms,
            'costing_per_vals' => $costing_per_vals,
            'buying_agents' => $buying_agents,
            'regions' => $regions,
            'currencies' => $currencies,
            'incoterms' => $incoterms,
            'color_ranges' => $color_ranges,
            'ready_to_approve_status' => $ready_to_approve_status,
            'garment_items' => $garment_items,
            'quot_date' => $quot_date,
            'price_quotation' => $price_quotation,
            "buyers" => $buyers,
            'garmentsItem' => $garmentsItem,
            'productCategories' => $productCategories,
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */


    public function checkItemInFabricTrim(Request $request): JsonResponse
    {
        try {
            $costingDetails = CostingDetails::query()
                ->where("price_quotation_id", $request->get('price_quotation_id'));

            $get_fabric_cost_value = $costingDetails->where("type", "fabric_costing")
                ->first();

            $get_trims_cost_value = $costingDetails->where("type", "trims_costing")
                ->first();

            $get_trims_cost = $get_trims_cost_value ? $get_trims_cost_value->toArray() : [];
            $get_fabric_cost = $get_fabric_cost_value ? $get_fabric_cost_value->toArray() : [];

            $get_trims_cost_details = isset($get_trims_cost['details']) ? $get_trims_cost['details']['details'] : [];
            $get_fabric_cost_details = isset($get_fabric_cost['details']) ? $get_fabric_cost['details']['details']['fabricForm'] : [];
            $check_trims_cost = collect($get_trims_cost_details)->where("gmts_item_id", $request->item_id)->count();
            $check_fabric_cost = collect($get_fabric_cost_details)->where("garment_item_id", $request->item_id)->count();
            $response = $check_fabric_cost || $check_trims_cost;

            return response()->json($response, Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param Request $request
     * @return Application|\Illuminate\Contracts\View\Factory|View|RedirectResponse
     */
    public function costingSectionForm(Request $request)
    {
        if (!$request->get('quotation_id')) {
            Session::flash('alert-danger', 'Please update the main section!');

            return back()->withInput();
        }
        $price_quotation = $request->get('quotation_id') ? PriceQuotation::where('quotation_id', $request->quotation_id)->first() : null;

        $variable_data = MerchandisingVariableSettings::query()
            ->where("factory_id", $price_quotation->factory_id)
            ->where("buyer_id", $price_quotation->buyer_id)
            ->first();

        $variable_status = $variable_data->variables_details['price_quotation_approval_maintain'] ?? 2;
        if ($variable_status == 1 && $price_quotation->is_approve != 1) {
            Session::flash('alert-danger', 'Please approve first!');

            return back()->withInput();
        }

        return view('merchandising::price_quotation.costing_section_form', [
            'price_quotation' => $price_quotation,
        ]);
    }

    /**
     * @param PriceQuotationRequest $request
     * @param StyleEntryAction $styleEntryAction
     * @return Application|RedirectResponse|Redirector
     * @throws Throwable
     */

    public function store(PriceQuotationRequest $request, StyleEntryAction $styleEntryAction)
    {
        try {
            DB::beginTransaction();
            $item_details = [];
            $garment_items_count = 0;
            $total_ratio = 0;
            $total_smv = 0;
            if ($request->has('garment_item_id')) {
                $garment_items_count = count($request->garment_item_id);
                foreach ($request->garment_item_id as $key => $garment_item_id) {
                    $total_ratio += $request->item_ratio[$key];
                    $total_smv += ($request->item_ratio[$key] * $request->smv_given[$key]);
                    $item_details[] = [
                        'garment_item_id' => $garment_item_id,
                        'item_ratio' => $request->item_ratio[$key],
                        'smv' => $request->smv[$key],
                        'smv_given' => $request->smv_given[$key],
                    ];
                }
            }
            if ($request->has('total_ratio') && $request->has('total_smv')) {
                $item_details[] = [
                    'total_item' => $garment_items_count,
                    'total_ratio' => $total_ratio,
                    'total_smv' => $total_smv,
                ];
            }
            $price_quotation = new PriceQuotation();
            $formRequest = $request->all();
            $formRequest['item_details'] = $item_details;
            $formRequest['costing_multiplier'] = CostingMultiplierService::generate($request->get('style_uom'), $request->get('costing_per'));
            if ($request->hasFile('image')) {
                $time = time();
                $image = $request->image;
                $image->storeAs('price_quotation_images', $time . $image->getClientOriginalName());
                $formRequest['image'] = $time . $image->getClientOriginalName();
            }
            $price_quotation->fill($formRequest)->save();
            if (collect($request->file('files'))->count()) {
                $this->multiAttachmentUpdate($price_quotation->id, $request->file('files'));
            }
            $styleEntryAction->execute($price_quotation, 'price_quotation_id', $request);
            DB::commit();
            Session::flash('alert-success', 'Price Quotation Created Successfully!');
            return redirect('price-quotations/main-section-form?quotation_id=' . $price_quotation->quotation_id);
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('alert-danger', "Something went wrong!{$e->getMessage()}");

            return redirect()->back();
        }
    }

    /**
     * @param $id
     * @param PriceQuotationRequest $request
     * @param StyleEntryAction $styleEntryAction
     * @return Application|RedirectResponse|Redirector
     * @throws Throwable
     */

    public function update($id, PriceQuotationRequest $request, StyleEntryAction $styleEntryAction)
    {
        try {
            DB::beginTransaction();
            $item_details = [];
            $total_ratio = 0;
            $total_smv = 0;
            if ($request->has('garment_item_id')) {
                foreach ($request->garment_item_id as $key => $garment_item_id) {
                    $total_ratio += $request->item_ratio[$key];
                    $total_smv += ($request->item_ratio[$key] * $request->smv_given[$key]);
                    $item_details[] = [
                        'garment_item_id' => $garment_item_id,
                        'item_ratio' => $request->item_ratio[$key],
                        'smv' => $request->smv[$key],
                        'smv_given' => $request->smv_given[$key] ?? '',
                    ];
                }
                $item_details[] = [
                    'total_item' => count($request->garment_item_id),
                    'total_ratio' => collect($item_details)->sum('item_ratio'),
                    'total_smv' => collect($item_details)->sum('smv'),
                ];
            }

            $price_quotation = PriceQuotation::query()->findOrFail($id);
            $formRequest = $request->all();
            $formRequest['item_details'] = $item_details;
            $formRequest['costing_multiplier'] = CostingMultiplierService::generate(
                $request->get('style_uom'),
                $request->get('costing_per')
            );

//            if ($request->hasFile('file')) {
//                if (isset($price_quotation->file) &&
//                    Storage::disk('public')->exists('/price_quotation_files/' . $price_quotation->file)) {
//                    Storage::delete('price_quotation_files/' . $price_quotation->file);
//                }
//                $time = time();
//                $file = $request->file;
//                $file->storeAs('price_quotation_files', $time . $file->getClientOriginalName());
//                $formRequest['file'] = $time . $file->getClientOriginalName();
//            }

            if (collect($request->file('files'))->count()) {
                $this->multiAttachmentUpdate($price_quotation->id, $request->file('files'));
            }

            if ($request->hasFile('image')) {
                if (isset($price_quotation->image) &&
                    Storage::disk('public')->exists('/price_quotation_images/' . $price_quotation->image)) {
                    Storage::delete('price_quotation_images/' . $price_quotation->image);
                }
                $time = time();
                $image = $request->image;
                $image->storeAs('price_quotation_images', $time . $image->getClientOriginalName());
                $formRequest['image'] = $time . $image->getClientOriginalName();
            }

            $price_quotation->fill($formRequest)->save();
            PriceQuotationNotification::send($price_quotation);
            $styleEntryAction->execute($price_quotation, 'price_quotation_id', $request);
            if ($request->get('costing_section')) {
                return redirect()->route('costing_section', ['quotation_id' => $request->get('quotation_id')]);
            }
            DB::commit();
            Session::flash('alert-success', 'Data stored successfully!');
            return redirect('price-quotations/main-section-form?quotation_id=' . $price_quotation->quotation_id);
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('alert-danger', "Something went wrong!{$e->getMessage()}");
            return redirect()->back();
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function deletePriceQuotationFiles(Request $request): JsonResponse
    {
        try {
            $price_quotation = PriceQuotation::query()
                ->where("quotation_id", $request->get('quotation_id'))
                ->first();
            if ($request->type == "image") {
                if (isset($price_quotation->image)) {
                    $image_name_to_delete = $price_quotation->image;
                    if (Storage::disk('public')->exists('/price_quotation_images/' . $image_name_to_delete)
                        && $image_name_to_delete) {
                        Storage::delete('price_quotation_images/' . $image_name_to_delete);
                    }
                    $price_quotation->image = null;
                }
            } else {
                if (isset($price_quotation->file)) {
                    $file_name_to_delete = $price_quotation->file;
                    if (Storage::disk('public')->exists('/price_quotation_files/' . $file_name_to_delete)
                        && $file_name_to_delete) {
                        Storage::delete('price_quotation_files/' . $file_name_to_delete);
                    }
                    $price_quotation->file = null;
                }
            }
            $price_quotation->save();

            return response()->json([
                "message" => "Deleted Successfully",
            ], Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param $quotation_id
     * @return JsonResponse
     */

    public function getPriceQuotationCostingSummary($quotation_id): JsonResponse
    {
        if (!$quotation_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'No quid found',
                'errors' => null,
            ]);
        }

        try {
            $price_quotation = PriceQuotation::where('quotation_id', $quotation_id)->first();

            return response()->json([
                'status' => 'success',
                'message' => 'Success',
                'errors' => null,
                'price_quotation' => $price_quotation,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'No quid found',
                'errors' => $e->getMessage(),
            ]);
        }
    }

    /**
     * @param $quotation_id
     * @param Request $request
     * @return JsonResponse
     * @throws Throwable
     */

    public function priceQuotationCostingSummary($quotation_id, Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            PriceQuotation::query()
                ->where('quotation_id', $quotation_id)
                ->update([
                    'confirm_date' => $request->confirm_date ?? null,
                    'fab_cost' => $request->fab_cost ?? null,
                    'fab_cost_prcnt' => $request->fab_cost_prcnt ?? null,
                    'trims_cost' => $request->trims_cost ?? null,
                    'trims_cost_prcnt' => $request->trims_cost_prcnt ?? null,
                    'embl_cost' => $request->embl_cost ?? null,
                    'embl_cost_prcnt' => $request->embl_cost_prcnt ?? null,
                    'gmt_wash' => $request->gmt_wash ?? null,
                    'gmt_wash_prcnt' => $request->gmt_wash_prcnt ?? null,
                    'comml_cost' => $request->comml_cost ?? null,
                    'comml_cost_prcnt' => $request->comml_cost_prcnt ?? null,
                    'lab_cost' => $request->lab_cost ?? null,
                    'lab_cost_prcnt' => $request->lab_cost_prcnt ?? null,
                    'inspect_cost' => $request->inspect_cost ?? null,
                    'inspect_cost_prcnt' => $request->inspect_cost_prcnt ?? null,
                    'cm_cost' => $request->cm_cost ?? null,
                    'cm_cost_prcnt' => $request->cm_cost_prcnt ?? null,
                    'freight_cost' => $request->freight_cost ?? null,
                    'freight_cost_prcnt' => $request->freight_cost_prcnt ?? null,
                    'currier_cost' => $request->currier_cost ?? null,
                    'currier_cost_prcnt' => $request->currier_cost_prcnt ?? null,
                    'certif_cost' => $request->certif_cost ?? null,
                    'certif_cost_prcnt' => $request->certif_cost_prcnt ?? null,
                    'common_oh' => $request->common_oh ?? null,
                    'common_oh_prcnt' => $request->common_oh_prcnt ?? null,
                    'total_cost' => $request->total_cost ?? null,
                    'total_cost_prcnt' => $request->total_cost_prcnt ?? null,
                    'final_cost_pc_set' => $request->final_cost_pc_set ?? null,
                    'final_cost_pc_set_prcnt' => $request->final_cost_pc_set_prcnt ?? null,
                    'asking_profit_pc_set' => $request->asking_profit_pc_set ?? null,
                    'asking_profit_pc_set_prcnt' => $request->asking_profit_pc_set_prcnt ?? null,
                    'asking_quoted_pc_set' => $request->asking_quoted_pc_set ?? null,
                    'asking_quoted_pc_set_prcnt' => $request->asking_quoted_pc_set_prcnt ?? null,
                    'revised_price_pc_set' => $request->revised_price_pc_set ?? null,
                    'revised_price_pc_set_prcnt' => $request->revised_price_pc_set_prcnt ?? null,
                    'confirm_price_pc_set' => $request->confirm_price_pc_set ?? null,
                    'confirm_price_pc_set_prcnt' => $request->confirm_price_pc_set_prcnt ?? null,
                    'price_bef_commn_dzn' => $request->price_bef_commn_dzn ?? null,
                    'price_bef_commn_dzn_prcnt' => $request->price_bef_commn_dzn_prcnt ?? null,
                    'prod_cost_dzn' => $request->prod_cost_dzn ?? null,
                    'prod_cost_dzn_prcnt' => $request->prod_cost_dzn_prcnt ?? null,
                    'margin_dzn' => $request->margin_dzn ?? null,
                    'margin_dzn_prcnt' => $request->margin_dzn_prcnt ?? null,
                    'commi_dzn' => $request->commi_dzn ?? null,
                    'commi_dzn_prcnt' => $request->commi_dzn_prcnt ?? null,
                    'price_with_commn_dzn' => $request->price_with_commn_dzn ?? null,
                    'price_with_commn_dzn_prcnt' => $request->price_with_commn_dzn_prcnt ?? null,
                    'price_with_commn_pcs' => $request->price_with_commn_pcs ?? null,
                    'price_with_commn_pcs_prcnt' => $request->price_with_commn_pcs_prcnt ?? null,
                    'target_price' => $request->target_price ?? null,
                    'target_price_prcnt' => $request->target_price_prcnt ?? null,
                ]);

            // Lock PO
            $priceQuotationId = PriceQuotation::query()->where('quotation_id', $quotation_id)->first()->id;
            $orders = Order::with('purchaseOrders')->where('order_copy_from', $priceQuotationId)->get();
            foreach ($orders as $order) {
                foreach ($order['purchaseOrders'] as $po) {
                    PurchaseOrder::query()->find($po->id)->update([
                        'is_locked' => true,
                    ]);
                }
            }
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Data stored successfully!',
                'error' => null,
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'danger',
                'message' => 'Something went wrong!',
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * @param $id
     * @return RedirectResponse
     * @throws Throwable
     */
    // public function delete($id): RedirectResponse
    public function delete($id)
    {

        // Delete Validation
        $validate = Validation::check($id, [Order::class, 'price_quotation_id']);
        $validate2 = Validation::check(PriceQuotation::find($id)->quotation_id, [Budget::class, 'quotation_id']);
        if (!$validate || !$validate2) {
            return back()->with('alert-danger', "You Can Not Delete This Item!");
        }

        try {
            DB::beginTransaction();
            $price_quotation = PriceQuotation::query()->where("id", $id)->first();
            CostingDetails::query()->where("price_quotation_id", $price_quotation->id)->delete();
            $price_quotation->delete();
            Session::flash('alert-success', 'Price Quotation Deleted Successfully!');
            DB::commit();
        } catch (Exception $exception) {
            Session::flash('alert-danger', 'Something Went Wrong!');
        }

        return back();
    }


    /**
     * @param $id
     * @return RedirectResponse
     * @throws Throwable
     */
    public function copyPriceQuotation($id): RedirectResponse
    {
        DB::beginTransaction();
        $price_quotation = PriceQuotation::query()->where("quotation_id", $id)->first();
        $costingDetails = CostingDetails::query()->where("price_quotation_id", $price_quotation->id)->get();
        $fabric_costing_details = $costingDetails->where("type", "fabric_costing")->first();
        $trim_costing_details = $costingDetails->where("type", "trims_costing")->first();
        $embellishment_costing_details = $costingDetails->where("type", "embellishment_cost")->first();
        $wash_costing_details = $costingDetails->where("type", "wash_cost")->first();
        $commercial_costing_details = $costingDetails->where("type", "commercial_cost")->first();

        $existingPriceQuotImg = $price_quotation->image;

        $copy_price_quotation = $price_quotation->replicate();
        $copy_price_quotation->style_name = null;
        $copy_price_quotation->style_desc = null;

        if (Storage::disk('public')->exists('/price_quotation_images/' . $existingPriceQuotImg) && $existingPriceQuotImg) {
            $existingPriceQuotImgFullPath = '/price_quotation_images/' . $existingPriceQuotImg;
            $extension = pathinfo(storage_path($existingPriceQuotImgFullPath), PATHINFO_EXTENSION);
            $imageName = time() . '.' . $extension;
            Storage::copy($existingPriceQuotImgFullPath, '/price_quotation_images/' . $imageName);
            $copy_price_quotation->image = $imageName;
        }

        $copy_price_quotation->save();

        if ($fabric_costing_details) {
            $copy_fabric_costing_details = $fabric_costing_details->replicate();
            $copy_fabric_costing_details->price_quotation_id = $copy_price_quotation->id;
            $copy_fabric_costing_details->save();
        }

        if ($trim_costing_details) {
            $copy_trim_costing_details = $trim_costing_details->replicate();
            $copy_trim_costing_details->price_quotation_id = $copy_price_quotation->id;
            $copy_trim_costing_details->save();
        }

        if ($embellishment_costing_details) {
            $copy_embellishment_costing_details = $embellishment_costing_details->replicate();
            $copy_embellishment_costing_details->price_quotation_id = $copy_price_quotation->id;
            $copy_embellishment_costing_details->save();
        }

        if ($wash_costing_details) {
            $copy_wash_costing_details = $wash_costing_details->replicate();
            $copy_wash_costing_details->price_quotation_id = $copy_price_quotation->id;
            $copy_wash_costing_details->save();
        }

        if ($commercial_costing_details) {
            $copy_commercial_costing_details = $commercial_costing_details->replicate();
            $copy_commercial_costing_details->price_quotation_id = $copy_price_quotation->id;
            $copy_commercial_costing_details->save();
        }
        DB::commit();

        return redirect()->route("price_quotation_main_section", ['quotation_id' => $copy_price_quotation->quotation_id]);
    }

    /**
     * @param $factoryId
     * @param $buyerId
     * @return JsonResponse
     */
    public function loadSeasons($factoryId, $buyerId): JsonResponse
    {
        $seasons = Season::query()->where('buyer_id', $buyerId)
            ->where('factory_id', $factoryId)
            ->get();

        return response()->json($seasons);
    }

    /**
     * @param $factoryId
     * @param $buyerId
     * @return JsonResponse
     */
    public function loadBuyingAgentMerchant($factoryId, $buyingAgentId): JsonResponse
    {
        $buyingAgentMerchants = BuyingAgentMerchantModel::query()->where('buying_agent_id', $buyingAgentId)->where('factory_id', $factoryId)->get();

        return response()->json($buyingAgentMerchants);
    }


    public function multiAttachmentUpdate($id, $files)
    {
        $attachments = [];
        foreach ($files as $file) {
            $time = time();
            $file->storeAs('price_quotation_files', $time . '_' . $file->getClientOriginalName());
            $attachments[] = [
                'price_quotation_id' => $id,
                'name' => $file->getClientOriginalName(),
                'path' => $time . '_' . $file->getClientOriginalName(),
                'type' => 'file',
            ];
        }
        PriceQuotationAttachment::insert($attachments);
    }

    public function attachmentDownload($id, $attachmentId)
    {
        $attachment = PriceQuotationAttachment::query()->findOrFail($attachmentId);
        return Storage::download('price_quotation_files/' . $attachment->path);
    }

    public function deleteAttachment($id, $attachmentId)
    {
        $price_quotation = PriceQuotation::query()->findOrFail($id);
        $attachment = PriceQuotationAttachment::query()
            ->where('price_quotation_id', $price_quotation->id)
            ->findOrFail($attachmentId);

        if (isset($attachment->path) &&
            Storage::disk('public')->exists('/price_quotation_files/' . $attachment->path)) {
            Storage::delete('price_quotation_files/' . $attachment->path);
            $attachment->deleteOrFail();
        }
        return \response()->json([
            'status' => 'success'
        ]);
    }
}
