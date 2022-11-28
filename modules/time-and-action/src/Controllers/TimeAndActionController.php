<?php

namespace SkylarkSoft\GoRMG\TimeAndAction\Controllers;


use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\TimeAndAction\PackageConst;

class TimeAndActionController extends Controller
{
    public function index()
    {
        return view(PackageConst::VIEW . 'index');
    }
}
