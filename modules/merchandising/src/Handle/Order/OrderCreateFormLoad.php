<?php
/**
 * Created by PhpStorm.
 * User: siam
 * Date: 8/24/19
 * Time: 3:13 PM
 */

namespace SkylarkSoft\GoRMG\Merchandising\Handle\Order;

use Illuminate\Support\Facades\Auth;
use SkylarkSoft\GoRMG\Merchandising\Models\Fabric_composition;
use SkylarkSoft\GoRMG\Merchandising\Models\Sample;
use SkylarkSoft\GoRMG\Merchandising\Models\SampleDetail;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\BuyingAgentModel;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\Currency;
use SkylarkSoft\GoRMG\SystemSettings\Models\ItemGroupAssign;
use SkylarkSoft\GoRMG\SystemSettings\Models\ProductDepartments;
use SkylarkSoft\GoRMG\SystemSettings\Models\Size;
use SkylarkSoft\GoRMG\SystemSettings\Models\Team;
use SkylarkSoft\GoRMG\SystemSettings\Models\TeamMemberAssign;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;

class OrderCreateFormLoad
{
    private $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function load()
    {
        $data['order'] = null;
        $data['user'] = Auth::id();
        $data['buyer'] = Buyer::withoutGlobalScope('factoryId')->pluck('name', 'id');
        $data['item_category'] = ITEM_CATEGORY;
        $data['sample'] = Sample::find($this->request->sample_id);
        $data['currency'] = Currency::pluck('currency_name', 'id');
        $team = TeamMemberAssign::where('member_id', Auth::user()->id)->first();
        $data['user_team'] = ($team) ? $team->team_id : null;
        $data['team'] = Team::pluck('team_name', 'id');
        $data['currency'] = Currency::pluck('currency_name', 'id')->all();
        $data['merchant'] = User::get()->map(function ($item) {
            $full_name = $item->screen_name ?? $item->first_name . ' ' . $item->last_name;
            $item->name = $full_name . ' (' . $item->email . ')';

            return $item;
        })->pluck('name', 'id');
        $data['colors'] = Color::pluck('name', 'id');
        $data['fabrication_list'] = Fabric_composition::pluck('yarn_composition', 'id');
        $data['sizes'] = Size::pluck('name', 'id');
        $data['agent'] = BuyingAgentModel::pluck('buying_agent_name', 'id');
        $data['product_department'] = ProductDepartments::orderBy('product_department', 'ASC')->pluck('product_department', 'id');
        $data['sample_id'] = $this->request->sample_id ? $this->request->sample_id : '';
        $data['sample_details'] = SampleDetail::with('item', 'fabrication')->where('sample_id', $this->request->sample_id)->get();
        $data['items'] = ItemGroupAssign::with('item')->where('item_group_id', 1)->get()->pluck('item.item_name', 'item.id');
        $data['defaultCurrency'] = Currency::where('currency_name', 'USD')->first()->id;

        return $data;
    }
}
