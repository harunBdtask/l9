<?php

use App\Constants\ApplicationConstant;
use Carbon\Carbon;
use Skylarksoft\Cuttingdroplets\Models\BundleCard;
use SkylarkSoft\GoRMG\Merchandising\Models\Fabric_composition;
use SkylarkSoft\GoRMG\Merchandising\PackageConst;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use Skylarksoft\Systemsettings\Models\TeamMemberAssign;

const COSTING_PER = [1 => 'Piece', 2 => 'Dozon', 3 => 'SET'];
const INCOTERM = [1 => 'FOB', 2 => 'CFR', 3 => 'CIF', 4 => 'FCA', 5 => 'CPT', 6 => 'EXW', 7 => 'FAS', 8 => 'CIP', 9 => 'DAF', 10 => 'DES', 11 => 'DEQ', 12 => 'DDU', 13 => 'DDP'];
const SHIPMENT_MODE = [1 => 'Sea', 2 => 'Air', 3 => 'Road', 4 => 'Train', 5 => 'Sea/Air', 6 => 'Road/Air'];
const PACKING_MODE = [0 => 'N/A', 1 => 'SCSS', 2 => 'ACSS', 3 => 'SCAS', 4 => 'ACAS', 5 => 'MEP Assort', 6 => 'RSA Solid'];
const PRODUCT_CATEGORY = ['1' => 'Garments', '2' => 'Intimates', '3' => 'Sweater', '4' => 'Socks', '5' => 'Fabric'];

const DATE_REGEX = '/[0-9]{2} [A-Za-z]{3} [0-9]{4}/';

const MONTHS = [
    'jan',
    'feb',
    'mar',
    'apr',
    'may',
    'jun',
    'jul',
    'aug',
    'sep',
    'oct',
    'nov',
    'dec',
    "january",
    "february",
    'march',
    'april',
    'june',
    'july',
    'august',
    'september',
    'october',
    'november',
    'december',];

const MONTH_ASSOCIATIVE_VALUE = [
    'jan' => 1,
    'feb' => 2,
    'mar' => 3,
    'apr' => 4,
    'may' => 5,
    'jun' => 6,
    'jul' => 7,
    'aug' => 8,
    'sep' => 9,
    'oct' => 10,
    'nov' => 11,
    'dec' => 12,
    "january" => 1,
    "february" => 2,
    'march' => 3,
    'april' => 4,
    'june' => 6,
    'july' => 7,
    'august' => 8,
    'september' => 9,
    'october' => 10,
    'november' => 11,
    'december' => 12,
];

const COST_COMPONENT = [
    '1' => 'Fabric Cost',
    '2' => 'Trims Cost',
    '3' => 'Print Cost',
    '4' => 'Embrodary Cost',
    '5' => 'Washing Cost',
    '6' => 'Commercial Cost',
    '7' => 'Labtest Cost',
    '8' => 'CPM Cost',
    '9' => 'Inspection Cost',
    '10' => 'Freight Cost',
    '11' => 'Courrier Cost',
    '12' => 'Certificate Cost',
    '13' => 'Operating Cost',
    '14' => 'Foreign Commissioin',
    '15' => 'Local Commission',
    '16' => 'depreciation Commission',
];

const ITEM_CATEGORY = [
    '1' => 'T-shirt',
    '2' => 'Polo',
    '3' => 'Pants',
    '4' => 'Intimates',
    '5' => 'Others',
];

const TRIMS_BREAKDOWN_TYPES = [
    '0' => 'Color Wise',
    '1' => 'Single Color Single size',
    '2' => 'All Color All Size',
    '3' => 'Size Wise Percentage',
    '4' => 'Additional',
];

const BUDGET_SOURCE = ['1' => 'Purchase', '2' => 'In house'];

const BOOKING_UNIT_CONSUMPTION = ['1' => 'KGS/DOZ', '2' => 'KGS/PCS', '3' => 'KGS/YDS', '4' => 'YDS/PCS', '5' => 'YDS/DOZ', '6' => 'PCS/COLLAR', '7' => 'PCS/CUFF',];

const CURRENCIES = [
    'usd' => '$',
    'tk' => '৳',
    'TAKA' => '৳',
    'euro' => '€',
];

if (!function_exists('getCurrencySign')) {
    function getCurrencySign($currency): string
    {
        return CURRENCIES[$currency] ?? '';
    }
}
function get_list_view_permission($user)
{
    $is_data = Skylarksoft\Systemsettings\Models\TeamMemberAssign::where('member_id', $user->id)->first();
    if ($is_data) {
        if ($is_data->is_team_lead == 1) {
            return TeamMemberAssign::where('team_id', $is_data->team_id)->pluck('member_id');
        } else {
            return [$user->id];
        }
    }
}

function get_lists_data_team_wise($user, $query)
{
    if ($user->role_id != 1 && $user->role_id != 2 && $user->role_id != 3) {  /* if role is user or admin or super admin */
        $list_view_permission_ids = get_list_view_permission($user);
        $query->whereIn('created_by', $list_view_permission_ids);
        $column = (request()->segment('1') != 'order') ? 'order.dealing_merchants' : 'dealing_merchants';
        $query->orWhereHas($column, function ($query) use ($user) {
            $query->where('dealing_merchant', $user->id);
        });

        return $query;
    }
}

function get_fabrication_name($id)
{
    return Fabric_composition::find($id)->yarn_composition;
}

function is_bundle_card_create($buyer_id, $order_id, $purchase_order_id = '')
{
    $no_of_bundle = BundleCard::where(
        [
            'buyer_id' => $buyer_id,
            'order_id' => $order_id,
            'purchase_order_id' => $purchase_order_id,
        ]
    )->count();
    if ($no_of_bundle > 0) {
        return true;
    }

    return false;
}

function calculateUnitOfMeasurement($mesurementName, $total)
{
    if (strtolower($mesurementName) == 'pcs') {
        $result = $total;
    } elseif (strtolower($mesurementName) == 'dzn') {
        $result = $total / 12;
    } elseif (strtolower($mesurementName) == 'grs') {
        $result = $total / 144;
    } elseif (strtolower($mesurementName) == 'gg') {
        $result = $total / 1728;
    }

    return $result ?? $total;
}

if (!function_exists('getPrefix')) {
    function getPrefix(): string
    {
        if (session()->has('prefix')) {
            return session('prefix');
        }

        $factoryId = auth()->user()->factory_id ?? 1;
        $prefix = Factory::find($factoryId)->factory_short_name ?? null;

        if ($prefix) {
            $prefix .= '-';
            session()->put('prefix', $prefix);

            return $prefix;
        }

        $prefix = 'NA-';

        session()->put('prefix', $prefix);

        return $prefix;
    }
}

if (!function_exists('format')) {
    function format($number): string
    {
        return sprintf(ApplicationConstant::DECIMAL_PLACE, $number);
    }
}

if (!function_exists('formatDate')) {
    function formatDate($date): ?string
    {
        return $date ? date_format(date_create($date), 'd/m/Y') : null;
    }
}

if (!function_exists('years')) {
    function years(): array
    {
        $years = [];
        for ($i = -5; $i < 5; $i++) {
            $year = Carbon::now()->addYear($i)->format('Y');
            $years[] = $year;
        }

        return $years;
    }
}

if (!function_exists('months')) {
    function months(): array
    {
        return array(
            1 => 'January',
            2 => 'February',
            3 => 'March',
            4 => 'April',
            5 => 'May',
            6 => 'June',
            7 => 'July ',
            8 => 'August',
            9 => 'September',
            10 => 'October',
            11 => 'November',
            12 => 'December',
        );
    }
}

const PK_REGEX = '[0-9]+';


if (!function_exists('getHeadBanner')) {
    function getHeadBanner($requestPath = null): array
    {
        $requestPath = preg_replace("/\d+/", "{id}", $requestPath);
        return PackageConst::ROUTE_LOOKUP[$requestPath] ?? [];
    }
}
