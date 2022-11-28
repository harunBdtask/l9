<?php
/**
 * Created by PhpStorm.
 * User: siam
 * Date: 8/25/19
 * Time: 10:05 AM
 */

namespace SkylarkSoft\GoRMG\Merchandising\Handle\Order;

use Illuminate\Support\Facades\Auth;
use SkylarkSoft\GoRMG\SystemSettings\Models\TeamMemberAssign;

class GetDealingMerchant
{
    private $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function get()
    {
        $team_leader_id = $this->request->team_leader_id;
        $dealing_merchant = $this->request->dealing_merchant;
        $data = TeamMemberAssign::where('factory_id', Auth::user()->factory_id)->where('team_id', $team_leader_id)->with('members')->get();
        $html = '';
        if (isset($data) && $data->count() > 0) {
            $html .= '<option value="">Select Merchant</option>';
            foreach ($data as $member) {
                $selected = $member->members->id == $dealing_merchant ? 'selected="selected"' : '';
                $html .= '<option value="' . $member->members->id . '" ' . $selected . ' >' . $member->members->first_name . ' ' . $member->members->last_name . ' (' . $member->members->email . ')' . '</option>';
            }
        } else {
            $html .= '<option value="">No Data</option>';
        }

        return $html;
    }
}
