<?php


namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCardGenerationDetail;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsProductionEntry;

class GarmentsProductionEntryController extends Controller
{
    public function index(Request $request)
    {
        $factory_options = Factory::query()->pluck('factory_name', 'id');

        $finishing_target_entry_options = GarmentsProductionEntry::FINISHING_TARGET_ENTRY_OPTION;
        $methods = GarmentsProductionEntry::ENTRY_METHODS;
        $types = GarmentsProductionEntry::ENTRY_TYPES;
        $style_filter_options = GarmentsProductionEntry::STYLE_FILTER_OPTIONS;
        $bundle_card_serials = GarmentsProductionEntry::BUNDLE_CARD_SERIALS;
        $bundle_card_suffix_styles = GarmentsProductionEntry::BUNDLE_CARD_SUFFIX_STYLES;
        $bundle_card_print_styles = GarmentsProductionEntry::BUNDLE_CARD_PRINT_STYLES;
        $bundle_card_size_suffix_sl_statuses = GarmentsProductionEntry::BUNDLE_CARD_SIZE_SUFFIX_SL_STATUSES;
        $bundle_card_serial_options = BundleCardGenerationDetail::BUNDLE_CARD_SERIAL_OPTIONS;
        $pdf_upload_menu_hide_options = GarmentsProductionEntry::MENU_HIDE_STATUSES;
        $cutting_plan_menu_hide_options = GarmentsProductionEntry::MENU_HIDE_STATUSES;
        $sewing_plan_menu_hide_options = GarmentsProductionEntry::MENU_HIDE_STATUSES;
        $erp_menu_view_options = GarmentsProductionEntry::ERP_MENU_VIEW_OPTION;
        $line_wise_hour_show_data = GarmentsProductionEntry::LINE_WISE_HOURS;
        $show_hide_status_options = GarmentsProductionEntry::SHOW_HIDE_STATUSES;
        $cutting_target_versions = GarmentsProductionEntry::CUTTING_TARGET_VERSIONS;
        $sewing_line_target_versions = GarmentsProductionEntry::SEWING_LINE_TARGET_VERSIONS;
        $sewing_starting_hour_options = GarmentsProductionEntry::SEWING_STARTING_HOURS;
        $hr_menu_view_status_options = GarmentsProductionEntry::HR_MENU_VIEW_STATUS;
        $yarn_store_barcode_meta_labels = GarmentsProductionEntry::YARN_STORE_BARCODE_META_LABEL;
        $finishing_productions = GarmentsProductionEntry::FINISHING_PRODUCTION;

        return view("system-settings::pages.garments-production-entry", compact([
            'methods',
            'factory_options',
            'types',
            'style_filter_options',
            'bundle_card_serials',
            'bundle_card_suffix_styles',
            'bundle_card_print_styles',
            'bundle_card_size_suffix_sl_statuses',
            'bundle_card_serial_options',
            'finishing_target_entry_options',
            'pdf_upload_menu_hide_options',
            'cutting_plan_menu_hide_options',
            'sewing_plan_menu_hide_options',
            'erp_menu_view_options',
            'line_wise_hour_show_data',
            'show_hide_status_options',
            'cutting_target_versions',
            'sewing_line_target_versions',
            'sewing_starting_hour_options',
            'hr_menu_view_status_options',
            'yarn_store_barcode_meta_labels',
            'finishing_productions',
        ]));
    }

    public function store(Request $request)
    {
        $this->validation($request);
        $factory_id = $request->factory_id;
        $entry_method = $request->entry_method;
        $entry_type = $request->entry_type;
        $garments_production_variable = GarmentsProductionEntry::firstOrNew(['factory_id' => $factory_id]);
        $garments_production_variable->factory_id = $factory_id;
        $garments_production_variable->entry_method = $entry_method;
        $garments_production_variable->entry_type = $entry_type;
        $garments_production_variable->style_filter_option = $request->style_filter_option;
        $garments_production_variable->bundle_card_serial = $request->bundle_card_serial ?? 'bundle_no';
        $garments_production_variable->bundle_card_suffix_style = $request->bundle_card_suffix_style ?? 0;
        $garments_production_variable->size_suffix_sl_status = $request->size_suffix_sl_status ?? 0;
        $garments_production_variable->customized_sticker_serials = $request->customized_sticker_serials ?? [];
        $garments_production_variable->bundle_straight_serial_max_limit = $request->bundle_straight_serial_max_limit ?? null;
        $garments_production_variable->bundle_card_print_style = $request->bundle_card_print_style ?? 0;
        $garments_production_variable->bundle_card_sticker_width = $request->bundle_card_sticker_width ?? null;
        $garments_production_variable->bundle_card_sticker_height = $request->bundle_card_sticker_height ?? null;
        $garments_production_variable->bundle_card_sticker_font_size = $request->bundle_card_sticker_font_size ?? null;
        $garments_production_variable->bundle_card_sticker_max_width = $request->bundle_card_sticker_max_width ?? null;
        $garments_production_variable->bundle_card_sticker_max_height = $request->bundle_card_sticker_max_height ?? null;
        $garments_production_variable->bundle_card_sticker_ratio_view_status = $request->bundle_card_sticker_ratio_view_status ?? 0;
        $garments_production_variable->barcode_height = $request->barcode_height ?? null;
        $garments_production_variable->barcode_width = $request->barcode_width ?? null;
        $garments_production_variable->finishing_target_entry_option = $request->finishing_target_entry_option ?? null;
        $garments_production_variable->pdf_upload_menu_hide_status = $request->pdf_upload_menu_hide_status ?? 0;
        $garments_production_variable->cutting_plan_menu_hide_status = $request->cutting_plan_menu_hide_status ?? 0;
        $garments_production_variable->sewing_plan_menu_hide_status = $request->sewing_plan_menu_hide_status ?? 0;
        $garments_production_variable->erp_menu_view_status = $request->erp_menu_view_status ?? 1;
        $garments_production_variable->hr_menu_view_status = $request->hr_menu_view_status ?? 0;
        $garments_production_variable->cutting_target_version = $request->cutting_target_version ?? 1;
        $garments_production_variable->sewing_line_target_vesrion = $request->sewing_line_target_vesrion ?? 1;
        $garments_production_variable->sewing_starting_hour = $request->sewing_starting_hour ?? 8;
        $garments_production_variable->line_wise_hour_show = $request->line_wise_hour_show ?? GarmentsProductionEntry::DEFAULT_LINE_WISE_HOURS;
        $garments_production_variable->yarn_store_barcode_meta = $request->yarn_store_barcode_meta ?? GarmentsProductionEntry::DEFAULT_YARN_STORE_BARCODE_META;
        $garments_production_variable->scan_data_caching_time = $request->scan_data_caching_time;
        $garments_production_variable->cutting_qty_validation = $request->cutting_qty_validation;
        $garments_production_variable->fabric_cons_approval = $request->fabric_cons_approval;
        $garments_production_variable->max_bundle_qty = $request->max_bundle_qty;
        $garments_production_variable->finishing_report = $request->finishing_report;
        $garments_production_variable->save();
        Session::flash('success', 'Data Updated Successfully');
        return redirect('/garments-production-entry');
    }

    public function fetch($factory_id): JsonResponse
    {
        $profile = GarmentsProductionEntry::query()->where(['factory_id' => $factory_id])->first();
        return response()->json($profile);
    }

    public function update($id, Request $request)
    {
        $this->validation($request);
        GarmentsProductionEntry::query()->findOrFail($id)->update([
            'factory_id' => $request->factory_id,
            'entry_method' => $request->entry_method,
            'entry_type' => $request->entry_type,
            'style_filter_option' => $request->style_filter_option,
            'bundle_card_serial' => $request->bundle_card_serial ?? 'bundle_no',
            'bundle_card_suffix_style' => $request->bundle_card_suffix_style ?? 0,
            'size_suffix_sl_status' => $request->size_suffix_sl_status ?? 0,
            'customized_sticker_serials' => $request->customized_sticker_serials ?? [],
            'bundle_straight_serial_max_limit' => $request->bundle_straight_serial_max_limit ?? null,
            'bundle_card_print_style' => $request->bundle_card_print_style ?? 0,
            'bundle_card_sticker_width' => $request->bundle_card_sticker_width ?? null,
            'bundle_card_sticker_height' => $request->bundle_card_sticker_height ?? null,
            'bundle_card_sticker_font_size' => $request->bundle_card_sticker_font_size ?? null,
            'bundle_card_sticker_max_width' => $request->bundle_card_sticker_max_width ?? null,
            'bundle_card_sticker_max_height' => $request->bundle_card_sticker_max_height ?? null,
            'bundle_card_sticker_ratio_view_status' => $request->bundle_card_sticker_ratio_view_status ?? 0,
            'barcode_height' => $request->barcode_height ?? null,
            'barcode_width' => $request->barcode_width ?? null,
            'finishing_target_entry_option' => $request->finishing_target_entry_option ?? null,
            'pdf_upload_menu_hide_status' => $request->pdf_upload_menu_hide_status ?? 0,
            'cutting_plan_menu_hide_status' => $request->cutting_plan_menu_hide_status ?? 0,
            'sewing_plan_menu_hide_status' => $request->sewing_plan_menu_hide_status ?? 0,
            'erp_menu_view_status' => $request->erp_menu_view_status ?? 1,
            'hr_menu_view_status' => $request->hr_menu_view_status ?? 0,
            'cutting_target_version' => $request->cutting_target_version ?? 1,
            'sewing_line_target_vesrion' => $request->sewing_line_target_vesrion ?? 1,
            'sewing_starting_hour' => $request->sewing_starting_hour ?? 8,
            'line_wise_hour_show' => $request->line_wise_hour_show ?? GarmentsProductionEntry::DEFAULT_LINE_WISE_HOURS,
            'yarn_store_barcode_meta' => $request->yarn_store_barcode_meta ?? GarmentsProductionEntry::DEFAULT_YARN_STORE_BARCODE_META,
            'scan_data_caching_time' => $request->scan_data_caching_time,
            'cutting_qty_validation' => $request->cutting_qty_validation,
            'fabric_cons_approval' => $request->fabric_cons_approval,
            'max_bundle_qty' => $request->max_bundle_qty,
            'finishing_report' => $request->finishing_report,
        ]);
        Session::flash('success', 'Data Updated Successfully');
        return redirect('/garments-production-entry');
    }

    public function destroy($id)
    {
        GarmentsProductionEntry::query()->findOrFail($id)->delete();
        Session::flash('success', 'Data Deleted Successfully');
        return redirect('/garments-production-entry');
    }

    private function validation($request)
    {
        $required_or_integer = 'required|integer';
        $nullable_or_integer = 'nullable|integer';
        $nullable_or_positive_integer = 'nullable|numeric|min:0|not_in:0';
        $request->validate([
            'factory_id' => $required_or_integer,
            'entry_method' => $required_or_integer,
            'entry_type' => $required_or_integer,
            'bundle_card_serial' => 'nullable',
            'bundle_straight_serial_max_limit' => 'nullable|numeric|min:0|max:2147483647',
            'bundle_card_suffix_style' => $nullable_or_integer,
            'bundle_card_print_style' => $nullable_or_integer,
            'bundle_card_sticker_width' => $nullable_or_positive_integer,
            'bundle_card_sticker_height' => $nullable_or_positive_integer,
            'bundle_card_sticker_font_size' => $nullable_or_positive_integer,
            'bundle_card_sticker_max_width' => $nullable_or_positive_integer,
        ], [
            'required' => "This field is required",
            'integer' => "This must be an integer",
            'numeric' => "This must be a number",
            'min' => "Must be positive integer",
            'not_in' => "Must be positive integer",
        ]);
    }
}
