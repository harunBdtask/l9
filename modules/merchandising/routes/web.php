<?php

use App\Mail\InformingMail;
use App\MailChannels\Mailers\POShipmentReminder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;
use SkylarkSoft\GoRMG\Merchandising\Controllers\Api\CareLabelTypesApiController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\Api\FabricCompositionTypesApiController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\Api\FabricConstructionApiController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\Api\FeatureVersionCheckController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\Api\TrimsBookingPreviousSensitivityApiController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\ApprovalApiController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\AskingProfitCalculationApiController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\BodyPartApiController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\Bookings\BodyPartController as BookingsBodyPartController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\Bookings\BookingSearchController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\Bookings\BudgetWiseCopyFabricBookingController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\Bookings\CollarCuffApiController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\Bookings\FabricBookingController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\Bookings\FabricBookingDetailsController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\Bookings\FabricServiceBookingController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\Bookings\FabricServiceBookingDescriptionController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\Bookings\FabricShortBookingController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\Bookings\ShortBookingSearchController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\Bookings\ShortFabricBookingDetailsController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\Bookings\TrimsBookingBreakDownController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\Bookings\TrimsBookingColorSizeController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\Bookings\TrimsBookingController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\Bookings\TrimsContrastColorApiController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\Bookings\TrimsItemWiseGroupFieldsApiController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\Budgets\API\BudgetCostingApiController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\Budgets\API\BudgetDependentApiController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\Budgets\API\BudgetTemplateApiController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\Budgets\BudgetController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\Budgets\BudgetCostingController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\Budgets\BudgetDownloadAbleController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\Budgets\BudgetGreyConsBreakdownController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\Budgets\CommercialBudgetCostingController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\Budgets\CommissionCostingController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\Budgets\EmbellishmentCostingController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\Budgets\OrderWiseBudgetCopyController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\Budgets\StripController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\Budgets\TechPackApiController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\Budgets\TechPackTagsApiController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\Budgets\TrimsBudgetController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\Budgets\WashBudgetCostingController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\Budgets\WashCostingController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\BuyerPermissionCheckApiController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\BuyerSeasonColorOrderReportController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\BuyerSeasonOrderReportController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\CmCostApiController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\ColorApiController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\ColorSizeBreakDownApiController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\ColorTypesApiController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\CommercialCostController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\CommonAPIController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\ConsumptionBasisApiController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\CostingDetailsApiController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\CostingSummerApiController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\DiaTypesApiController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\EmbellishmentCostController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\FabricCompositionDetailsApi;
use SkylarkSoft\GoRMG\Merchandising\Controllers\FabricCostDetailsController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\FabricDescriptionApiController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\FabricNatureApiController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\FabricSourceApiController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\FormsApiController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\GatePassChallan\GatePassChallanApiController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\GatePassChallan\GatePassChallanController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\ItemGroupsApiController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\NewReport\OrderRecapReportV2Controller;
use SkylarkSoft\GoRMG\Merchandising\Controllers\OrderInHandReportController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\Orders\API\BudgetCostingUpdateController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\Orders\API\BuyerWiseStyleApiController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\Orders\API\OrderAssociateBudgetApiController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\Orders\API\OrderDependentApiController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\Orders\API\OrderRepeatApiController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\Orders\API\PoQuantityMatrixApiController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\Orders\API\PriceQuotationFilterApiController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\Orders\BookedColorsAndSizesForPoController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\Orders\OrderAllPoApprovalController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\Orders\OrderApprovalStatusApiController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\Orders\OrderController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\Orders\OrderDownloadAbleController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\Orders\OrderPoApprovalController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\Orders\OrderRepeatController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\Orders\OrderReportController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\Orders\ShipmentWiseOrderReportController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\Orders\StyleWisePoApiController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\Orders\UsedStyleApiController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\OrderWiseColorApiController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\POFilesController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\POFilesExcelController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\PriceQuotation\PQCommissionCostController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\PriceQuotation\PriceQuotationAdditionalCostingController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\PriceQuotation\PriceQuotationApprovalController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\PriceQuotation\PriceQuotationController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\PriceQuotation\PriceQuotationReportController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\ProcessApiController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\ProTracker\BudgetCostingDetailsController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\ProTracker\ProTrackerDataController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\PurchaseOrderController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\PurchaseRequisitions\YarnPurchaseOrderApiController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\PurchaseRequisitions\YarnPurchaseRequisitionsController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\QuotationInquiryController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\QuotationItemsApiController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\ReportController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\Reports\BomReportController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\Reports\BudgetWiseWOReportController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\Reports\ColorWiseOrderVolumeReportController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\Reports\FabricBookingDetailsReportController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\Reports\FinalCostingReportController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\Reports\OrderRecapReportController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\Reports\OrderVolumeReportController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\Reports\PriceComparisonReportController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\Reports\SampleSummaryReportController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\SalesTargetController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\SampleController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\SampleRequisitionController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\Samples\SampleBookingForBeforeOrderController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\Samples\SampleBookingForConfirmOrderController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\Samples\SampleListController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\Samples\SampleRequiredAccessoryController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\Samples\SampleRequisitionDetailController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\Samples\SampleRequisitionFabricDetailController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\SampleTrimsBookingController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\ShortTrimsBookings\ShortTrimsBookingBreakDownController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\ShortTrimsBookings\ShortTrimsBookingColorSizeController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\ShortTrimsBookings\ShortTrimsBookingController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\SizeApiController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\StyleAuditReportController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\StyleEntry\StyleGenerationController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\SupplierApiController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\SuppliersApiController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\TechPackFilesController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\TechPackSampleFileController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\TemplateApiController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\TrimCostController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\UserWiseBuyerApiController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\Variables\VariableController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\WashPriceQuotationController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\WorkOrders\EmbellishmentColorSizeController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\WorkOrders\EmbellishmentSearchApiController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\WorkOrders\EmbellishmentWorkOrderBreakDownController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\WorkOrders\EmbellishmentWorkOrderController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\YarnCompositionApiController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\YarnCountApiController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\YarnPurchase\RequisitionController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\YarnPurchase\YarnPurchaseOrderController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\YarnTypeApiController;
use SkylarkSoft\GoRMG\Merchandising\Services\LeadTimeCalculator;
use SkylarkSoft\GoRMG\SystemSettings\Controllers\BodyPartController;
use SkylarkSoft\GoRMG\SystemSettings\Controllers\TermsAndConditionController;
use SkylarkSoft\GoRMG\SystemSettings\Controllers\UserManagerController;

Route::group(['middleware' => ['web', 'auth', 'menu-auth']], function () {
    // Api
    Route::group(['namespace' => 'VariableController', 'prefix' => '/api/variables'], function () {
        Route::get('/{factory_id}/{buyer_id}', [VariableController::class, 'load']);
    });

    /* Version 3 sample development */
    Route::get('sample', [SampleController::class, 'sampleList']);
    Route::get('sample/create', [SampleController::class, 'sampleCreate']);
    Route::post('sample/store', [SampleController::class, 'sampleStore']);
    Route::get('sample-details/{id}', [SampleController::class, 'sampleDetails']);
    Route::get('sample/{id}/edit', [SampleController::class, 'edit']);
    Route::put('sample/{id}/update', [SampleController::class, 'sampleStore']);
    Route::get('sample/{id}/delete', [SampleController::class, 'sampleDelete']);
    Route::get('sample-search', [SampleController::class, 'sampleDevelopmentSearch']);
    //sample download
    Route::get('download-work-sample-file/{sample}', [SampleController::class, 'downloadWorkSampleFile']);
    Route::get('get-composition-list', [SampleController::class, 'getCompositionList']);
    Route::get('sampleGetPdf', [SampleController::class, 'getSamplePdf']);

    // Quotation Inquiry
    Route::get('quotation-inquiries', [QuotationInquiryController::class, 'index']);
    Route::get('quotation-inquiries/create', [QuotationInquiryController::class, 'create']);
    Route::post('quotation-inquiries', [QuotationInquiryController::class, 'store']);
    Route::get('quotation-inquiries/{id}/edit', [QuotationInquiryController::class, 'edit']);
    Route::put('quotation-inquiries/{id}', [QuotationInquiryController::class, 'update']);
    Route::delete('quotation-inquiries/{id}', [QuotationInquiryController::class, 'destroy']);
    Route::delete('quotation-inquiry-details/{id}', [QuotationInquiryController::class, 'destroyDetails']);
    Route::get('get-quotation-inquiry-details', [QuotationInquiryController::class, 'getQuotationInquiryDetails']);

    // Price Quotation
    Route::get('price-quotations', [PriceQuotationController::class, 'index']);
    Route::get('price-quotations/excel-list-all', [PriceQuotationController::class, 'orderListExcelAll']);
    Route::get('price-quotations/excel-list-by-page', [PriceQuotationController::class, 'orderListExcelList']);
    Route::get('price-quotations/main-section-form', [PriceQuotationController::class, 'mainSectionForm'])
        ->name('price_quotation_main_section')->middleware('authorize:permission_of_price_quotation_add');
    Route::get('/price-quotations/get-buyer-season/{factory_id}/{buyer_id}', [PriceQuotationController::class, 'loadSeasons']);
    Route::get('/price-quotations/get-buying-agent-merchant/{factory_id}/{buying_agent_id}', [PriceQuotationController::class, 'loadBuyingAgentMerchant']);
    Route::get('price-quotations/costing-section-form', [PriceQuotationController::class, 'costingSectionForm'])
        ->name('costing_section');
    Route::post('price-quotations', [PriceQuotationController::class, 'store']);
    Route::put('price-quotations/{id}', [PriceQuotationController::class, 'update']);
    Route::delete('/price-quotations/{id}', [PriceQuotationController::class, 'delete'])
        ->middleware('authorize:permission_of_price_quotation_delete');
    Route::get('/get-price-quotation-costing-summary/{qid}', [PriceQuotationController::class, 'getPriceQuotationCostingSummary']);
    Route::post('/price-quotation-costing-summary/{qid}', [PriceQuotationController::class, 'priceQuotationCostingSummary']);
    Route::get('/check_item_in_fabric_and_trim', [PriceQuotationController::class, 'checkItemInFabricTrim']);
    Route::get('/price-quotations/{id}/copy', [PriceQuotationController::class, 'copyPriceQuotation']);
    Route::get('/price-quotations/{priceQuotation:quotation_id}/style-generate', [StyleGenerationController::class, 'store']);
    Route::post('/price-quotations/{id}/attachment', [PriceQuotationController::class, 'multiAttachmentUpdate']);
    Route::get('/price-quotations/{id}/attachment/{attachmentId}', [PriceQuotationController::class, 'attachmentDownload']);
    Route::get('/price-quotations/{id}/attachment/{attachmentId}/delete', [PriceQuotationController::class, 'deleteAttachment']);

    Route::get('get-pq-commission-costs/{qid}', [PQCommissionCostController::class, 'index']);
    Route::post('/pq-commission-costs', [PQCommissionCostController::class, 'store']);
    Route::post('/pq-commission-costs/save', [PQCommissionCostController::class, 'save']);
    Route::delete('/pq-commission-costs/{id}', [PQCommissionCostController::class, 'destroy']);

    Route::get('yarn-count-get', [YarnCountApiController::class, '__invoke']);
    Route::get('yarn-composition-get', [YarnCompositionApiController::class, '__invoke']);
    Route::get('yarn-type-get', [YarnTypeApiController::class, '__invoke']);
    Route::get('process-get', [ProcessApiController::class, '__invoke']);

    // Price Quotation Additional Costing
    Route::post('price-quotations/additional-costing-form/{priceQuotation}', [PriceQuotationAdditionalCostingController::class, 'store']);
    Route::get('price-quotations/additional-costing-form', [PriceQuotationAdditionalCostingController::class, 'index']);
    Route::get('price-quotations/additional-costing-form/{priceQuotation}', [PriceQuotationAdditionalCostingController::class, 'edit']);

    // price quotation view
    Route::get('/price-quotations/{id}/view', [PriceQuotationReportController::class, 'view'])
        ->middleware('authorize:PRICE_QUOTATION_VIEW');
    Route::get('/price-quotations/{id}/view-an', [PriceQuotationReportController::class, 'viewForAnwar']);
    Route::get('/price-quotations/{id}/pdf-an', [PriceQuotationReportController::class, 'pdfAnwar']);
    Route::get('/price-quotations/{id}/print', [PriceQuotationReportController::class, 'print']);
    Route::get('/price-quotations/{id}/pdf', [PriceQuotationReportController::class, 'pdf']);
    Route::get('/price-quotations/{id}/costing', [PriceQuotationReportController::class, 'costingView'])
        ->middleware('authorize:PRICE_QUOTATION_COSTING');
    Route::get('/price-quotations/{id}/costing-print', [PriceQuotationReportController::class, 'costingPrint']);
    Route::get('/price-quotations/{id}/costing-pdf', [PriceQuotationReportController::class, 'costingPDF']);
    Route::delete('/delete_price_quotation_files', [PriceQuotationController::class, 'deletePriceQuotationFiles']);
    Route::put('/save_unapproved_request_price_quotation', [PriceQuotationApprovalController::class, 'saveUnapprovedRequest']);
    Route::get('/fetch-unapproved-request-data-pq', [PriceQuotationApprovalController::class, 'getUnapprovedData']);
    Route::put('/update_approve_status_pq', [PriceQuotationApprovalController::class, 'updateApprovedStatus']);

    // fabric_section_select_apis
    Route::get('/quotation_items/{quotation}', [QuotationItemsApiController::class, '__invoke']);
    Route::get('/body_parts', [BodyPartApiController::class, '__invoke']);
    Route::get('/suppliers_api', [SupplierApiController::class, '__invoke']);
    Route::get('/fabric_nature', [FabricNatureApiController::class, '__invoke']);
    Route::get('/color_types', [ColorTypesApiController::class, '__invoke']);
    Route::get('/fabric_sources', [FabricSourceApiController::class, '__invoke']);
    Route::get('/dia_types', [DiaTypesApiController::class, '__invoke']);
    Route::get('/consumption_basis', [ConsumptionBasisApiController::class, '__invoke']);
    Route::get('/fabric_description/{fabricNature?}', [FabricDescriptionApiController::class, '__invoke']);

    Route::post('/fabric_cost_details', [FabricCostDetailsController::class, 'store']);
    Route::put('/fabric_cost_details/{id}', [FabricCostDetailsController::class, 'update']);
    Route::get('/quotation_fabric_costs/{quotation}', [FabricCostDetailsController::class, 'quotationFabricCost']);
    Route::delete('/quotation_fabric_costs_delete/{id}', [FabricCostDetailsController::class, 'deleteFabricCost']);
    Route::post('/fabric_cost_details/save', [FabricCostDetailsController::class, 'save']);

    Route::get('/get_costing_summery/{quotation}', [CostingSummerApiController::class, '__invoke']);
    Route::get('/get-cm-cost/{quotation}', [CmCostApiController::class, '__invoke']);
    Route::get('/get_costing_details/{quotation}/{type}', [CostingDetailsApiController::class, '__invoke']);

    // Please change the following route: seperate by dash other than underscore
    Route::get('/get_asking_profit_calculation', [AskingProfitCalculationApiController::class, '__invoke']);

    // trim section apis

    Route::get('/item_groups_api', [ItemGroupsApiController::class, '__invoke']);
    Route::get('/suppliers_api', [SuppliersApiController::class, '__invoke']);
    Route::get('/get_templates', [TemplateApiController::class, '__invoke']);

    //trims form and template
    Route::post('/trim_cost_form', [TrimCostController::class, 'store']);
    Route::get('fabric-composition-details/{fabric_composition_id}', [FabricCompositionDetailsApi::class, '__invoke']);

    Route::get('/version-check', [FeatureVersionCheckController::class, '__invoke']);

    // Order Routes Starts
    Route::group(['prefix' => 'orders'], function () {
        Route::group(['prefix' => '/protracker'], function () {
            Route::get('/save/{poId}', [ProTrackerDataController::class, 'save']);
        });

        Route::get('/', [OrderController::class, 'index']);
        Route::get('/create', [OrderController::class, 'create'])
            ->middleware('authorize:permission_of_order_entry_add');
        Route::get('/fetch-order-pdf-attachments/{id}', [OrderController::class, 'fetchPdfAttachments']);
        Route::delete('/delete-order-pdf-attachment', [OrderController::class, 'deletePdfAttachment']);
        Route::get('/get-factories', [OrderDependentApiController::class, 'getFactories']);
        Route::get('/get-buyers', [OrderDependentApiController::class, 'getBuyers']);
        Route::get('/get-categories', [OrderDependentApiController::class, 'getCategories']);
        Route::get('/get-departments', [OrderDependentApiController::class, 'getDepartments']);
        Route::get('/get-additional-data', [OrderController::class, 'loadData']);
        Route::get('/get-team-members', [OrderDependentApiController::class, 'loadTeamMembers']);
        Route::get('/get-seasons', [OrderDependentApiController::class, 'loadSeasons']);
        Route::get('/get-items', [OrderController::class, 'loadItem']);
        Route::post('/save', [OrderController::class, 'save']);
        Route::get('/edit', [OrderController::class, 'edit'])
            ->middleware('authorize:permission_of_order_entry_edit');
        Route::get('/old-data/{id}', [OrderController::class, 'loadOldData']);
        Route::get('/get_quotation', [OrderController::class, 'getQuotation']);
        Route::get('/filtered_price_quotation', [PriceQuotationFilterApiController::class, '__invoke']);
        Route::get('/get_variable_settings', [OrderController::class, 'getVariableSettings']);
        Route::post('/get_color_size_break_down', [ColorSizeBreakDownApiController::class, '__invoke']);
        Route::get('/search', [OrderController::class, 'search']);
        Route::get('/excel-list-all', [OrderDownloadAbleController::class, 'orderListExcelAll']);
        Route::get('/excel-list-by-page', [OrderDownloadAbleController::class, 'orderListExcelByPage']);
        Route::delete('/{id}', [OrderController::class, 'delete'])
            ->middleware('authorize:permission_of_order_entry_delete');
        Route::get('/{id}/load-po', [OrderController::class, 'loadPo']);
        Route::post('/add_color', [ColorApiController::class, '__invoke']);
        Route::post('/add_size', [SizeApiController::class, '__invoke']);
        Route::get('/read_quantity_matrix_from_po_file/{po_no}', [PoQuantityMatrixApiController::class, 'readQuantityMatrix']);
        Route::get('/get_color_size_from_po_file/{po_no}', [PoQuantityMatrixApiController::class, 'readColorSizePoFile']);
        Route::post('/orders_image_remove', [OrderController::class, 'orderImageRemove']);
        Route::get('/get-jobs', [OrderDependentApiController::class, 'getJobs']);
        Route::get('/seasons', [OrderDependentApiController::class, 'getSeasons']);
        Route::post('/get-job-wise-po', [OrderDependentApiController::class, 'getJobWisePo']);
        Route::get('/pdf', [OrderDownloadAbleController::class, 'orderPDF']);
        Route::get('/excel', [OrderDownloadAbleController::class, 'orderExcel']);
        Route::get('/print', [OrderDownloadAbleController::class, 'orderPrint']);
        Route::get('booked-colors-for-po', [BookedColorsAndSizesForPoController::class, '__invoke']);
        Route::get('/get-dealing-merchant', [OrderDependentApiController::class, 'getDealingMerchant']);
        Route::get('/send-unapproved-request/{purchaseOrder}', [OrderPoApprovalController::class, 'sendUnApprovedRequest']);
        Route::post('/po-ready-to-approve', [OrderPoApprovalController::class, 'poReadyToApprove']);
        Route::post('/po-ready-to-approve-all', [OrderPoApprovalController::class, 'poReadyToApproveAll']);
        Route::post('/po-un-approve-request', [OrderPoApprovalController::class, 'poUnApproveRequest']);

        Route::get('/style-wise-po/{style}', [StyleWisePoApiController::class, '__invoke']);
        Route::get('/used-style-get', [UsedStyleApiController::class, '__invoke']);
        Route::get('/get-associate-budget/{orderId}', [OrderAssociateBudgetApiController::class, '__invoke']);
        Route::get('/get-orders', [OrderRepeatApiController::class, '__invoke']);
        Route::get('/get-styles', [BuyerWiseStyleApiController::class, '__invoke']);

        Route::get('/get-po-approval-status/{orderId}', OrderApprovalStatusApiController::class);
        //=======Order Repeat Routes======//
        Route::post('/repeat-order', [OrderRepeatController::class, 'store']);


        //---------ORDER VIEW----------//
        Route::get('/color-wise-summary/{order}', [OrderController::class, 'colorWiseSummary']);
        Route::get('/color-wise-summary/{order}/pdf', [OrderController::class, 'colorWiseSummaryPdf']);
        Route::get('/color-wise-summary/{order}/excel', [OrderController::class, 'colorWiseSummaryExcel']);

        Route::get('/check-garments-item/{orderId}/{itemId}', [OrderController::class, 'checkItemInBundleCard']);

        //all-po-approval
        Route::post('/order-all-po-approve', [OrderAllPoApprovalController::class, 'allPoApprove']);
    });
    Route::get('/orders/work-order-sheet', [OrderController::class, 'getWorkOrderSheet']);
    Route::get('/orders/work-orders-sheet-pdf/{id}', [OrderController::class, 'workOrderSheetPdf']);
    Route::get('/orders/work-orders-sheet-excel/{id}', [OrderController::class, 'workOrderSheetExcel']);

    Route::get('/order-entry-report', [OrderReportController::class, 'view'])
        ->middleware('authorize:ORDER_VIEW');
    Route::get('/order-entry-report-dealing-merchant', [OrderReportController::class, 'viewDealingMerchantWise']);
    Route::get('/order-details-report', [OrderReportController::class, 'orderDetails']);
    Route::get('/order-recap-report', [OrderReportController::class, 'recapView']);
    Route::get('/orders-recap/print', [OrderReportController::class, 'orderRecapPrint']);
    Route::get('/orders-recap/pdf', [OrderReportController::class, 'orderRecapPdf']);

    Route::get('shipment-wise-order-report', [ShipmentWiseOrderReportController::class, 'index']);
    Route::get('shipment-wise-order-report-pdf', [ShipmentWiseOrderReportController::class, 'pdf']);
    Route::get('shipment-wise-order-report-excel', [ShipmentWiseOrderReportController::class, 'excel']);

    // New Reports
    Route::group(['namespace' => 'NewReport'], function () {
        Route::match(['get', 'post'], '/order-recap-report-v2', [OrderRecapReportV2Controller::class, 'index']);
        Route::post('/order-recap-report-v2-pdf', [OrderRecapReportV2Controller::class, 'getReportPdf']);
        Route::post('/order-recap-report-v2-excel', [OrderRecapReportV2Controller::class, 'getReportExcel']);
        Route::get('/get-seasons-style', [OrderRecapReportV2Controller::class, 'getSeasonsStyle']);
        Route::get('/get-pos', [OrderRecapReportV2Controller::class, 'getPoId']);
    });

    // Purchase Order related routes
    Route::group(['prefix' => 'purchase-order'], function () {
        Route::get('/common-data', [PurchaseOrderController::class, 'loadCommonData']);
        Route::post('/save', [PurchaseOrderController::class, 'save']);
        Route::get('/get-colors', [PurchaseOrderController::class, 'getColors']);
        Route::post('/load-previous-data', [PurchaseOrderController::class, 'loadOldData']);
        Route::get('/load-dependent-data/{order_id}', [PurchaseOrderController::class, 'loadOrderDependentData']);
        Route::post('/breakdown-update', [PurchaseOrderController::class, 'breakdownUpdate']);
        Route::post('/budget-fabric-cost-update/{orderId}', [BudgetCostingUpdateController::class, 'fabricCosting']);
        Route::post('/budget-trims-cost-update/{orderId}', [BudgetCostingUpdateController::class, 'trimCosting']);
        Route::post('/budget-embellishment-cost-update/{orderId}', [BudgetCostingUpdateController::class, 'embellishmentCosting']);
        Route::post('/budget-wash-cost-update/{orderId}', [BudgetCostingUpdateController::class, 'washCosting']);
        Route::get('/unique-po-check/{po_no}/{order_id}/{po_id}', [PurchaseOrderController::class, 'uniquePOCheck']);
        Route::get('/copy-po/{poId}', [PurchaseOrderController::class, 'copyPo']);
        Route::get('/delete-po/{poId}', [PurchaseOrderController::class, 'deletePo']);
        Route::get('/check-color/{poId}/{itemId}/{colorId}', [PurchaseOrderController::class, 'checkColorInBundleCurd']);
        Route::get('/check-size/{poId}/{itemId}/{sizeId}', [PurchaseOrderController::class, 'checkSizeInBundleCurd']);
    });

    //Report generate routes (ReportController need to refactor)
    Route::get('order-confirmation-list', [ReportController::class, 'getOrderConfirmation']);
    Route::get('order-search-list', [ReportController::class, 'getOrderSearch']);
    Route::get('order-search-wise-pdf-confirmation-report', [ReportController::class, 'getOrderSearchWisePDFConfirmationReport']);
    Route::get('order-search-wise-excel-confirmation-report', [ReportController::class, 'getOrderSearchWiseExcelConfirmationReport']);

    /* monthly fabric summary report */
    Route::get('monthly-fabric-summary-report', [ReportController::class, 'monthlyFabricSummaryReport']);
    Route::get('monthly-fabric-report-summary', [ReportController::class, 'monthlyFabricReportSummary']);
    Route::get('monthly-fabric-report-summary-pdf-download', [ReportController::class, 'monthlyFabricReportSummaryPdfDownload']);

    // Sales Target Determination
    Route::group(['prefix' => 'sales-target-determination'], function () {
        Route::get('/', [SalesTargetController::class, 'index']);
        Route::get('/create', [SalesTargetController::class, 'create']);
        Route::post('/create', [SalesTargetController::class, 'store']);
        Route::get('/{id}/edit', [SalesTargetController::class, 'edit']);
        Route::get('/{id}', [SalesTargetController::class, 'show']);
        Route::put('/{id}', [SalesTargetController::class, 'update']);
        Route::delete('/{id}', [SalesTargetController::class, 'destroy']);
    });

    // Embellishment Cost Calculation related routes For PriceQuotation
    Route::group(['prefix' => 'embellishment-costs'], function () {
        Route::post('/save', [EmbellishmentCostController::class, 'save']);
        Route::get('/old-data/{pq_id}/{type}', [EmbellishmentCostController::class, 'oldData']);
        Route::get('/load-names', [EmbellishmentCostController::class, 'loadNames']);
        Route::get('/get-names', [EmbellishmentCostController::class, 'getNames']);
        Route::get('/load-names-wise-data/{name}', [EmbellishmentCostController::class, 'loadNamesWiseData']);
    });

    // Wash Cost Calculation related routes For PriceQuotation
    Route::group(['prefix' => 'wash-quotation'], function () {
        Route::post('/save', [WashPriceQuotationController::class, 'save']);
        Route::get('/old-data/{pq_id}/{type}', [WashPriceQuotationController::class, 'oldData']);
        Route::get('/load-names', [WashPriceQuotationController::class, 'loadNames']);
        Route::get('/load-names-wise-data/{name}', [WashPriceQuotationController::class, 'loadNamesWiseData']);
        Route::get('/load-wash-names/{name}', [WashPriceQuotationController::class, 'loadWashName']);
    });
    // Commercial Cost Calculation related routes For PriceQuotation
    Route::group(['prefix' => 'commercials'], function () {
        Route::get('/get-types', [CommercialCostController::class, 'getTypes']);
        Route::post('/save', [CommercialCostController::class, 'save']);
        Route::get('/old-data/{pq_id}/{type}', [CommercialCostController::class, 'oldData']);
    });

    // PO Files related route
    Route::group(['prefix' => 'po_files'], function () {
        Route::get('/{id}', [POFilesController::class, 'reProcess']);
        Route::get('/get-issues/{buyerId}', [POFilesController::class, 'buyerWiseIssue']);
        Route::get('/{id}/download', [POFilesController::class, 'download']);
        Route::get('/{id}/edit-content', [POFilesController::class, 'editContent']);
        Route::post('/{id}/content-update', [POFilesController::class, 'updateContent']);
        Route::get('/{id}/view', [POFilesController::class, 'view']);
        Route::get('/get-po-quantity/{poNo}', [POFilesController::class, 'getPoQuantity']);
        Route::delete('/{id}', [POFilesController::class, 'destroy']);
        Route::resource('/', POFilesController::class);
    });


    Route::group(['prefix' => 'po-files-excel'], function () {
        Route::get('/sample-download', [POFilesExcelController::class, 'sampleDownload']);
        Route::get('/{id}/download', [POFilesExcelController::class, 'download']);
        Route::get('/{pOFileModel}/edit', [POFilesExcelController::class, 'edit']);
        Route::get('/{id}/replace', [POFilesExcelController::class, 'replace']);
        Route::post('/{id}/replace', [POFilesExcelController::class, 'storeRemarks']);
        Route::put('/{pOFileModel}', [POFilesExcelController::class, 'update']);
        Route::delete('/{id}', [POFilesExcelController::class, 'destroy']);
        Route::resource('/', POFilesExcelController::class);
    });

    // Tech Pack Files related routes
    Route::group(['prefix' => 'tech-pack-files'], function () {
        Route::get('/{id}/download', [TechPackFilesController::class, 'download']);
        Route::get('/{id}/view', [TechPackFilesController::class, 'viewContent']);
        Route::get('/{id}/edit', [TechPackFilesController::class, 'edit']);
        Route::put('/{id}', [TechPackFilesController::class, 'update']);
        Route::delete('/{id}', [TechPackFilesController::class, 'destroy']);
        Route::resource('/', TechPackFilesController::class);

        Route::any('sample-download', TechPackSampleFileController::class);
    });
    Route::get('/tech-pack-content/{id}', [TechPackFilesController::class, 'editContent'])->name('tech-pack-content');
    Route::post('/tech-pack-content-update/{id}', [TechPackFilesController::class, 'updateContent']);
    Route::put('/tech-pack-process/{id}', [TechPackFilesController::class, 'processContent']);

    // Main Trims Bookings
    Route::group(['prefix' => 'trims-bookings'], function () {
        Route::get('/booking-details/{id}', [TrimsBookingController::class, 'getBookingDetails']);
        Route::delete('/booking-details/delete/{booking_id}/{item_id}/{budgetId}', [TrimsBookingController::class, 'deleteBookingDetails']);
        Route::post('/save-trims-booking-details', [TrimsBookingController::class, 'trimsBookingDetails']);
        Route::post('/save-breakdown-details', [TrimsBookingBreakDownController::class, 'store']);
        Route::get('/', [TrimsBookingController::class, 'index']);
        Route::get('/search', [TrimsBookingController::class, 'search']);
        Route::get('/excel-list-all', [TrimsBookingController::class, 'TrimsListExcelAll']);
        Route::post('/load-po', [TrimsBookingController::class, 'fetchPo']);
        Route::post('/load-unique-id', [TrimsBookingController::class, 'fetchUniqueId']);

        Route::get('/{any?}', [TrimsBookingController::class, 'bookingMainPage'])->where('any', '.*');
    });

    Route::get('/fetch-trims-booking-previous-sensitivity', TrimsBookingPreviousSensitivityApiController::class);

    Route::get('trims-booking-search', [TrimsBookingController::class, 'trimsBookingSearch']);
    Route::post('booking/trims/image-upload', [TrimsBookingController::class, 'imageUpload']);
    Route::post('booking/trims/image-remove', [TrimsBookingController::class, 'deleteUpload']);
    Route::get('bookings/trims-bookings/getFabricCompositions', [TrimsBookingController::class, 'getFabricCompositions']);

    // Trims Bookings related routes
    Route::group(['prefix' => 'bookings'], function () {
        Route::get('trims-bookings/{id}', [TrimsBookingController::class, 'show']);
        Route::post('trims-bookings', [TrimsBookingController::class, 'store']);
        Route::put('trims-bookings/{booking}', [TrimsBookingController::class, 'update']);
        Route::delete('trims-bookings/{booking}', [TrimsBookingController::class, 'delete']);
        Route::get('trims/color-size-wise-breakdown', [TrimsBookingColorSizeController::class, '__invoke']);
        Route::get('trims/color-size-wise-breakdown/delete-images', [TrimsBookingColorSizeController::class, 'deleteImages']);
        Route::get('trims/get-contrast-tags/{style}', [TrimsBookingController::class, 'getContrastTags']);
        Route::post('trims/get-contrast-colors/', [TrimsContrastColorApiController::class, '__invoke']);
        Route::get('trims-bookings/{itemId}/group-wise-fields', TrimsItemWiseGroupFieldsApiController::class);
    });

    // trims booking view
    Route::group(['prefix' => 'trims-bookings-views'], function () {
        Route::get('/{id}/view', [TrimsBookingController::class, 'view'])->middleware('authorize:TRIMS_BOOKINGS_SHEET');
        Route::get('/{id}/view-2', [TrimsBookingController::class, 'view'])->middleware('authorize:TRIMS_BOOKINGS_SHEET_V2');
        Route::get('/{id}/view-3', [TrimsBookingController::class, 'view'])->middleware('authorize:TRIMS_BOOKINGS_SHEET_V3');
        Route::get('/{id}/view-4', [TrimsBookingController::class, 'view'])->middleware('authorize:TRIMS_BOOKINGS_SHEET_V4');
        Route::get('/{id}/view-5', [TrimsBookingController::class, 'view'])->middleware('authorize:TRIMS_BOOKINGS_SHEET_V5');
        Route::get('/{id}/view-6', [TrimsBookingController::class, 'view'])->middleware('authorize:TRIMS_BOOKINGS_SHEET_V6');
        Route::get('/{id}/view-9', [TrimsBookingController::class, 'bookingView']);
        Route::get('/{id}/pdf/view-9', [TrimsBookingController::class, 'bookingViewPDF']);
        Route::get('/{id}/excel/view-9', [TrimsBookingController::class, 'bookingViewExcel']);
        Route::get('/{id}/mondol-view', [TrimsBookingController::class, 'view'])->middleware('authorize:TRIMS_BOOKINGS_SHEET_V7');
        Route::get('/{id}/gears-view', [TrimsBookingController::class, 'view'])->middleware('authorize:TRIMS_BOOKINGS_SHEET_V8');
        Route::get('/{id}/print', [TrimsBookingController::class, 'printView']);
        Route::get('/{id}/pdf', [TrimsBookingController::class, 'pdfView']);
        Route::get('/{id}/excel', [TrimsBookingController::class, 'excelView']);
        Route::get('/{id}/wo-wise-view', [TrimsBookingController::class, 'woWiseView']);
        Route::get('/{id}/wo-wise-print', [TrimsBookingController::class, 'woWisePrintView']);
        Route::get('/{id}/wo-wise-pdf', [TrimsBookingController::class, 'woWisePdf']);
    });

    // trims short bookings
    Route::group(['prefix' => 'short-trims-bookings'], function () {
        Route::get('/', [ShortTrimsBookingController::class, 'index']);
        Route::get('/search', [ShortTrimsBookingController::class, 'search']);
        Route::get('/{id}/view', [ShortTrimsBookingController::class, 'view'])
            ->middleware('authorize:SHORT_TRIMS_BOOKINGS_SHEET');
        Route::get('/{id}/print', [ShortTrimsBookingController::class, 'printView']);
        Route::get('/{id}/pdf', [ShortTrimsBookingController::class, 'pdfView']);
        Route::get('trims-bookings/{id}', [ShortTrimsBookingController::class, 'show']);
        Route::post('/', [ShortTrimsBookingController::class, 'store']);
        Route::put('/{booking}', [ShortTrimsBookingController::class, 'update']);
        Route::delete('trims-bookings/{booking}', [ShortTrimsBookingController::class, 'delete']);
        Route::get('trims/color-size-wise-breakdown', [ShortTrimsBookingColorSizeController::class, '__invoke']);

        Route::get('/booking-details/{id}', [ShortTrimsBookingController::class, 'getBookingDetails']);
        Route::delete('/booking-details/delete/{booking_id}/{item_id}', [ShortTrimsBookingController::class, 'deleteBookingDetails']);
        Route::get('/short-trims-bookings-search', [ShortTrimsBookingController::class, 'trimsBookingSearch']);
        Route::post('/save-trims-booking-details', [ShortTrimsBookingController::class, 'trimsBookingDetails']);
        Route::post('/save-breakdown-details', [ShortTrimsBookingBreakDownController::class, 'store']);
        Route::get('/{any?}', [ShortTrimsBookingController::class, 'bookingMainPage'])
            ->where('any', '.*');
    });

    // Sample trims booking
    Route::group(['prefix' => 'sample-trims-booking'], function () {
        Route::get('/', [SampleTrimsBookingController::class, 'index']);
        Route::get('/{id}/view', [SampleTrimsBookingController::class, 'view']);
        Route::get('/{id}/pdf', [SampleTrimsBookingController::class, 'pdf']);
        Route::post('/', [SampleTrimsBookingController::class, 'store']);
        Route::get('/{any?}', [SampleTrimsBookingController::class, 'sampleTrimsPages'])->where('any', '.*');
    });

    Route::group(['prefix' => 'sample-trims-booking-api'], function () {
        Route::get('/fetch-filter-data', [SampleTrimsBookingController::class, 'filterData']);
        Route::post('/filter-requisition-data', [SampleTrimsBookingController::class, 'filterRequisitionData']);
        Route::post('/save-details', [SampleTrimsBookingController::class, 'storeDetails']);
        Route::get('/sample-trims/{sampleTrimsBooking}/delete', [SampleTrimsBookingController::class, 'deleteSample']);
        Route::get('/sample-trims-detail/{sampleTrimsBookingDetail}/delete', [SampleTrimsBookingController::class, 'deleteDetail']);
        Route::get('/{sampleTrimsBooking}/show', [SampleTrimsBookingController::class, 'show']);
    });

    // Budget Route
    Route::get('budgeting/{any?}', [BudgetController::class, 'createOrUpdate'])->where('any', '.*')
        ->middleware('authorize:permission_of_budget_add');
    Route::get('budget-create-page', [BudgetController::class, 'createOrUpdate']);
    Route::get('/get-approval-list/{buyerId}/{page}', [ApprovalApiController::class, 'fetchApprovalList']);

    Route::group(['prefix' => 'budgets'], function () {
        Route::get('/', [BudgetController::class, 'index']);
        Route::post('/save', [BudgetController::class, 'save']);
        Route::get('/edit', [BudgetController::class, 'edit']);
        Route::get('/{id}/get', [BudgetController::class, 'get']);
        Route::get('/{id}/remove-file/{type}', [BudgetController::class, 'removeFile']);
        Route::get('/search', [BudgetController::class, 'search']);
        Route::delete('/{id}', [BudgetController::class, 'delete'])
            ->middleware('authorize:permission_of_budget_delete');
        Route::post('/{id}/trims-budget', [BudgetController::class, 'saveTrimsBudget']);

        // Order wise direct budget create
        Route::get('/copy-form-order/{order}', OrderWiseBudgetCopyController::class);

        Route::get('/load-common-data', [BudgetDependentApiController::class, 'loadCommonData']);
        Route::get('/factories', [BudgetDependentApiController::class, 'getFactories']);
        Route::get('/factory/{id}/buyers', [BudgetDependentApiController::class, 'getBuyers']);
        Route::get('/factory/{factory_id}/buyer/{buyer_id}', [BudgetDependentApiController::class, 'getJobs']);
        Route::post('/job-search', [BudgetDependentApiController::class, 'jobSearch']);

        Route::get('/{id}/view', [BudgetDownloadAbleController::class, 'view'])
            ->middleware('authorize:BUDGET_VIEW');
        Route::get('/{id}/print', [BudgetDownloadAbleController::class, 'budgetPrint']);
        Route::get('/{id}/pdf', [BudgetDownloadAbleController::class, 'budgetPdf']);
        Route::get('/{id}/report', [BudgetDownloadAbleController::class, 'budgetReportView']);
        Route::get('/{id}/cost-breakdown-sheet/{type}', [BudgetDownloadAbleController::class, 'costingSheetView'])
            ->middleware('authorize:BUDGET_COSTING_SHEET');
        Route::get('/{id}/cost-breakdown-pdf/{type}', [BudgetDownloadAbleController::class, 'costingSheetPdf']);
        Route::get('/{id}/cost-breakdown-print/{type}', [BudgetDownloadAbleController::class, 'costingSheetPrint']);
        Route::get('/{id}/cost-breakdown-excel/{type}', [BudgetDownloadAbleController::class, 'costingSheetExcel']);
        Route::get('/excel-list-all', [BudgetDownloadAbleController::class, 'BudgetFilterExcelAll']);
        Route::get('/excel-list-by-page', [BudgetDownloadAbleController::class, 'BudgetFilterExcelByPage']);

        Route::post('/save-trims-file', [TrimsBudgetController::class, 'saveFile']);

        Route::group(['prefix' => 'costings'], function () {
            Route::get('/get_budget_templates', [BudgetTemplateApiController::class, '__invoke']);
            Route::get('/get_costing_types', [BudgetCostingController::class, 'getCostingTypes']);
            Route::get('/get-budget-data/{budgetId}', [BudgetCostingController::class, 'getBudgetData']);
            Route::post('/save-costing-data', [BudgetCostingController::class, 'store']);
            Route::get('/get-item-wise-breakdown/{budgetId}/{itemId}', [BudgetGreyConsBreakdownController::class, 'loadItemWiseBreakdown']);
            Route::get('/get-costing-data/{budgetId}/{type}', [BudgetCostingApiController::class, '__invoke']);
            Route::get('/get-item-wise-order-color/{orderId}/{itemId}', [OrderWiseColorApiController::class, '__invoke']);
            Route::get('/get-fabric-colors', [BudgetCostingController::class, 'getFabricColors']);
            Route::post('/fabric-cost-save', [BudgetCostingController::class, 'fabricCostingStore']);
            Route::get('/get-cm-cost/{budgetId}', [BudgetCostingController::class, 'getCmCost']);
            Route::group(['prefix' => 'strips'], function () {
                Route::get('/get-common-data', [StripController::class, 'commonData']);
            });
            Route::get('/get-body-part/{budgetId}', [EmbellishmentCostingController::class, 'bodyPart']);
            Route::get('/get-countries/', [EmbellishmentCostingController::class, 'getCountries']);
            Route::post('/embellishment-cost-save', [EmbellishmentCostingController::class, 'store']);
            Route::post('/wash-cost-save', [WashBudgetCostingController::class, 'store']);
            Route::post('/commercial-cost-save', [CommercialBudgetCostingController::class, 'store']);
            Route::get('/get-variable-types/', [CommercialBudgetCostingController::class, 'getTypes']);
            Route::post('/commission-cost-save', [CommissionCostingController::class, 'store']);
            Route::get('/{budgetId}/budget', [CommissionCostingController::class, 'getBudget']);
        });
    });

    Route::get('fetch-countries', [FormsApiController::class, 'fetchCountries']);
    Route::get('fetch-default-country', [FormsApiController::class, 'defaultCountry']);
    Route::get('fetch-brands', [FormsApiController::class, 'fetchBrands']);
    Route::get('fetch-trims-items', [FormsApiController::class, 'trimsItems']);
    Route::get('/get-uom-conversion-factor/{groupId}', [FormsApiController::class, 'getConversionFactor']);
    Route::get('fetch-trims-costing/{budgetId}', [FormsApiController::class, 'fetchCostingData']);
    Route::get('fetch-consumption-uom', [FormsApiController::class, 'consumptionUoms']);
    Route::get('fetch-factories', [FormsApiController::class, 'fetchFactories']);
    Route::get('fetch-buyers', [FormsApiController::class, 'fetchBuyers']);
    Route::get('fetch-suppliers-for-booking', [FormsApiController::class, 'suppliersForBooking']);
    Route::post('embellishments-po-item-color-size-details/{budgetId}', [EmbellishmentCostingController::class, 'poItemColorSizeBreakdown']);
    Route::post('wash-po-item-color-size-details/{budgetId}', [WashCostingController::class, 'poItemColorSizeBreakdown']);
    Route::post('trims-color-size-breakdown/{budgetId}', [TrimsBudgetController::class, 'trimsItemSizeWiseBreakdown']);

    Route::post('/fetch-tech-pack-colors', [TechPackApiController::class, '__invoke']);
    Route::get('/get-tech-pack-tags', [TechPackTagsApiController::class, '__invoke']);

    // Short Fabric Booking related routes
    Route::group(['prefix' => 'short-fabric-bookings'], function () {
        Route::get('/{id}/summary-view', [ShortFabricBookingDetailsController::class, 'summaryView'])
            ->middleware('authorize:SHORT_FABRIC_BOOKINGS_SUMMARY');
        Route::get('/{id}/summary-pdf', [ShortFabricBookingDetailsController::class, 'summaryPdf']);
        Route::get('/', [FabricShortBookingController::class, 'index']);
        Route::get('/create', [FabricShortBookingController::class, 'createOrUpdate'])
            ->middleware('authorize:permission_of_short_fabric_bookings_add');
        Route::get('/search', [FabricShortBookingController::class, 'search']);
        Route::delete('/{id}', [FabricShortBookingController::class, 'delete'])
            ->middleware('authorize:permission_of_short_fabric_bookings_delete');

        Route::post('/store', [FabricShortBookingController::class, 'store']);
        Route::get('/{id}/get', [FabricShortBookingController::class, 'get']);
        Route::post('/booking-search', [ShortBookingSearchController::class, 'bookingSearch']);
        Route::post('/booking-search/save', [ShortBookingSearchController::class, 'store']);
        Route::get('/load-old-selected-data/{bookingId}', [ShortBookingSearchController::class, 'oldData']);
        Route::post('/manipulation/load-breakdown-data', [ShortFabricBookingDetailsController::class, 'fabricBookingDetailsData']);
        Route::post('/manipulation/store', [ShortFabricBookingDetailsController::class, 'store']);
        Route::get('/{id}/get-list', [FabricShortBookingController::class, 'getList']);
        Route::get('/delete-list', [FabricShortBookingController::class, 'deleteList']);
    });

    // Fabric Booking related routes
    Route::group(['prefix' => 'fabric-bookings'], function () {
        Route::get('/{id}/summary-view', [FabricBookingDetailsController::class, 'summaryView'])
            ->middleware('authorize:FABRIC_BOOKINGS_SUMMARY');
        Route::get('/{id}/summary-print', [FabricBookingDetailsController::class, 'summaryPrint']);
        Route::get('/{id}/summary-pdf', [FabricBookingDetailsController::class, 'summaryPdf']);
        Route::get('/', [FabricBookingController::class, 'index']);
        Route::delete('/{id}', [FabricBookingController::class, 'delete'])
            ->middleware('authorize:permission_of_main_fabric_bookings_delete');
        Route::get('/create', [FabricBookingController::class, 'createOrUpdate'])
            ->middleware('authorize:permission_of_main_fabric_bookings_add');
        Route::get('/search', [FabricBookingController::class, 'search']);
        Route::get('/excel-list-all', [FabricBookingController::class, 'FabricListExcelAll']);
        Route::get('/factories', [FabricBookingController::class, 'loadFactories']);
        Route::get('/factory/{factoryId}/load-common-data', [FabricBookingController::class, 'loadCommonData']);
        Route::post('/store', [FabricBookingController::class, 'store']);
        Route::get('/{id}/get', [FabricBookingController::class, 'get']);
        Route::get('/{id}/load-po', [FabricBookingController::class, 'loadPo']);
        Route::post('/booking-search', [BookingSearchController::class, 'bookingSearch']);
        Route::post('/get-body-parts', [BookingsBodyPartController::class, 'bodyParts']);
        Route::post('/booking-search/save', [BookingSearchController::class, 'store']);
        Route::get('details-data', [FabricBookingDetailsController::class, 'fabricBookingDetailsData']);
        Route::post('/get-collar-cuff-data', [CollarCuffApiController::class, '__invoke']);
        Route::get('/load-old-selected-data/{bookingId}', [BookingSearchController::class, 'oldData']);
        Route::post('/manipulation/load-breakdown-data', [FabricBookingDetailsController::class, 'fabricBookingDetailsData']);
        Route::post('/manipulation/store', [FabricBookingDetailsController::class, 'store']);
        Route::get('/{id}/get-list', [FabricBookingController::class, 'getList']);
        Route::get('/delete-list/all', [FabricBookingController::class, 'deleteList']);
        Route::get('/{id}/view', [FabricBookingDetailsController::class, 'view'])
            ->middleware('authorize:FABRIC_BOOKINGS_VIEW');
        Route::get('/{id}/print', [FabricBookingDetailsController::class, 'printView']);
        Route::get('/{id}/pdf', [FabricBookingDetailsController::class, 'pdf']);
        Route::get('/gears/{id}/view', [FabricBookingDetailsController::class, 'gearsView'])
            ->middleware('authorize:FABRIC_BOOKINGS_SHEET');
        Route::get('/gears/{id}/print', [FabricBookingDetailsController::class, 'gearsPrint']);
        Route::get('/gears/{id}/pdf', [FabricBookingDetailsController::class, 'gearsPdf']);
        Route::get('/{id}/view-2', [FabricBookingDetailsController::class, 'styleWiseView'])
            ->middleware('authorize:FABRIC_BOOKINGS_PURCHASE_ORDER');
        Route::get('/{id}/view-3', [FabricBookingDetailsController::class, 'viewMondol']);
        Route::get('/{id}/pdf-3', [FabricBookingDetailsController::class, 'pdfMondol']);
        Route::get('/{id}/view-4', [FabricBookingDetailsController::class, 'viewFour']);
        Route::get('/{id}/pdf-4', [FabricBookingDetailsController::class, 'pdfViewFour']);
        Route::get('/{id}/excel-4', [FabricBookingDetailsController::class, 'excelViewFour']);
        Route::get('/{id}/style-wise-print', [FabricBookingDetailsController::class, 'styleWisePrint']);
        Route::get('/{id}/style-wise-pdf', [FabricBookingDetailsController::class, 'styleWisePdf']);

        Route::get('/copy-from-budget/{budget}', BudgetWiseCopyFabricBookingController::class);
    });

    Route::get('/fabric-bookings-moq-qty', [FabricBookingController::class, 'moqQty']);

    Route::group(['prefix' => 'work-order'], function () {
        Route::get('/embl-search', [EmbellishmentWorkOrderController::class, 'searchData']);
        Route::post('/embellishment-wo-search', [EmbellishmentSearchApiController::class, '__invoke']);
        Route::post('/save-embellishment-booking-details', [EmbellishmentWorkOrderController::class, 'saveBookingDetails']);
        Route::get('/embellishment-booking-details/{id}', [EmbellishmentWorkOrderController::class, 'bookingDetails']);
        Route::delete('/embellishment-booking-details-delete/{bookingId}/{itemId}/{itemTypeId}', [EmbellishmentWorkOrderController::class, 'deleteBookingDetails']);
        Route::get('/embel-color-size-wise-breakdown', [EmbellishmentColorSizeController::class, '__invoke']);
        Route::post('/save-embellishment-work-order-details', [EmbellishmentWorkOrderBreakDownController::class, 'store']);
        Route::group(['prefix' => 'embellishment'], function () {
            Route::get('/excel-list-all', [EmbellishmentWorkOrderController::class, 'orderListExcelAll']);
            Route::get('/excel-list-by-page', [EmbellishmentWorkOrderController::class, 'orderListExcelList']);
            Route::get('/', [EmbellishmentWorkOrderController::class, 'index']);
            Route::get('/search', [EmbellishmentWorkOrderController::class, 'searchData']);
            Route::get('/{id}/view', [EmbellishmentWorkOrderController::class, 'view'])
                ->middleware('authorize:EMBELLISHMENT_WORK_ORDER_VIEW');
            Route::get('/{id}/pdf', [EmbellishmentWorkOrderController::class, 'pdf']);
            Route::get('/{id}/print', [EmbellishmentWorkOrderController::class, 'print']);

            Route::group(['prefix' => 'api'], function () {
                Route::post('/', [EmbellishmentWorkOrderController::class, 'store']);
                Route::get('/{id}', [EmbellishmentWorkOrderController::class, 'show']);
                Route::delete('/{id}', [EmbellishmentWorkOrderController::class, 'delete']);
            });

            Route::get('/{any?}', [EmbellishmentWorkOrderController::class, 'create'])->where('any', '.*');
        });
    });

    Route::group(['prefix' => 'sample-booking-for-confirm-order'], function () {
        Route::get('/', [SampleBookingForConfirmOrderController::class, 'index']);
        Route::get('/{id}/view', [SampleBookingForConfirmOrderController::class, 'view']);
        Route::get('/{id}/pdf', [SampleBookingForConfirmOrderController::class, 'pdf']);
        Route::view('/create', 'merchandising::sample-booking.confirm-order');
        Route::view('/{id}/edit', 'merchandising::sample-booking.confirm-order');
    });

    Route::group(['prefix' => 'sample-booking-for-before-order'], function () {
        Route::get('/', [SampleBookingForBeforeOrderController::class, 'index']);
        Route::view('/create', 'merchandising::sample-booking.before-order');
        Route::view('/{id}/edit', 'merchandising::sample-booking.before-order');
    });

    Route::get('samples', [SampleListController::class, 'index']);
    Route::get('samples/excel-list-all', [SampleListController::class, 'SampleListExcelAll']);
    //view 1
    Route::get('samples/{id}', [SampleListController::class, 'view']);
    Route::get('samples/{id}/pdf', [SampleListController::class, 'viewPdf']);

    //view 2
    Route::get('samples/v2/{id}', [SampleListController::class, 'viewV2']);
    Route::get('samples/v2/{id}/pdf', [SampleListController::class, 'viewPdfV2']);

    Route::get('sample-requisitions/{any?}', [SampleRequisitionController::class, 'index'])->where('any', '.*');

    Route::group(['prefix' => 'sample-requisitions-api'], function () {
        Route::post('', [SampleRequisitionController::class, 'store']);
        Route::get('', [SampleRequisitionController::class, 'list']);
        Route::delete('{sampleRequisition}', [SampleRequisitionController::class, 'delete']);

        Route::get('details/{sampleRequisition}', [SampleRequisitionDetailController::class, 'details']);
        Route::post('details/{sampleRequisition}', [SampleRequisitionDetailController::class, 'store']);
        Route::post('details/delete/{sampleRequisitionDetail}', [SampleRequisitionDetailController::class, 'delete']);
        Route::post('details/delete-image/{sampleRequisitionDetail}', [SampleRequisitionDetailController::class, 'deleteSingleImage']);

        Route::post('accessories/{requisition}', [SampleRequiredAccessoryController::class, 'store']);
        Route::get('accessories/{requisition}', [SampleRequiredAccessoryController::class, 'list']);
        Route::delete('accessories/{accessory}', [SampleRequiredAccessoryController::class, 'delete']);
        Route::post('trims-fetch-from-budget', [SampleRequiredAccessoryController::class, 'trimsCostingFromBudget']);
        Route::post('accessories/delete-image/{sampleRequisitionDetail}', [SampleRequiredAccessoryController::class, 'deleteImageFromDb']);

        Route::get('fabrics/{requisition}', [SampleRequisitionFabricDetailController::class, 'details']);
        Route::post('fabrics/{requisition}', [SampleRequisitionFabricDetailController::class, 'store']);
        Route::delete('fabrics/{fabricDetail}', [SampleRequisitionFabricDetailController::class, 'deleteFabric']);
        Route::post('fabrics-fetch-from-budget', [SampleRequisitionFabricDetailController::class, 'fabricCostingFromBudget']);
        Route::post('fabrics/delete-image/{fabricDetail}', [SampleRequisitionFabricDetailController::class, 'deleteImageFromDb']);

        Route::get('fabric-details-for-sample-requisition', [SampleRequisitionController::class, 'getFabricDetails']);
        Route::get('required-samples', [SampleRequisitionController::class, 'requiredSamples']);

        Route::get('styles-search', [SampleRequisitionController::class, 'stylesSearch']);
        Route::get('qty-form-data', [SampleRequisitionController::class, 'qtyFormData']);
        Route::get('{requisition}', [SampleRequisitionController::class, 'show'])->where('requisition', '[0-9]+');
        Route::put('{requisition}', [SampleRequisitionController::class, 'update'])->where('requisition', '[0-9]+');
    });

    Route::group(['prefix' => 'sample-booking-for-confirm-order-api'], function () {
        Route::post('', [SampleBookingForConfirmOrderController::class, 'store']);
        Route::get('search-requisition', [SampleBookingForConfirmOrderController::class, 'searchRequisition']);
        Route::get('search-requisition-details', [SampleBookingForConfirmOrderController::class, 'sampleRequisitionDetails']);
        Route::get('{sampleBookingConfirmOrder}', [SampleBookingForConfirmOrderController::class, 'show']);
        Route::delete('{sampleBookingConfirmOrder}', [SampleBookingForConfirmOrderController::class, 'delete']);
        Route::delete('{detail}/detail', [SampleBookingForConfirmOrderController::class, 'deleteDetail']);
        Route::get('{sampleBookingConfirmOrder}/details', [SampleBookingForConfirmOrderController::class, 'details']);
        Route::post('{sampleBookingConfirmOrder}/details', [SampleBookingForConfirmOrderController::class, 'storeDetails']);
    });

    Route::group(['prefix' => 'sample-booking-for-before-order-api'], function () {
        Route::post('', [SampleBookingForBeforeOrderController::class, 'store']);
        Route::get('search-requisition', [SampleBookingForBeforeOrderController::class, 'searchRequisition']);
        Route::get('search-requisition-details', [SampleBookingForBeforeOrderController::class, 'sampleRequisitionDetails']);
        Route::get('{sampleBooking}', [SampleBookingForBeforeOrderController::class, 'show']);
        Route::delete('{sampleBooking}', [SampleBookingForBeforeOrderController::class, 'delete']);
        Route::delete('{detail}/detail', [SampleBookingForBeforeOrderController::class, 'deleteDetail']);
        Route::get('{sampleBooking}/details', [SampleBookingForBeforeOrderController::class, 'details']);
        Route::post('{sampleBooking}/details', [SampleBookingForBeforeOrderController::class, 'storeDetails']);
    });

    Route::group(['prefix' => 'common-api'], function () {
        Route::get('buyers', [CommonAPIController::class, 'allBuyers']);
        Route::get('{factoryId}/buyers', [CommonAPIController::class, 'buyers']);
        Route::get('currencies', [SampleRequisitionController::class, 'currencies']);
        Route::get('buying-agents', [SampleRequisitionController::class, 'buyingAgents']);
        Route::get('{factory}/dealing-merchants', [SampleRequisitionController::class, 'dealingMerchants']);
        Route::get('users', [UserManagerController::class, 'getAllUser']);
        Route::get('processes', [CommonAPIController::class, 'processes']);
        Route::get('brands', [CommonAPIController::class, 'brands']);
        Route::get('yarn-counts', [CommonAPIController::class, 'yarnCounts']);
        Route::get('yarn-compositions', [CommonAPIController::class, 'yarnCompositions']);
        Route::get('yarn-types', [CommonAPIController::class, 'yarnTypes']);
        Route::get('unit-of-measurements', [CommonAPIController::class, 'unitOfMeasurements']);
        Route::get('style-names', [CommonAPIController::class, 'styleNames']);
        Route::get('buyers-style-name/{id}', [CommonAPIController::class, 'buyerStyleNames']);
        Route::get('factory-buyers-style-name/{factoryId}/{buyerId}', [CommonAPIController::class, 'factoryBuyerStyleNames']);
        Route::get('ship-modes', [CommonAPIController::class, 'shipModes']);
        Route::get('items', [CommonAPIController::class, 'items']);
        Route::get("buyers-styles/{id}", [CommonAPIController::class, 'buyersStyle']);
        Route::get("factory-buyers-styles/{factoryId}/{buyerId}", [CommonAPIController::class, 'factoryBuyersStyle']);
        Route::get("fetch-unique-id", [CommonAPIController::class, 'fetchUniqueId']);
        Route::get("fetch-po", [CommonAPIController::class, 'fetchPo']);
        Route::get("orders-items/{id}", [CommonAPIController::class, 'ordersItems']);

        Route::get('garments-items', [CommonAPIController::class, 'garmentsItems']);
        Route::post('upload-file', [SampleRequisitionController::class, 'uploadFile']);
        Route::get('order-item-po', [CommonAPIController::class, 'styleItemPO']);
        Route::get('po-item-colors', [CommonAPIController::class, 'poItemColors']);

        Route::post('delete-image-from-folder', [CommonAPIController::class, 'deleteImageFromFolder']);
        Route::get('get-current-user-info', [CommonAPIController::class, 'currentUserInfo']);
        Route::get('get-suppliers-info', [CommonAPIController::class, 'suppliersInfo']);
        Route::get('get-teams', [CommonAPIController::class, 'teams']);
        Route::get('get-care-instructions', [CommonAPIController::class, 'careInstructions']);
        Route::get('get-garments-item-group', [CommonAPIController::class, 'garmentsItemGroup']);
        Route::get('get-currencies', [CommonAPIController::class, 'currencies']);
        Route::get('fetch-care-label-types', [CareLabelTypesApiController::class, '__invoke']);
        Route::get('fetch-supplier-detail/{supplier}', [CommonAPIController::class, 'supplierDetail']);
        Route::get('fetch-item-groups', [CommonAPIController::class, 'itemGroups']);
        Route::get('fetch-sizes', [CommonAPIController::class, 'sizes']);
        Route::get('fetch-colors', [CommonAPIController::class, 'colors']);
    });

    Route::group(['prefix' => 'fabric-service-bookings-api'], function () {
        Route::get('{booking}', [FabricServiceBookingController::class, 'show'])->where('booking', PK_REGEX);
        Route::post('', [FabricServiceBookingController::class, 'store']);
        Route::put('{booking}', [FabricServiceBookingController::class, 'update'])->where('booking', PK_REGEX);
        Route::get('budget-search', [FabricServiceBookingController::class, 'budgetSearch']);
        Route::post('descriptions', [FabricServiceBookingDescriptionController::class, 'descriptions']);
        Route::get('descriptions-list', [FabricServiceBookingDescriptionController::class, 'descriptionsList']);
        Route::get('po-for-colors', [FabricServiceBookingDescriptionController::class, 'poForColors']);
        Route::get('colors-for-description', [FabricServiceBookingDescriptionController::class, 'colorsForDescription']);
        Route::post('budget-details-data', [FabricServiceBookingDescriptionController::class, 'budgetDetailsData']);
        Route::post('/{serviceBooking}/save-details-data', [FabricServiceBookingDescriptionController::class, 'store']);
        Route::get('/{serviceBooking}/details-list', [FabricServiceBookingDescriptionController::class, 'show']);
        Route::delete('/details-delete/{id}', [FabricServiceBookingDescriptionController::class, 'delete']);
    });

    Route::group(['prefix' => '/fabric-service-bookings'], function () {
        Route::get('', [FabricServiceBookingController::class, 'index']);
        Route::get('/create', [FabricServiceBookingController::class, 'create']);
        Route::get('/{id}/view', [FabricServiceBookingController::class, 'view'])
            ->middleware('authorize:FABRIC_SERVICE_BOOKINGS_VIEW');
        Route::get('/{id}/print', [FabricServiceBookingController::class, 'print']);
        Route::get('/{id}/pdf', [FabricServiceBookingController::class, 'pdf']);
        Route::get('/search', [FabricServiceBookingController::class, 'searchData']);
        Route::delete('/{id}', [FabricServiceBookingController::class, 'delete']);
    });

    Route::group(['prefix' => 'ypr-api'], function () {
        Route::post('', [YarnPurchaseRequisitionsController::class, 'store']);
        Route::put('{requisition}', [YarnPurchaseRequisitionsController::class, 'update'])->where('requisition', PK_REGEX);
        Route::get('{requisition}', [YarnPurchaseRequisitionsController::class, 'show'])->where('requisition', PK_REGEX);
        Route::get('budget-details-search', [YarnPurchaseRequisitionsController::class, 'budgetDetailsSearch']);
        Route::post('yarn-requisition-details-store', [YarnPurchaseRequisitionsController::class, 'yarnRequisitionDetailsStore']);
        Route::get('yarn-requisition-details', [YarnPurchaseRequisitionsController::class, 'yarnRequisitionDetails']);
        Route::delete('yarn-purchase-requisition-details-delete/{id}', [YarnPurchaseRequisitionsController::class, 'yarnRequisitionDetailsDelete']);
    });

    Route::group(['prefix' => 'ypo-api'], function () {
        Route::post('', [YarnPurchaseOrderApiController::class, 'store']);
        Route::get('/requisition-search', [YarnPurchaseOrderApiController::class, 'requisitionSearch']);
        Route::post('/search', [YarnPurchaseOrderApiController::class, 'detailsSearch']);
        Route::put('/requisition-details/{id}', [YarnPurchaseOrderApiController::class, 'orderDetailsCreateUpdate']);
        Route::delete('/order-details/{detail}', [YarnPurchaseOrderApiController::class, 'orderDetailsDelete']);
        Route::get('/styles', [YarnPurchaseOrderApiController::class, 'getStyles']);
        Route::get('/yarn-counts', [YarnPurchaseOrderApiController::class, 'getYarnCounts']);
        Route::get('/yarn-compositions', [YarnPurchaseOrderApiController::class, 'getYarnCompositions']);
        Route::get('/yarn-types', [YarnPurchaseOrderApiController::class, 'getYarnTypes']);

        Route::get('/{id}', [YarnPurchaseOrderApiController::class, 'edit']);
        Route::put('/{order}', [YarnPurchaseOrderApiController::class, 'update']);
    });

    Route::group(['prefix' => '/yarn-purchase'], function () {
        Route::resource('/requisition', RequisitionController::class);
        Route::get('/requisition/{id}/view', [YarnPurchaseRequisitionsController::class, 'view']);
        Route::get('/requisition/{id}/pdf', [YarnPurchaseRequisitionsController::class, 'pdf']);
        Route::resource('/order', YarnPurchaseOrderController::class);
        Route::get('/order/{id}/view', [YarnPurchaseOrderController::class, 'view']);
        Route::get('/order/{id}/print', [YarnPurchaseOrderController::class, 'print']);
        Route::get('/order/{id}/pdf', [YarnPurchaseOrderController::class, 'pdf']);
        Route::get('/order/{id}/yarn-booking/view', [YarnPurchaseOrderController::class, 'yarnBookingView']);
        Route::get('/order/{id}/yarn-booking/pdf', [YarnPurchaseOrderController::class, 'yarnBookingPdf']);
    });

    Route::get('price-quotations/body-part-types-entry-pages', [BodyPartController::class, 'getBodyPartEntryPageOptions']);
    Route::post('price-quotations/body-part', [BodyPartController::class, 'store']);

    Route::get('get-permitted-buyer', [BuyerPermissionCheckApiController::class, '__invoke']);
    Route::get('get-user-wise-buyer/{userId}', [UserWiseBuyerApiController::class, '__invoke']);

    //Order Recap Report Route
    Route::get('order-recap-report', [OrderRecapReportController::class, 'index']);
    Route::get('order-recap-report/pdf', [OrderRecapReportController::class, 'pdf']);

    Route::get('bom-report', [BomReportController::class, 'index']);
    Route::get('bom-report-fetch', [BomReportController::class, 'fetchReport']);
    Route::get('bom-report-checklist', [BomReportController::class, 'checklist']);
    Route::get('bom-report-fetch-checklist', [BomReportController::class, 'fetchReportChecklist']);

    Route::get('get-terms-and-conditions/{page_name}', [TermsAndConditionController::class, 'getTermsAndConditions']);

    Route::group(['prefix' => 'buyer-season-order'], function () {
        Route::get('', [BuyerSeasonOrderReportController::class, 'index']);
        Route::post('/get-report', [BuyerSeasonOrderReportController::class, 'getReport']);
        Route::get('/get-report-pdf', [BuyerSeasonOrderReportController::class, 'getReportPdf']);
        Route::get('/get-report-print', [BuyerSeasonOrderReportController::class, 'getReportPrint']);
        Route::get('/get-report-excel', [BuyerSeasonOrderReportController::class, 'getReportExcel']);
    });
    Route::get('/get-buyers-seasons/{id}', [BuyerSeasonOrderReportController::class, 'getBuyersSeasons']);

    Route::group(['prefix' => 'buyer-season-color-order'], function () {
        Route::get('/', [BuyerSeasonColorOrderReportController::class, 'index']);
        Route::post('/get-report', [BuyerSeasonColorOrderReportController::class, 'getReport']);
        Route::get('/get-report-pdf', [BuyerSeasonColorOrderReportController::class, 'getReportPdf']);
        Route::get('/get-report-print', [BuyerSeasonColorOrderReportController::class, 'getReportPrint']);
        Route::get('/get-report-excel', [BuyerSeasonColorOrderReportController::class, 'getReportExcel']);
    });

    Route::group(['prefix' => 'order-volume-report'], function () {
        Route::get('/', [OrderVolumeReportController::class, 'index']);
        Route::post('/get-report', [OrderVolumeReportController::class, 'getReport']);
        Route::get('/get-report-data', [OrderVolumeReportController::class, 'getReportData']);
        Route::get('/get-report-pdf', [OrderVolumeReportController::class, 'getReportPdf']);
        Route::get('/get-report-excel', [OrderVolumeReportController::class, 'getReportExcel']);
    });

    Route::group(['prefix' => 'budget-wise-wo-report'], function () {
        Route::get('/', [BudgetWiseWOReportController::class, 'index']);
        Route::get('/get-report', [BudgetWiseWOReportController::class, 'getReport']);
        Route::get('/get-report-pdf', [BudgetWiseWOReportController::class, 'getReportPdf']);
        Route::get('/get-report-excel', [BudgetWiseWOReportController::class, 'getReportExcel']);
    });

    Route::group(['prefix' => 'current-order-status-report'], function () {
        Route::get('/', [OrderDownloadAbleController::class, 'orderCurrentStatusView']);
        Route::get('/get-report', [OrderDownloadAbleController::class, 'orderCurrentStatusReportData']);
        Route::get('/get-report-pdf', [OrderDownloadAbleController::class, 'orderCurrentStatusPdfData']);
        Route::get('/get-report-excel', [OrderDownloadAbleController::class, 'orderCurrentStatusExcelData']);
    });
    Route::group(['prefix' => 'order-status-report'], function () {
        Route::get('/{id}', [OrderDownloadAbleController::class, 'orderCurrentStatusView']);
        Route::get('/get-report/{id}', [OrderDownloadAbleController::class, 'orderCurrentStatusReportData']);
        Route::get('/get-report-pdf/{id}', [OrderDownloadAbleController::class, 'orderCurrentStatusPdfData']);
        Route::get('/get-report-excel/{id}', [OrderDownloadAbleController::class, 'orderCurrentStatusExcelData']);
    });

    Route::group(['prefix' => 'final-costing-report'], function () {
        Route::get('/', [FinalCostingReportController::class, 'index']);
        Route::get('/get-report', [FinalCostingReportController::class, 'view']);
        Route::get('/get-report-pdf', [FinalCostingReportController::class, 'pdf']);
    });

    Route::group(['prefix' => 'sample-summary-report'], function () {
        Route::get('/', [SampleSummaryReportController::class, 'index']);
        Route::get('/get-report', [SampleSummaryReportController::class, 'view']);
        Route::get('/get-report-pdf', [SampleSummaryReportController::class, 'pdf']);
        Route::get('/excel', [SampleSummaryReportController::class, 'excel']);
    });

    Route::group(['prefix' => 'price-comparison-report'], function () {
        Route::get('/', [PriceComparisonReportController::class, 'index']);
        Route::get('/get', [PriceComparisonReportController::class, 'getReport']);
        Route::get('/pdf', [PriceComparisonReportController::class, 'getReportPdf']);
        Route::get('/excel', [PriceComparisonReportController::class, 'getReportExcel']);
    });

    Route::group(['prefix' => 'gate-pass-challan'], function () {

        Route::get('/exit-point-scan', [GatePassChallanController::class, 'exitPointScanView']);
        Route::get('/exit-point-scan-update', [GatePassChallanController::class, 'exitPointScanUpdate']);
        Route::get('/exit-list', [GatePassChallanController::class, 'exitList']);
        Route::get('/exit-list/{id}/view', [GatePassChallanController::class, 'exitListSingleView']);

        Route::get('/{id}/view', [GatePassChallanController::class, 'view']);
        Route::get('/{id}/pdf', [GatePassChallanController::class, 'pdf']);
        Route::post('', [GatePassChallanController::class, 'store']);
        Route::get('/create', [GatePassChallanController::class, 'create']);
        Route::get('/{gatePasChallan}', [GatePassChallanController::class, 'show']);
        Route::get('/search', [GatePassChallanController::class, 'search']);
        Route::delete('/{gatePasChallan}', [GatePassChallanController::class, 'destroy']);
        Route::get('/{id}/edit', [GatePassChallanController::class, 'create']);
        Route::get('/', [GatePassChallanController::class, 'index']);
        Route::put('/{gatePasChallan}', [GatePassChallanController::class, 'update']);
        Route::post('/gate-pass-image-remove', [GatePassChallanController::class, 'imageRemove']);
        Route::put('/update-returnable/{gatePasChallan}', [GatePassChallanController::class, 'updateReturnable']);
    });

    Route::group(['prefix' => 'gate-pass-challan/api/'], function () {
        Route::get('get-garments-sample', [GatePassChallanApiController::class, 'getGarmentsSample']);
        Route::get('get-style-wise-color', [GatePassChallanApiController::class, 'getStyleWiseColor']);
        Route::get('get-style-wise-size', [GatePassChallanApiController::class, 'getStyleWiseSize']);
        Route::get('get-fabric-composition', [GatePassChallanApiController::class, 'getFabricComposition']);
    });

    Route::group(['prefix' => 'style-audit-report'], function () {
        Route::get('/', [StyleAuditReportController::class, 'index']);
        Route::get('/value', [StyleAuditReportController::class, 'indexValue']);
        Route::get('/get-report', [StyleAuditReportController::class, 'getReport']);
        Route::get('/get-report/pdf', [StyleAuditReportController::class, 'getReportPdf']);
        Route::get('/get-report/value', [StyleAuditReportController::class, 'getValueReport']);
        Route::get('/get-report-value/pdf', [StyleAuditReportController::class, 'getValueReportPdf']);
        Route::get('/xls', [StyleAuditReportController::class, 'getAuditExcel']);
        Route::get('/value/xls', [StyleAuditReportController::class, 'getAuditValueExcel']);
    });

    Route::group(['prefix' => 'order-in-hand-report'], function () {
        Route::get('', [OrderInHandReportController::class, 'index']);
        Route::post('/get-report', [OrderInHandReportController::class, 'getReport']);
        Route::get('/get-report-pdf', [OrderInHandReportController::class, 'getReportPdf']);
        Route::get('/get-report-excel', [OrderInHandReportController::class, 'getReportExcel']);
    });

    Route::group(['prefix' => 'color-wise-order-volume-report'], function () {
        Route::get('/', [ColorWiseOrderVolumeReportController::class, 'index']);
        Route::get('/get-report', [ColorWiseOrderVolumeReportController::class, 'getReport']);
        Route::get('/buyer-wise-season', [ColorWiseOrderVolumeReportController::class, 'getSeason']);
        Route::get('/get-pdf', [ColorWiseOrderVolumeReportController::class, 'getPdf']);
        Route::get('/get-excel', [ColorWiseOrderVolumeReportController::class, 'getExcel']);
    });

    Route::get('/get-fabric-constructions', FabricConstructionApiController::class);
    Route::get('/get-fabric-composition-types', FabricCompositionTypesApiController::class);

    Route::get('/fabric-booking-summery-report', [FabricBookingDetailsReportController::class, 'index']);
    Route::get('/fabric-booking-summery-report/get-report-data', [FabricBookingDetailsReportController::class, 'reportData']);
    Route::get('/fabric-booking-summery-report/pdf', [FabricBookingDetailsReportController::class, 'pdf']);

    Route::get('/budget-costing-details', [BudgetCostingDetailsController::class, 'get']);
});

Route::get('/format-date', function () {
    $data = \SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder::query()
        ->get();

    foreach ($data as $d) {
        $d->po_receive_date = $d->po_receive_date ? Carbon::make($d->po_receive_date)->format('Y-m-d') : null;
        $d->ex_factory_date = $d->ex_factory_date ? Carbon::make($d->ex_factory_date)->format('Y-m-d') : null;
        $d->save();
    }
});

Route::get('/store-order-conf-report-data', function () {
    DB::beginTransaction();
    $purchaseOrders = \SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder::query()->get()->each(function ($po) {
        $data['production_lead_time'] = LeadTimeCalculator::calculate()->setLeadTime($po->lead_time)->getProductionLeadTime();
        $data['pi_bunch_budget_date'] = LeadTimeCalculator::calculate()->setDate($po->po_receive_date)->getPiBunchBudgetDate();
        $data['ex_bom_handover_date'] = LeadTimeCalculator::calculate()->setLeadTime($po->lead_time)->setDate($po->ex_factory_date)->getExBomHandOverDate();
        $po->update($data);
    });
    DB::commit();
    echo 'Done!';
});

Route::get('/mail', function () {
    return (new InformingMail(new POShipmentReminder()))->render();
});
