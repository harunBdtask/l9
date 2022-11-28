<?php

namespace SkylarkSoft\GoRMG\Knitting\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use SkylarkSoft\GoRMG\Knitting\Services\FabricBookingListService;

class FabricBookingController
{
    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function __invoke(Request $request)
    {
        $q = $request->all()??null;
        $data = (new FabricBookingListService($q))->getData();
        //TODO REFACTOR;
        $data['dashboardOverview'] = [
            'Not Started' => 0,
            'In Progress' => 0,
            'On Hold' => 0,
            'Cancelled' => 0,
            'Finished' => 0
        ];


        return view('knitting::fabric-booking-list', $data);
    }
}
