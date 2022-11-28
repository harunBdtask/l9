<?php

namespace SkylarkSoft\GoRMG\Commercial\Controllers\BTBLcAmendment;

use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\Commercial\Models\B2BMarginLCAmendment;

class BtbLcAmendmentController extends Controller
{
    public function index()
    {
        $amendments = B2BMarginLCAmendment::with('b2bMargin')
            ->latest()->paginate(15);

        return view('commercial::btb-lc-amendment.index', compact('amendments'));
    }

    public function create()
    {
        return view('commercial::btb-lc-amendment.create_update');
    }
}
