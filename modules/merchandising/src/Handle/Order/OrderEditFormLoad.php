<?php

namespace SkylarkSoft\GoRMG\Merchandising\Handle\Order;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Merchandising\Models\Fabric_composition;
use SkylarkSoft\GoRMG\Merchandising\Models\IncotermPlace;
use SkylarkSoft\GoRMG\Merchandising\Models\OrderColorBreakdown;
use SkylarkSoft\GoRMG\Merchandising\Models\OrderItemDetail;
use SkylarkSoft\GoRMG\Merchandising\Models\OrderSizeBreakdown;
use SkylarkSoft\GoRMG\Merchandising\Models\Sample;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\BuyingAgentModel;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\Currency;
use SkylarkSoft\GoRMG\SystemSettings\Models\Incoterm;
use SkylarkSoft\GoRMG\SystemSettings\Models\ItemGroupAssign;
use SkylarkSoft\GoRMG\SystemSettings\Models\ProductDepartments;
use SkylarkSoft\GoRMG\SystemSettings\Models\Size;
use SkylarkSoft\GoRMG\SystemSettings\Models\Team;
use SkylarkSoft\GoRMG\SystemSettings\Models\TeamMemberAssign;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;

class OrderEditFormLoad
{
    private $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function load()
    {
        // DB is used for getting original value of order_style_no column NB:: SEE Order->getOrderStyleNoAttribute
        $data['order'] = DB::table('orders')->find($this->request->order_id);
        $data['user'] = Auth::id();
        $data['buyer'] = Buyer::withoutGlobalScope('factoryId')->pluck('name', 'id');
        $data['sample'] = Sample::find($this->request->sample_id);
        $data['currency'] = Currency::pluck('currency_name', 'id');
        $team = TeamMemberAssign::where('member_id', Auth::id())->first();
        $data['item_category'] = ITEM_CATEGORY;
        $data['user_team'] = ($team) ? $team->team_id : null;
        $data['team'] = Team::pluck('team_name', 'id');
        $data['currency'] = Currency::pluck('currency_name', 'id');
        $data['merchant'] = User::get()->map(function ($item) {
            $full_name = $item->screen_name ?? $item->first_name . ' ' . $item->last_name;
            $item->name = $full_name . ' (' . $item->email . ')';

            return $item;
        })->pluck('name', 'id');
        $data['fabrication_list'] = Fabric_composition::pluck('yarn_composition', 'id');
        $data['colors'] = Color::pluck('name', 'id');
        $data['sizes'] = Size::pluck('name', 'id');
        $data['order_color'] = OrderColorBreakdown::withoutGlobalScope('factoryId')->where('order_id', $this->request->order_id)->pluck('color_id');
        $data['order_size'] = OrderSizeBreakdown::withoutGlobalScope('factoryId')->where('order_id', $this->request->order_id)->pluck('size_id');
        $data['incoterm'] = Incoterm::pluck('incoterm', 'id');
        $data['incoterm_places'] = IncotermPlace::pluck('incoterm_place', 'id');
        $data['agent'] = BuyingAgentModel::pluck('buying_agent_name', 'id');
        $data['product_department'] = ProductDepartments::pluck('product_department', 'id');
        $data['sample_id'] = $this->request->sample_id ? $this->request->sample_id : '';
        $data['sample_details'] = OrderItemDetail::withoutGlobalScope('factoryId')->with('item', 'fabrication')->where('order_id', $this->request->order_id)->get();
        $data['items'] = ItemGroupAssign::withoutGlobalScope('factoryId')->with('item')->where('item_group_id', 1)->get()->pluck('item.item_name', 'item.id');
        $data['defaultCurrency'] = null;

        return $data;
    }

    public function ableToUpdate()
    {
        $data['order'] = DB::table('orders')->find($this->request->order_id);
        $team = TeamMemberAssign::where('member_id',  $data['order']->dealing_merchant)->first()->team_id ?? null;
        $teamMembers = TeamMemberAssign::where('team_id', $team)->pluck('member_id')->toArray() ?? null;
        if (in_array(Auth::id(), $teamMembers) || (getRole() == 'admin' || getRole() == 'super-admin')) {
            return true;
        }

        return false;
    }
}
