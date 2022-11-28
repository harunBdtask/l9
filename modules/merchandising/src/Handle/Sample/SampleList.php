<?php
/**
 * Created by PhpStorm.
 * User: siam
 * Date: 8/19/19
 * Time: 12:55 PM
 */

namespace SkylarkSoft\GoRMG\Merchandising\Handle\Sample;

use Illuminate\Support\Facades\Auth;
use SkylarkSoft\GoRMG\Merchandising\Models\Sample;

class SampleList
{
    public function generate()
    {
        $user = Auth::user();
        $query = Sample::withoutGlobalScope('factoryId')->with('buyer', 'agent', 'sampleDetails', 'dealingMerchant', 'teamLead');
        $data['sample_lists'] = $query->orderBy('id', 'desc')->paginate();

        return $data;
    }
}
