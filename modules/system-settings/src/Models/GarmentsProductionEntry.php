<?php


namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use App\Casts\Json;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GarmentsProductionEntry extends Model
{
    protected $table = 'garments_production_entries';

    protected $fillable = [
        'factory_id',
        'entry_method',
        'entry_type',
        'style_filter_option',
        'bundle_card_serial',
        'size_suffix_sl_status',
        'customized_sticker_serials',
        'bundle_straight_serial_max_limit',
        'bundle_card_suffix_style',
        'bundle_card_print_style',
        'bundle_card_sticker_width',
        'bundle_card_sticker_height',
        'bundle_card_sticker_font_size',
        'bundle_card_sticker_max_width',
        'bundle_card_sticker_max_height',
        'bundle_card_sticker_ratio_view_status',
        'barcode_height',
        'barcode_width',
        'finishing_target_entry_option',
        'pdf_upload_menu_hide_status',
        'erp_menu_view_status',
        'hr_menu_view_status',
        'cutting_plan_menu_hide_status',
        'sewing_plan_menu_hide_status',
        'cutting_target_version',
        'sewing_line_target_vesrion',
        'line_wise_hour_show',
        'sewing_starting_hour',
        'yarn_store_barcode_meta',
        'scan_data_caching_time',
        'cutting_qty_validation',
        'fabric_cons_approval',
        'max_bundle_qty',
        'finishing_report',
    ];

    protected $casts = [
        'customized_sticker_serials' => Json::class,
        'line_wise_hour_show' => Json::class,
        'yarn_store_barcode_meta' => Json::class
    ];

    const ENTRY_METHODS = [
        1 => 'Order Wise',
        2 => 'Color Wise',
        3 => 'Size Wise'
    ];
    const ENTRY_TYPES = [
        1 => 'Manual',
        2 => 'Automated',
    ];
    const STYLE_FILTER_OPTIONS = [
        1 => 'Style Name',
        2 => 'Booking No',
        3 => 'Reference No'
    ];
    const BUNDLE_CARD_SERIALS = [
        'size_wise_bundle_no' => 'Size Wise',
        'bundle_no' => 'Straight',
    ];
    const BUNDLE_CARD_SUFFIX_STYLES = [
        0 => 'Number',
        1 => 'Letter',
    ];
    const BUNDLE_CARD_PRINT_STYLES = [
        0 => 'Page',
        1 => 'Sticker',
        2 => 'Page(GMS)',
    ];
    const MENU_HIDE_STATUSES = [
        0 => 'No',
        1 => 'Yes',
    ];

    const BUNDLE_CARD_SIZE_SUFFIX_SL_STATUSES = [
        0 => 'No',
        1 => 'Yes',
    ];

    const SHOW_HIDE_STATUSES = [
        0 => 'Hide',
        1 => 'Show',
    ];

    const FINISHING_TARGET_ENTRY_OPTION = [
        1 => 'Floor Wise',
        2 => 'Table Wise'
    ];

    const ERP_MENU_VIEW_OPTION = [
        // 1 => 'ERP Menu',
        // 2 => 'PROTRACKER Menu',
        // 3 => 'Textile Menu',
        // 4 => 'MC Inventory',
        5 => 'Customized',
    ];

    const CUTTING_TARGET_VERSIONS = [
        1 => 'V1',
        2 => 'V2',
    ];

    const SEWING_LINE_TARGET_VERSIONS = [
        1 => 'V1',
        2 => 'V2',
    ];

    const LINE_WISE_HOURS = [
        'hour_0' => '12-1 AM',
        'hour_1' => '1-2 AM',
        'hour_2' => '2-3 AM',
        'hour_3' => '3-4 AM',
        'hour_4' => '4-5 AM',
        'hour_5' => '5-6 AM',
        'hour_6' => '6-7 AM',
        'hour_7' => '7-8 AM',
        'hour_8' => '8-9 AM',
        'hour_9' => '9-10 AM',
        'hour_10' => '10-11 AM',
        'hour_11' => '11-12 AM',
        'hour_12' => '12-1 PM',
        'hour_13' => '1-2 PM',
        'hour_14' => '2-3 PM',
        'hour_15' => '3-4 PM',
        'hour_16' => '4-5 PM',
        'hour_17' => '5-6 PM',
        'hour_18' => '6-7 PM',
        'hour_19' => '7-8 PM',
        'hour_20' => '8-9 PM',
        'hour_21' => '9-10 PM',
        'hour_22' => '10-11 PM',
        'hour_23' => '11-12 PM',
    ];
    const DEFAULT_LINE_WISE_HOURS = [
        'hour_0' => 0,
        'hour_1' => 0,
        'hour_2' => 0,
        'hour_3' => 0,
        'hour_4' => 0,
        'hour_5' => 0,
        'hour_6' => 0,
        'hour_7' => 0,
        'hour_8' => 1,
        'hour_9' => 1,
        'hour_10' => 1,
        'hour_11' => 1,
        'hour_12' => 1,
        'hour_13' => 1,
        'hour_14' => 1,
        'hour_15' => 1,
        'hour_16' => 1,
        'hour_17' => 1,
        'hour_18' => 1,
        'hour_19' => 0,
        'hour_20' => 0,
        'hour_21' => 0,
        'hour_22' => 0,
        'hour_23' => 0,
    ];

    const ORDER_WISE_ENTRY = 1;
    const COLOR_WISE_ENTRY = 2;
    const SIZE_WISE_ENTRY = 3;

    const ERP_MENU_VIEW = 1;
    const PROTRACKER_MENU_VIEW = 2;
    const TEXTILE_MENU_VIEW = 3;
    const MC_INVENTORY_MENU_VIEW = 4;
    const CUSTOMIZED_MENU_VIEW = 5;

    const SEWING_LINE_TARGET_VERSION_1 = 1;
    const SEWING_LINE_TARGET_VERSION_2 = 2;

    const STYLE_NAME_FILTER = 1;
    const BOOKING_NO_FILTER = 2;
    const REFERENCE_NO_FILTER = 3;

    const SEWING_STARTING_HOURS = [
        '12 AM',
        '1 AM',
        '2 AM',
        '3 AM',
        '4 AM',
        '5 AM',
        '6 AM',
        '7 AM',
        '8 AM',
        '9 AM',
        '10 AM',
        '11 AM',
        '12 PM',
        '1 PM',
        '2 PM',
        '3 PM',
        '4 PM',
        '5 PM',
        '6 PM',
        '7 PM',
        '8 PM',
        '9 PM',
        '10 PM',
        '11 PM',
    ];

    const HR_MENU_VIEW_STATUS = [
        0 => 'No',
        1 => 'Yes',
    ];

    const YARN_STORE_BARCODE_META_LABEL = [
        'barcode_width' => 'Barcode Width',
        'barcode_height' => 'Barcode Height',
        'barcode_font_size' => 'Sticker Font Size(px)',
        'barcode_container_m_top' => 'Sticker Margin Top(px)',
        'barcode_container_m_left' => 'Sticker Margin Left(px)',
        'barcode_container_m_right' => 'Sticker Margin Right(px)',
        'barcode_container_m_bottom' => 'Sticker Margin Bottom(px)',
        'barcode_container_p_top' => 'Sticker Padding Top(px)',
        'barcode_container_p_left' => 'Sticker Padding Left(px)',
        'barcode_container_p_right' => 'Sticker Padding Right(px)',
        'barcode_container_p_bottom' => 'Sticker Padding Bottom(px)'
    ];

    const DEFAULT_YARN_STORE_BARCODE_META = [
        'barcode_width' => 2.98,
        'barcode_height' => 58,
        'barcode_font_size' => 25,
        'barcode_container_m_top' => 10,
        'barcode_container_m_left' => 10,
        'barcode_container_m_right' => 10,
        'barcode_container_m_bottom' => 10,
        'barcode_container_p_top' => 0,
        'barcode_container_p_left' => 0,
        'barcode_container_p_right' => 0,
        'barcode_container_p_bottom' => 0
    ];

    const FINISHING_PRODUCTION = [
        'hourly_finishing_production' => 'HOURLY FINISHING PRODUCTION',
        'iron_poly_packing' => 'IRON, POLY, PACKING',
    ];

    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class)->withDefault();
    }
}
