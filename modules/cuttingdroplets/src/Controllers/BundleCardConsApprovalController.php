<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Controllers;

use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCardGenerationDetail;

class BundleCardConsApprovalController extends Controller
{
    public function __invoke($id)
    {
        BundleCardGenerationDetail::findOrFail($id)->update(['is_cons_approved' => 1]);
        return redirect('/bundle-card-generations/' . $id);
    }
}
