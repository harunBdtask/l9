<?php
/**
 * Created by PhpStorm.
 * User: siam
 * Date: 8/19/19
 * Time: 1:00 PM
 */

namespace SkylarkSoft\GoRMG\Merchandising\Handle\Sample;

use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\BuyingAgentModel;
use SkylarkSoft\GoRMG\SystemSettings\Models\Currency;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsItem;
use SkylarkSoft\GoRMG\SystemSettings\Models\NewFabricComposition;
use SkylarkSoft\GoRMG\SystemSettings\Models\Team;

class SampleCreateFormLoad
{
    public function load(): array
    {
        $data['user'] = Auth::id();
        $data['sample_development'] = null;
        $data['buyer'] = Buyer::pluck('name', 'id');
        $team = Team::where('member_id', auth()->id())->first();
        $data['user_team'] = null;
        $team_leaders = [];
        Team::where('role', 'Leader')->get()->map(function ($teamMember) use (&$team_leaders) {
            return $team_leaders[$teamMember->member->id] = $teamMember->member->first_name . ' ' . $teamMember->member->last_name;
        });
        $data['team'] = $team_leaders;
        $data['currency'] = Currency::pluck('currency_name', 'id');
        $data['fabrication_list'] = NewFabricComposition::pluck('construction', 'id');
        $data['merchant'] = User::select(DB::raw("CONCAT(first_name,' ',COALESCE(last_name, ''), ' (' ,email , ') ') AS name"), 'id')->pluck('name', 'id');
        $data['agent'] = BuyingAgentModel::pluck('buying_agent_name', 'id');
        $data['items'] = GarmentsItem::all()->pluck('name', 'id');

        return $data;
    }
}
