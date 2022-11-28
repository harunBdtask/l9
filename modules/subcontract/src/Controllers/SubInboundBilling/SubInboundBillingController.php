<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\SubInboundBilling;

use App\Http\Controllers\Controller;

class SubInboundBillingController extends Controller
{
    public function index()
    {
        return 'list will show here';
    }

    public function create()
    {
        return view('subcontract::textile_module.sub-inbound-billing.form');
    }
}
